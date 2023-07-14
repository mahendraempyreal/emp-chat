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
        $this->publishes([
            __DIR__ . '/../database/migrations/2023_06_02_102925_add_active_status_to_users.php' => database_path('migrations/' . date('Y_m_d') . '_102925_add_active_status_to_users.php'),
            __DIR__ . '/../database/migrations/2023_06_02_102925_add_avatar_to_users.php' => database_path('migrations/' . date('Y_m_d') . '_102925_add_avatar_to_users.php'),
            __DIR__ . '/../database/migrations/2023_06_02_102925_create_ei_chat_message_table.php' => database_path('migrations/' . date('Y_m_d') . '_102925_create_ei_chat_message_table.php'),
        ], 'empchat-migrations');
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
        // dd($this->apiRoutesConfigurations());
        Route::group($this->apiRoutesConfigurations(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
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
    private function apiRoutesConfigurations()
    {
        return [
            'prefix' => config('eichat.api_routes.prefix'),
            'namespace' =>  config('eichat.api_routes.namespace'),
            'middleware' => config('eichat.api_routes.middleware'),
        ];
    }
}