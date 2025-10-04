<?php

use Illuminate\Support\Facades\Route;
use Ssh521\SimplePosts\Http\Controllers\SimplePostController;

Route::prefix(config('simple-posts.route.prefix', 'posts'))
    ->name(config('simple-posts.route.name', 'posts.'))
    ->middleware(config('simple-posts.route.middleware', []))
    ->group(function () {
        Route::get('/', [SimplePostController::class, 'index'])->name('index');
        Route::get('/create', [SimplePostController::class, 'create'])->name('create');
        Route::post('/', [SimplePostController::class, 'store'])->name('store');
        Route::post('/upload-image', [SimplePostController::class, 'uploadImage'])->name('upload-image');
        Route::get('/{post}', [SimplePostController::class, 'show'])->name('show');
        Route::get('/{post}/edit', [SimplePostController::class, 'edit'])->name('edit');
        Route::put('/{post}', [SimplePostController::class, 'update'])->name('update');
        Route::delete('/{post}', [SimplePostController::class, 'destroy'])->name('destroy');
    });
