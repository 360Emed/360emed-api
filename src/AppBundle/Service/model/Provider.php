<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 11/25/17
 * Time: 10:28 PM
 */

namespace AppBundle\Service\model;


class Provider
{
    var $id;
    var $firstName;
    var $lastName;
    var $email;
    var $phone;
    var $mobile;
    var $address;
    var $city;
    var $state;
    var $zip;
    var $notes;
    var $emr_provider_id;
    var $local_provider_id;
    //array of service IDs only
    var $services;
    //this is eaappointment specific model attributes
    var $settings;
}