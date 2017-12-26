<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 12/26/17
 * Time: 2:36 PM
 */

namespace AppBundle\Service\easyappointment;

use AppBundle\Service\model\Service;

class ServiceConnector extends RestAPI
{
    public function insertService(Service $service)
    {
        $post_uri = '/index.php//api/v1/services';
        $response = $this->client->post($post_uri,array(
            'body' => \GuzzleHttp\json_encode($service)
        ));

        if ($response->getStatusCode()>299)
            throw new \Exception("Bad Status Code:" . $response->getStatusCode());

        return $response->getBody();
    }

    public function updateService(Service $service)
    {
        $post_uri = '/index.php//api/v1/services/' . $service->id;
        $response = $this->client->put($post_uri,array(
            'body' => \GuzzleHttp\json_encode($service)
        ));
        if ($response->getStatusCode()>299)
            throw new \Exception();
        return $response->getBody();
    }

    public function getService(Service $service)
    {
        if ($service->id==null)
            return null;
        $post_uri = '/index.php/api/v1/services/' . $service->id;
        $response = $this->client->get($post_uri);
        if ($response->getStatusCode()>=500)
            throw new \Exception();
        return $response;
    }

    public function deleteService(Service $service)
    {
        $post_uri = '/index.php/api/v1/services/' . $service->id;
        $response = $this->client->delete($post_uri);
        if ($response->getStatusCode() > 299)
            throw new \Exception();
        return $response;
    }
}