<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

// register route
Route::post("register",[ApiController::class,'register']);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
