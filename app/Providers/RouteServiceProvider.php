<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    protected $routeSettings = [
        [
            'version'           => 'v1',
            'storeNamespace'    => 'App\Http\Controllers\Store',
            'adminNamespace'    => 'App\Http\Controllers\Admin',
            'storeFile'         => 'store',
            'adminFile'         => 'admin',
            'commonFile'        => 'common',
            'storePrefix'       => 'store',
            'adminPrefix'       => 'admin',
        ]
    ];

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));

        foreach( $this->routeSettings as $routeSetting )
        {
            // store routes file
            Route::prefix( $routeSetting['version'] . '/' . $routeSetting['storePrefix'] )
                ->middleware( 'api' )
                ->namespace( $routeSetting['storeNamespace'] )
                ->group( base_path('routes/' . $routeSetting['version'] . '/' . $routeSetting['storeFile'] . '.php') );

            // admin routes file
            Route::prefix( $routeSetting['version'] . '/' . $routeSetting['adminPrefix'] )
                ->middleware( 'api' )
                ->namespace( $routeSetting['adminNamespace'] )
                ->group( base_path('routes/' . $routeSetting['version'] . '/' . $routeSetting['adminFile'] . '.php') );

            // common routes file
            Route::prefix( $routeSetting['version'] )
                ->middleware( 'api' )
                ->namespace($this->namespace)
                ->group( base_path('routes/' . $routeSetting['version'] . '/' . $routeSetting['commonFile'] . '.php') );
        }
    }
}
