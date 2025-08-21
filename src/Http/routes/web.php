<?php

use Illuminate\Support\Facades\Route;
use Ssh521\SimplePosts\Http\Controllers\PostController;

Route::prefix(config('simple-posts.route.prefix', 'posts'))
    ->name(config('simple-posts.route.name', 'posts.'))
    ->middleware(config('simple-posts.route.middleware', []))
    ->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::get('/create', [PostController::class, 'create'])->name('create');
        Route::post('/', [PostController::class, 'store'])->name('store');
        Route::get('/{post}', [PostController::class, 'show'])->name('show');
        Route::get('/{post}/edit', [PostController::class, 'edit'])->name('edit');
        Route::put('/{post}', [PostController::class, 'update'])->name('update');
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');
    });