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

    public function __construct()
    {
        $this->sqlconnector = new ServiceConnector();
    }

    public function getAllServices()
    {
        return $this->sqlconnector->getServices();
    }

    public function generateMapping($easerviceID, $serviceID, $serviceName)
    {
        $this->sqlconnector->generateMappingRecord($easerviceID, $serviceID, $serviceName);
    }

    public function checkServiceMappingExists($serviceID)
    {
        return $this->sqlconnector->checkMappingExists($serviceID);
    }

    public function updateMapping($serviceID, $easerviceID)
    {
        $this->sqlconnector->updateMappingRecord($serviceID, $easerviceID);
    }

    public function getFacilityIDbyEACategoryID($eacategoryID)
    {
        return $this->sqlconnector->getFacilityIDByCategoryID($eacategoryID);
    }

    public function getEACategoryIDByFacilityID($facilityID)
    {
       return $this->sqlconnector->getEAServiceIDByFacilityID($facilityID);
    }
}