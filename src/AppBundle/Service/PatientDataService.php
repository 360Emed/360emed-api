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

        $this->saveToDBPatient($pfirstname,$plastname ,$pemail, $pid);
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
        $this->saveToDBAppointment($pfirstname,$plastname ,$pemail, $pid, $pdata);
    }

    private function saveToDBPatient($firstname, $lastname, $email, $pid, $data)
    {
        $this->sqlconnector=new PatientConnector();
        $pid = $this->sqlconnector->insertPatient($firstname, $lastname, $email, $pid);
        
    }

    private function saveToDBAppointment($firstname, $lastname, $email, $pid, $data)
    {
        $this->sqlconnector=new PatientConnector();
        $pid = $this->sqlconnector->insertPatient($firstname, $lastname, $email, $pid);
        $this->sqlconnector->insertData($pid,$data,'APPOINTMENT');
    }
}