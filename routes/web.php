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

Route::get('/', function () {
    return view('welcome');
});

Route::get('dashboard', [ 'as' => 'dashboard', 'uses' => 'App\Http\Controllers\MainController@dashboard'])->middleware(['auth']);



Route::group(['middleware' => ['auth']], function () {
	Route::get('newcand', [ 'as' => 'newcand', 'uses' => 'App\Http\Controllers\MainController@newcand'])->middleware(['permission:gestione_archivi']);

	Route::post('save_newcand', [ 'as' => 'save_newcand', 'uses' => 'App\Http\Controllers\MainController@save_newcand'])->middleware(['permission:gestione_archivi']);
	
	Route::get('listcand', [ 'as' => 'listcand', 'uses' => 'App\Http\Controllers\MainController@listcand'])->middleware(['permission:gestione_archivi']);

	Route::get('tipo_contratto', [ 'as' => 'tipo_contratto', 'uses' => 'App\Http\Controllers\ControllerArchivi@tipo_contratto'])->middleware(['auth']);

});





//routing Ajax
Route::post('lista_province', 'App\Http\Controllers\AjaxControllerCand@lista_province');
Route::post('lista_comuni', 'App\Http\Controllers\AjaxControllerCand@lista_comuni');
Route::post('lista_cap', 'App\Http\Controllers\AjaxControllerCand@lista_cap');


require __DIR__.'/auth.php';
