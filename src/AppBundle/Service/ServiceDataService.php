<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 12/26/17
 * Time: 2:24 PM
 */

namespace AppBundle\Service;


use AppBundle\Service\database\MySQL\ServiceConnector;

class ServiceDataService
{
    var $sqlconnector;

    public function getAllServices()
    {
        $this->sqlconnector=new ServiceConnector();
        return $this->sqlconnector->getServices();
    }

    public function generateMapping($easerviceID, $serviceID, $serviceName)
    {
        $this->sqlconnector=new ServiceConnector();
        $this->sqlconnector->generateMappingRecord($easerviceID, $serviceID, $serviceName);
    }

    public function checkServiceMappingExists($serviceID)
    {
        $this->sqlconnector=new ServiceConnector();
        return $this->sqlconnector->checkMappingExists($serviceID);
    }

    public function updateMapping($serviceID, $easerviceID)
    {
        $this->sqlconnector=new ServiceConnector();
        $this->sqlconnector->updateMappingRecord($serviceID, $easerviceID);
    }

    public function getFacilityIDbyEACategoryID($eacategoryID)
    {
        $this->sqlconnector=new ServiceConnector();
        $this->sqlconnector->getFacilityIDByCategoryID($eacategoryID);
    }
}