<?php

namespace BiNet\Core\Providers;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
	/**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->singleton(Connection::class, function ($app) {
        //     return new Connection(config('riak'));
        // });

        # php-app
        $this->app->singleton(
            'BiNet\App\Support\Contracts\IHasher',
            'BiNet\App\Support\BcryptHasher'
        );

        $this->app->singleton(
            'BiNet\App\Validators\Contracts\IValidator',
            'BiNet\Core\Validators\Validator'
        );
    }
}