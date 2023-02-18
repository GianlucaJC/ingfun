<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('servizio_scadenze', [ 'as' => 'servizio_scadenze', 'uses' => 'App\Http\Controllers\ControllerPersonale@servizio_scadenze']);


//API x APP
Route::post('login', [ 'as' => 'login', 'uses' => 'App\Http\Controllers\ApiController@login']);

Route::post('countappalti', [ 'as' => 'countappalti', 'uses' => 'App\Http\Controllers\ApiController@countappalti']);
