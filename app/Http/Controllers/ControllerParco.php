<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\user;
use App\Models\mezzi;
use App\Models\parco_carta_carburante;
use App\Models\parco_marca_mezzo;
use App\Models\parco_modello_mezzo;
use App\Models\parco_badge_cisterna;
use App\Models\parco_telepass;
use App\Models\parco_scheda_mezzo;
use App\Models\parco_servizi_noleggio;

use DB;

class ControllerParco extends Controller
{
	public function __construct() {
		$this->middleware('auth')->except(['index']);
	}
	
	public function save_mezzo($request) {
		$id_mezzo=$request->input('id_mezzo');
		if ($id_mezzo!=0) 
			$psm = parco_scheda_mezzo::find($id_mezzo);
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
		$id_mezzo=$psm->id;
		return $id_mezzo;
	}
	
	public function scheda_mezzo($id_mezzo=0) {
		$request=request();
		$btn_save_mezzo=$request->input("btn_save_mezzo");
		$save_mezzo=0;
		if ($btn_save_mezzo=="save") 
			$save_mezzo=$this->save_mezzo($request);
		
		
		$tipomezzo=$this->tipomezzi();

		$marche=parco_marca_mezzo::select('id','marca')
		->orderBy('marca')
		->get();
		
		
		$info_mezzo=array();
		$modello="";
	
		if ($id_mezzo!=0) {
			$info_mezzo=DB::table('parco_scheda_mezzo as s')
			->select('s.*')
			->where('id', "=", $id_mezzo)
			->get();
			$marca=$info_mezzo[0]->marca;
			$modello=parco_modello_mezzo::select('id','modello')
			->where("id_marca","=",$marca)
			->get();		
		}


		$carta_carburante=parco_carta_carburante::select('id','id_carta')
		->orderBy('id_carta')
		->get();
	
		$badges=parco_badge_cisterna::select('id','id_badge')
		->orderBy('id_badge')
		->get();

		$teles=parco_telepass::select('id','id_telepass')
		->orderBy('id_telepass')
		->get();

		$servizi_noleggio=parco_servizi_noleggio::select('id','descrizione')
		->orderBy('descrizione')
		->get();

		


		$data=array("carte_c"=>$carta_carburante,"tipomezzo"=>$tipomezzo,"marche"=>$marche,"badges"=>$badges,"teles"=>$teles,"info_mezzo"=>$info_mezzo,"id_mezzo"=>$id_mezzo,"modello"=>$modello,"servizi_noleggio"=>$servizi_noleggio);

		
		if ($request->has("btn_save_mezzo")) {
			$id_mezzo=$request->input('id_mezzo');
			if ($save_mezzo!=0) $id_mezzo=$save_mezzo;
			return redirect()->route("scheda_mezzo",['id'=>$id_mezzo]);
		}
		else
		
			return view('all_views/parco/scheda_mezzo')->with($data);
		
	}
	
	public function inventario_flotta(Request $request){

		$view_dele=$request->input("view_dele");
		
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");

		$marche_db=parco_marca_mezzo::select('id','marca')->get();
		$marche=array();
		foreach($marche_db as $mx) {
			$marche[$mx->id]=$mx->marca;
		}
		$modelli_db=parco_modello_mezzo::select('id','modello')->get();		
		$modelli=array();
		foreach($modelli_db as $mx) {
			$modelli[$mx->id]=$mx->modello;
		}
		
		if (strlen($dele_contr)!=0) {
			parco_scheda_mezzo::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			parco_scheda_mezzo::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		
		$inventario=DB::table('parco_scheda_mezzo')
		->when($view_dele=="0", function ($inventario) {
			return $inventario->where('dele', "=","0");
		})
		->orderBy('targa')->get();

		return view('all_views/parco/inventario_flotta')->with('inventario', $inventario)->with("view_dele",$view_dele)->with('marche',$marche)->with('modelli',$modelli);

	}	
	
	
	
	
	
	public function tipomezzi() {
		$mezzi=array();
		$mezzi[0]['id']=1;$mezzi[0]['descrizione']="Carro funebre";
		$mezzi[1]['id']=2;$mezzi[1]['descrizione']="Furgone";
		$mezzi[2]['id']=3;$mezzi[2]['descrizione']="Auto";
		$mezzi[3]['id']=4;$mezzi[3]['descrizione']="Furgone attrezzato";
		
		return $mezzi;
	}
	
}

