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
        $messages = [
              "letters" => "The :attribute must include at least one letter.",
            "case_diff" => "The :attribute must include both upper and lower case letters.",
            "numbers" => "The :attribute must include at least one number."
        ];

        $this->package('schuppo/password-strength');

        $pS = $this->app->make('Schuppo\PasswordStrength');

        $translator = $this->app['validator']->getTranslator();

        $this->app['validator']->extend('letters', function ($attribute, $value, $parameters) use ($pS) {
            return $pS->validateLetters($attribute, $value, $parameters);
        }, $messages['letters']);
        $this->app['validator']->extend('numbers', function ($attribute, $value, $parameters) use ($pS) {
            return $pS->validateNumbers($attribute, $value, $parameters);
        }, $messages['numbers']);
        $this->app['validator']->extend('caseDiff', function ($attribute, $value, $parameters) use ($pS) {
            return $pS->validateCaseDiff($attribute, $value, $parameters);
        }, $messages['case_diff']);
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
