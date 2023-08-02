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

Route::post('dashboard', [ 'as' => 'dashboard', 'uses' => 'App\Http\Controllers\MainController@dashboard'])->middleware(['auth']);

Route::get('menuhr', [ 'as' => 'menuhr', 'uses' => 'App\Http\Controllers\MainController@menuhr'])->middleware(['role:admin|coord']);

Route::get('amministrazione', [ 'as' => 'amministrazione', 'uses' => 'App\Http\Controllers\MainController@amministrazione'])->middleware(['role:admin|coord|resp']);

Route::get('menuparco', [ 'as' => 'menuparco', 'uses' => 'App\Http\Controllers\MainController@menuparco'])->middleware(['role:admin']);

Route::get('appalti', [ 'as' => 'appalti', 'uses' => 'App\Http\Controllers\MainController@appalti'])->middleware(['role:admin|coord|resp']);

Route::get('menuaziende', [ 'as' => 'menuaziende', 'uses' => 'App\Http\Controllers\MainController@menuaziende'])->middleware(['role:admin|coord']);

Route::get('cliditte', [ 'as' => 'cliditte', 'uses' => 'App\Http\Controllers\MainController@cliditte'])->middleware(['role:admin|coord']);



Route::group(['only_log' => ['auth']], function () {

	Route::get('export-parco', [ 'as' => 'export-parco', 'uses' => 'App\Http\Controllers\MainController@exportParco'])->middleware(['role:admin']);
	
	Route::get('inventario_flotta', [ 'as' => 'inventario_flotta', 'uses' => 'App\Http\Controllers\ControllerParco@inventario_flotta'])->middleware(['role:admin']);
	
	Route::post('inventario_flotta', [ 'as' => 'inventario_flotta', 'uses' => 'App\Http\Controllers\ControllerParco@inventario_flotta'])->middleware(['role:admin']);


	Route::get('servizi_noleggio', [ 'as' => 'servizi_noleggio', 'uses' => 'App\Http\Controllers\ControllerArchiviParco@servizi_noleggio'])->middleware(['role:admin']);
	
	Route::post('servizi_noleggio', [ 'as' => 'servizi_noleggio', 'uses' => 'App\Http\Controllers\ControllerArchiviParco@servizi_noleggio'])->middleware(['role:admin']);

	Route::post('scheda_mezzo', [ 'as' => 'scheda_mezzo', 'uses' => 'App\Http\Controllers\ControllerParco@scheda_mezzo'])->middleware(['role:admin']);

	Route::get('scheda_mezzo/{id?}', [ 'as' => 'scheda_mezzo', 'uses' => 'App\Http\Controllers\ControllerParco@scheda_mezzo'])->middleware(['role:admin']);	
	
	
	Route::get('modello', [ 'as' => 'modello', 'uses' => 'App\Http\Controllers\ControllerArchiviParco@modello'])->middleware(['role:admin']);
	
	Route::post('modello', [ 'as' => 'modello', 'uses' => 'App\Http\Controllers\ControllerArchiviParco@modello'])->middleware(['role:admin']);


	
	Route::get('marca', [ 'as' => 'marca', 'uses' => 'App\Http\Controllers\ControllerArchiviParco@marca'])->middleware(['role:admin']);
	
	Route::post('marca', [ 'as' => 'marca', 'uses' => 'App\Http\Controllers\ControllerArchiviParco@marca'])->middleware(['role:admin']);
	
	Route::get('badge', [ 'as' => 'badge', 'uses' => 'App\Http\Controllers\ControllerArchiviParco@badge'])->middleware(['role:admin']);
	
	Route::post('badge', [ 'as' => 'badge', 'uses' => 'App\Http\Controllers\ControllerArchiviParco@badge'])->middleware(['role:admin']);
	
	Route::get('cartac', [ 'as' => 'cartac', 'uses' => 'App\Http\Controllers\ControllerArchiviParco@cartac'])->middleware(['role:admin']);
	
	Route::post('cartac', [ 'as' => 'cartac', 'uses' => 'App\Http\Controllers\ControllerArchiviParco@cartac'])->middleware(['role:admin']);

	Route::get('telepass', [ 'as' => 'telepass', 'uses' => 'App\Http\Controllers\ControllerArchiviParco@telepass'])->middleware(['role:admin']);
	
	Route::post('telepass', [ 'as' => 'telepass', 'uses' => 'App\Http\Controllers\ControllerArchiviParco@telepass'])->middleware(['role:admin']);

	
	Route::post('load_contatti_soc', 'App\Http\Controllers\AjaxControllerServ@load_contatti_soc');
	
	Route::get('invito/{id?}', [ 'as' => 'invito', 'uses' => 'App\Http\Controllers\ControllerInvito@invito'])->middleware(['role:admin|coord']);
	
	Route::post('invito/{id?}', [ 'as' => 'invito', 'uses' => 'App\Http\Controllers\ControllerInvito@invito'])->middleware(['role:admin|coord']);

	Route::get('preventivo/{id?}', [ 'as' => 'preventivo', 'uses' => 'App\Http\Controllers\ControllerPreventivi@preventivo'])->middleware(['role:admin|coord']);
	
	Route::post('preventivo/{id?}', [ 'as' => 'preventivo', 'uses' => 'App\Http\Controllers\ControllerPreventivi@preventivo'])->middleware(['role:admin|coord']);

	Route::get('lista_preventivi', [ 'as' => 'lista_preventivi', 'uses' => 'App\Http\Controllers\ControllerPreventivi@lista_preventivi'])->middleware(['role:admin|coord']);
	
	Route::post('lista_preventivi', [ 'as' => 'lista_preventivi', 'uses' => 'App\Http\Controllers\ControllerPreventivi@lista_preventivi'])->middleware(['role:admin|coord']);




	Route::get('invoice/{id_doc?}', [ 'as' => 'invoice', 'uses' => 'App\Http\Controllers\ControllerInvito@invoice'])->middleware(['role:admin|coord']);


	Route::get('lista_inviti', [ 'as' => 'lista_inviti', 'uses' => 'App\Http\Controllers\ControllerInvito@lista_inviti'])->middleware(['role:admin|coord']);
	
	Route::post('lista_inviti', [ 'as' => 'lista_inviti', 'uses' => 'App\Http\Controllers\ControllerInvito@lista_inviti'])->middleware(['role:admin|coord']);




	Route::get('utenti', [ 'as' => 'utenti', 'uses' => 'App\Http\Controllers\ControllerPersonale@utenti'])->middleware(['role:admin']);
	
	Route::post('utenti', [ 'as' => 'utenti', 'uses' => 'App\Http\Controllers\ControllerPersonale@utenti'])->middleware(['role:admin']);

	Route::get('cedolini_view', [ 'as' => 'cedolini_view', 'uses' => 'App\Http\Controllers\ControllerPersonale@cedolini_view'])->middleware(['permission:user_view|gestione_archivi']);
	
	Route::post('cedolini_view', [ 'as' => 'cedolini_view', 'uses' => 'App\Http\Controllers\ControllerPersonale@cedolini_view'])->middleware(['permission:user_view|gestione_archivi']);

	
	Route::get('registro', [ 'as' => 'registro', 'uses' => 'App\Http\Controllers\Registro@presenze'])->middleware(['role:admin|coord']);
	Route::post('registro', [ 'as' => 'registro', 'uses' => 'App\Http\Controllers\Registro@presenze'])->middleware(['role:admin|coord']);


	Route::get('giustificativi', [ 'as' => 'giustificativi', 'uses' => 'App\Http\Controllers\Registro@giustificativi'])->middleware(['role:admin|coord|resp']);

	Route::post('giustificativi', [ 'as' => 'giustificativi', 'uses' => 'App\Http\Controllers\Registro@giustificativi'])->middleware(['role:admin|coord|resp']);



	Route::get('archivi', [ 'as' => 'archivi', 'uses' => 'App\Http\Controllers\MainController@archivi'])->middleware(['role:admin']);

	
	Route::get('newcand/{id?}/{from?}', [ 'as' => 'newcand', 'uses' => 'App\Http\Controllers\MainController@newcand'])->middleware(['role:admin']);



	Route::post('save_newcand', [ 'as' => 'save_newcand', 'uses' => 'App\Http\Controllers\MainController@save_newcand'])->middleware(['role:admin']);

	Route::post('save_newuser', [ 'as' => 'save_newuser', 'uses' => 'App\Http\Controllers\MainController@save_newuser'])->middleware(['role:admin']);

	Route::post('disable_user', [ 'as' => 'disable_user', 'uses' => 'App\Http\Controllers\MainController@disable_user'])->middleware(['role:admin']);

	Route::post('set_ruolo', [ 'as' => 'set_ruolo', 'uses' => 'App\Http\Controllers\MainController@set_ruolo'])->middleware(['role:admin']);
	
	Route::get('listcand', [ 'as' => 'listcand', 'uses' => 'App\Http\Controllers\MainController@listcand'])->middleware(['role:admin']);
	
	Route::post('listcand', [ 'as' => 'listcand', 'uses' => 'App\Http\Controllers\MainController@listcand'])->middleware(['role:admin']);

	Route::get('newpassuser', [ 'as' => 'newpassuser', 'uses' => 'App\Http\Controllers\MainController@newpassuser'])->middleware(['role:user']);
	
	Route::post('newpassuser', [ 'as' => 'newpassuser', 'uses' => 'App\Http\Controllers\MainController@newpassuser'])->middleware(['role:user']);

	Route::get('listpers', [ 'as' => 'listpers', 'uses' => 'App\Http\Controllers\ControllerPersonale@listpers'])->middleware(['role:admin']);
	
	Route::post('listpers', [ 'as' => 'listpers', 'uses' => 'App\Http\Controllers\ControllerPersonale@listpers'])->middleware(['role:admin']);

	Route::get('scadenze_contratti', [ 'as' => 'scadenze_contratti', 'uses' => 'App\Http\Controllers\ControllerPersonale@scadenze_contratti'])->middleware(['role:admin']);
	
	Route::post('scadenze_contratti', [ 'as' => 'scadenze_contratti', 'uses' => 'App\Http\Controllers\ControllerPersonale@scadenze_contratti'])->middleware(['role:admin']);

	Route::get('cedolini_up', [ 'as' => 'cedolini_up', 'uses' => 'App\Http\Controllers\ControllerPersonale@cedolini_up'])->middleware(['role:admin']);
	
	Route::post('cedolini_up', [ 'as' => 'cedolini_up', 'uses' => 'App\Http\Controllers\ControllerPersonale@cedolini_up'])->middleware(['role:admin']);

	Route::get('tipo_contratto', [ 'as' => 'tipo_contratto', 'uses' => 'App\Http\Controllers\ControllerArchivi@tipo_contratto'])->middleware(['role:admin']);
	
	Route::post('tipo_contratto', [ 'as' => 'tipo_contratto', 'uses' => 'App\Http\Controllers\ControllerArchivi@tipo_contratto'])->middleware(['role:admin']);
	
	Route::get('frm_attestati', [ 'as' => 'frm_attestati', 'uses' => 'App\Http\Controllers\ControllerArchivi@frm_attestati'])->middleware(['role:admin']);
	
	Route::post('frm_attestati', [ 'as' => 'frm_attestati', 'uses' => 'App\Http\Controllers\ControllerArchivi@frm_attestati'])->middleware(['role:admin']);

	Route::get('societa_assunzione', [ 'as' => 'societa_assunzione', 'uses' => 'App\Http\Controllers\ControllerArchivi@societa_assunzione'])->middleware(['role:admin']);
	
	Route::post('societa_assunzione', [ 'as' => 'societa_assunzione', 'uses' => 'App\Http\Controllers\ControllerArchivi@societa_assunzione'])->middleware(['role:admin']);

	Route::get('costo', [ 'as' => 'costo', 'uses' => 'App\Http\Controllers\ControllerArchivi@costo'])->middleware(['role:admin']);
	
	Route::post('costo', [ 'as' => 'costo', 'uses' => 'App\Http\Controllers\ControllerArchivi@costo'])->middleware(['role:admin']);

	Route::get('area_impiego', [ 'as' => 'area_impiego', 'uses' => 'App\Http\Controllers\ControllerArchivi@area_impiego'])->middleware(['role:admin']);
	
	Route::post('area_impiego', [ 'as' => 'area_impiego', 'uses' => 'App\Http\Controllers\ControllerArchivi@area_impiego'])->middleware(['role:admin']);

	Route::get('mansione', [ 'as' => 'mansione', 'uses' => 'App\Http\Controllers\ControllerArchivi@mansione'])->middleware(['role:admin']);
	Route::post('mansione', [ 'as' => 'mansione', 'uses' => 'App\Http\Controllers\ControllerArchivi@mansione'])->middleware(['role:admin']);

	Route::get('ccnl', [ 'as' => 'ccnl', 'uses' => 'App\Http\Controllers\ControllerArchivi@ccnl'])->middleware(['role:admin']);
	
	Route::post('ccnl', [ 'as' => 'ccnl', 'uses' => 'App\Http\Controllers\ControllerArchivi@ccnl'])->middleware(['role:admin']);

	Route::get('tipologia_contr', [ 'as' => 'tipologia_contr', 'uses' => 'App\Http\Controllers\ControllerArchivi@tipologia_contr'])->middleware(['role:admin']);
	
	Route::post('tipologia_contr', [ 'as' => 'tipologia_contr', 'uses' => 'App\Http\Controllers\ControllerArchivi@tipologia_contr'])->middleware(['role:admin']);


	Route::get('tipo_documento', [ 'as' => 'tipo_documento', 'uses' => 'App\Http\Controllers\ControllerArchivi@tipo_documento'])->middleware(['role:admin']);
	
	Route::post('tipo_documento', [ 'as' => 'tipo_documento', 'uses' => 'App\Http\Controllers\ControllerArchivi@tipo_documento'])->middleware(['role:admin']);

	Route::get('sotto_tipo_documento', [ 'as' => 'sotto_tipo_documento', 'uses' => 'App\Http\Controllers\ControllerArchivi@sotto_tipo_documento'])->middleware(['role:admin']);
	
	Route::post('sotto_tipo_documento', [ 'as' => 'sotto_tipo_documento', 'uses' => 'App\Http\Controllers\ControllerArchivi@sotto_tipo_documento'])->middleware(['role:admin']);


	Route::get('documenti/{id_ref?}', [ 'as' => 'documenti', 'uses' => 'App\Http\Controllers\ControllerArchivi@documenti'])->middleware(['role:admin']);
	
	Route::post('documenti', [ 'as' => 'documenti', 'uses' => 'App\Http\Controllers\ControllerArchivi@documenti'])->middleware(['role:admin']);




	Route::get('contatti', [ 'as' => 'contatti', 'uses' => 'App\Http\Controllers\ControllerArchivi@contatti'])->middleware(['role:admin']);
	
	Route::post('contatti', [ 'as' => 'contatti', 'uses' => 'App\Http\Controllers\ControllerArchivi@contatti'])->middleware(['role:admin']);

	 Route::get('export-users', [ 'as' => 'export-users', 'uses' => 'App\Http\Controllers\MainController@exportUsers'])->middleware(['role:admin']);



	Route::get('gestione_servizi', [ 'as' => 'gestione_servizi', 'uses' => 'App\Http\Controllers\ControllerServizi@gestione_servizi'])->middleware(['role:admin|coord']);
	
	Route::post('gestione_servizi', [ 'as' => 'gestione_servizi', 'uses' => 'App\Http\Controllers\ControllerServizi@gestione_servizi'])->middleware(['role:admin|coord']);


	Route::get('aliquote', [ 'as' => 'aliquote', 'uses' => 'App\Http\Controllers\ControllerInvito@aliquote'])->middleware(['role:admin|coord']);
	
	Route::post('aliquote', [ 'as' => 'aliquote', 'uses' => 'App\Http\Controllers\ControllerInvito@aliquote'])->middleware(['role:admin|coord']);


	Route::get('servizi/{id_ref?}', [ 'as' => 'servizi', 'uses' => 'App\Http\Controllers\ControllerServizi@servizi'])->middleware(['role:admin|coord']);
	
	Route::post('servizi', [ 'as' => 'servizi', 'uses' => 'App\Http\Controllers\ControllerServizi@servizi'])->middleware(['role:admin|coord']);

	Route::get('ditte', [ 'as' => 'ditte', 'uses' => 'App\Http\Controllers\ControllerServizi@ditte'])->middleware(['role:admin|coord']);
	
	Route::post('ditte', [ 'as' => 'ditte', 'uses' => 'App\Http\Controllers\ControllerServizi@ditte'])->middleware(['role:admin|coord']);

	Route::get('sezionali', [ 'as' => 'sezionali', 'uses' => 'App\Http\Controllers\ControllerServizi@sezionali'])->middleware(['role:admin']);
	
	Route::post('sezionali', [ 'as' => 'sezionali', 'uses' => 'App\Http\Controllers\ControllerServizi@sezionali'])->middleware(['role:admin']);


	Route::get('listapp/{id?}', [ 'as' => 'listapp', 'uses' => 'App\Http\Controllers\ControllerAppalti@listapp'])->middleware(['role:admin|coord|resp']);
	
	Route::post('listapp/{id?}', [ 'as' => 'listapp', 'uses' => 'App\Http\Controllers\ControllerAppalti@listapp'])->middleware(['role:admin|coord|resp']);
	

	Route::get('newapp/{id?}/{from?}/{num_send?}', [ 'as' => 'newapp', 'uses' => 'App\Http\Controllers\ControllerAppalti@newapp'])->middleware(['role:admin|coord|resp']);

	Route::post('save_newapp', [ 'as' => 'save_newapp', 'uses' => 'App\Http\Controllers\ControllerAppalti@save_newapp'])->middleware(['role:admin|coord|resp']);


	Route::get('listrep/{id?}', [ 'as' => 'listrep', 'uses' => 'App\Http\Controllers\ControllerReperibilita@listrep'])->middleware(['role:admin|coord|resp']);
	
	Route::post('listrep/{id?}', [ 'as' => 'listrep', 'uses' => 'App\Http\Controllers\ControllerReperibilita@listrep'])->middleware(['role:admin|coord|resp']);

	Route::get('newreper/{id?}', [ 'as' => 'newreper', 'uses' => 'App\Http\Controllers\ControllerReperibilita@newreper'])->middleware(['role:admin|coord|resp']);

	Route::post('save_reper', [ 'as' => 'save_reper', 'uses' => 'App\Http\Controllers\ControllerReperibilita@save_reper'])->middleware(['role:admin|coord|resp']);
	
	Route::get('rifornimenti/{id?}', [ 'as' => 'rifornimenti', 'uses' => 'App\Http\Controllers\ControllerRifornimenti@rifornimenti'])->middleware(['role:admin|coord|resp']);
	
	Route::post('rifornimenti/{id?}', [ 'as' => 'rifornimenti', 'uses' => 'App\Http\Controllers\ControllerRifornimenti@rifornimenti'])->middleware(['role:admin|coord|resp']);




});




//routing Ajax
Route::group(['only_log' => ['auth']], function () {

	//chiamate ajax parco auto
	Route::post('refresh_servizi_noleggio', 'App\Http\Controllers\AjaxControllerParco@refresh_servizi_noleggio');

	Route::post('popola_modelli', 'App\Http\Controllers\AjaxControllerParco@popola_modelli');

	Route::post('refresh_marca', 'App\Http\Controllers\AjaxControllerParco@refresh_marca');

	Route::post('refresh_carta', 'App\Http\Controllers\AjaxControllerParco@refresh_carta');

	Route::post('refresh_badge', 'App\Http\Controllers\AjaxControllerParco@refresh_badge');

	Route::post('refresh_telepass', 'App\Http\Controllers\AjaxControllerParco@refresh_telepass');


	Route::post('popola_servizi', 'App\Http\Controllers\AjaxControllerServ@popola_servizi');
	
	Route::post('lavoratori_sezionali', 'App\Http\Controllers\AjaxControllerServ@lavoratori_sezionali');


	Route::post('save_value_presenze', 'App\Http\Controllers\AjaxControllerServ@save_value_presenze');


	Route::get('save_value_presenze', 'App\Http\Controllers\AjaxControllerServ@save_value_presenze');

	Route::post('azzera_notif', 'App\Http\Controllers\AjaxControllerCand@azzera_notif');

	Route::post('dele_curr', 'App\Http\Controllers\AjaxControllerCand@dele_curr');
	Route::post('remove_doc', 'App\Http\Controllers\AjaxControllerCand@remove_doc');
	Route::post('update_doc', 'App\Http\Controllers\AjaxControllerCand@update_doc');
	
	Route::post('update_doc_ditte', 'App\Http\Controllers\AjaxControllerServ@update_doc_ditte');

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

	Route::post('refresh_aliquota', 'App\Http\Controllers\AjaxControllerServ@refresh_aliquota');

	Route::post('refresh_servizi', 'App\Http\Controllers\AjaxControllerServ@refresh_servizi');

	Route::post('get_doc_ditta', 'App\Http\Controllers\AjaxControllerServ@get_doc_ditta');
	
	Route::post('remove_doc_ditta', 'App\Http\Controllers\AjaxControllerServ@remove_doc_ditta');	
	
	Route::post('edit_row_fattura', 'App\Http\Controllers\AjaxControllerFatture@edit_row_fattura');

});


require __DIR__.'/auth.php';
