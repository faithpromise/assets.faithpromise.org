<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/staff-8bit.css', 'EightBitController@index');
Route::get('/{display_size}/{image_size}/{image_path}', 'ImageResizeController@index')
    ->where('display_size', '(sm|md|lg|xl)')
    ->where('image_size', '(full|half|third|quarter)')
    ->where('image_path', '.*');
