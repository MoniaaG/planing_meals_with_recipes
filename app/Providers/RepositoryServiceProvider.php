<?php

namespace App\Providers;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\RecipeRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Repositories\RecipeRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider 
{
    /**
     * Register services
     * 
     * @return void
     */

    public function register()
    {
        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );

        $this->app->bind(
            RecipeRepositoryInterface::class,
            RecipeRepository::class
        );
    }

    /**
     * Bootstrap services
     * 
     * @return void
     */
    public function boot()
    {
        //
    }

}