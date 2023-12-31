<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::prefix('v1')->controller(AuthController::class)->group(function(){
    Route::post("/register","register");
    Route::post("/login","login");
});


Route::middleware('auth:sanctum')->group(function(){
    Route::post("/logout",[AuthController::class,"logout"])->name("logout");
    Route::apiResource("/post",PostController::class);
    Route::get("/me",[PostController::class,"profile"])->name("me");
});

