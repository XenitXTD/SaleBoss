<?php namespace SaleBoss\Services;
use Illuminate\Support\ServiceProvider;
use SaleBoss\Services\Authenticator\AuthenticatorInterface;
use SaleBoss\Services\Authenticator\SentryAuthenticator;
use SaleBoss\Repositories\NotificationRepositoryInterface;
use SaleBoss\Repositories\Eloquent\NotificationRepository;

class ServicesServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            AuthenticatorInterface::class,
            SentryAuthenticator::class
        );

        $this->app->bind(
            NotificationRepositoryInterface::class,
            NotificationRepository::class
        );
    }
}