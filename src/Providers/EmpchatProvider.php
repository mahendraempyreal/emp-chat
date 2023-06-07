<?php

namespace Mahendraempyreal\EmpChat\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class EmpchatProvider extends ServiceProvider
{
    public function register()
    {
        app()->bind('ChatMessenger', function () {
            return new \Mahendraempyreal\EmpChat\ChatMessenger;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/eichat.php' => config_path('eichat.php'),
        ], 'empchat-config');
        // $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../views', 'empchat');
        $this->loadRoutes();

        // Load user's avatar folder from package's config
        $userAvatarFolder = json_decode(json_encode(include(__DIR__.'/../config/eichat.php')))->user_avatar->folder;

        // Assets
        $this->publishes([
            // CSS
            __DIR__ . '/../assets' => public_path('empchat'),
            __DIR__ . '/../imgs' => storage_path('app/public/' . $userAvatarFolder),
        ], 'empchat-assets');

    }
    protected function loadRoutes()
    {
        Route::group($this->routesConfigurations(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    /**
     * Routes configurations.
     *
     * @return array
     */
    private function routesConfigurations()
    {
        return [
            'as' => config('eichat.routes.as'),
            'prefix' => config('eichat.routes.prefix'),
            'namespace' =>  config('eichat.routes.namespace'),
            'middleware' => config('eichat.routes.middleware'),
        ];
    }
}