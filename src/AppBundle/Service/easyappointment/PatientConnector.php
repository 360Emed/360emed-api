<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 11/25/17
 * Time: 10:33 PM
 */

namespace AppBundle\Service\easyappointment;

use AppBundle\Service\model\Patient;

class PatientConnector extends RestAPI
{
    public function insertPatient(Patient $Patient)
    {
        $post_uri = '/api/v1/customers';
        $post_body = array(RequestOptions::JSON =>
            [
                'foo' => 'bar'
            ]
        );
        $this->client->post($post_uri);
    }

    public function updatePatient(Patient $Patient)
    {
        $post_uri = '/api/v1/customers';
        $post_body = array(RequestOptions::JSON =>
            [
                'foo' => 'bar'
            ]
        );
        $this->client->post($post_uri);
    }

    public function getPatient(Patient $Patient)
    {
        $post_uri = '/api/v1/customers';
        $post_body = array(RequestOptions::JSON =>
            [
                'foo' => 'bar'
            ]
        );
        $this->client->post($post_uri);
    }

}