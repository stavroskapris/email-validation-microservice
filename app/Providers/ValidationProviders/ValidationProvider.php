<?php

namespace App\Providers\ValidationProviders;

use App\Services\Cache\AbsentCacheService\AbsentCacheService;
use App\Services\CacheService;
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
    ) {
        $this->client = $client;
        $this->cacheService = $cacheService;
    }

    /**
     * @param string $domain
     * @return bool
     */
    abstract function validateByRequest(string $domain): bool;

    /**
     * @param string $domain
     * @return bool
     */
    public function validate(string $domain): bool
    {
        if (!$this->cacheService instanceof AbsentCacheService && $this->isDisposable = $this->cacheService->get($domain)) {
            return $this->isDisposable;
        } else {
            return $this->validateByRequest($domain);
        }
    }
}
