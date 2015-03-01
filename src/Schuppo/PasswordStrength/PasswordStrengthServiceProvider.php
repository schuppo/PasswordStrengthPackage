<?php namespace Schuppo\PasswordStrength;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\Translator;

class PasswordStrengthServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('passwordStrength', function() {
            return new PasswordStrength();
        });
	}

    public function boot(Factory $validator)
    {

        $passwordStrength = new PasswordStrength();

        /** @var Translator $translator */
        $translator = $validator->getTranslator();
        $translator->addNamespace('password-strength', __DIR__ . '/../lang');
        $translator->load('password-strength', 'validation', $translator->locale());

        foreach(['letters', 'numbers', 'caseDiff', 'symbols'] as $rule)
        {
            $validator->extend($rule, function ($_, $value, $_) use ($passwordStrength, $rule) {
                $capitalizedType = ucfirst($rule);
                return call_user_func([$passwordStrength, "validate$capitalizedType"], $value);
            }, $translator->get("password-strength::validation.$rule"));
        }
    }
}
