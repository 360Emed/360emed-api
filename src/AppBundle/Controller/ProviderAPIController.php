<?php

namespace AppBundle\Controller;

use AppBundle\Service\ProviderDataService;
use AppBundle\Service\PatientDataService;
use AppBundle\Utils\DataEncoder;
use AppBundle\Utils\Validator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProviderAPIController extends Controller
{
    var $doctorDataService;


    public function updateProviderAction(Request $request)
    {
        if (Validator::isRequestValid($request))
        {
            $this->doctorDataService = new ProviderDataService();
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
            $this->doctorDataService = new ProviderDataService();
            $this->doctorDataService->saveDoctorScheduleData($request->getContent());
            $message = DataEncoder::createMessageJson("Doctor Schedule Updated");
        }
        else
        {
            $message = DataEncoder::createMessageJson(Validator::unauthorizedMsg);
        }

        return new Response(
            $message
        );

    }

    public function getProviderScheduleAction(Request $request, $providerID, $startDate, $endDate)
    {
        if (Validator::isRequestValid($request))
        {
            $this->doctorDataService = new ProviderDataService();
            $schedulData = $this->doctorDataService->getProviderSchedule($providerID, $startDate, $endDate);
            //get the schedule data and return as json body
            $message = \json_encode($schedulData);
        }
        else
        {
            $message = DataEncoder::createMessageJson(Validator::unauthorizedMsg);
        }

        return new Response(
            $message
        );

    }
}
