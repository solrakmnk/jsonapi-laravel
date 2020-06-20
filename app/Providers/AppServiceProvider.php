<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
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
        Builder::macro('jsonPaginate', function () {
            return $this->paginate(
                request('page.size'),
                ['*'],
                'page[number]',
                request('page.number')
            )->appends(request()->except('page.number'));
        });
    }
}
