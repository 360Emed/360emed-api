<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 12/3/17
 * Time: 7:02 PM
 */

namespace AppBundle\Controller;

use AppBundle\Service\easyappointment\EALocalSync;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Utils\Validator;

class SyncController extends Controller
{

    public function syncEasyAppointmentAction(Request $request)
    {
        if (Validator::isRequestValid($request))
        {
            $syncservice = new EALocalSync();
            $syncservice->syncAll();
            $message = 'Sync data is completed';
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