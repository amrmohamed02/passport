<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

// why the prefix route
Route::group(['as' => 'users.', 'prefix' => 'users'], function () {
    Route::post('/reg', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    Route::group(['middleware' => ['auth:user-api']], function () {
        Route::get('get_profile', [UserController::class, 'get_profile']);
        Route::post('edit_profile', [UserController::class, 'edit_profile']);
    });
});


