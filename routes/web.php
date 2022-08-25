<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\Front\IndexController;
use App\Http\Controllers\Front\PostController as FrontPostController;
use App\Http\Controllers\Dashboard\PostController as DashboardPostController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', IndexController::class)->name('index');

Route::prefix('dashboard')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/', function () {
            return Inertia::render('Dashboard/Index');
        })->name('dashboard');

        Route::resource('posts', DashboardPostController::class)->except(['update']);
        Route::prefix('posts')->group(function () {
            Route::put('/publish/{post}', [DashboardPostController::class, 'publish'])->name('posts.publish');
            Route::post('/update/{post}', [DashboardPostController::class, 'update'])->name('posts.update');
        });
    });

require __DIR__ . '/auth.php';

Route::get('/{post:slug}', [FrontPostController::class, 'show'])->name('front.posts.show');
