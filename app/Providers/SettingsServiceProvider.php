<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Config;
use DB;

class SettingsServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        //
        // only use the Settings package if the Settings table is present in the database
        if (count(DB::select("SHOW TABLES LIKE 'settings'"))) {
            // get all settings from the database
            $settings = Setting::all();

            // bind all settings to the Laravel config, so you can call them like
            // call : Config::get('settings.contact_email') or config()->get('settings.contact_email')
            foreach ($settings as $key => $setting) {
                Config::set('settings.' . $setting->key, $setting->value);
            }
           Config::set('front_theme', 'frontend.' . Config::get('settings.frontend_theme'));
            
            Config::set('front_app', 'frontend.' . Config::get('settings.frontend_theme') .'.app');            
            Config::set('front_web', 'frontend.' . Config::get('settings.frontend_theme') .'.web');                        
            
            Config::set('back_theme', 'backend.' . Config::get('settings.backend_theme'));
     //       Config::set('assets_frontend', '/themes/frontend/' . Config::get('settings.frontend_theme') . '/');
            
            Config::set('assets_frontend_app', '/themes/frontend/' . Config::get('settings.frontend_theme') . '/app/');                        
            Config::set('assets_frontend_web', '/themes/frontend/' . Config::get('settings.frontend_theme') . '/web/');            
            
            Config::set('assets_backend', '/themes/backend/' . Config::get('settings.backend_theme') . '/');           
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        //
        $this->registerSettings();
    }

    private function registerSettings() {

        $this->app->bind('settings', function ($app) {
            return new Settings($app);
        });
    }

}
