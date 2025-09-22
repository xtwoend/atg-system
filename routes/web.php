<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false, 'reset' => false]);
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::group([
    'middleware' => 'auth',
], function(){
    
    // Atg
    Route::get('atg/{id}', [\App\Http\Controllers\AtgController::class, 'show'])->name('atg');
    Route::get('atg/{id}/trend', [\App\Http\Controllers\AtgController::class, 'trend'])->name('atg.trend');
    Route::get('atg/{id}/report', [\App\Http\Controllers\AtgController::class, 'report'])->name('atg.report');
    Route::get('atg/{id}/logger', [\App\Http\Controllers\AtgController::class, 'logger'])->name('atg.logger');
    Route::get('atg/{id}/discharge', [\App\Http\Controllers\DischargeController::class, 'index'])->name('atg.discharge');
    Route::get('atg/{id}/discharge/data', [\App\Http\Controllers\DischargeController::class, 'data'])->name('atg.discharge.data');
    Route::get('atg/{id}/discharge/download', [\App\Http\Controllers\DischargeController::class, 'download'])->name('atg.discharge.download');

    Route::get('atg/{id}/trend/data', [\App\Http\Controllers\AtgController::class, 'trendData'])->name('atg.trend.data');
    Route::get('atg/{id}/data', [\App\Http\Controllers\AtgController::class, 'data'])->name('atg.data');
    Route::get('atg/{id}/data/download', [\App\Http\Controllers\AtgController::class, 'download'])->name('atg.data.download');
    Route::get('atg/{id}/stock/data', [\App\Http\Controllers\StockController::class, 'stock'])->name('atg.stock.data');
    Route::get('atg/{id}/stock/trend', [\App\Http\Controllers\StockController::class, 'trend'])->name('atg.stock.trend');
    Route::get('atg/{id}/stock/download', [\App\Http\Controllers\StockController::class, 'download'])->name('atg.stock.download');


    // devices
    Route::resource('devices', \App\Http\Controllers\DeviceController::class);

    // client
    Route::resource('client', \App\Http\Controllers\ClientController::class);

    // users
    Route::get('profile', [\App\Http\Controllers\UserController::class, 'profile'])->name('users.profile');
    Route::put('profile', [\App\Http\Controllers\UserController::class, 'updateProfile'])->name('users.profile.update');
    Route::resource('users', \App\Http\Controllers\UserController::class);
});

// table tank atg
Route::group([
    'middleware' => 'auth',
    'prefix' => 'atgsetting',
    'as' => 'atgsetting.',
], function(){
    Route::resource('tank', \App\Http\Controllers\TankController::class);
    Route::resource('table-volume', \App\Http\Controllers\VolumeTableController::class);
    Route::resource('table-density', \App\Http\Controllers\DensityTableController::class);
});

// connections
Route::group([
    'middleware' => 'auth',
    'prefix' => 'connections',
    'as' => 'connections.',
], function() {
    
    Route::get('mqtt/reload', [\App\Http\Controllers\MQTTSettingController::class, 'reload'])->name('mqtt.reload');
    Route::get('mqtt/start/{id}', [\App\Http\Controllers\MQTTSettingController::class, 'start'])->name('mqtt.start');
    Route::get('mqtt/stop/{id}', [\App\Http\Controllers\MQTTSettingController::class, 'stop'])->name('mqtt.stop');
    Route::resource('mqtt', \App\Http\Controllers\MQTTSettingController::class);
});
