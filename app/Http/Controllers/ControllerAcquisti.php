<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\user;
use App\Models\italy_cities;

use DB;

class ControllerAcquisti extends Controller
{
	public function __construct() {
		$this->middleware('auth')->except(['index']);
	}
	
	public function save_fornitore($request) {
		$id_fornitore=$request->input('id_fornitore');
		if ($id_fornitore!=0) 
			$psm = parco_scheda_mezzo::find($id_fornitore);
		else 
			$psm = new parco_scheda_mezzo;
		$psm->targa = strtoupper($request->input('targa'));
		$psm->numero_interno = $request->input('numero_interno');
		$psm->tipologia = $request->input('tipologia');
		$psm->marca = $request->input('marca');
		$psm->modello = $request->input('modello');
		$psm->telaio = $request->input('telaio');
		$psm->alimentazione = $request->input('alimentazione');
		$psm->proprieta = $request->input('proprieta');
		$notifica_alert_noleggio=$request->input('notifica_alert_noleggio');
		if ($notifica_alert_noleggio=="0") 
			$psm->notifica_alert_noleggio =0;
		
		
		$tdn=$request->input('tipo_durata_noleggio');
		$dn=$request->input('durata_noleggio');
		$psm->tipo_durata_noleggio = $tdn;
		$psm->durata_noleggio = $dn;
		
		$dn_gg=0;
		if ($tdn=="g") $dn_gg=$dn;
		elseif ($tdn=="m") $dn_gg=$dn*30;
		elseif ($tdn=="a") $dn_gg=$dn*365;
		if ($dn_gg!=0) $psm->durata_noleggio_gg = $dn_gg;
		if (strlen($dn)==0) $psm->durata_noleggio_gg = null;
		
		$psm->da_data_n = $request->input('da_data_n');
		
		if (strlen($dn)==0) $psm->a_data_n = null;
		else {
			$da_data_n=$request->input('da_data_n');
			$a_data_n=date('Y-m-d', strtotime($da_data_n. " + $dn_gg days"));
			$psm->a_data_n=$a_data_n;
		}
		
		$tan=$request->input('tipo_alert_noleggio');
		$psm->tipo_alert_noleggio = $tan;
		$am=$request->input('alert_mail');
		$psm->alert_mail = $am;
		$dna_gg=0;
		if ($tan=="g") $dna_gg=$am;
		elseif ($tan=="m") $dna_gg=$am*30;
		elseif ($tan=="a") $dna_gg=$am*365;
		
		if ($dna_gg!=0) $psm->gg_alert_mail = $dna_gg;
		if (strlen($am)==0) $psm->gg_alert_mail = null;

		
		$psm->importo_noleggio = $request->input('importo_noleggio');
		$psm->km_alert_mail = $request->input('km_alert_mail');
		
		$psm->km_noleggio_remote=$request->input('km_noleggio_remote');
		
		$arr_servizi=$request->input('servizi_noleggio');
		if (is_array($arr_servizi))
			$servizi_noleggio=implode(";",$arr_servizi);
		else 
			$servizi_noleggio=null;
		$psm->servizi_noleggio = $servizi_noleggio;
				
		$psm->km_noleggio = $request->input('km_noleggio');
		$psm->posti = $request->input('posti');
		$psm->chilometraggio = $request->input('chilometraggio');
		$psm->catene = $request->input('catene');
		$psm->carta_carburante = $request->input('carta_carburante');
		$psm->badge_cisterna = $request->input('badge_cisterna');
		$psm->telepass = $request->input('telepass');
		$psm->data_immatricolazione = $request->input('data_immatricolazione');
		$psm->ultima_revisione = $request->input('ultima_revisione');
		$psm->scadenza_assicurazione = $request->input('scadenza_assicurazione');
		$psm->scadenza_bollo = $request->input('scadenza_bollo');
		$psm->prossimo_tagliando = $request->input('prossimo_tagliando');
		$psm->marca_modello_pneumatico = $request->input('marca_modello_pneumatico');
		$psm->misura_pneumatico = $request->input('misura_pneumatico');
		$psm->primo_equipaggiamento = $request->input('primo_equipaggiamento');
		$psm->km_installazione = $request->input('km_installazione');
		$psm->officina_installazione = $request->input('officina_installazione');
		$psm->anomalia_note = $request->input('anomalia_note');
		$psm->mezzo_marciante = $request->input('mezzo_marciante');
		$psm->mezzo_manutenzione = $request->input('mezzo_manutenzione');
		$psm->mezzo_riparazione = $request->input('mezzo_riparazione');
		$psm->officina_riferimento = $request->input('officina_riferimento');
		$psm->data_consegna_riparazione = $request->input('data_consegna_riparazione');
		$psm->importo_preventivo = $request->input('importo_preventivo');
		$psm->importo_fattura = $request->input('importo_fattura');

		$psm->save();
		$id_fornitore=$psm->id;
		return $id_fornitore;
	}
	
	public function scheda_fornitore($id_fornitore=0) {
		$request=request();
		$btn_save_fornitore=$request->input("btn_save_fornitore");
		$save_fornitore=0;
		if ($btn_save_fornitore=="save") 
			$save_fornitore=$this->save_fornitore($request);

		$info_fornitore=array();
		$modello="";
	
		if ($id_fornitore!=0) {
			/*
			$info_fornitore=DB::table('parco_scheda_mezzo as s')
			->select('s.*')
			->where('id', "=", $id_fornitore)
			->get();
			$marca=$info_fornitore[0]->marca;
			$modello=parco_modello_mezzo::select('id','modello')
			->where("id_marca","=",$marca)
			->get();		
			*/
		}
		
		$lista_pagamenti=$this->lista_pagamenti();
		/*
		$carta_carburante=parco_carta_carburante::select('id','id_carta')
		->orderBy('id_carta')
		->get();
		*/
		$all_comuni = italy_cities::orderBy('comune')->get();		

		$data=array("id_fornitore"=>$id_fornitore,'info_fornitore'=>$info_fornitore,'all_comuni'=>$all_comuni,'lista_pagamenti'=>$lista_pagamenti);

		
		if ($request->has("btn_save_fornitore")) {
			$id_fornitore=$request->input('id_fornitore');
			if ($save_fornitore!=0) $id_fornitore=$save_fornitore;
			return redirect()->route("scheda_fornitore",['id'=>$id_fornitore]);
		}
		else
		
			return view('all_views/fornitori/scheda_fornitore')->with($data);
		
	}
	
	function lista_pagamenti() {
		$lista=array();
		$lista[0]['id']=1;
		$lista[0]['descrizione']="Contanti";
		$lista[1]['id']=2;
		$lista[1]['descrizione']="Bancomat";
		$lista[2]['id']=3;
		$lista[2]['descrizione']="Assegno";
		$lista[3]['id']=4;
		$lista[3]['descrizione']="Bonifico";
		return $lista;
	}	
	
}

