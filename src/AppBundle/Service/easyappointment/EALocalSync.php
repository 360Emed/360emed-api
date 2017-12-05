<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 12/2/17
 * Time: 9:55 PM
 */

namespace AppBundle\Service\easyappointment;

use AppBundle\Service\database\MySQL\PatientConnector;
use AppBundle\Service\easyappointment\PatientConnector as EAPatientConnector;
use AppBundle\Service\model\Patient;
use AppBundle\Service\PatientDataService;
use AppBundle\Service\easyappointment\RestAPI;

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
        //initialize patient services
        $patientService = new PatientDataService();
        $patients = $patientService->getAllPatients();

        $ea_patientConnector = new EAPatientConnector();

        //loop through all patients
        foreach ($patients as $patient)
        {
            $patientExists=false;
            if ($patient->id!=null && $patient->id!='')
            {
                $result = $ea_patientConnector->getPatient($patient);
                if ($result && $result->id!=null)
                {
                    //patient exists
                    $patientExists=true;
                }
            }
            //update patient if patient exists
            if ($patientExists)
            {
                //if integration link exists, call update api, if not, call insert api
                $ea_patientConnector->updatePatient($patient);
            }
            else
            {
                //insert patient
                $ea_patientConnector->insertPatient($patient);
                //when inserting, make sure the patient association record is there
                //if insert is called, create integration link
                $patientService->insertPatientMapping($patient);
            }

            print_r('Updated record for patient: ' . $patient->email);

        }


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