<?php

namespace Schuppo\PasswordStrength;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class PasswordStrengthServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        app()->singleton('passwordStrength', static function () {
            return new PasswordStrength();
        });

        app()->singleton('passwordStrength.translationProvider', static function () {
            return new PasswordStrengthTranslationProvider();
        });
    }

    /**
     * Bootstrap application services.
     */
    public function boot(Factory $validator): void
    {
        $passwordStrength = app('passwordStrength');
        $translator = app('passwordStrength.translationProvider')->get($validator);

        foreach(['letters', 'numbers', 'caseDiff', 'symbols'] as $rule) {
            $snakeCasedRule = Str::snake($rule);

            $validator->extend($rule, static function ($_, $value, $__) use ($passwordStrength, $rule) {
                $capitalizedRule = ucfirst($rule);
                return call_user_func([$passwordStrength, "validate{$capitalizedRule}"], $value);
            }, $translator->get("password-strength::validation.{$snakeCasedRule}"));
        }
    }
}
