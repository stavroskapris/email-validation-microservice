<?php

namespace App\Providers\Validation;

use App\Http\Models\LogExceptions;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

class OpenKickBoxIo extends ValidationProvider
{
    /**
     * open api endpoint to check for disposable domains
     */
    const DISPOSABLE_CHECK_API_URL = 'https://open.kickbox.com/v1/disposable/';

    /**
     * Exception
     */
    const EXCEPTION_TYPE = 'openKickBoxIo_request_failure';

    /**
     * @param string $domain
     * @return string
     * @throws GuzzleException
     * @throws Exception
     */
    public function validateByRequest(string $domain): string
    {

        try {
            $response = $this->client->request(
                'GET',
                self::DISPOSABLE_CHECK_API_URL.$domain,
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
            $this->isDisposable = $responseContent['disposable'] == true ? 'true' : 'false';

            //cache result
            $this->cacheService->set($domain, $this->isDisposable, config('cache.ttl'));

            return $this->isDisposable;
        } catch (Exception $e) {
            LogExceptions::log($e, self::EXCEPTION_TYPE);
            throw $e;
        }
    }























    ///**
    // * @param $domain
    // * @return bool
    // */
    //public function makeRequest($domain)
    //{
    //    return $this->handleSuccessResponse($this->isDisposableDomainApiCall($domain));
    //}
    //
    ///**
    // * @param String $domain
    // * @return bool
    // */
    //private function isDisposableDomainApiCall(String $domain)
    //{
    //    //return cached value if present
    //    if ($cachedRes = $this->getCachedResponse($domain)) {
    //        return $cachedRes;
    //    }
    //    //return stored response if present
    //    if ($storedRes = $this->getStoredResponse($domain)) {
    //        return $storedRes;
    //    }
    //    // check the external service using the domain only
    //    $request = self::DISPOSABLE_CHECK_API_URL.$domain;
    //    $this->curl->init(self::DISPOSABLE_CHECK_API_URL);
    //    $this->curl->setOption(CURLOPT_URL, $request);
    //    $this->curl->setOption(CURLOPT_RETURNTRANSFER, true);
    //    $this->curl->setOption(
    //        CURLOPT_HTTPHEADER,
    //        [
    //            'Content-Type: application/json',
    //            'Accept: application/json'
    //        ]
    //    );
    //    $this->curl->setOption(CURLOPT_CONNECTTIMEOUT, 5);
    //    $this->curl->setOption(CURLOPT_TIMEOUT, 5);
    //
    //    $response = $this->curl->execute();
    //    if (!$response || $this->curl->error() !== 0) {
    //        $this->curl->close();
    //
    //        return $this->handleErrorResponse('Service Unavailable', 1001);
    //    }
    //    $this->curl->close();
    //    // decode the json response
    //    $data = json_decode($response, true);
    //    $isDisposable = $data['disposable'] == true ? 'yes' : 'no';
    //    //cache value
    //    $this->cacheResponse($domain, $isDisposable.'cachedapoKickBox');
    //    //store value
    //    $this->storeResponse($domain, $isDisposable);
    //
    //    return $isDisposable;
    //}
}
