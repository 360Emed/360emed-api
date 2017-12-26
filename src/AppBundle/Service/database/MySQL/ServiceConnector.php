<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 12/26/17
 * Time: 2:02 PM
 */

namespace AppBundle\Service\database\MySQL;


use AppBundle\Service\model\Service;

class ServiceConnector extends DBConnector
{
    /**
     *
     * @param $eacategoryID
     * @return mixed
     *
     */
    public function getFacilityIDByCategoryID($eacategoryID)
    {
        $sql = "SELECT categoryID FROM category_eacategory WHERE eacategoryID = :eacategoryID";
        $query = $this->pdo->prepare($sql);
        $query->execute(array(
            'eacategoryID'=>$eacategoryID
        ));
        //loop through data to get schedule data
        while($row = $query->fetch()) {
            return $row['categoryID'];
        }
    }
    public function getServices()
    {
        $services = array();

        //get the doctor id from provider ID in the join, then get the schedule based on the doctor id
        $sql = "SELECT schedule_data FROM schedule";
        $query = $this->pdo->prepare($sql);
        $query->execute();

        //loop through data to get schedule data
        while($row = $query->fetch()) {

            //get the data in json form
            $dataJson = json_decode($row['schedule_data']);
            $service = new Service();
            $service->emrserviceId = $dataJson->facilityid;
            $service->name = $dataJson->facilityname;
            $services[$service->emrserviceId] = $service;
        }

        return $services;
    }

    function generateMappingRecord($easerviceID, $serviceID, $serviceName)
    {
        //insert
        $sql = "INSERT INTO category_eacategory (categoryID, eacategoryID, categoryName)
                    VALUES (:categoryID, :eacategoryID, :categoryName)";
        $query = $this->pdo->prepare($sql);
        // use exec() because no results are returned
        $query->execute(array(
            'categoryID'=>$serviceID,
            'eacategoryID'=>$easerviceID,
            'categoryName'=>$serviceName
        ));
    }

    function checkMappingExists($serviceID)
    {
        print_r('Service ID to check if mapping exists: ' . $serviceID);
        $sql = "SELECT * FROM category_eacategory
                    WHERE categoryID = :categoryID";
        $query = $this->pdo->prepare($sql);
        // use exec() because no results are returned
        $query->execute(array(
            'categoryID'=>$serviceID));
        $count = $query->rowCount();
        if ($count>0)
        {
            $row = $query->fetch();
            return $row['eacategoryID'];
        }

        return null;
    }

    function updateMappingRecord($serviceID, $easerviceID)
    {
        //update
        $sql = "UPDATE category_eacategory SET eacategoryID=:eacategoryID WHERE categoryID = :categoryID";
        $query = $this->pdo->prepare($sql);
        // use exec() because no results are returned
        $query->execute(array(
            'categoryID'=>$serviceID,
            'eacategoryID'=>$easerviceID
        ));
    }
}