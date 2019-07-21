<?php

namespace App\Services;

use App\Providers\ValidationProviders\OpenKickBoxIo;
use App\Providers\ValidationProviders\Debounce;
use App\Providers\ValidationProviders\ValidationProvider;

/**
 * Class ValidationProviderGetService
 *
 * @package App\Services
 */
class ValidationProviderGetService
{
    /**
     * @var OpenKickBoxIo
     */
    private $kickBoxIo;

    /**
     * @var Debounce
     */
    private $debounce;

    /**
     * ValidationProviderGetService constructor.
     *
     * @param OpenKickBoxIo $kickBoxIo
     * @param Debounce $debounce
     */
    public function __construct(OpenKickBoxIo $kickBoxIo, Debounce $debounce)
    {
        $this->debounce = $debounce;
        $this->kickBoxIo = $kickBoxIo;
    }

    /**
     * @param $provider
     * @return ValidationProvider
     */
    public function getValidationProvider(?string $provider): ValidationProvider
    {
        switch ($provider) {
            case 'kickbox' :
            default :
                return $this->kickBoxIo;
                break;
            case 'debounce' :
                return $this->debounce;
                break;
        }
    }
}
