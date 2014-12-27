<?php namespace SaleBoss\Services\ViewBuilder;


use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class ViewBuilderServiceProvider extends ServiceProvider {

	protected $defered = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('ViewBuilder',function($app)
		{
			return App::make('SaleBoss\Services\ViewBuilder\CallBuilder');
		});
	}
}