<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

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

Route::redirect('/', '/login');

Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth', 'verified']
], function () {

    Route::get('dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard.index');

    Route::get('customer', [CustomerController::class, 'index'])
        ->name('admin.customer.index');

    Route::get('profile', [ProfileController::class, 'index'])
        ->name('admin.profile.index');

    Route::resource('order', OrderController::class, ['as' => 'admin'])
        ->only('index', 'show');

    Route::resource('slider', SliderController::class, ['as' => 'admin'])
        ->only('index', 'store', 'destroy');

    Route::resources([
        'category' => CategoryController::class,
        'product' => ProductController::class,
        'user' => UserController::class
    ], [
        'as' => 'admin',
        'except' => 'show'
    ]);
});

Route::fallback(function () {
    abort(404);
});

