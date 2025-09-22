<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiKeyMiddleware;
use App\Http\Controllers\Api\AtgController;

Route::get('atg/{id}/log', [AtgController::class, 'data'])->middleware(ApiKeyMiddleware::class);