<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 10/17/17
 * Time: 11:03 AM
 */

namespace AppBundle\Service;

use AppBundle\Service\database\MySQL\DoctorConnector;

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
        $this->sqlconnector->cleanScheduleData($pid);
        $this->sqlconnector->insertData($pid,$data,'APPOINTMENT');
    }

    private function getProviderIDs($emr_provider_id)
    {
        $ids = array();
        return $ids;
    }

    private function saveToEzyAppointment()
    {
        //implement logic to save data to ezy appointment
    }
}