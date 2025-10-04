<?php

namespace Ssh521\SimplePosts;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Ssh521\SimplePosts\Models\Post;

class SimplePostsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/simple-posts.php', 'simple-posts');
    }

    public function boot()
    {
        // php artisan vendor:publish --tag=simple-posts-config
        // php artisan vendor:publish --tag=simple-posts-views
        // php artisan vendor:publish --tag=simple-posts-migrations
        // php artisan vendor:publish --tag=simple-posts-routes

        // 라우트 모델 바인딩 설정 (사용자가 라우트를 등록할 때 사용할 수 있도록)
        Route::model('post', Post::class);
        
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'simple-posts');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        
        if (config('simple-posts.auto_load_migrations', true)) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
        
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/simple-posts'),
            ], 'simple-posts-views');
            
            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'simple-posts-migrations');
            
            $this->publishes([
                __DIR__.'/../config/simple-posts.php' => config_path('simple-posts.php'),
            ], 'simple-posts-config');
            
            $this->publishes([
                __DIR__.'/../routes/web.php' => base_path('routes/simple-posts.php'),
            ], 'simple-posts-routes');
        }
    }
}