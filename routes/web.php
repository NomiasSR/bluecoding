<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//check url shortener
Route::get('/{param?}', 'App\Http\Controllers\UrlShortenerController@checkUrlShortener');

//default route 
Route::get('/', function () {
  return view('welcome');
});
