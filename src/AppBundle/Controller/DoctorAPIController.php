<?php

namespace AppBundle\Controller;

use AppBundle\Service\DoctorDataService;
use AppBundle\Service\PatientDataService;
use AppBundle\Utils\DataEncoder;
use AppBundle\Utils\Validator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DoctorAPIController extends Controller
{
    var $doctorDataService;


    public function updateDoctorAction(Request $request)
    {
        if (Validator::isRequestValid($request))
        {
            $this->doctorDataService = new DoctorDataService();
            $this->doctorDataService->saveDoctorData($request->getContent());
            $message = DataEncoder::createMessageJson("Doctor Updated");
        }
        else
        {
            $message = DataEncoder::createMessageJson(Validator::unauthorizedMsg);
        }

        return new Response(
            $message
        );
    }

    public function updateScheduleAction(Request $request)
    {
        if (Validator::isRequestValid($request))
        {
            $this->doctorDataService = new DoctorDataService();
            $this->doctorDataService->saveDoctorScheduleData($request->getContent());
            $message = DataEncoder::createMessageJson("Doctor Schedule Updated");
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
