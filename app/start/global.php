<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/database/seeds',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path().'/logs/laravel.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception, $code)
{
	Log::error($exception);
    //return Redirect::to('dash')->with('error_message','مشکلی در پردازش درخواست شما وجود دارد. لطفا به بخش نرم افزار اطلاع دهید.');
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function()
{
	return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

/*
* Log SQL QUERIES FOR PERFORMANCE
*/
Event::listen('illuminate.query', function($query, $bindings, $time, $name)
{
	if(Config::get('app.debug')){
		$data = compact('bindings', 'time', 'name');

// Format binding data for sql insertion
		foreach ($bindings as $i => $binding)
		{
			if ($binding instanceof \DateTime){
				$bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
			}
			else if (is_string($binding)){
				$bindings[$i] = "'$binding'";
			}
		}

// Insert bindings into query
		$query = str_replace(array('%', '?'), array('%%', '%s'), $query);
		$query = vsprintf($query, $bindings);

		Log::info($query, $data);
	}
});

/*
|--------------------------------------------------------------------------
| Register Notification Events
|--------------------------------------------------------------------------
| We want to check what panel are sending to user by notifications
|
*/

Notifynder::bootListeners();

View::share('opiloConfig',Config::get('saleboss/opilo_configs'));
require app_path().'/filters.php';
