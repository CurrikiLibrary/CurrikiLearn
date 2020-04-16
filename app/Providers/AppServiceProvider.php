<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Bind CallingAndMessagingInterface to Twilio implementation.
         */
        $this->app->bind(
            'App\Contracts\Services\WpApiInterface', 'App\Services\WpApi\GuzzleHttp'
        );

        /**
         * Bind UserRepositoryInterface to UserRepository implementation.
         */
        $this->app->bind(
            'App\Contracts\Repositories\UserRepositoryInterface', 'App\Repositories\UserRepository'
        );

        /**
         * Bind ResourceRepositoryInterface to ResourceRepository implementation.
         */
        $this->app->bind(
            'App\Contracts\Repositories\ResourceRepositoryInterface', 'App\Repositories\ResourceRepository'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
//        URL::forceScheme('https');
        if(env('APP_ENV') == 'production')
        {
            $url->forceScheme('https');
        }
    }
}
