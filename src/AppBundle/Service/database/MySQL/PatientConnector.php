<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 9/22/17
 * Time: 3:47 PM
 */

namespace AppBundle\Service\database\MySQL;


class PatientConnector
{
    var $pdo;


    function init()
    {
        try {
            $this->pdo = new \PDO("mysql:host=" . Config::host . ";dbname=" . Config::dbname, Config::username, Config::password);
            // set the PDO error mode to exception
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        }
        catch(\PDOException $e)
        {
            echo $e->getMessage();
        }

    }

    function checkPatientExists($firstname, $lastname, $email)
    {
        if ($this->pdo == null)
        {
            $this->init();
        }
        $sql = "SELECT id FROM patient
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

    function checkPatientExistsByHospitalPatientID($patientid)
    {
        if ($this->pdo == null)
        {
            $this->init();
        }
        $sql = "SELECT id FROM patient
                    WHERE hospital_patient_id=:PATIENTID LIMIT 1";
        $query = $this->pdo->prepare($sql);
        // use exec() because no results are returned
        $query->execute(array(
            'PATIENTID'=>$patientid));
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
     * Insert Patient, however if patient exists, return existing user id
     *
     * @param $firstname
     * @param $lastname
     * @param $email
     * @return String
     */
    function insertPatient($firstname, $lastname, $email, $pid)
    {
        if ($this->pdo == null)
        {
            $this->init();
        }

        $patientID = $this->checkPatientExistsByHospitalPatientID($pid);

        if ($patientID !==false)
            return $patientID;

        $sql = "INSERT INTO patient (first_name, last_name, email, hospital_patient_id)
                    VALUES (:FIRSTNAME, :LASTNAME, :EMAIL, :HOSPITALPATIENTID)";
        $query = $this->pdo->prepare($sql);
        // use exec() because no results are returned
        $query->execute(array(
            'FIRSTNAME'=>$firstname,
            'LASTNAME'=>$lastname,
            'EMAIL'=>$email,
            'HOSPITALPATIENTID'=>$pid));

        return $this->pdo->lastInsertId("id");

    }

    /**
     *
     * Always overwrite data for patient
     *
     * @param $patientID
     * @param $patientData
     */
    function insertData($patientID, $patientData, $type)
    {

        //insert
        $sql = "INSERT INTO patient_data (data, patient_id, data_type)
                    VALUES (:data, :patientID, :datatype)";
        $query = $this->pdo->prepare($sql);
        // use exec() because no results are returned
        $query->execute(array(
            'data'=>json_encode($patientData),
            'patientID'=>$patientID,
            'datatype'=>$type
        ));
        
    }
}