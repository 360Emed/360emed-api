<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 11/25/17
 * Time: 10:33 PM
 */

namespace AppBundle\Service\easyappointment;

use AppBundle\Service\model\Appointment;

class AppointmentConnector extends RestAPI
{
    public function insertAppointment(Appointment $appointment)
    {
        $post_uri = '/api/v1/appointments';
        $request = $this->client->post($post_uri,array(
            'content-type' => 'application/json'
        ),array());
        $request->setBody(\GuzzleHttp\json_encode($appointment));
        $request->send();
    }

    public function updateAppointment(Appointment $appointment)
    {
        $post_uri = '/api/v1/appointments';
        $request = $this->client->post($post_uri,array(
            'content-type' => 'application/json'
        ),array());
        $request->setBody(\GuzzleHttp\json_encode($appointment));
    }

    public function cancelAppointment(Appointment $appointment)
    {
        $post_uri = '/api/v1/appointments';
        $post_body = array(RequestOptions::JSON =>
            [
                'foo' => 'bar'
            ]
        );
        $this->client->post($post_uri);
    }

    public function getAppointment(Appointment $appointment)
    {
        $post_uri = '/api/v1/appointments';
        $post_body = array(RequestOptions::JSON =>
            [
                'foo' => 'bar'
            ]
        );
        $this->client->post($post_uri);
    }
}