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

Route::get('menuhr', [ 'as' => 'menuhr', 'uses' => 'App\Http\Controllers\MainController@menuhr'])->middleware(['auth']);

Route::get('amministrazione', [ 'as' => 'amministrazione', 'uses' => 'App\Http\Controllers\MainController@amministrazione'])->middleware(['auth']);

Route::get('appalti', [ 'as' => 'appalti', 'uses' => 'App\Http\Controllers\MainController@appalti'])->middleware(['auth']);

Route::get('menuaziende', [ 'as' => 'menuaziende', 'uses' => 'App\Http\Controllers\MainController@menuaziende'])->middleware(['auth']);

Route::get('cliditte', [ 'as' => 'cliditte', 'uses' => 'App\Http\Controllers\MainController@cliditte'])->middleware(['auth']);

Route::get('serviziapp', [ 'as' => 'serviziapp', 'uses' => 'App\Http\Controllers\MainController@serviziapp'])->middleware(['auth']);


Route::group(['only_log' => ['auth']], function () {

	Route::get('cedolini_view', [ 'as' => 'cedolini_view', 'uses' => 'App\Http\Controllers\ControllerPersonale@cedolini_view'])->middleware(['permission:user_view|gestione_archivi']);
	Route::post('cedolini_view', [ 'as' => 'cedolini_view', 'uses' => 'App\Http\Controllers\ControllerPersonale@cedolini_view'])->middleware(['permission:user_view|gestione_archivi']);

	
	Route::get('archivi', [ 'as' => 'archivi', 'uses' => 'App\Http\Controllers\MainController@archivi'])->middleware(['auth']);

	
	Route::get('newcand/{id?}/{from?}', [ 'as' => 'newcand', 'uses' => 'App\Http\Controllers\MainController@newcand'])->middleware(['permission:gestione_archivi']);



	Route::post('save_newcand', [ 'as' => 'save_newcand', 'uses' => 'App\Http\Controllers\MainController@save_newcand'])->middleware(['permission:gestione_archivi']);

	Route::post('save_newuser', [ 'as' => 'save_newuser', 'uses' => 'App\Http\Controllers\MainController@save_newuser'])->middleware(['permission:gestione_archivi']);

	Route::post('disable_user', [ 'as' => 'disable_user', 'uses' => 'App\Http\Controllers\MainController@disable_user'])->middleware(['permission:gestione_archivi']);
	
	Route::get('listcand', [ 'as' => 'listcand', 'uses' => 'App\Http\Controllers\MainController@listcand'])->middleware(['permission:gestione_archivi']);
	Route::post('listcand', [ 'as' => 'listcand', 'uses' => 'App\Http\Controllers\MainController@listcand'])->middleware(['permission:gestione_archivi']);

	Route::get('listpers', [ 'as' => 'listpers', 'uses' => 'App\Http\Controllers\ControllerPersonale@listpers'])->middleware(['permission:gestione_archivi']);
	Route::post('listpers', [ 'as' => 'listpers', 'uses' => 'App\Http\Controllers\ControllerPersonale@listpers'])->middleware(['permission:gestione_archivi']);

	Route::get('scadenze_contratti', [ 'as' => 'scadenze_contratti', 'uses' => 'App\Http\Controllers\ControllerPersonale@scadenze_contratti'])->middleware(['permission:gestione_archivi']);
	Route::post('scadenze_contratti', [ 'as' => 'scadenze_contratti', 'uses' => 'App\Http\Controllers\ControllerPersonale@scadenze_contratti'])->middleware(['permission:gestione_archivi']);

	Route::get('cedolini_up', [ 'as' => 'cedolini_up', 'uses' => 'App\Http\Controllers\ControllerPersonale@cedolini_up'])->middleware(['permission:gestione_archivi']);
	Route::post('cedolini_up', [ 'as' => 'cedolini_up', 'uses' => 'App\Http\Controllers\ControllerPersonale@cedolini_up'])->middleware(['permission:gestione_archivi']);

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

	 Route::get('export-users', [ 'as' => 'export-users', 'uses' => 'App\Http\Controllers\MainController@exportUsers'])->middleware(['permission:gestione_archivi']);






	Route::get('servizi', [ 'as' => 'servizi', 'uses' => 'App\Http\Controllers\ControllerServizi@servizi'])->middleware(['permission:gestione_archivi']);
	Route::post('servizi', [ 'as' => 'servizi', 'uses' => 'App\Http\Controllers\ControllerServizi@servizi'])->middleware(['permission:gestione_archivi']);

	Route::get('ditte', [ 'as' => 'ditte', 'uses' => 'App\Http\Controllers\ControllerServizi@ditte'])->middleware(['permission:gestione_archivi']);
	Route::post('ditte', [ 'as' => 'ditte', 'uses' => 'App\Http\Controllers\ControllerServizi@ditte'])->middleware(['permission:gestione_archivi']);

	Route::get('lavoratori', [ 'as' => 'lavoratori', 'uses' => 'App\Http\Controllers\ControllerServizi@lavoratori'])->middleware(['permission:gestione_archivi']);
	Route::post('lavoratori', [ 'as' => 'lavoratori', 'uses' => 'App\Http\Controllers\ControllerServizi@lavoratori'])->middleware(['permission:gestione_archivi']);


	Route::get('listapp/{id?}', [ 'as' => 'listapp', 'uses' => 'App\Http\Controllers\ControllerServizi@listapp'])->middleware(['permission:gestione_archivi']);
	Route::post('listapp/{id?}', [ 'as' => 'listapp', 'uses' => 'App\Http\Controllers\ControllerServizi@listapp'])->middleware(['permission:gestione_archivi']);


	Route::get('rifornimenti', [ 'as' => 'rifornimenti', 'uses' => 'App\Http\Controllers\ControllerRifornimenti@rifornimenti'])->middleware(['permission:gestione_archivi']);
	Route::post('rifornimenti', [ 'as' => 'rifornimenti', 'uses' => 'App\Http\Controllers\ControllerRifornimenti@rifornimenti'])->middleware(['permission:gestione_archivi']);


	Route::get('newapp/{id?}/{from?}/{num_send?}', [ 'as' => 'newapp', 'uses' => 'App\Http\Controllers\ControllerServizi@newapp'])->middleware(['permission:gestione_archivi']);

	Route::post('save_newapp', [ 'as' => 'save_newapp', 'uses' => 'App\Http\Controllers\ControllerServizi@save_newapp'])->middleware(['permission:gestione_archivi']);


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
	
	
	Route::post('check_url', 'App\Http\Controllers\AjaxControllerCand@check_url');

	Route::post('send_mail', 'App\Http\Controllers\AjaxControllerCand@send_mail');
	
	Route::post('count_pdf', 'App\Http\Controllers\ControllerPdf@count_pdf');
	Route::post('analisi_pdf', 'App\Http\Controllers\ControllerPdf@analisi_pdf');
	Route::post('split_pdf', 'App\Http\Controllers\ControllerPdf@split_pdf');
	
	

	Route::post('getditta', 'App\Http\Controllers\AjaxControllerServ@getditta');

});


require __DIR__.'/auth.php';
