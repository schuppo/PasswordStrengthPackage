<?php

namespace Schuppo\PasswordStrength;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\Translator;

class PasswordStrengthServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        app()->singleton('passwordStrength', function () {
            return new PasswordStrength();
        });

        app()->singleton('passwordStrength.translationProvider', function () {
            return new PasswordStrengthTranslationProvider();
        });
    }

    /**
     * Bootstrap application services.
     *
     * @return void
     */
    public function boot(Factory $validator)
    {
        $passwordStrength = app('passwordStrength');
        $translator = app('passwordStrength.translationProvider')->get($validator);

        foreach(['letters', 'numbers', 'caseDiff', 'symbols'] as $rule) {
            $snakeCasedRule = snake_case($rule);

            $validator->extend($rule, function ($_, $value, $__) use ($passwordStrength, $rule) {
                $capitalizedRule = ucfirst($rule);
                return call_user_func([$passwordStrength, "validate{$capitalizedRule}"], $value);
            }, $translator->get("password-strength::validation.{$snakeCasedRule}"));
        }
    }
}
