<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 9/22/17
 * Time: 3:47 PM
 */

namespace AppBundle\Service\database\MySQL;

use AppBundle\Service\database\MySQL\Config;
use AppBundle\Service\model\Provider;
use AppBundle\Service\model\Schedule;

class ProviderConnector extends DBConnector
{
    /**
     *
     * Check to see if doctor exists
     *
     * @param $firstname
     * @param $lastname
     * @param $email
     * @return bool
     */
    function checkDoctorExists($firstname, $lastname, $email)
    {

        $sql = "SELECT id FROM doctor
                    WHERE first_name=:FIRSTNAME and last_name=:LASTNAME and email=:EMAIL LIMIT 1";
        $query = $this->pdo->prepare($sql);
        // use exec() because no results are returned
        $query->execute(array(
            'FIRSTNAME'=>$firstname,
            'LASTNAME'=>$lastname,
            'EMAIL'=>$email));
        $count = $query->rowCount();
        if ($count>0)
        {
            $row = $query->fetch();
            return $row['id'];
        }
        return false;
    }

    function checkDoctorExistsByHospitalDoctorID($doctorid)
    {

        $sql = "SELECT id FROM doctor
                    WHERE hospital_doctor_id=:DOCTORID LIMIT 1";
        $query = $this->pdo->prepare($sql);
        // use exec() because no results are returned
        $query->execute(array(
            'DOCTORID'=>$doctorid));
        $count = $query->rowCount();
        if ($count>0)
        {
            $row = $query->fetch();
            return $row['id'];
        }
        return false;
    }

    /**
     *
     * Insert Doctor but check for existence first
     *
     * @param $firstname
     * @param $lastname
     * @param $email
     * @return String
     */
    function insertDoctor($firstname, $lastname, $email, $pid)
    {
       $patientID = $this->checkDoctorExistsByHospitalDoctorID($pid);

        if ($patientID !==false)
            return $patientID;

        $sql = "INSERT INTO doctor (first_name, last_name, email, hospital_doctor_id)
                    VALUES (:FIRSTNAME, :LASTNAME, :EMAIL, :HOSPITALDOCTORID)";
        $query = $this->pdo->prepare($sql);
        // use exec() because no results are returned
        $query->execute(array(
            'FIRSTNAME'=>$firstname,
            'LASTNAME'=>$lastname,
            'EMAIL'=>$email,
            'HOSPITALDOCTORID'=>$pid));

        return $this->pdo->lastInsertId("id");

    }

    /**
     *
     * Always overwrite data for doctor
     *
     * @param $patientID
     * @param $patientData
     */
    function cleanScheduleData($doctorID)
    {
        //delete
        $sql = "DELETE FROM schedule WHERE 
                    doctor_id=:doctorID";
        $query = $this->pdo->prepare($sql);
        // use exec() because no results are returned
        $query->execute(array(
            'doctorID'=>$doctorID
        ));
    }

    /**
     *
     * Always overwrite data for doctor
     *
     * @param $patientID
     * @param $patientData
     */
    function insertData($doctorID, $doctorData, $type)
    {

        //insert
        $sql = "INSERT INTO schedule (doctor_id, schedule_data)
                    VALUES (:doctorID, :schedule_data)";
        $query = $this->pdo->prepare($sql);
        // use exec() because no results are returned
        $query->execute(array(
            'schedule_data'=>json_encode($doctorData),
            'doctorID'=>$doctorID
        ));
        
    }

    /**
     * Get all the providers service ids in an array
     *
     * @param $providerID
     */
    function getEAServiceIDs($providerID)
    {
        $sql = "SELECT * FROM schedule WHERE doctor_id=:providerID";
        $query = $this->pdo->prepare($sql);
        // use exec() because no results are returned
        $query->execute(array(
            'providerID'=>$providerID
        ));

        //get all facility ids
        $facilityIDs = array();
        while($row = $query->fetch())
        {
            $dataJson = json_decode($row['schedule_data']);
            $facilityIDs[$dataJson->facilityid] = $dataJson->facilityid;
        }
        //for each facility id, get the easervice ID
        //get the facility ID by categoryID
        $easerviceIDs = array();
        $srv_conn = new ServiceConnector();
        foreach($facilityIDs as $facilityID)
        {
            $serviceID = $srv_conn->getEAServiceIDByFacilityID($facilityID);
            $easerviceIDs[] = $serviceID;
        }

        return $easerviceIDs;

    }

    /**
     * returns all doctors as Doctor Object
     */
    function getAllProviders()
    {
        $providers = array();
        $sql = "SELECT * FROM doctor d LEFT OUTER JOIN provider_appointmentprovider ps ON d.id=ps.providerID ";
        $query = $this->pdo->prepare($sql);
        // use exec() because no results are returned
        $query->execute();
        while($row = $query->fetch())
        {
            $provider = new Provider();
            $provider->id = $row['appointmentproviderID'];
            $provider->firstName = $row['first_name'];
            $provider->lastName = $row['last_name'];
            $provider->email = $row['email'];
            $provider->emr_provider_id = $row['hospital_doctor_id'];
            $provider->local_provider_id = $row['id'];

            //provider needs to be associated with services
            $providers[] = $provider;
        }

        return $providers;
    }

    function generateMappingRecord($eaproviderID, $providerID)
    {

        //insert
        $sql = "INSERT INTO provider_appointmentprovider (providerID, appointmentproviderID)
                    VALUES (:providerID, :eaproviderID)";
        $query = $this->pdo->prepare($sql);
        // use exec() because no results are returned
        $query->execute(array(
            'providerID'=>$providerID,
            'eaproviderID'=>$eaproviderID
        ));
    }

   
    /**
     * This function returns the schedule timeslot match between the date and time
     * Date String format is mm/dd/yyyy
     *
     * @param $eaproviderID
     * @param $eacategoryID
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public function getProviderSchedule($eaproviderID, $eacategoryID, $startDate, $endDate)
    {
        //get the facility ID by categoryID
        $srv_conn = new ServiceConnector();

        $facilityID = $srv_conn->getFacilityIDByCategoryID($eacategoryID);
        //get the doctor id from provider ID in the join, then get the schedule based on the doctor id
        $sql = "SELECT * FROM provider_appointmentprovider pa, doctor p, schedule s WHERE appointmentproviderID = :eaproviderID and pa.providerID = p.ID and s.doctor_id = p.id and schedule_data LIKE :facilityID";
        $query = $this->pdo->prepare($sql);
        $query->execute(array(
            'eaproviderID'=>$eaproviderID,
            'facilityID'=>"%\"facilityid\":\"". $facilityID . "\"%"
        ));

        $schedules = array();

        //loop through data to get schedule data
        while($row = $query->fetch()) {

            //get the data in json form
            $dataJson = json_decode($row['schedule_data']);
            //create new schedule based on the json data
            $schedule = new Schedule();
            $schedule->start = $dataJson->slottimestart;
            $schedule->end = $dataJson->slottimeend;
            $schedule->id = $dataJson->apptslotid;
            $schedule->providerID = $row['doctor_id'];
            $schedule->eaproviderID = $eaproviderID;
            $schedule->eacategoryID = $eacategoryID;

            print_r($schedule->start);
            //validate datetime
            if (strtotime($schedule->start)>=\DateTime::createFromFormat('m-d-Y',$startDate)->getTimestamp() && strtotime($schedule->end)<=\DateTime::createFromFormat('m-d-Y',$endDate)->getTimestamp())
            {
                $schedules[] = $schedule;
            }



        }
        return $schedules;
    }
}