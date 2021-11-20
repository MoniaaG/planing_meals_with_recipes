<?php

namespace App\Providers;

use App\Repositories\CalendarRepository;
use App\Repositories\Interfaces\CalendarRepositoryInterface;
use App\Repositories\Interfaces\ProductCategoryRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\RecipeRepositoryInterface;
use App\Repositories\ProductCategoryRepository;
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
            ProductCategoryRepositoryInterface::class,
            ProductCategoryRepository::class
        );

        $this->app->bind(
            RecipeRepositoryInterface::class,
            RecipeRepository::class
        );

        $this->app->bind(
            CalendarRepositoryInterface::class,
            CalendarRepository::class
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