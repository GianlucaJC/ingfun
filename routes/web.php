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
	Route::get('archivi', [ 'as' => 'archivi', 'uses' => 'App\Http\Controllers\MainController@archivi'])->middleware(['auth']);


	Route::get('newcand/{id?}/{from?}', [ 'as' => 'newcand', 'uses' => 'App\Http\Controllers\MainController@newcand'])->middleware(['permission:gestione_archivi']);



	Route::post('save_newcand', [ 'as' => 'save_newcand', 'uses' => 'App\Http\Controllers\MainController@save_newcand'])->middleware(['permission:gestione_archivi']);
	
	Route::get('listcand', [ 'as' => 'listcand', 'uses' => 'App\Http\Controllers\MainController@listcand'])->middleware(['permission:gestione_archivi']);
	Route::post('listcand', [ 'as' => 'listcand', 'uses' => 'App\Http\Controllers\MainController@listcand'])->middleware(['permission:gestione_archivi']);

	Route::get('listpers', [ 'as' => 'listpers', 'uses' => 'App\Http\Controllers\ControllerPersonale@listpers'])->middleware(['permission:gestione_archivi']);
	Route::post('listpers', [ 'as' => 'listpers', 'uses' => 'App\Http\Controllers\ControllerPersonale@listpers'])->middleware(['permission:gestione_archivi']);

	Route::get('scadenze_contratti', [ 'as' => 'scadenze_contratti', 'uses' => 'App\Http\Controllers\ControllerPersonale@scadenze_contratti'])->middleware(['permission:gestione_archivi']);
	Route::post('scadenze_contratti', [ 'as' => 'scadenze_contratti', 'uses' => 'App\Http\Controllers\ControllerPersonale@scadenze_contratti'])->middleware(['permission:gestione_archivi']);



	Route::get('tipo_contratto', [ 'as' => 'tipo_contratto', 'uses' => 'App\Http\Controllers\ControllerArchivi@tipo_contratto'])->middleware(['permission:gestione_archivi']);
	Route::post('tipo_contratto', [ 'as' => 'tipo_contratto', 'uses' => 'App\Http\Controllers\ControllerArchivi@tipo_contratto'])->middleware(['permission:gestione_archivi']);
	
	Route::get('frm_attestati', [ 'as' => 'frm_attestati', 'uses' => 'App\Http\Controllers\ControllerArchivi@frm_attestati'])->middleware(['permission:gestione_archivi']);
	Route::post('frm_attestati', [ 'as' => 'frm_attestati', 'uses' => 'App\Http\Controllers\ControllerArchivi@frm_attestati'])->middleware(['permission:gestione_archivi']);

	Route::get('societa_assunzione', [ 'as' => 'societa_assunzione', 'uses' => 'App\Http\Controllers\ControllerArchivi@societa_assunzione'])->middleware(['permission:gestione_archivi']);
	Route::post('societa_assunzione', [ 'as' => 'societa_assunzione', 'uses' => 'App\Http\Controllers\ControllerArchivi@societa_assunzione'])->middleware(['permission:gestione_archivi']);

	Route::get('costo', [ 'as' => 'costo', 'uses' => 'App\Http\Controllers\ControllerArchivi@costo'])->middleware(['permission:gestione_archivi']);
	Route::post('costo', [ 'as' => 'costo', 'uses' => 'App\Http\Controllers\ControllerArchivi@costo'])->middleware(['permission:gestione_archivi']);

	Route::get('area_impiego', [ 'as' => 'area_impiego', 'uses' => 'App\Http\Controllers\ControllerArchivi@area_impiego'])->middleware(['permission:gestione_archivi']);
	Route::post('area_impiego', [ 'as' => 'area_impiego', 'uses' => 'App\Http\Controllers\ControllerArchivi@area_impiego'])->middleware(['permission:gestione_archivi']);

	Route::get('mansione', [ 'as' => 'mansione', 'uses' => 'App\Http\Controllers\ControllerArchivi@mansione'])->middleware(['permission:gestione_archivi']);
	Route::post('mansione', [ 'as' => 'mansione', 'uses' => 'App\Http\Controllers\ControllerArchivi@mansione'])->middleware(['permission:gestione_archivi']);

	Route::get('ccnl', [ 'as' => 'ccnl', 'uses' => 'App\Http\Controllers\ControllerArchivi@ccnl'])->middleware(['permission:gestione_archivi']);
	Route::post('ccnl', [ 'as' => 'ccnl', 'uses' => 'App\Http\Controllers\ControllerArchivi@ccnl'])->middleware(['permission:gestione_archivi']);

	Route::get('tipologia_contr', [ 'as' => 'tipologia_contr', 'uses' => 'App\Http\Controllers\ControllerArchivi@tipologia_contr'])->middleware(['permission:gestione_archivi']);
	Route::post('tipologia_contr', [ 'as' => 'tipologia_contr', 'uses' => 'App\Http\Controllers\ControllerArchivi@tipologia_contr'])->middleware(['permission:gestione_archivi']);


	Route::get('tipo_documento', [ 'as' => 'tipo_documento', 'uses' => 'App\Http\Controllers\ControllerArchivi@tipo_documento'])->middleware(['permission:gestione_archivi']);
	Route::post('tipo_documento', [ 'as' => 'tipo_documento', 'uses' => 'App\Http\Controllers\ControllerArchivi@tipo_documento'])->middleware(['permission:gestione_archivi']);

	Route::get('sotto_tipo_documento', [ 'as' => 'sotto_tipo_documento', 'uses' => 'App\Http\Controllers\ControllerArchivi@sotto_tipo_documento'])->middleware(['permission:gestione_archivi']);
	Route::post('sotto_tipo_documento', [ 'as' => 'sotto_tipo_documento', 'uses' => 'App\Http\Controllers\ControllerArchivi@sotto_tipo_documento'])->middleware(['permission:gestione_archivi']);


	Route::get('documenti/{id_ref?}', [ 'as' => 'documenti', 'uses' => 'App\Http\Controllers\ControllerArchivi@documenti'])->middleware(['permission:gestione_archivi']);
	Route::post('documenti', [ 'as' => 'documenti', 'uses' => 'App\Http\Controllers\ControllerArchivi@documenti'])->middleware(['permission:gestione_archivi']);

	Route::get('contatti', [ 'as' => 'contatti', 'uses' => 'App\Http\Controllers\ControllerArchivi@contatti'])->middleware(['permission:gestione_archivi']);
	Route::post('contatti', [ 'as' => 'contatti', 'uses' => 'App\Http\Controllers\ControllerArchivi@contatti'])->middleware(['permission:gestione_archivi']);

});




//routing Ajax
Route::group(['only_log' => ['auth']], function () {
	Route::post('azzera_notif', 'App\Http\Controllers\AjaxControllerCand@azzera_notif');

	Route::post('dele_curr', 'App\Http\Controllers\AjaxControllerCand@dele_curr');
	Route::post('remove_doc', 'App\Http\Controllers\AjaxControllerCand@remove_doc');
	Route::post('update_doc', 'App\Http\Controllers\AjaxControllerCand@update_doc');

	Route::post('sottotipo', 'App\Http\Controllers\AjaxControllerCand@sottotipo');
	Route::post('lista_province', 'App\Http\Controllers\AjaxControllerCand@lista_province');
	Route::post('lista_comuni', 'App\Http\Controllers\AjaxControllerCand@lista_comuni');
	Route::post('lista_cap', 'App\Http\Controllers\AjaxControllerCand@lista_cap');
	Route::post('refresh_tipoc', 'App\Http\Controllers\AjaxControllerCand@refresh_tipoc');
	Route::post('refresh_soc', 'App\Http\Controllers\AjaxControllerCand@refresh_soc');
	Route::post('refresh_costo', 'App\Http\Controllers\AjaxControllerCand@refresh_costo');
	Route::post('refresh_area', 'App\Http\Controllers\AjaxControllerCand@refresh_area');
	Route::post('refresh_mansione', 'App\Http\Controllers\AjaxControllerCand@refresh_mansione');
	Route::post('refresh_ccnl', 'App\Http\Controllers\AjaxControllerCand@refresh_ccnl');
	Route::post('refresh_tipologia_contr', 'App\Http\Controllers\AjaxControllerCand@refresh_tipologia_contr');
	Route::post('refresh_tipo_doc', 'App\Http\Controllers\AjaxControllerCand@refresh_tipo_doc');
	Route::post('refresh_sotto_tipo_doc', 'App\Http\Controllers\AjaxControllerCand@refresh_sotto_tipo_doc');

	Route::post('storia_campo', 'App\Http\Controllers\AjaxControllerCand@storia_campo');
	Route::post('load_contatti', 'App\Http\Controllers\AjaxControllerCand@load_contatti');
	Route::post('azione', 'App\Http\Controllers\AjaxControllerCand@azione');


	Route::post('send_mail', 'App\Http\Controllers\AjaxControllerCand@send_mail');
});


require __DIR__.'/auth.php';
