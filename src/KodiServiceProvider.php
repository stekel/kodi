<?php

namespace stekel\Kodi;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\ServiceProvider;
use stekel\Kodi\Kodi;
use stekel\Kodi\KodiAdapter;

class KodiServiceProvider extends ServiceProvider
{
    /**
     * Defer loading until
     *
     * @var boolean
     */
    protected $defer = true;
    
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/Config/kodi.php' => config_path('kodi.php'),
        ]);
        
        $this->mergeConfigFrom(
            __DIR__.'/Config/kodi.php', 'kodi'
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('kodi.client', function($app) {
            
            return new GuzzleClient([
                'base_uri' => 'http://'.config('kodi.host').':'.config('kodi.port').'/',
                'timeout'  => 2.0,
            ]);
        });
        
        $this->app->bind(KodiAdapter::class, function($app) {
            
            return new KodiAdapter(app('kodi.client'));
        });

        $this->app->bind(MethodFactory::class, function($app) {

            return new MethodFactory(app(KodiAdapter::class));
        });

        $this->app->bind(Kodi::class, function($app) {
            
            return new Kodi(app(KodiAdapter::class), app(MethodFactory::class));
        });
    }
    
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'kodi.client',
            KodiAdapter::class,
            Kodi::class
        ];
    }
}
