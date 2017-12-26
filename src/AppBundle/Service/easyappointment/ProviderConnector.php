<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 11/25/17
 * Time: 10:33 PM
 */

namespace AppBundle\Service\easyappointment;

use AppBundle\Service\model\Provider;
use AppBundle\Service\easyappointment\RestAPI;

class ProviderConnector extends RestAPI
{
    public function insertProvider(Provider $provider)
    {
        $post_uri = '/index.php/api/v1/providers';
        $response = $this->client->post($post_uri,array(
            'body' => \GuzzleHttp\json_encode($provider)
        ));

        if ($response->getStatusCode()>299)
            throw new \Exception("Bad Status Code:" . $response->getStatusCode());

        return $response->getBody()->getContents();
    }

    public function updateProvider(Provider $provider)
    {
        print_r(\GuzzleHttp\json_encode($provider));
        $post_uri = '/index.php/api/v1/providers' . $provider->id;
        $response = $this->client->put($post_uri,array(
            'body' => \GuzzleHttp\json_encode($provider)
        ));
        if ($response->getStatusCode()>299)
            throw new \Exception();
        return $response->getBody()->getContents();
    }

    public function getProvider(Provider $provider)
    {
        print_r($provider);
        if ($provider->id==null)
            return null;
        $post_uri = '/index.php/api/v1/providers/' . $provider->id;
        $response = $this->client->get($post_uri);
        if ($response->getStatusCode()>=500)
            throw new \Exception();
        return $response->getBody()->getContents();
    }

    public function deleteProvider(Provider $provider)
    {
        $post_uri = '/index.php/api/v1/providers/' . $provider->id;
        $response = $this->client->delete($post_uri);
        if ($response->getStatusCode()>299)
            throw new \Exception();
        return $response->getBody()->getContents();
    }

    /**
     * This function block an existing timeslot for the provider
     *
     * @param $blocking_appointment
     */
    public function blockTimeSlot($blocking_appointment)
    {
        //tbd
    }
}