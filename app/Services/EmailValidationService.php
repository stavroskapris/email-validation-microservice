<?php

namespace App\Services;


/**
 * Class EmailValidationService
 *
 * @package App\Services
 */
class EmailValidationService
{
    /**
     * @var ValidationProviderGetService
     */
    private $validationProviderGetService;

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
     * @param string      $domain
     * @param string|null $providerParam
     *
     * @return string
     */
    public function validateEmail(string $domain, ?string $providerParam): string
    {
        $provider = $this->validationProviderGetService->getValidationProvider($providerParam);

        return $provider->validate($domain);
    }
}
