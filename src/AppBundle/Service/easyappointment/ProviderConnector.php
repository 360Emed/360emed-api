<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 11/25/17
 * Time: 10:33 PM
 */

namespace AppBundle\Service\easyappointment;

use AppBundle\Service\model\Provider;

class ProviderConnector extends RestAPI
{
    public function insertProvider(Provider $provider)
    {
        $post_uri = '/api/v1/providers';
        $post_body = array(RequestOptions::JSON =>
            [
                'foo' => 'bar'
            ]
        );
        $this->client->post($post_uri);
    }

    public function updateProvider(Provider $provider)
    {
        $post_uri = '/api/v1/providers';
        $post_body = array(RequestOptions::JSON =>
            [
                'foo' => 'bar'
            ]
        );
        $this->client->post($post_uri);
    }

    public function getProvider(Provider $provider)
    {
        $post_uri = '/api/v1/providers';
        $post_body = array(RequestOptions::JSON =>
            [
                'foo' => 'bar'
            ]
        );
        $this->client->post($post_uri);
    }
}