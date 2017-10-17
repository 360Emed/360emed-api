<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 9/22/17
 * Time: 3:43 PM
 */

namespace AppBundle\Service;


interface iPatientDataService
{
    function savePatientData($dataString);
    function savePatientAppointmentData($dataString);

}