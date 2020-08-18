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

        $this->mapAuthRoutes();

        $this->mapHomeRoutes();

        $this->mapAdminRoutes();

    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapHomeRoutes()
    {
        Route::group([
            'namespace' => $this->namespace.'\Home',
        ], function ($router) {
            require base_path('routes/home.php');
        });
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
        Route::group([
            'middleware' => 'api',
            'namespace' => $this->namespace.'\Api',
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }
    protected function mapAuthRoutes()
    {
        Route::group([
            'middleware' => 'auth',
            'namespace' => $this->namespace,
            'prefix' => 'auth',
        ], function ($router) {
            require base_path('routes/auth.php');
        });
    }

    /**
     * 新增 自定义后台路由文件
     */
    protected function mapAdminRoutes()
    {
        Route::group([
            'namespace' => $this->namespace.'\Admin',
        ], function ($router) {
            require base_path('routes/admin.php');
        });
    }
}
