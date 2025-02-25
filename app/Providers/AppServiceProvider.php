<?php

namespace App\Providers;

use App\Models\Program;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // check if the table exists
        if (Schema::hasTable('programs')) {
            View::share('programs', Program::all());
        }
    }
}
