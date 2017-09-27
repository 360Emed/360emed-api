<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 9/22/17
 * Time: 3:47 PM
 */

namespace AppBundle\Service\database;


class MySQLPatientConnector
{
    var $username = "360emed";
    var $password= "emed1@3";
    var $host = "localhost";
    var $port = "3306";
    var $dbname = "emed_patient_management";
    var $pdo;


    function init()
    {
        try {
            $this->pdo = new \PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
            // set the PDO error mode to exception
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            echo "New record created successfully";
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

    /**
     *
     * Insert Patient, however if patient exists, return existing user id
     *
     * @param $firstname
     * @param $lastname
     * @param $email
     * @return String
     */
    function insertPatient($firstname, $lastname, $email)
    {
        if ($this->pdo == null)
        {
            $this->init();
        }

        $patientID = $this->checkPatientExists($firstname, $lastname, $email);

        if ($patientID !==false)
            return $patientID;

        $sql = "INSERT INTO patient (first_name, last_name, email)
                    VALUES (:FIRSTNAME, :LASTNAME, :EMAIL)";
        $query = $this->pdo->prepare($sql);
        // use exec() because no results are returned
        $query->execute(array(
            'FIRSTNAME'=>$firstname,
            'LASTNAME'=>$lastname,
            'EMAIL'=>$email));

        return $this->pdo->lastInsertId("id");

    }

    /**
     *
     * Always overwrite data for patient
     *
     * @param $patientID
     * @param $patientData
     */
    function insertData($patientID, $patientData)
    {
        print_r('patient id: ' . $patientID);
        //delete existing patient data
        $sql = "DELETE FROM patient_data WHERE patient_id=:PATIENTID)";
        $query = $this->pdo->prepare($sql);
        // use exec() because no results are returned
        $query->execute(array(
            'PATIENTID'=>$patientID
        ));

        //insert
        $sql = "INSERT INTO PATIENT_DATA (patient_id, data)
                    VALUES (:data, :patientID)";
        $query = $this->pdo->prepare($sql);
        // use exec() because no results are returned
        $query->execute(array(
            'data'=>$patientData,
            'patientID'=>$patientID
        ));
        
    }
}