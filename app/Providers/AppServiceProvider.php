<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        Schema::defaultStringLength(191);
        \Carbon\Carbon::setLocale(config('app.locale'));
        $this->defineSingletonTenant();
        $this->setConnection();

        if(app()->isProduction()) {
            \URL::forceScheme('https');
        }
    }


    protected function defineSingletonTenant()
    {
        app()->singleton('tenant', function(){
            $app = new \stdClass();
            $name = '';
            $url = request()->root();
            preg_match("/^http[s]?:\/\/(?:www\.)?([a-z0-9\-]+)([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/", $url, $result);
            if(count($result) > 2){
                $name = $result[1];
            }
            $app->name = $name;
            return $app;
        });
        $app = app()->make('tenant');
        view()->share('tenant', $app);
    }

    protected function setConnection()
    {
        if (in_array(app('tenant')->name, array_keys (config('mercagest.tenants')))) {
            config()->set('database.default', app('tenant')->name);
        } else {
            app('tenant')->name = env('TENANT_NAME', 'demo');
            config()->set('database.default', 'mysql');
        }
    }

}
