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



Route::group(['only_log' => ['auth']], function () {
	Route::get('newcand/{id?}', [ 'as' => 'newcand', 'uses' => 'App\Http\Controllers\MainController@newcand'])->middleware(['permission:gestione_archivi']);

	Route::post('save_newcand', [ 'as' => 'save_newcand', 'uses' => 'App\Http\Controllers\MainController@save_newcand'])->middleware(['permission:gestione_archivi']);
	
	Route::get('listcand', [ 'as' => 'listcand', 'uses' => 'App\Http\Controllers\MainController@listcand'])->middleware(['permission:gestione_archivi']);
	Route::post('listcand', [ 'as' => 'listcand', 'uses' => 'App\Http\Controllers\MainController@listcand'])->middleware(['permission:gestione_archivi']);

	Route::get('tipo_contratto', [ 'as' => 'tipo_contratto', 'uses' => 'App\Http\Controllers\ControllerArchivi@tipo_contratto'])->middleware(['auth']);
	Route::post('tipo_contratto', [ 'as' => 'tipo_contratto', 'uses' => 'App\Http\Controllers\ControllerArchivi@tipo_contratto'])->middleware(['auth']);
	
	Route::get('frm_attestati', [ 'as' => 'frm_attestati', 'uses' => 'App\Http\Controllers\ControllerArchivi@frm_attestati'])->middleware(['auth']);
	Route::post('frm_attestati', [ 'as' => 'frm_attestati', 'uses' => 'App\Http\Controllers\ControllerArchivi@frm_attestati'])->middleware(['auth']);

	Route::get('societa_assunzione', [ 'as' => 'societa_assunzione', 'uses' => 'App\Http\Controllers\ControllerArchivi@societa_assunzione'])->middleware(['auth']);
	Route::post('societa_assunzione', [ 'as' => 'societa_assunzione', 'uses' => 'App\Http\Controllers\ControllerArchivi@societa_assunzione'])->middleware(['auth']);

	Route::get('costo', [ 'as' => 'costo', 'uses' => 'App\Http\Controllers\ControllerArchivi@costo'])->middleware(['auth']);
	Route::post('costo', [ 'as' => 'costo', 'uses' => 'App\Http\Controllers\ControllerArchivi@costo'])->middleware(['auth']);

	Route::get('area_impiego', [ 'as' => 'area_impiego', 'uses' => 'App\Http\Controllers\ControllerArchivi@area_impiego'])->middleware(['auth']);
	Route::post('area_impiego', [ 'as' => 'area_impiego', 'uses' => 'App\Http\Controllers\ControllerArchivi@area_impiego'])->middleware(['auth']);


});





//routing Ajax

Route::post('dele_curr', 'App\Http\Controllers\AjaxControllerCand@dele_curr');

Route::post('lista_province', 'App\Http\Controllers\AjaxControllerCand@lista_province');
Route::post('lista_comuni', 'App\Http\Controllers\AjaxControllerCand@lista_comuni');
Route::post('lista_cap', 'App\Http\Controllers\AjaxControllerCand@lista_cap');
Route::post('refresh_tipoc', 'App\Http\Controllers\AjaxControllerCand@refresh_tipoc');
Route::post('refresh_soc', 'App\Http\Controllers\AjaxControllerCand@refresh_soc');
Route::post('refresh_costo', 'App\Http\Controllers\AjaxControllerCand@refresh_costo');
Route::post('refresh_area', 'App\Http\Controllers\AjaxControllerCand@refresh_area');


require __DIR__.'/auth.php';
