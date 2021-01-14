<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\UserRestController as UserRestController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
// */

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::prefix('v1')->group(function () { 
    Route::get('/test',[UserRestController::class, 'test']);
    Route::post('/login',[UserRestController::class, 'login']);
    Route::post('/register',[UserRestController::class, 'register']);

    Route::group(['middleware' => 'auth:api' , 'role'], function () {
        // get user with roles 
        Route::get('/users',[UserRestController::class, 'list']);
    });
});
