<?php

namespace App\Providers;

use App\Models\Post;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::bind('post', function ($value) {
            try {
                return Post::findOrFail($value); // Return the post or throw an exception
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                // If not found, throw a NotFoundHttpException
                throw new NotFoundHttpException('Post not found');
            }
        });
    }
}
