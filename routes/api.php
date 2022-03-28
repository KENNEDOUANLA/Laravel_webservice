<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TokenController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('account')->group(function () {

    Route::controller(UserController::class)->group(function () {

        Route::get('/{id}',"index");

        Route::post('/',"Create");

        Route::put('/{id}',"Modifier");

    });
});

Route::prefix('token')->group(function () {

    Route::controller(TokenController::class)->group(function () {

        Route::get('/{accessToken}',"Valide");

        Route::post('/',"Create");

        Route::post('/{refreshToken}',"Refresh");
        
    });

});
