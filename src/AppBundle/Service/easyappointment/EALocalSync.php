<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 12/2/17
 * Time: 9:55 PM
 */

namespace AppBundle\Service\easyappointment;

/**
 * This class sync all the existing content between Easy Appointment and local integration data
 * What it means, is that it will reset all data in easy appointment.
 *
 * Class EALocalSync
 * @package AppBundle\Service\easyappointment
 */
class EALocalSync extends RestAPI
{
    public function syncAll()
    {
        $this->syncDoctors();
        $this->syncPatients();
        $this->syncAppointments();
        $this->syncAvailability();
    }

    public function syncPatients()
    {
        //loop through all patients
        //if integration link exists, call update api, if not, call insert api
        //if insert is called, create integration link
    }

    public function syncDoctors()
    {
        //loop through all doctors
        //if integration link exists, call update api, if not, call insert api
        //if insert is called, create integration link
    }

    public function syncAvailability()
    {
        //not sure how this works yet, need to figure it out
    }

    public function syncAppointments()
    {
        //loop through all appointments
        //if integration link exists, call update api, if not, call insert api
        //if insert is called, create integration link
    }

}