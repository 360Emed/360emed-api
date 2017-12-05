<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 11/25/17
 * Time: 10:33 PM
 */

namespace AppBundle\Service\easyappointment;

use AppBundle\Service\model\Appointment;
use AppBundle\Service\easyappointment\RestAPI;

class AppointmentConnector extends RestAPI
{
    public function insertAppointment(Appointment $appointment)
    {
        $post_uri = '/index.php/api/v1/appointments';
        $request = $this->client->post($post_uri,array(
            'content-type' => 'application/json'
        ),array());
        $request->setBody(\GuzzleHttp\json_encode($appointment));
        $response = $request->send();
        return $response->getBody();
    }

    public function updateAppointment(Appointment $appointment)
    {
        $put_uri = '/index.php/api/v1/appointments/' . $appointment->id;
        $request = $this->client->put($put_uri,array(
            'content-type' => 'application/json'
        ),array());
        $request->setBody(\GuzzleHttp\json_encode($appointment));
        $request->send();
    }

    public function cancelAppointment(Appointment $appointment)
    {
        $delete_uri = '/index.php/api/v1/appointments/' . $appointment->id;

        $this->client->delete($delete_uri)->send();
    }

    public function getAppointment(Appointment $appointment)
    {
        $get_uri = '/index.php/api/v1/appointments/' . $appointment->id;

        $response = $this->client->get($get_uri)->send();
        return $response->getBody();
    }
}