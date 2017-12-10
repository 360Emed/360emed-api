<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 12/2/17
 * Time: 9:55 PM
 */

namespace AppBundle\Service\easyappointment;

use AppBundle\Service\easyappointment\PatientConnector as EAPatientConnector;
use AppBundle\Service\model\Patient;
use AppBundle\Service\model\Provider;
use AppBundle\Service\PatientDataService;
use AppBundle\Service\easyappointment\RestAPI;
use AppBundle\Service\ProviderDataService;

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
        $this->syncProviders();
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
            //this is the process for loading the patients
            try
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

                $this->repairData($patient);

                //update patient if patient exists
                if ($patientExists)
                {
                    //if integration link exists, call update api, if not, call insert api
                    $ea_patientConnector->updatePatient($patient);
                }
                else
                {
                    //insert patient
                    $response = json_decode($ea_patientConnector->insertPatient($patient));



                    $patient->id=$response->id;
                    //when inserting, make sure the patient association record is there
                    //if insert is called, create integration link
                    $patientService->insertPatientMapping($patient);
                }

            }
            catch (\Exception $e)
            {
                print_r($e->getMessage());
                //record the error
            }

            print_r('Updated record for patient: ' . $patient->email);

        }
        print_r('The sync for patients is completed.');
    }

    public function syncProviders()
    {
        //loop through all doctors
        $providerService = new ProviderDataService();
        $providers = $providerService->getAllProviders();

        $ea_providerConnector = new ProviderConnector();
        //loop through all patients
        foreach ($providers as $provider)
        {
            //this is the process for loading the patients
            try
            {
                $providerExists=false;
                if ($provider->id!=null && $provider->id!='')
                {
                    $result = $ea_providerConnector->getProvider($provider);
                    if ($result && $result->id!=null)
                    {
                        //patient exists
                        $providerExists=true;
                    }
                }

                $this->repairData($patient);

                //update patient if patient exists
                if ($providerExists)
                {
                    //if integration link exists, call update api, if not, call insert api
                    $ea_providerConnector->updateProvider($provider);
                }
                else
                {
                    //insert patient
                    $response = json_decode($ea_providerConnector->insertProvider($provider));



                    $provider->id=$response->id;
                    //when inserting, make sure the patient association record is there
                    //if insert is called, create integration link
                    $providerService->insertProviderMapping($provider);
                }

            }
            catch (\Exception $e)
            {
                print_r($e->getMessage());
                //record the error
            }

            print_r('Updated record for provider: ' . $provider->email);

        }
        print_r('The sync for patients is completed.');
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

    public function repairData(&$patient)
    {
        //fix patient data fields
        if ($patient->phone==null)
            $patient->phone='000-000-0000';
        if ($patient->email=='None')
            $patient->email=$patient->local_patient_id . '-default@360emed.hmtrevolution.com';


    }

}