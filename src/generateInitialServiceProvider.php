<?php

namespace torkzz\generateLaravelApi;

use Illuminate\Support\ServiceProvider;

class generateInitialServiceProvider extends ServiceProvider
{
  
    protected $commands = [
        'torkzz\generateLaravelApi\generate'
    ];
  
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
      
        $this->mergeConfigFrom(
            __DIR__ . '/config/generateLaravelApi.php',
            'generateLaravelApi'
        );
    
        $this->commands($this->commands);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        
        $this->loadViewsFrom(__DIR__.'/templates', 'generateLaravelApi');
        
        $this->publishes([
             __DIR__ . '/config/generateLaravelApi.php' => config_path('generateLaravelApi.php'),
         ], 'config');
         
    
        
        $this->publishes([
         __DIR__.'/templates' => resource_path('views/vendor/generateLaravelApi'),
       ], 'templates');
         
    }
    
    

}
