<?php

namespace LiveSelect;

use Illuminate\Support\ServiceProvider;
use Livewire;

class LiveSelectServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'live-select');
        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/live-select'),
        ]);

        Livewire::component('live-select', LiveSelect::class);
    }
}
