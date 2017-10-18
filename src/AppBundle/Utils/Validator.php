<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 10/17/17
 * Time: 10:52 AM
 */

namespace AppBundle\Utils;

use Symfony\Component\HttpFoundation\Request;

class Validator
{
    const validationToken="112112-1221-221212";
    const unauthorizedMsg = "Invalid Auth Token";
    /**
     * Validate Request
     * @param Request $request
     * @return bool
     */
    public static function isRequestValid(Request $request)
    {
        if ($request->get('validationToken') == Validator::validationToken)
        {
            return true;
        }
        return false;
    }

}