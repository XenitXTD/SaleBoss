<?php

namespace SaleBoss\Repositories;


use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

        $this->app->bind(
            'SaleBoss\Repositories\MenuTypeRepositoryInterface',
            'SaleBoss\Repositories\Eloquent\MenuTypeRepository'
        );

        $this->app->bind(
            'SaleBoss\Repositories\MenuRepositoryInterface',
            'SaleBoss\Repositories\Eloquent\MenuRepository'
        );

		$this->app->bind(
			'SaleBoss\Repositories\UserRepositoryInterface',
			'SaleBoss\Repositories\Eloquent\UserRepository'
		);

		$this->app->bind(
			'SaleBoss\Repositories\GroupRepositoryInterface',
			'SaleBoss\Repositories\Eloquent\GroupRepository'
		);

		$this->app->bind(
			'SaleBoss\Repositories\StateRepositoryInterface',
			'SaleBoss\Repositories\Eloquent\StateRepository'
		);

		$this->app->bind(
			'SaleBoss\Repositories\OrderRepositoryInterface',
			'SaleBoss\Repositories\Eloquent\OrderRepository'
		);

        $this->app->bind(
            'SaleBoss\Repositories\OrderLogRepositoryInterface',
            'SaleBoss\Repositories\Eloquent\OrderLogRepository'
        );

        $this->app->bind(
            'SaleBoss\Repositories\LeadRepositoryInterface',
            'SaleBoss\Repositories\Eloquent\LeadRepository'
        );

		$this->app->bind(
			'SaleBoss\Repositories\PhoneRepositoryInterface',
			'SaleBoss\Repositories\Eloquent\PhoneRepository'
		);

		$this->app->bind(
			'SaleBoss\Repositories\TagRepositoryInterface',
			'SaleBoss\Repositories\Eloquent\TagRepository'
		);

		$this->app->bind(
			'SaleBoss\Repositories\TaskRepositoryInterface',
			'SaleBoss\Repositories\Eloquent\TaskRepository'
		);

		$this->app->bind(
			'SaleBoss\Repositories\TaskMessagesRepositoryInterface',
			'SaleBoss\Repositories\Eloquent\TaskMessagesRepository'
		);

		$this->app->bind(
			'SaleBoss\Repositories\UploadRepositoryInterface',
			'SaleBoss\Repositories\Eloquent\UploadRepository'
		);
	}
}