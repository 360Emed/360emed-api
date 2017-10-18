<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 10/17/17
 * Time: 11:03 AM
 */

namespace AppBundle\Service;


use AppBundle\Service\database\DoctorConnector;

class DoctorDataService implements iDoctorDataService
{
    var $sqlconnector;

    function saveDoctorData($dataString)
    {
        // TODO: Implement saveDoctorData() method.
        $data = json_decode($dataString, true);

        $pfirstname = $data['doctor']['firstname'];
        $plastname = $data['doctor']['lastname'];
        $pemail = $data['doctor']['email'];
        $pid = $data['doctor']['doctorid'];

        $this->saveToDBDoctor($pfirstname,$plastname ,$pemail, $pid);
    }

    function saveDoctorScheduleData($dataString)
    {
        // TODO: Implement saveDoctorSchedulingData() method.
        $data = json_decode($dataString, true);
        $pfirstname = $data['doctor']['firstname'];
        $plastname = $data['doctor']['lastname'];
        $pemail = $data['doctor']['email'];
        $pid = $data['doctor']['doctorid'];
        $pdata = $data['doctor'];
        $this->saveToDBSchedule($pfirstname,$plastname ,$pemail, $pid, $pdata);
    }

    private function saveToDBDoctor($firstname, $lastname, $email, $did, $data)
    {
        $this->sqlconnector=new DoctorConnector();
        $pid = $this->sqlconnector->insertDoctor($firstname, $lastname, $email, $did);

    }

    private function saveToDBSchedule($firstname, $lastname, $email, $did, $data)
    {
        $this->sqlconnector=new DoctorConnector();
        $pid = $this->sqlconnector->insertDoctor($firstname, $lastname, $email, $did);
        $this->sqlconnector->insertData($pid,$data,'APPOINTMENT');
    }
}