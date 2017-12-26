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
use AppBundle\Service\model\Settings;
use AppBundle\Service\PatientDataService;
use AppBundle\Service\easyappointment\RestAPI;
use AppBundle\Service\ProviderDataService;
use AppBundle\Service\ServiceDataService;

/**
 * This class sync all the existing content between Easy Appointment and local integration data
 * What it means, is that it will reset all data in easy appointment.
 *
 * Class EALocalSync
 * @package AppBundle\Service\easyappointment
 */
class EALocalSync extends RestAPI
{
    var $default_service = 13;

    public function syncAll()
    {
        $this->syncProviders();
        $this->syncCategories();
        //$this->syncPatients();
        //$this->syncAppointments();
        //$this->syncAvailability();
    }

    /**
     * Sync the patients from the patient api database to easy appointment
     */
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

                $this->repairPatientData($patient);

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

    /**
     * Sync all providers from api local database to easyappointment
     */
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
                $this->repairProviderData($provider);
                //update patient if patient exists
                if ($providerExists)
                {
                    //if integration link exists, call update api, if not, call insert api
                    $ea_providerConnector->updateProvider($provider);
                    //print_r("provider exists");
                }
                else
                {
                    //insert provider
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

           // print_r('Updated record for provider: ' . $provider->email);

        }
        print_r('The sync for provider is completed.');
        //if integration link exists, call update api, if not, call insert api
        //if insert is called, create integration link
    }

    public function syncCategories()
    {
        //get the services classes instantiated
        $serviceConnector = new CategoryConnector();
        //get all categories
        $categoryService = new ServiceDataService();
        //loop through and check if mapping exists in current db
        $services = $categoryService->getAllServices();

        foreach ($services as $service)
        {
            //if yes, update the category in easyappointment with new name, etc.
            if ($categoryID = $categoryService->checkServiceMappingExists($service->id)!==false)
            {
                $service->id = $categoryID;
                $serviceConnector->updateCategory($service);
            }
            else
            {
                //if no, insert category in easyappointment, get the id, and then insert the mapping here
                $response = json_decode($serviceConnector->insertCategory($service));
                $service->id = $response->id;
                $categoryService->generateMapping($service->id,$service->emrserviceID ,$service->name);
            }

        }

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

    /**
     * perform data augmentation to allow data sync to easy appointment correctly for patient data
     * @param $patient
     */
    public function repairPatientData(&$patient)
    {
        //fix patient data fields
        if ($patient->phone==null)
            $patient->phone='000-000-0000';
        if ($patient->email=='None')
            $patient->email=$patient->local_patient_id . '-default@360emed.hmtrevolution.com';
    }

    /**
     * repair provider data
     * @param Provider $provider
     */
    public function repairProviderData(Provider &$provider)
    {
        //fix patient data fields
        if ($provider->phone==null)
            $provider->phone='000-000-0000';
        if ($provider->email=='')
            $provider->email=$provider->local_provider_id . '-provider-default@360emed.hmtrevolution.com';

        //add the default service for sync
        $services = array();
        $services[] = $this->default_service;

        $provider->services = $services;

        $settings = new Settings();
        $settings->username = $provider->firstName . "." . $provider->lastName . $provider->local_provider_id;

        $provider->settings = $settings;

    }

}