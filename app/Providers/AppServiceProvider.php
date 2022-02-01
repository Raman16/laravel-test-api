<?php

namespace App\Providers;

use Google\Client;
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
        $this->app->singleton(Client::class,function(){
            $client =  new  Client();//service container
            $config = config('services.google');
            $client->setClientId($config['key']);
            $client->setClientSecret($config['secret']);
            $client->setRedirectUri($config['redirect_url']);
            return $client;
        });
    }
 
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    
    }
}
