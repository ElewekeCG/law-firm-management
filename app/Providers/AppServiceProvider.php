<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\Http\View\Composers\NotificationComposer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        View::composer('*', NotificationComposer::class);
    }
}
