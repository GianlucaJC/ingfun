<?php
namespace App\Http\Controllers;
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
/*
	N.B.
	le rotte vengono analizzate oltre che da quì, ovviamente, anche tramite la tabella main_menu che per ogni route contiene i riferimenti di chi (ruolo) possiede le facoltà di accesso alla risorsa. 
	Quindi 
	web route->controller->view->caricamento del template index.blade che al suo interno contiene il controllo dell'utenza facendo riferimento alla tabella main_menu 
*/


	/* vecchie pagine di menu
	
	Route::get('dashboard', [ 'as' => 'dashboard', 'uses' => 'App\Http\Controllers\MainController@dashboard'])->middleware(['auth']);

	Route::post('dashboard', [ 'as' => 'dashboard', 'uses' => 'App\Http\Controllers\MainController@dashboard'])->middleware(['auth']);
	
	Route::get('menuhr', [ 'as' => 'menuhr', 'uses' => 'App\Http\Controllers\MainController@menuhr'])->middleware(['role:admin|coord']);

	Route::get('amministrazione', [ 'as' => 'amministrazione', 'uses' => 'App\Http\Controllers\MainController@amministrazione'])->middleware(['role:admin|coord|resp']);

	Route::get('menuparco', [ 'as' => 'menuparco', 'uses' => 'App\Http\Controllers\MainController@menuparco'])->middleware(['role:admin|coord|resp']);

	Route::get('appalti', [ 'as' => 'appalti', 'uses' => 'App\Http\Controllers\MainController@appalti'])->middleware(['role:admin|coord|resp']);

	Route::get('menuaziende', [ 'as' => 'menuaziende', 'uses' => 'App\Http\Controllers\MainController@menuaziende'])->middleware(['role:admin|coord']);

	Route::get('cliditte', [ 'as' => 'cliditte', 'uses' => 'App\Http\Controllers\MainController@cliditte'])->middleware(['role:admin|coord']);


	Route::get('archivi', [ 'as' => 'archivi', 'uses' => 'App\Http\Controllers\MainController@archivi'])->middleware(['role:admin']);
	*/

	Route::get('dashboard', [ 'as' => 'dashboard', 'uses' => 'App\Http\Controllers\MainController@menu'])->middleware(['auth']);

/////////////////

Route::get('sinistri/{id_appalto?}/{id_sinistro?}/{from?}', [ 'as' => 'sinistri', 'uses' => 'App\Http\Controllers\ControllerSinistri@sinistri']);

Route::post('sinistri/{id_appalto?}/{id_sinistro?}/{from?}', [ 'as' => 'sinistri', 'uses' => 'App\Http\Controllers\ControllerSinistri@sinistri']);


Route::group(['only_log' => ['auth']], function () {

	/*
		blocco di rotte presenti nella tabella main_menu ma riservate (reserved=1) quindi non mostrate nella gestione menu.
		Per aggiungere altre route nel DB, aggiungere alla tabella main_menu specificando il ruolo o in alternativa inserirle solo qui in web.php indicando il ruolo nella middleware
	*/
		Route::get('invoice/{id_doc?}', [ 'as' => 'invoice', 'uses' => 'App\Http\Controllers\ControllerInvito@invoice']);

		Route::post('save_newapp', [ 'as' => 'save_newapp', 'uses' => 'App\Http\Controllers\ControllerAppalti@save_newapp']);

		Route::get('newreper/{id?}', [ 'as' => 'newreper', 'uses' => 'App\Http\Controllers\ControllerReperibilita@newreper']);

		Route::post('save_reper', [ 'as' => 'save_reper', 'uses' => 'App\Http\Controllers\ControllerReperibilita@save_reper']);

		Route::get('contatti', [ 'as' => 'contatti', 'uses' => 'App\Http\Controllers\ControllerArchivi@contatti']);
		
		Route::post('contatti', [ 'as' => 'contatti', 'uses' => 'App\Http\Controllers\ControllerArchivi@contatti']);

		Route::get('documenti/{id_ref?}', [ 'as' => 'documenti', 'uses' => 'App\Http\Controllers\ControllerArchivi@documenti']);
		
		Route::post('documenti', [ 'as' => 'documenti', 'uses' => 'App\Http\Controllers\ControllerArchivi@documenti']);

		Route::post('save_newcand', [ 'as' => 'save_newcand', 'uses' => 'App\Http\Controllers\MainController@save_newcand']);

		Route::post('save_newuser', [ 'as' => 'save_newuser', 'uses' => 'App\Http\Controllers\MainController@save_newuser']);

		Route::post('disable_user', [ 'as' => 'disable_user', 'uses' => 'App\Http\Controllers\MainController@disable_user']);

		Route::post('set_ruolo', [ 'as' => 'set_ruolo', 'uses' => 'App\Http\Controllers\MainController@set_ruolo']);
		
		Route::get('export-parco', [ 'as' => 'export-parco', 'uses' => 'App\Http\Controllers\MainController@exportParco']);

		 Route::get('export-users', [ 'as' => 'export-users', 'uses' => 'App\Http\Controllers\MainController@exportUsers']);
		

		Route::post('definizione_articolo', [ 'as' => 'definizione_articolo', 'uses' => 'App\Http\Controllers\ControllerArticoli@definizione_articolo']);

		Route::get('definizione_articolo/{id?}', [ 'as' => 'definizione_articolo', 'uses' => 'App\Http\Controllers\ControllerArticoli@definizione_articolo']);	


	//rotte per le quali non so dove mostrare nel menu (o sidemenu) e preferisco renderle nascoste nella tabella (con reserved=1)
		Route::get('categorie_prodotti', [ 'as' => 'categorie_prodotti', 'uses' => 'App\Http\Controllers\ControllerArticoli@categorie_prodotti']);
		
		Route::post('categorie_prodotti', [ 'as' => 'categorie_prodotti', 'uses' => 'App\Http\Controllers\ControllerArticoli@categorie_prodotti']);

		Route::get('sottocategorie_prodotti', [ 'as' => 'sottocategorie_prodotti', 'uses' => 'App\Http\Controllers\ControllerArticoli@sottocategorie_prodotti']);
		
		Route::post('sottocategorie_prodotti', [ 'as' => 'sottocategorie_prodotti', 'uses' => 'App\Http\Controllers\ControllerArticoli@sottocategorie_prodotti']);
	
	/////////////////////////////*	

	//ELENCO rotte presenti in main_menu e visibili nella gestione dei menu dall'operatore preposto

	Route::get('adminmenu/{id_get?}/{parent_get?}', [ 'as' => 'adminmenu', 'uses' => 'App\Http\Controllers\ControllerAdmin@adminmenu']);

	Route::post('adminmenu/{id_get?}/{parent_get?}', [ 'as' => 'menu', 'uses' => 'App\Http\Controllers\ControllerAdmin@adminmenu']);

	Route::get('menu/{parent_id?}', [ 'as' => 'menu', 'uses' => 'App\Http\Controllers\MainController@menu']);

	Route::post('menu/{parent_id?}', [ 'as' => 'menu', 'uses' => 'App\Http\Controllers\MainController@menu']);

	Route::get('newcand/{id?}/{from?}/{setuser?}', [ 'as' => 'newcand', 'uses' => 'App\Http\Controllers\MainController@newcand']);


	Route::get('listcand', [ 'as' => 'listcand', 'uses' => 'App\Http\Controllers\MainController@listcand']);
	
	Route::post('listcand', [ 'as' => 'listcand', 'uses' => 'App\Http\Controllers\MainController@listcand']);

	Route::get('listpers', [ 'as' => 'listpers', 'uses' => 'App\Http\Controllers\ControllerPersonale@listpers']);
	
	Route::post('listpers', [ 'as' => 'listpers', 'uses' => 'App\Http\Controllers\ControllerPersonale@listpers']);

	Route::get('scadenze_contratti', [ 'as' => 'scadenze_contratti', 'uses' => 'App\Http\Controllers\ControllerPersonale@scadenze_contratti']);
	
	Route::post('scadenze_contratti', [ 'as' => 'scadenze_contratti', 'uses' => 'App\Http\Controllers\ControllerPersonale@scadenze_contratti']);

	Route::get('registro', [ 'as' => 'registro', 'uses' => 'App\Http\Controllers\Registro@presenze']);
	
	Route::post('registro', [ 'as' => 'registro', 'uses' => 'App\Http\Controllers\Registro@presenze']);

	Route::get('giustificativi', [ 'as' => 'giustificativi', 'uses' => 'App\Http\Controllers\Registro@giustificativi']);

	Route::post('giustificativi', [ 'as' => 'giustificativi', 'uses' => 'App\Http\Controllers\Registro@giustificativi']);


	Route::get('cedolini_view', [ 'as' => 'cedolini_view', 'uses' => 'App\Http\Controllers\ControllerPersonale@cedolini_view'])->middleware(['permission:user_view|gestione_archivi']);
	
	Route::post('cedolini_view', [ 'as' => 'cedolini_view', 'uses' => 'App\Http\Controllers\ControllerPersonale@cedolini_view'])->middleware(['permission:user_view|gestione_archivi']);


	Route::get('cedolini_up', [ 'as' => 'cedolini_up', 'uses' => 'App\Http\Controllers\ControllerPersonale@cedolini_up']);
	
	Route::post('cedolini_up', [ 'as' => 'cedolini_up', 'uses' => 'App\Http\Controllers\ControllerPersonale@cedolini_up']);

	Route::get('tipo_contratto', [ 'as' => 'tipo_contratto', 'uses' => 'App\Http\Controllers\ControllerArchivi@tipo_contratto']);
	
	Route::post('tipo_contratto', [ 'as' => 'tipo_contratto', 'uses' => 'App\Http\Controllers\ControllerArchivi@tipo_contratto']);
	
	Route::get('frm_attestati', [ 'as' => 'frm_attestati', 'uses' => 'App\Http\Controllers\ControllerArchivi@frm_attestati']);
	
	Route::post('frm_attestati', [ 'as' => 'frm_attestati', 'uses' => 'App\Http\Controllers\ControllerArchivi@frm_attestati']);

	Route::get('societa_assunzione', [ 'as' => 'societa_assunzione', 'uses' => 'App\Http\Controllers\ControllerArchivi@societa_assunzione']);
	
	Route::post('societa_assunzione', [ 'as' => 'societa_assunzione', 'uses' => 'App\Http\Controllers\ControllerArchivi@societa_assunzione']);

	Route::get('costo', [ 'as' => 'costo', 'uses' => 'App\Http\Controllers\ControllerArchivi@costo']);
	
	Route::post('costo', [ 'as' => 'costo', 'uses' => 'App\Http\Controllers\ControllerArchivi@costo']);

	Route::get('area_impiego', [ 'as' => 'area_impiego', 'uses' => 'App\Http\Controllers\ControllerArchivi@area_impiego']);
	
	Route::post('area_impiego', [ 'as' => 'area_impiego', 'uses' => 'App\Http\Controllers\ControllerArchivi@area_impiego']);

	Route::get('mansione', [ 'as' => 'mansione', 'uses' => 'App\Http\Controllers\ControllerArchivi@mansione']);
	
	Route::post('mansione', [ 'as' => 'mansione', 'uses' => 'App\Http\Controllers\ControllerArchivi@mansione']);

	Route::get('ccnl', [ 'as' => 'ccnl', 'uses' => 'App\Http\Controllers\ControllerArchivi@ccnl']);
	
	Route::post('ccnl', [ 'as' => 'ccnl', 'uses' => 'App\Http\Controllers\ControllerArchivi@ccnl']);

	Route::get('tipologia_contr', [ 'as' => 'tipologia_contr', 'uses' => 'App\Http\Controllers\ControllerArchivi@tipologia_contr']);
	
	Route::post('tipologia_contr', [ 'as' => 'tipologia_contr', 'uses' => 'App\Http\Controllers\ControllerArchivi@tipologia_contr']);

	Route::get('tipo_documento', [ 'as' => 'tipo_documento', 'uses' => 'App\Http\Controllers\ControllerArchivi@tipo_documento']);
	
	Route::post('tipo_documento', [ 'as' => 'tipo_documento', 'uses' => 'App\Http\Controllers\ControllerArchivi@tipo_documento']);

	Route::get('sotto_tipo_documento', [ 'as' => 'sotto_tipo_documento', 'uses' => 'App\Http\Controllers\ControllerArchivi@sotto_tipo_documento']);
	
	Route::post('sotto_tipo_documento', [ 'as' => 'sotto_tipo_documento', 'uses' => 'App\Http\Controllers\ControllerArchivi@sotto_tipo_documento']);


	Route::get('utenti', [ 'as' => 'utenti', 'uses' => 'App\Http\Controllers\ControllerPersonale@utenti']);
	
	Route::post('utenti', [ 'as' => 'utenti', 'uses' => 'App\Http\Controllers\ControllerPersonale@utenti']);

	Route::get('sezionali', [ 'as' => 'sezionali', 'uses' => 'App\Http\Controllers\ControllerServizi@sezionali']);
	
	Route::post('sezionali', [ 'as' => 'sezionali', 'uses' => 'App\Http\Controllers\ControllerServizi@sezionali']);

	Route::get('ditte', [ 'as' => 'ditte', 'uses' => 'App\Http\Controllers\ControllerServizi@ditte']);
	
	Route::post('ditte', [ 'as' => 'ditte', 'uses' => 'App\Http\Controllers\ControllerServizi@ditte']);

	Route::get('lista_preventivi', [ 'as' => 'lista_preventivi', 'uses' => 'App\Http\Controllers\ControllerPreventivi@lista_preventivi']);
	
	Route::post('lista_preventivi', [ 'as' => 'lista_preventivi', 'uses' => 'App\Http\Controllers\ControllerPreventivi@lista_preventivi']);

	Route::get('preventivo/{id?}', [ 'as' => 'preventivo', 'uses' => 'App\Http\Controllers\ControllerPreventivi@preventivo']);
	
	Route::post('preventivo/{id?}', [ 'as' => 'preventivo', 'uses' => 'App\Http\Controllers\ControllerPreventivi@preventivo']);

	Route::get('servizi/{id_ref?}', [ 'as' => 'servizi', 'uses' => 'App\Http\Controllers\ControllerServizi@servizi']);
	
	Route::post('servizi', [ 'as' => 'servizi', 'uses' => 'App\Http\Controllers\ControllerServizi@servizi']);

	Route::get('aliquote', [ 'as' => 'aliquote', 'uses' => 'App\Http\Controllers\ControllerInvito@aliquote']);
	
	Route::post('aliquote', [ 'as' => 'aliquote', 'uses' => 'App\Http\Controllers\ControllerInvito@aliquote']);

	Route::get('gestione_servizi', [ 'as' => 'gestione_servizi', 'uses' => 'App\Http\Controllers\ControllerServizi@gestione_servizi']);
	
	Route::post('gestione_servizi', [ 'as' => 'gestione_servizi', 'uses' => 'App\Http\Controllers\ControllerServizi@gestione_servizi']);

	Route::get('newapp/{id?}/{from?}/{num_send?}', [ 'as' => 'newapp', 'uses' => 'App\Http\Controllers\ControllerAppalti@newapp']);


	Route::get('listapp/{id?}', [ 'as' => 'listapp', 'uses' => 'App\Http\Controllers\ControllerAppalti@listapp']);
	
	Route::post('listapp/{id?}', [ 'as' => 'listapp', 'uses' => 'App\Http\Controllers\ControllerAppalti@listapp']);

	Route::get('listrep/{id?}', [ 'as' => 'listrep', 'uses' => 'App\Http\Controllers\ControllerReperibilita@listrep']);
	
	Route::post('listrep/{id?}', [ 'as' => 'listrep', 'uses' => 'App\Http\Controllers\ControllerReperibilita@listrep']);

	Route::get('rifornimenti/{id?}', [ 'as' => 'rifornimenti', 'uses' => 'App\Http\Controllers\ControllerRifornimenti@rifornimenti']);
	
	Route::post('rifornimenti/{id?}', [ 'as' => 'rifornimenti', 'uses' => 'App\Http\Controllers\ControllerRifornimenti@rifornimenti']);

	Route::get('invito/{id?}', [ 'as' => 'invito', 'uses' => 'App\Http\Controllers\ControllerInvito@invito']);
	
	Route::post('invito/{id?}', [ 'as' => 'invito', 'uses' => 'App\Http\Controllers\ControllerInvito@invito']);

	Route::get('lista_inviti', [ 'as' => 'lista_inviti', 'uses' => 'App\Http\Controllers\ControllerInvito@lista_inviti']);
	
	Route::post('lista_inviti', [ 'as' => 'lista_inviti', 'uses' => 'App\Http\Controllers\ControllerInvito@lista_inviti']);

	Route::post('scheda_mezzo', [ 'as' => 'scheda_mezzo', 'uses' => 'App\Http\Controllers\ControllerParco@scheda_mezzo']);

	Route::get('scheda_mezzo/{id?}', [ 'as' => 'scheda_mezzo', 'uses' => 'App\Http\Controllers\ControllerParco@scheda_mezzo']);	


	Route::get('inventario_flotta', [ 'as' => 'inventario_flotta', 'uses' => 'App\Http\Controllers\ControllerParco@inventario_flotta']);
	
	Route::post('inventario_flotta', [ 'as' => 'inventario_flotta', 'uses' => 'App\Http\Controllers\ControllerParco@inventario_flotta']);


	Route::post('riparazioni', [ 'as' => 'riparazioni', 'uses' => 'App\Http\Controllers\ControllerParco@riparazioni']);

	Route::get('riparazioni/{id_mezzo?}', [ 'as' => 'riparazioni', 'uses' => 'App\Http\Controllers\ControllerParco@riparazioni']);	

	Route::post('riparazione', [ 'as' => 'riparazione', 'uses' => 'App\Http\Controllers\ControllerParco@riparazione']);

	Route::get('riparazione/{id_mezzo?}', [ 'as' => 'riparazione', 'uses' => 'App\Http\Controllers\ControllerParco@riparazione']);
	
	
	Route::get('elenco_sinistri', [ 'as' => 'elenco_sinistri', 'uses' => 'App\Http\Controllers\ControllerSinistri@elenco_sinistri']);
	
	Route::post('elenco_sinistri', [ 'as' => 'elenco_sinistri', 'uses' => 'App\Http\Controllers\ControllerSinistri@elenco_sinistri']);


	

	Route::post('elenco_articoli', [ 'as' => 'elenco_articoli', 'uses' => 'App\Http\Controllers\ControllerArticoli@elenco_articoli']);

	Route::get('elenco_articoli', [ 'as' => 'elenco_articoli', 'uses' => 'App\Http\Controllers\ControllerArticoli@elenco_articoli']);


	Route::get('magazzini', [ 'as' => 'magazzini', 'uses' => 'App\Http\Controllers\ControllerArticoli@magazzini']);
	
	Route::post('magazzini', [ 'as' => 'magazzini', 'uses' => 'App\Http\Controllers\ControllerArticoli@magazzini']);

	Route::get('elenco_fornitori', [ 'as' => 'elenco_fornitori', 'uses' => 'App\Http\Controllers\ControllerAcquisti@elenco_fornitori']);
	
	Route::post('elenco_fornitori', [ 'as' => 'elenco_fornitori', 'uses' => 'App\Http\Controllers\ControllerAcquisti@elenco_fornitori']);


	Route::get('elenco_ordini_fornitori', [ 'as' => 'elenco_ordini_fornitori', 'uses' => 'App\Http\Controllers\ControllerAcquisti@elenco_ordini_fornitori']);
	
	Route::post('elenco_ordini_fornitori', [ 'as' => 'elenco_ordini_fornitori', 'uses' => 'App\Http\Controllers\ControllerAcquisti@elenco_ordini_fornitori']);

	


	Route::post('scheda_fornitore', [ 'as' => 'scheda_fornitore', 'uses' => 'App\Http\Controllers\ControllerAcquisti@scheda_fornitore']);

	Route::get('scheda_fornitore/{id?}', [ 'as' => 'scheda_fornitore', 'uses' => 'App\Http\Controllers\ControllerAcquisti@scheda_fornitore']);	


	Route::post('ordini_fornitore', [ 'as' => 'ordini_fornitore', 'uses' => 'App\Http\Controllers\ControllerAcquisti@ordini_fornitore']);

	Route::get('ordini_fornitore/{id?}', [ 'as' => 'ordini_fornitore', 'uses' => 'App\Http\Controllers\ControllerAcquisti@ordini_fornitore']);


	Route::post('evasione_ordini', [ 'as' => 'evasione_ordini', 'uses' => 'App\Http\Controllers\ControllerAcquisti@evasione_ordini']);

	Route::get('evasione_ordini/{id?}', [ 'as' => 'evasione_ordini', 'uses' => 'App\Http\Controllers\ControllerAcquisti@evasione_ordini']);	

	Route::get('newpassuser', [ 'as' => 'newpassuser', 'uses' => 'App\Http\Controllers\MainController@newpassuser']);
	
	Route::post('newpassuser', [ 'as' => 'newpassuser', 'uses' => 'App\Http\Controllers\MainController@newpassuser']);


	Route::get('servizi_noleggio', [ 'as' => 'servizi_noleggio', 'uses' => 'App\Http\Controllers\ControllerArchiviParco@servizi_noleggio']);
	
	Route::post('servizi_noleggio', [ 'as' => 'servizi_noleggio', 'uses' => 'App\Http\Controllers\ControllerArchiviParco@servizi_noleggio']);

	Route::get('modello', [ 'as' => 'modello', 'uses' => 'App\Http\Controllers\ControllerArchiviParco@modello']);
	
	Route::post('modello', [ 'as' => 'modello', 'uses' => 'App\Http\Controllers\ControllerArchiviParco@modello']);
	
	Route::get('marca', [ 'as' => 'marca', 'uses' => 'App\Http\Controllers\ControllerArchiviParco@marca']);
	
	Route::post('marca', [ 'as' => 'marca', 'uses' => 'App\Http\Controllers\ControllerArchiviParco@marca']);

	Route::get('badge', [ 'as' => 'badge', 'uses' => 'App\Http\Controllers\ControllerArchiviParco@badge']);
	
	Route::post('badge', [ 'as' => 'badge', 'uses' => 'App\Http\Controllers\ControllerArchiviParco@badge']);		
	
	Route::get('cartac', [ 'as' => 'cartac', 'uses' => 'App\Http\Controllers\ControllerArchiviParco@cartac']);
	
	Route::post('cartac', [ 'as' => 'cartac', 'uses' => 'App\Http\Controllers\ControllerArchiviParco@cartac']);


	Route::get('telepass', [ 'as' => 'telepass', 'uses' => 'App\Http\Controllers\ControllerArchiviParco@telepass']);
	
	Route::post('telepass', [ 'as' => 'telepass', 'uses' => 'App\Http\Controllers\ControllerArchiviParco@telepass']);

});



//routing Ajax
Route::group(['only_log' => ['auth']], function () {

	//chiamate ajax prodotti

	Route::post('elenco_categorie', 'App\Http\Controllers\AjaxControllerAcquisti@elenco_categorie');
	
	Route::post('elenco_sottocategorie', 'App\Http\Controllers\AjaxControllerAcquisti@elenco_sottocategorie');

	Route::post('refresh_prodotti', 'App\Http\Controllers\AjaxControllerAcquisti@refresh_prodotti');
	//
	
	//chiamate ajax parco auto
	Route::post('refresh_servizi_noleggio', 'App\Http\Controllers\AjaxControllerParco@refresh_servizi_noleggio');

	Route::post('popola_modelli', 'App\Http\Controllers\AjaxControllerParco@popola_modelli');

	Route::post('refresh_marca', 'App\Http\Controllers\AjaxControllerParco@refresh_marca');

	Route::post('refresh_carta', 'App\Http\Controllers\AjaxControllerParco@refresh_carta');

	Route::post('refresh_badge', 'App\Http\Controllers\AjaxControllerParco@refresh_badge');

	Route::post('refresh_telepass', 'App\Http\Controllers\AjaxControllerParco@refresh_telepass');
	///



	Route::post('refresh_forn', 'App\Http\Controllers\AjaxControllerAcquisti@refresh_forn');


	Route::post('load_contatti_soc', 'App\Http\Controllers\AjaxControllerServ@load_contatti_soc');
	
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

	Route::post('refresh_servizi_ditte', 'App\Http\Controllers\AjaxControllerServ@refresh_servizi_ditte');


	Route::post('get_doc_ditta', 'App\Http\Controllers\AjaxControllerServ@get_doc_ditta');
	
	Route::post('remove_doc_ditta', 'App\Http\Controllers\AjaxControllerServ@remove_doc_ditta');	
	
	Route::post('edit_row_fattura', 'App\Http\Controllers\AjaxControllerFatture@edit_row_fattura');

});


require __DIR__.'/auth.php';
