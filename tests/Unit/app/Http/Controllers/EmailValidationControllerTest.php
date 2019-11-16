<?php

namespace Tests\app\Http\Controllers;

use App\Http\Controllers\EmailValidationController;
use App\Services\EmailValidationService;
use Tests\TestCase;
use Mockery;

/**
 * Class EmailValidationControllerTest
 *
 * @package Tests\app\Http\Controllers
 */
class EmailValidationControllerTest extends TestCase
{
    /**
     * @test
     * @see EmailValidationController::validate()
     */
    public function validateReturns422ForMissingEmailParameter()
    {
        $mockEmailValidationService = Mockery::mock(EmailValidationService::class)
            ->shouldReceive("validateEmail")
            ->never()
            ->getMock();

        $this->app->instance(EmailValidationService::class, $mockEmailValidationService);
        $data = ['provider' => 'kickbox'];
        $response = $this->postJson(route('validateEmail'), $data);

        $response->assertStatus(422);
        $this->assertEquals('The email param is required', json_decode($response->getContent(), true)['message']);
    }

    /**
     * @test
     * @see EmailValidationController::validate()
     */
    public function validateReturnsProperMessageForMissingEmailParameter()
    {
        $mockEmailValidationService = Mockery::mock(EmailValidationService::class)
            ->shouldReceive("validateEmail")
            ->never()
            ->getMock();

        $this->app->instance(EmailValidationService::class, $mockEmailValidationService);
        $data = [];
        $response = $this->postJson(route('validateEmail'), $data);

        $this->assertEquals('The email param is required', json_decode($response->getContent(), true)['message']);
    }

    /**
     * @test
     * @see EmailValidationController::validate()
     */
    public function validateReturns200ForMissingProviderParameter()
    {
        $mockEmailValidationService = Mockery::mock(EmailValidationService::class)
            ->shouldReceive("validateEmail")
            ->once()
            ->withArgs(['hotmail.com', null])
            ->andReturn(false)
            ->getMock();

        $this->app->instance(EmailValidationService::class, $mockEmailValidationService);
        $data = ['email' => 'hotmail.com'];
        $response = $this->postJson(route('validateEmail'), $data);

        $response->assertStatus(200);
    }

    /**
     * @test
     * @see EmailValidationController::validate()
     */
    public function validateReturns200ForSuccess()
    {
        $mockEmailValidationService = Mockery::mock(EmailValidationService::class)
            ->shouldReceive("validateEmail")
            ->once()
            ->withArgs(['hotmail.com', 'kickbox'])
            ->andReturn(false)
            ->getMock();

        $this->app->instance(EmailValidationService::class, $mockEmailValidationService);
        $data = ['email' => 'hotmail.com', 'provider' => 'kickbox'];
        $response = $this->postJson(route('validateEmail'), $data);

        $response->assertStatus(200);
    }
}
