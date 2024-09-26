<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\Authentication;
use App\Http\Controllers\Api\MusicController;

// register route
Route::post("login",[Authentication::class,'login']);

Route::apiResource('musics', MusicController::class);