<?php namespace Schuppo\PasswordStrength;

use Illuminate\Support\ServiceProvider;

class PasswordStrengthServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bindShared('Schuppo\PasswordStrength', function() {
            return new PasswordStrength;
        });
	}

    public function boot()
    {
        $this->package('schuppo/password-strength');

        $pS = $this->app->make('Schuppo\PasswordStrength');

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
    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
