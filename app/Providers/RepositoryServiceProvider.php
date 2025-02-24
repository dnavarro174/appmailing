<?php

namespace App\Providers;

use App\Repositories\CampaniaRepository;
use App\Repositories\Interfaces\ICampaniaRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ICampaniaRepository::class, CampaniaRepository::class);
        //
    }
}
