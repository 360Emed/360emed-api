<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 10/17/17
 * Time: 10:54 AM
 */

namespace AppBundle\Utils;


class DataEncoder
{
    /**
     * Generate a json format message string
     * @param $message
     * @return array|string
     */
    public static function createMessageJson($message)
    {
        $message = array('message'=>$message);
        $message = json_encode($message);
        return $message;
    }
}