<?php

namespace App\Providers\Validation;

use App\Providers\Cache\AbsentCache;
use App\Services\Cache\CacheService;
use GuzzleHttp\Client;

/**
 * Class AbstractValidationProvider
 *
 * @package App\Providers\ValidationProviders
 */
abstract class ValidationProvider
{
    /**
     * @var Client
     */
    public $client;

    /**
     * @var CacheService
     */
    public $cacheService;

    /**
     * @var bool
     */
    public $isDisposable;

    /**
     * AbstractValidationProvider constructor.
     *
     * @param Client $client
     * @param CacheService $cacheService
     */
    public function __construct(
        Client $client,
        CacheService $cacheService
    )
    {
        $this->client = $client;
        $this->cacheService = $cacheService;
    }

    /**
     * @param string $domain
     * @return string
     */
    abstract function validateByRequest(string $domain): string;

    /**
     * @param string $domain
     * @return string
     */
    public function validate(string $domain): string
    {
        if (!$this->cacheService instanceof AbsentCache && $this->isDisposable = $this->cacheService->get($domain)) {

            return $this->isDisposable . ' cached';
        } else {
            return $this->validateByRequest($domain);
        }
    }
}
