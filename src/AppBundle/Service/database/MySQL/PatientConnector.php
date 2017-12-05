<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 9/22/17
 * Time: 3:47 PM
 */

namespace AppBundle\Service\database\MySQL;


use AppBundle\Service\model\Patient;

class PatientConnector extends DBConnector
{
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
     * returns all patients as Patient objects
     */
    function getAllPatients()
    {
        $patients = array();
        $sql = "SELECT * FROM patient p LEFT OUTER JOIN patient_scheduleuser ps ON p.id=ps.patientID ";
        $query = $this->pdo->prepare($sql);
        // use exec() because no results are returned
        $query->execute();
        while($row = $query->fetch())
        {
            $patient = new Patient();
            $patient->emr_patient_id = $row['hospital_patient_id'];
            $patient->firstName = $row['first_name'];
            $patient->lastName = $row['last_name'];
            $patient->email = $row['email'];
            $patient->id = $row['scheduleUserID'];
            $patient->local_patient_id = $row['id'];

            $patients[] = $patient;
        }

        return $patients;
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

    /**
     * Generating mapping record
     * @param $eapatientID
     * @param $patientID
     */
    function generateMappingRecord($eapatientID, $patientID)
    {
        //insert
        $sql = "INSERT INTO patient_scheduleuser (patientID, scheduleUserID)
                    VALUES (:patientID, :scheduleUserID)";
        $query = $this->pdo->prepare($sql);
        // use exec() because no results are returned
        $query->execute(array(
            'patientID'=>$patientID,
            'scheduleUserID'=>$eapatientID
        ));
    }
}