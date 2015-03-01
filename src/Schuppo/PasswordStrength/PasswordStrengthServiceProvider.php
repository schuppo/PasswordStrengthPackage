<?php namespace Schuppo\PasswordStrength;

use Illuminate\Support\ServiceProvider;

class PasswordStrengthServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('passwordStrength', function() {
            return new PasswordStrength;
        });
	}

    public function boot()
    {
        $pS = $this->app->make('passwordStrength');

        $translator = $this->app['validator']->getTranslator();

        $translator->addNamespace('password-strength', __DIR__ . '/../lang');

        $translator->load('password-strength', 'validation', $translator->locale());

        $validator = $this->app['validator'];


        $validator->extend('letters', function ($attribute, $value, $parameters) use ($pS) {
            return $pS->validateLetters($attribute, $value, $parameters);
        }, $translator->get('password-strength::validation.letters'));

        $validator->extend('numbers', function ($attribute, $value, $parameters) use ($pS) {
            return $pS->validateNumbers($attribute, $value, $parameters);
        }, $translator->get('password-strength::validation.numbers'));

        $validator->extend('caseDiff', function ($attribute, $value, $parameters) use ($pS) {
            return $pS->validateCaseDiff($attribute, $value, $parameters);
        }, $translator->get('password-strength::validation.case_diff'));
		
		$validator->extend('symbols', function ($attribute, $value, $parameters) use ($pS) {
            return $pS->validateSymbols($attribute, $value, $parameters);
        }, $translator->get('password-strength::validation.symbols'));
		
    }

}
