<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RajaOngkirController;
use App\Http\Controllers\Api\SliderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// auth routes
Route::post('/register', [AuthController::class, 'register'])->name('api.customer.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.customer.login');
Route::get('/user', [AuthController::class, 'getUser'])->name('api.customer.user');

// order routes
Route::get('/order', [OrderController::class, 'index'])->name('api.order.index');
Route::get('/order/{snap_token?}', [OrderController::class, 'show'])->name('api.order.show');

// category routes
Route::get('/categories', [CategoryController::class, 'index'])->name('customer.category.index');
Route::get('/category/{slug?}', [CategoryController::class, 'show'])->name('customer.category.show');
Route::get('/categoryHeader', [CategoryController::class, 'categoryHeader'])->name('customer.category.categoryHeader');

// product routes
Route::get('/products', [ProductController::class, 'index'])->name('customer.product.index');
Route::get('/product/{slug?}', [ProductController::class, 'show'])->name('customer.product.show');

// cart routes
Route::get('/cart', [CartController::class, 'index'])->name('customer.cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('customer.cart.store');
Route::get('/cart/totalPrice', [CartController::class, 'getCartTotalPrice'])->name('customer.cart.totalPrice');
Route::get('/cart/totalWeight', [CartController::class, 'getCartTotalWeight'])->name('customer.cart.totalWeight');
Route::post('/cart/remove', [CartController::class, 'removeCart'])->name('customer.cart.remove');
Route::post('/cart/removeAll', [CartController::class, 'removeAllCart'])->name('customer.cart.removeAll');

// raja ongkir routes
Route::get('/rajaongkir/provinces', [RajaOngkirController::class, 'getProvinces'])->name('customer.rajaongkir.getProvinces');
Route::get('/rajaongkir/cities', [RajaOngkirController::class, 'getCities'])->name('customer.rajaongkir.getCities');
Route::post('/rajaongkir/getCost', [RajaOngkirController::class, 'getCost'])->name('customer.rajaongkir.getCost');

// midtrans routes
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::post('/notificationHandler', [CheckoutController::class, 'notificationHandler'])->name('notificationHandler');

// sliders route
Route::get('/sliders', [SliderController::class, 'index'])->name('customer.slider.index');
