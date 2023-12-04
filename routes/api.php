<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;


Route::post('register', [AuthController::class, "register"]);
Route::post('login', [AuthController::class, "login"]);

Route::middleware(["auth:sanctum"])->group(function (){

    Route::post("logout", [AuthController::class, "logout"]);

    // Profile
    Route::get("profile", [ProfileController::class, "profile"]);
    Route::get("profile-post", [ProfileController::class, "posts"]);

    // Category (only get route, other create,update,delete will be controlled by backend)
    Route::get("categories", [CategoryController::class, "index"]);

    // Post
    Route::get("post", [PostController::class, "index"]);
    Route::post("post", [PostController::class, "create"]);
    Route::post("post/{id}", [PostController::class, "show"]);


});
