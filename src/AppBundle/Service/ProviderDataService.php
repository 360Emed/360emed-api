<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 10/17/17
 * Time: 11:03 AM
 */

namespace AppBundle\Service;

use AppBundle\Service\database\MySQL\ProviderConnector;
use AppBundle\Service\model\Provider;

class ProviderDataService implements iProviderDataService
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

        //Step 1 save doctor to local db
        $this->saveToDBDoctor($pfirstname,$plastname ,$pemail, $pid);

        //Step 2, check to see if doctor exists in integration table
        //Step 3, if doctor is not here, insert, else update
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

        //Step 1, save schedule in local db
        $this->saveToDBSchedule($pfirstname,$plastname ,$pemail, $pid, $pdata);

        //Step 2, update schedule in easy appointment, however, easy appointment does not offer availability
        //thus a new feature has to be created in easy appointment to allow availability updates
    }

    private function saveToDBDoctor($firstname, $lastname, $email, $did, $data)
    {
        $this->sqlconnector=new ProviderConnector();
        $pid = $this->sqlconnector->insertDoctor($firstname, $lastname, $email, $did);

    }

    private function saveToDBSchedule($firstname, $lastname, $email, $did, $data)
    {
        $this->sqlconnector=new ProviderConnector();
        $pid = $this->sqlconnector->insertDoctor($firstname, $lastname, $email, $did);
        //$this->sqlconnector->cleanScheduleData($pid);
        $this->sqlconnector->insertData($pid,$data,'APPOINTMENT');
    }

    public function getEASeviceIDsByProviderID($providerID)
    {
        $this->sqlconnector=new ProviderConnector();
        return $this->sqlconnector->getEAServiceIDs($providerID);
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

    public function getAllProviders()
    {
        $this->sqlconnector = new ProviderConnector();
        return $this->sqlconnector->getAllProviders();
    }

    public function insertProviderMapping(Provider $provider)
    {
        $this->sqlconnector=new ProviderConnector();
        $this->sqlconnector->generateMappingRecord($provider->id,$provider->local_provider_id);
    }

    public function getProviderSchedule($providerID, $categoryID, $startDate, $endDate)
    {
        $this->sqlconnector=new ProviderConnector();
        return $this->sqlconnector->getProviderSchedule($providerID,$categoryID, $startDate, $endDate);
    }
}