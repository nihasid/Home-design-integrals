<?php

namespace ReqRespLog;

use Illuminate\Support\ServiceProvider;
use ReqRespLog\Middleware\RequestResponseLogger;

class RequestResponseLogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');

        $this->app['router']->aliasMiddleware('reqRespLog', RequestResponseLogger::class);

        $this->loadRoutesFrom(__DIR__ . '/Routes.php');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
