<?php

namespace Illuminate\Tests\Integration\Auth;

use Illuminate\Support\Facades\Gate;
use Illuminate\Tests\Integration\Auth\Fixtures\AuthenticationTestUser;
use Illuminate\Tests\Integration\Auth\Fixtures\Policies\AuthenticationTestUserPolicy;
use Orchestra\Testbench\TestCase;

/**
 * @group integration
 */
class GatePolicyResolutionTest extends TestCase
{
    public function testPolicyCanBeGuessedUsingClassConventions()
    {
        $this->assertInstanceOf(
            AuthenticationTestUserPolicy::class,
            Gate::getPolicyFor(AuthenticationTestUser::class)
        );

        $this->assertInstanceOf(
            AuthenticationTestUserPolicy::class,
            Gate::getPolicyFor(Fixtures\Models\AuthenticationTestUser::class)
        );

        $this->assertNull(
            Gate::getPolicyFor(static::class)
        );
    }

    public function testPolicyCanBeGuessedUsingCallback()
    {
        Gate::guessPolicyNamesUsing(function () {
            return AuthenticationTestUserPolicy::class;
        });

        $this->assertInstanceOf(
            AuthenticationTestUserPolicy::class,
            Gate::getPolicyFor(AuthenticationTestUser::class)
        );
    }

    public function testPolicyCanBeGuessedMultipleTimes()
    {
        Gate::guessPolicyNamesUsing(function () {
            return [
                'App\\Policies\\TestUserPolicy',
                AuthenticationTestUserPolicy::class,
            ];
        });

        $this->assertInstanceOf(
            AuthenticationTestUserPolicy::class,
            Gate::getPolicyFor(AuthenticationTestUser::class)
        );
    }
}
