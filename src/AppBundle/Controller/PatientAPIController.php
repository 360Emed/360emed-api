<?php

namespace AppBundle\Controller;

use AppBundle\Service\PatientDataService;
use AppBundle\Utils\DataEncoder;
use AppBundle\Utils\Validator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PatientAPIController extends Controller
{

    var $patientDataService;

    /**
     * @Route("/updatePatient", name="updatePatient")
     */
    public function updatePatientAction(Request $request)
    {
        if (Validator::isRequestValid($request))
        {
            $this->patientDataService = new PatientDataService();
            $this->patientDataService->savePatientData($request->getContent());
            $message = DataEncoder::createMessageJson("Patient Updated");
        }
        else
        {
            $message = DataEncoder::createMessageJson(Validator::unauthorizedMsg);
        }

        return new Response(
            $message
        );
    }

    public function indexAction(Request $request)
    {
        return new Response(
            '<html><body>This is the patient load API.</body></html>'
        );
    }

    public function updateAppointmentAction(Request $request)
    {
        if (Validator::isRequestValid($request))
        {
            $this->patientDataService = new PatientDataService();
            $this->patientDataService->savePatientAppointmentData($request->getContent());
            $message = DataEncoder::createMessageJson("Patient Appointment Updated");
        }
        else
        {
            $message = $message = DataEncoder::createMessageJson(Validator::unauthorizedMsg);
        }

        return new Response(
            $message
        );

    }

   
}
