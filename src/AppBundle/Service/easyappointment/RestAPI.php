<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 11/25/17
 * Time: 10:09 PM
 */

namespace AppBundle\Service\easyappointment;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use AppBundle\Service\model\Patient;

class RestAPI
{
    var $client;
    var $base_uri = 'https://localhost-easyappointment-api';
    var $username = 'api_user';
    var $password = 'api-test-password';

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->base_uri,
            'verify' => false,
            'timeout'  => 5.0,
            'auth' => [$this->username, $this->password],
            'headers' => ['Content-Type' => 'application/json']
        ]);
    }

}