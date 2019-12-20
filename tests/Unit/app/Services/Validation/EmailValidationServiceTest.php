<?php

namespace Tests\Unit\app\Services\Validation;

use App\Providers\Validation\Debounce;
use App\Providers\Validation\OpenKickBoxIo;
use App\Services\EmailValidationService;
use App\Services\ValidationProviderGetService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Mockery;
use Tests\TestCase;

/**
 * Class EmailValidationServiceTest
 *
 * @package Tests\Unit\app\Services
 */
class EmailValidationServiceTest extends TestCase
{
    /**
     * @test
     * @throws BindingResolutionException
     * @see EmailValidationService::validateEmail()
     */
    public function validateEmailUsesOpenKickBoxIoParamAsExpected()
    {
        $mockOpenKickBoxIo = Mockery::mock(OpenKickBoxIo::class)
            ->shouldReceive('validate')
            ->withArgs(['hotmail.com'])
            ->once()
            ->andReturn(true)
            ->getMock();
        $mockValidationProviderGetService = Mockery::mock(ValidationProviderGetService::class)
            ->shouldReceive("getValidationProvider")
            ->withArgs(['kickbox'])
            ->once()
            ->andReturn($mockOpenKickBoxIo)
            ->getMock();

        $this->app->instance(ValidationProviderGetService::class, $mockValidationProviderGetService);
        /** @var EmailValidationService $emailValidationService */
        $emailValidationService = app()->make(EmailValidationService::class);

        $result = $emailValidationService->validateEmail('hotmail.com', 'kickbox');

        $this->assertEquals('1',$result);
    }

    /**
     * @test
     * @throws BindingResolutionException
     * @see EmailValidationService::validateEmail()
     */
    public function validateEmailUsesDebounceParamAsExpected()
    {
        $mockOpenKickBoxIo = Mockery::mock(Debounce::class)
            ->shouldReceive('validate')
            ->withArgs(['foo.com'])
            ->once()
            ->andReturn(true)
            ->getMock();
        $mockValidationProviderGetService = Mockery::mock(ValidationProviderGetService::class)
            ->shouldReceive("getValidationProvider")
            ->withArgs(['debounce'])
            ->once()
            ->andReturn($mockOpenKickBoxIo)
            ->getMock();

        $this->app->instance(ValidationProviderGetService::class, $mockValidationProviderGetService);
        /** @var EmailValidationService $emailValidationService */
        $emailValidationService = app()->make(EmailValidationService::class);

        $result = $emailValidationService->validateEmail('foo.com', 'debounce');

        $this->assertEquals('1',$result);
    }
}
