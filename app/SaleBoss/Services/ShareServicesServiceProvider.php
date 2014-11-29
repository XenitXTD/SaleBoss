<?php namespace SaleBoss\Services;
use Cartalyst\Sentry\Facades\Laravel\Sentry;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ShareServicesServiceProvider extends ServiceProvider {

    public function boot(){

        $this->app['view']->composer('admin.pages.dashboard.partials._my_notifications', 'SaleBoss\Services\Notification\Notification');
        $this->app->events->subscribe('SaleBoss\Events\NotificationEventHandler');

    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

}