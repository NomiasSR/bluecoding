<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

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
Route::post('/urlshortener/createcode', 'App\Http\Controllers\UrlShortenerController@createCode');
Route::post('/urlshortener/crawler', 'App\Http\Controllers\UrlShortenerController@crawler');
Route::post('/urlshortener/top100', 'App\Http\Controllers\UrlShortenerController@top100');