<?php

namespace App\Providers\ValidationProviders;

use App\Http\Models\LogExceptions;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

class Debounce extends ValidationProvider
{
    /**
     * open api endpoint to check for disposable domains
     */
    const DISPOSABLE_CHECK_API_URL = 'https://disposable.debounce.io/?email=';

    /**
     * Exception
     */
    const EXCEPTION_TYPE = 'debounce_request_failure';

    /**
     * @param string $domain
     * @return bool
     * @throws GuzzleException
     * @throws Exception
     */
    public function validateByRequest(string $domain): bool
    {
        $fakeEmail = 'stavros@'.$domain;
        try {
            $response = $this->client->request(
                'GET',
                self::DISPOSABLE_CHECK_API_URL.$fakeEmail,
                [
                    'connect_timeout' => 5,
                    'timeout'         => 3
                ]
            );

            $responseContent = json_decode(
                $response->getBody()
                    ->getContents(),
                true
            );

            $this->isDisposable = $responseContent['disposable'] == 'true';
            //cache result
            $this->cacheService->set($domain, $this->isDisposable, config('cache.ttl'));

            return $this->isDisposable;
        } catch (Exception $e) {
            LogExceptions::log($e, self::EXCEPTION_TYPE);
            throw $e;
        }
    }
}
