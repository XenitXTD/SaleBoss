<?php

namespace SaleBoss\Services\EavSmartAss\Form;

use Illuminate\Support\ServiceProvider;

class FormServiceProvider extends ServiceProvider {

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
		$this->app['Formgenerator'] = $this->app->share(function($app)
		{
			return new FormGenerator($app['form']);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('Formgenerator');
	}
}