<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

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
        // remove "data": {} from the resources and return direct object
       JsonResource::withoutWrapping();

        // Using Gate to check if user can access some endpoint
        // check if user has access to view this routes
        \Gate::define('view', function(User $user, $model) {
            // return false;
            return $user->hasAccess("view_{$model}") || $user->hasAccess("edit_{$model}");
        });

        \Gate::define('edit', function(User $user, $model) {
            return $user->hasAccess("edit_{$model}");
        });
    }
}