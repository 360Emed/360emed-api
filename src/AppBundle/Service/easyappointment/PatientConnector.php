<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 11/25/17
 * Time: 10:33 PM
 */

namespace AppBundle\Service\easyappointment;

use AppBundle\Service\model\Patient;
use AppBundle\Service\easyappointment\RestAPI;

class PatientConnector extends RestAPI
{
    public function insertPatient(Patient $patient)
    {
        $post_uri = '/index.php/api/v1/customers';
        $request = $this->client->post($post_uri,array(
            'content-type' => 'application/json'
        ),array());
        $request->setBody(\GuzzleHttp\json_encode($patient));
        $response = $request->send();
        return $response->getBody();
    }

    public function updatePatient(Patient $patient)
    {
        $post_uri = '/index.php/api/v1/customers/' . $patient->id;
        $request = $this->client->put($post_uri,array(
            'content-type' => 'application/json'
        ),array());
        $request->setBody(\GuzzleHttp\json_encode($patient));
        $response = $request->send();
        return $response->getBody();
    }

    public function getPatient(Patient $patient)
    {
        $post_uri = '/index.php/api/v1/customers/' . $patient->id;
        $request = $this->client->get($post_uri)->send();
        $response = $request->send();
        return $response->getBody();
    }

    public function deletePatient(Patient $patient)
    {
        $post_uri = '/index.php/api/v1/customers/' . $patient->id;
        $request = $this->client->delete($post_uri)->send();
        $response = $request->send();
        return $response->getBody();
    }
}