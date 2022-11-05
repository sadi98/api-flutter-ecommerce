<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ProductCategoryController;

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


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('product',[ProductController::class,'all']);
Route::get('categories',[ProductCategoryController::class,'all']);

Route::post('register',[UserController::class,'register']);
Route::post('login',[UserController::class,'login']);

/* Route di bawah ini untuk mengambil data user yg sudah login */
Route::middleware('auth:sanctrum')->group(function(){
    Route::controller(App\Http\Controllers\API\UserController::class)->group(function(){
        Route::get('user','fetch');
        Route::post('user','updateProfile');
        Route::post('logout','logout');
    });
    Route::controller(App\Http\Controllers\API\TransactionController::class)->group(function(){
        Route::get('transaction','all');
        Route::post('checkout','checkout');
    });

});
