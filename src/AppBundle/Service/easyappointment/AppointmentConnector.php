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
        $response = $this->client->post($post_uri,array(
            'body' => \GuzzleHttp\json_encode($appointment)
        ));

        if ($response->getStatusCode()!=200)
            throw new \Exception();
        
        return $response->getBody();
    }

    public function updateAppointment(Appointment $appointment)
    {
        $put_uri = '/index.php/api/v1/appointments/' . $appointment->id;
        $response = $this->client->put($put_uri,array(
            'body' => \GuzzleHttp\json_encode($appointment)
        ));

        if ($response->getStatusCode()!=200)
            throw new \Exception();

    }

    public function cancelAppointment(Appointment $appointment)
    {
        $delete_uri = '/index.php/api/v1/appointments/' . $appointment->id;

        $response = $this->client->delete($delete_uri);
        if ($response->getStatusCode()!=200)
            throw new \Exception();
    }

    public function getAppointment(Appointment $appointment)
    {
        if ($appointment->id==null)
            return null;
        $get_uri = '/index.php/api/v1/appointments/' . $appointment->id;

        $response = $this->client->get($get_uri);
        if ($response->getStatusCode()!=200)
            throw new \Exception();
        return $response->getBody();
    }
}