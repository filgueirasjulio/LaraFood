<?php

namespace App\Providers;



use App\Models\{Category, Plan, Company};
use App\Observers\{CategoryObserver, PlanObserver, CompanyObserver};
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
        Plan::observe(PlanObserver::class);
        Company::observe(CompanyObserver::class);
        Category::observe(CategoryObserver::class);
    }
}
