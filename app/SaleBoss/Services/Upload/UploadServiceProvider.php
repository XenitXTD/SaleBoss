<?php namespace SaleBoss\Services\Upload;


use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class UploadServiceProvider extends ServiceProvider {

	protected $defered = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('Upload',function($app)
		{
			return App::make('SaleBoss\Services\Upload\Upload');
		});
	}
}