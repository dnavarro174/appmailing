<?php

namespace App\Providers;

use App\Repositories\EstudianteRepository;
use App\Repositories\Interfaces\IEstudianteRepository;
use Illuminate\Support\ServiceProvider;

class EstudianteServiceProvider extends ServiceProvider
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
        $this->app->bind(IEstudianteRepository::class, EstudianteRepository::class);
    }
}
