<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 9/22/17
 * Time: 3:43 PM
 */

namespace AppBundle\Service;

use AppBundle\Service\database\MySQL\PatientConnector;

class PatientDataService implements iPatientDataService
{
    var $sqlconnector;

    function savePatientData($dataString)
    {
        // TODO: Implement savePatientData() method.
        $data = json_decode($dataString, true);

        $pfirstname = $data['patient']['firstname'];
        $plastname = $data['patient']['lastname'];
        $pemail = $data['patient']['email'];
        $pid = $data['patient']['patientid'];

        //step 1 save to local integration DB
        $this->saveToDBPatient($pfirstname,$plastname ,$pemail, $pid);
        //step 2, check to see if the account exists in the easyappointment system by checking the local reference table
        //step 3, if exists, update patient, if NOT exists, insert patient
    }

    function savePatientAppointmentData($dataString)
    {
        // TODO: Implement savePatientData() method.
        $data = json_decode($dataString, true);
        $pfirstname = $data['patient']['firstname'];
        $plastname = $data['patient']['lastname'];
        $pemail = $data['patient']['email'];
        $pid = $data['patient']['patientid'];
        $pdata = $data['patient'];

        //step 1 save to local integration DB
        $this->saveToDBAppointment($pfirstname,$plastname ,$pemail, $pid, $pdata);
        //step 2, check to see if the appointment exists in the easyappointment system by checking the local reference table
        //step 3, if exists, update appointment, if NOT exists, insert appointment
        //step 4, handle removal of cancelled appointments, this is going to be tricky...  If the EA
        // appointments for the patient DOES not exist in the appointment load here, delete them from easy appointment
    }

    private function saveToDBPatient($firstname, $lastname, $email, $pid, $data)
    {
        $this->sqlconnector=new PatientConnector();
        //insert patient takes care of both insert and update
        $pid = $this->sqlconnector->insertPatient($firstname, $lastname, $email, $pid);
        
    }

    private function saveToDBAppointment($firstname, $lastname, $email, $pid, $data)
    {
        $this->sqlconnector=new PatientConnector();
        //insert patient takes care of both insert and update
        $pid = $this->sqlconnector->insertPatient($firstname, $lastname, $email, $pid);
        $this->sqlconnector->insertData($pid,$data,'APPOINTMENT');
    }

    private function getPatientIDs($emrPatientID)
    {
        $ids = array();

        //logic to retrieve patient IDs

        return $ids;
    }

    private function saveToEzyAppointment()
    {
        //implement logic to save data to ezy appointment
    }

    public function getAllPatients()
    {
        $this->sqlconnector=new PatientConnector();
        return $this->sqlconnector->getAllPatients();
    }
}