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
        $post_uri = '/api/v1/providers';
        $request = $this->client->post($post_uri,array(
            'content-type' => 'application/json'
        ),array());
        $request->setBody(\GuzzleHttp\json_encode($provider));
        $response = $request->send();
        return $response->getBody();
    }

    public function updateProvider(Provider $provider)
    {
        $post_uri = '/api/v1/providers' . $provider->id;
        $request = $this->client->put($post_uri,array(
            'content-type' => 'application/json'
        ),array());
        $request->setBody(\GuzzleHttp\json_encode($provider));
        $response = $request->send();
        return $response->getBody();
    }

    public function getProvider(Provider $provider)
    {
        $post_uri = '/api/v1/providers/' . $provider->id;
        $response = $this->client->post($post_uri)->send();
        return $response;
    }

    public function deleteProvider(Provider $provider)
    {
        $post_uri = '/api/v1/providers/' . $provider->id;
        $response = $this->client->delete($post_uri)->send();
        return $response;
    }

    /**
     * This function block an existing timeslot for the provider
     *
     * @param $blocking_appointment
     */
    public function blockTimeSlot($blocking_appointment)
    {

    }
}