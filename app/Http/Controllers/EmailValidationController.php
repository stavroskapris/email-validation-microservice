<?php

namespace App\Http\Controllers;

use App\Http\Models\LogExceptions;
use App\Services\EmailValidationService;
use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Class EmailValidationController
 *
 * @package App\Http\Controllers
 */
class EmailValidationController
{
    /**
     * Exception
     */
    const EXCEPTION_TYPE = 'emailValidationController';

    /**
     * @param EmailValidationService $emailValidationService
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function validate(EmailValidationService $emailValidationService): JsonResponse
    {

        try {
            $email = trim(request()->input('email'));
            //check if required email param is present and if not
            //terminate process by returning proper response
            if (empty($email)) {
                return response()->json(

                    [
                        'message' => 'The email param is required',
                    ],
                    422
                );
            }

            //strip domain out of email
            if (strrpos($email, '@')) {
                $domain = substr($email, strrpos($email, '@') + 1);
            } else {
                //just the domain
                $domain = substr($email, strrpos($email, '@'));
            }
            //get provider param
            $providerToUse = ! empty(request()->input('provider')) ? trim(request()->input('provider')) : null;

            return response()->json(
                ['disposable' => $emailValidationService->validateEmail($domain, $providerToUse)],
                200
            );
        } catch (Exception $e) {
            LogExceptions::log($e, self::EXCEPTION_TYPE);
            throw $e;
        }
    }
}
