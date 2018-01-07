<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 1/7/18
 * Time: 11:18 AM
 */

namespace AppBundle\Service\easyappointment;


use AppBundle\Service\model\Schedule;

class ScheduleConnector extends RestAPI
{

    public function insertSchedule(Schedule $schedule)
    {
        //print_r($schedule);die;
        $post_uri = '/index.php/api/v1/schedule';
        $response = $this->client->post($post_uri,array(
            'body' => \GuzzleHttp\json_encode($schedule)
        ));

        if ($response->getStatusCode()!=200)
            throw new \Exception();

        return $response->getBody();
    }
}