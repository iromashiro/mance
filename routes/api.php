<?php

use App\Http\Controllers\WeatherProxyController;

Route::get('/wx/weather', [WeatherProxyController::class, 'weather']);
Route::get('/wx/air', [WeatherProxyController::class, 'air']);
