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
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'state');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/config/state.php' => config_path('state.php'),
            ], 'config');
            $this->publishes([
                __DIR__.'/resources/lang' => resource_path('lang'),
            ], 'resources');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->mergeConfigFrom(
            __DIR__.'/config/state.php', 'state'
        );
    }
}