<?php

namespace App\Providers;

use App\Foundation\Auth\CustomerEloquentAdminProvider;
use App\Foundation\Auth\CustomerEloquentHomeProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Auth::provider('customer-eloquent', function ($app, $config) {
            return new CustomerEloquentAdminProvider($this->app['hash'], $config['model']);
        });
        //自定义前台密码验证
        Auth::provider('customer-home-eloquent', function ($app, $config) {
            return new CustomerEloquentHomeProvider($this->app['hash'], $config['model']);
        });
    }
}
