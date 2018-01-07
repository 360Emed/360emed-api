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

class ScheduleDataService
{
    var $sqlconnector;

    public function getAllSchedule()
    {
        $this->sqlconnector=new ProviderConnector();
        return $this->sqlconnector->getProviderSchedule();
    }
}