<?php

namespace AppBundle\Controller;

use AppBundle\Service\PatientDataService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class APIController extends Controller
{
    var $validationToken="112112-1221-221212";
    var $unauthorizedMsg = "Invalid Auth Token";
    var $patientDataService;

    /**
     * @Route("/updatePatient", name="updatePatient")
     */
    public function updatePatientAction(Request $request)
    {
        if ($this->isRequestValid($request))
        {
            $this->patientDataService = new PatientDataService();
            $this->patientDataService->savePatientData($request->getContent());
            $message = $this->createMessageJson("Patient Updated");
        }
        else
        {
            $message = $this->createMessageJson($this->unauthorizedMsg);
        }

        return new Response(
            $message
        );
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return new Response(
            '<html><body>This is the patient load API.</body></html>'
        );
    }

    /**
     * @Route("/updateAppointment", name="updateAppointment")
     */
    public function updateAppointmentAction(Request $request)
    {
        if ($this->isRequestValid($request))
        {
            $this->patientDataService = new PatientDataService();
            $this->patientDataService->savePatientAppointmentData($request->getContent());
            $message = $this->createMessageJson("Patient Appointment Updated");
        }
        else
        {
            $message = $message = $this->createMessageJson($this->unauthorizedMsg);
        }

        return new Response(
            $message
        );

    }

    /**
     * Validate Request
     * @param Request $request
     * @return bool
     */
    private function isRequestValid(Request $request)
    {
        if ($request->get('validationToken') == $this->validationToken)
        {
            return true;
        }
        return false;
    }

    /**
     * Generate a json format message string
     * @param $message
     * @return array|string
     */
    private function createMessageJson($message)
    {
        $message = array('message'=>$message);
        $message = json_encode($message);
        return $message;
    }
}
