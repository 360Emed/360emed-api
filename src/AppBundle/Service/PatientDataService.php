<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 9/22/17
 * Time: 3:43 PM
 */

namespace AppBundle\Service;

use AppBundle\Service\database\MySQLPatientConnector;

class PatientDataService implements iPatientDataService
{
    var $sqlconnector;

    function savePatientData($dataString)
    {
        // TODO: Implement savePatientData() method.
        $data = json_decode($dataString, false);

        $pdata = $data->patient->data;
        $pfirstname = $data->patient->firstname;
        $plastname = $data->patient->lastname;
        $pemail = $data->patient->email;

        $this->saveToDB($pfirstname,$plastname ,$pemail, $pdata);
    }

    private function saveToDB($firstname, $lastname, $email, $data)
    {
        $this->sqlconnector=new MySQLPatientConnector();
        $pid = $this->sqlconnector->insertPatient($firstname, $lastname, $email);
        $this->sqlconnector->insertData($pid,$data );
    }
}