<?php namespace SaleBoss\Services\Notification;


use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider {

	protected $defered = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('Notification',function($app)
		{
			return App::make('SaleBoss\Services\Notification\Notification');
		});
	}
}