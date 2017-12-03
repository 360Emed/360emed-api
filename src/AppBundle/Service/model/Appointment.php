<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 11/25/17
 * Time: 10:23 PM
 */

namespace AppBundle\Service\model;


class Appointment
{
    var $id;
    var $start;
    var $end;
    var $hash;
    var $notes;
    var $patientID;
    var $providerID;
    var $serviceID;
    var $googleCalendarID;
}