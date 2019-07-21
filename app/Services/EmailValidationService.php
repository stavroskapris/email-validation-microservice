<?php

namespace App\Services;

use App\Providers\ValidationProviders\ValidationProvider;

/**
 * Class EmailValidationService
 *
 * @package App\Services
 */
class EmailValidationService
{
    /**
     * @var
     */
    private $validationProviderGetService;

    /**
     * @var ValidationProvider
     */
    private $provider;

    /**
     * EmailValidationService constructor.
     *
     * @param ValidationProviderGetService $validationProviderSetService
     */
    public function __construct(ValidationProviderGetService $validationProviderSetService)
    {
        $this->validationProviderGetService = $validationProviderSetService;
    }

    /**
     * @param string $domain
     * @param string|null $provider
     * @return bool
     */
    public function validateEmail(string $domain, ?string $provider): bool
    {
        $this->provider = $this->validationProviderGetService->getValidationProvider($provider);

        return $this->provider->validate($domain);
    }
}