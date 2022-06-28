<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
Use App\Http\Controllers\Api\v1\WeatherController;

Route::get('/get-weather', [WeatherController::class, 'getWeather']);
Route::get('/get-weather-statistics', [WeatherController::class, 'getStatistics']);
