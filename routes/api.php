<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware'=>'auth:sanctum'], function(){
});
Route::post('/login',[ApiController::class,'login']);
Route::post('/register',[ApiController::class,'register']);

Route::middleware('auth:sanctum')->get('/all-posts',[ApiController::class,'content']);

// Route::middleware('auth:sanctum')->get('/user-from-token', [ApiController::class, 'content']);

Route::middleware('auth:sanctum')->get('/all-roles',[ApiController::class,'roles']);
Route::middleware('auth:sanctum')->put('/update-post/{id}',[ApiController::class,'update']);
Route::middleware('auth:sanctum')->delete('/delete-post/{id}',[ApiController::class,'delete']);
Route::middleware('auth:sanctum')->post('/create-post',[ApiController::class,'create']);
Route::middleware('auth:sanctum')->delete('/delete-role/{id}',[ApiController::class,'deleteRole']);
Route::middleware('auth:sanctum')->put('/update-role/{id}',[ApiController::class,'updateRole']);