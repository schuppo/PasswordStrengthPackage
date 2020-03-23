<?php

namespace Schuppo\PasswordStrength\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Schuppo\PasswordStrength\PasswordStrengthServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            PasswordStrengthServiceProvider::class,
        ];
    }
}
