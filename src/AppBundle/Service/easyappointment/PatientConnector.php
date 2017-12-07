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
        print_r(\GuzzleHttp\json_encode($patient));
        $post_uri = '/index.php/api/v1/customers';
        $response = $this->client->post($post_uri,array(
            'body' => \GuzzleHttp\json_encode($patient)
        ));

        if ($response->getStatusCode()!=200)
            throw new \Exception();

        return $response->getBody();
    }

    public function updatePatient(Patient $patient)
    {
        print_r(\GuzzleHttp\json_encode($patient));
        $post_uri = '/index.php/api/v1/customers/' . $patient->id;
        $response = $this->client->put($post_uri,array(
            'body' => \GuzzleHttp\json_encode($patient)
        ));

        if ($response->getStatusCode()!=200)
            throw new \Exception();

        return $response->getBody();
    }

    public function getPatient(Patient $patient)
    {
        if ($patient->id==null)
            return null;
        $post_uri = '/index.php/api/v1/customers/' . $patient->id;
        $response = $this->client->get($post_uri);


        if ($response->getStatusCode()>=500)
            throw new \Exception();

        return $response->getBody();
    }

    public function deletePatient(Patient $patient)
    {
        $post_uri = '/index.php/api/v1/customers/' . $patient->id;
        $response = $this->client->delete($post_uri);

        if ($response->getStatusCode()!=200)
            throw new \Exception();

        return $response->getBody();
    }
}