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
}