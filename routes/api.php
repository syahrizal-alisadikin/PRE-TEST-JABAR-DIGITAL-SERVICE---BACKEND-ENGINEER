<?php

use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\LoginController;
use Illuminate\Http\Request;
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

Route::post('/register', RegisterController::class)->name('register');
Route::post('/login', LoginController::class)->name('login');


Route::middleware('auth:api')->group(function () {
    Route::get('/warga', [LoginController::class, 'warga'])->name('warga');
    Route::get('/products', [LoginController::class, 'products'])->name('products');
    Route::get('/product/admin', [LoginController::class, 'productsAdmin'])->name('product_admin');
    Route::get('/claim', [LoginController::class, 'claim'])->name('claim');
});
