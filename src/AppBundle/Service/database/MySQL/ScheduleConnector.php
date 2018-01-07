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
use AppBundle\Service\model\Schedule;
use AppBundle\Service\ProviderDataService;
use AppBundle\Service\ServiceDataService;

class ScheduleConnector extends DBConnector
{

    public function getAllSchedules()
    {
        $provider_service = new ProviderDataService();
        $service_service = new ServiceDataService();
        //get the doctor id from provider ID in the join, then get the schedule based on the doctor id
        $sql = "SELECT DISTINCT doctor_id, schedule_data FROM schedule s";
        $query = $this->pdo->prepare($sql);
        $query->execute();

        $schedules = array();

        //loop through data to get schedule data
        while($row = $query->fetch()) {
            //get the data in json form
            $dataJson = json_decode($row['schedule_data']);
            $eaproviderID = $provider_service->getEAProviderIDByProviderID($row['doctor_id']);
            $eacategoryID = $service_service->getEACategoryIDByFacilityID($dataJson->facilityid);

            //create new schedule based on the json data
            $schedule = new Schedule();
            $schedule->start = $dataJson->slottimestart;
            $schedule->end = $dataJson->slottimeend;
            $schedule->id = $dataJson->apptslotid;
            $schedule->providerID = $row['doctor_id'];
            $schedule->eaproviderID = $eaproviderID;
            $schedule->eacategoryID = $eacategoryID;
            $schedule->emrproviderID = $dataJson->doctorid;
            $schedule->emrcategoryID = $dataJson->facilityid;
            
            $schedules[] = $schedule;
        }
        return $schedules;
    }
}