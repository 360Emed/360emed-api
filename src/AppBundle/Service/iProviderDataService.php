<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 10/17/17
 * Time: 11:02 AM
 */

namespace AppBundle\Service;


interface iProviderDataService
{
    function saveDoctorData($dataString);
    function saveDoctorScheduleData($dataString);
}