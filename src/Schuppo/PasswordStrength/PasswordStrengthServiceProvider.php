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
		$this->app->bindShared('passwordStrength', function() {
            return PasswordStrength;
        });
	}

    public function boot()
    {
        \Validator::resolver(function($translator, $data, $rules, $messages)
        {
            return new PasswordStrength($translator, $data, $rules, $messages);
        });
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
