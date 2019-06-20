<?php

namespace Putchi\StateKeepers;

use Illuminate\Support\ServiceProvider;

class StateKeepersServiceProvider extends ServiceProvider {

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot() {
        $this->loadTranslationsFrom(__DIR__.'/resources/lang/en', 'state');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/config/state.php' => config_path('state.php'),
            ], 'config');
            $this->publishes([
                __DIR__.'/resources/lang/en' => resource_path('lang/en/state'),
            ]);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        //
    }
}