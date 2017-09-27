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
    var $patientDataService;

    /**
     * @Route("/uploadData", name="uploadData")
     */
    public function uploadAction(Request $request)
    {
        print_r($request->get('validationToken'));
        if ($request->get('validationToken') == $this->validationToken)
        {
            $this->patientDataService = PatientDataService();
            $this->patientDataService->savePatientData($request->getContent());
            $message = "successful";
        }
        else
        {
            $message = "Invalid token provided";
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
}
