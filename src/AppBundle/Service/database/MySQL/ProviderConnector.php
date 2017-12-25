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
        if ($this->pdo == null)
        {
            $this->init();
        }
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
        if ($this->pdo == null)
        {
            $this->init();
        }
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
        if ($this->pdo == null)
        {
            $this->init();
        }

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

    public function getFacilityIDByCategoryID($eacategoryID)
    {
        $sql = "SELECT categoryID FROM category_eacategory WHERE eacategoryID = :eacategoryID";
        $query = $this->pdo->prepare($sql);
        $query->execute(array(
            'eacategoryID'=>$eacategoryID
        ));
        //loop through data to get schedule data
        while($row = $query->fetch()) {
            return $row['categoryID'];
        }
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
        $facilityID = $this->getFacilityIDByCategoryID($eacategoryID);
        //get the doctor id from provider ID in the join, then get the schedule based on the doctor id
        $sql = "SELECT * FROM provider_appointmentprovider pa, provider p WHERE appointmentproviderID = :eaproviderID and pa.providerID = p.providerID and scheduleData LIKE '%\"facilityid\"' . ':' . '\":facilityID\"%'";
        $query = $this->pdo->prepare($sql);
        $query->execute(array(
            'eaproviderID'=>$eaproviderID,
            'facilityid'=>$facilityID
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

            //validate datetime
            if (strtotime($schedule->start)>=strtotime($startDate) && strtotime($schedule->end)<=strtotime($endDate))
            {
                $schedules[] = $schedule;
            }



        }
        return $schedules;
    }
}