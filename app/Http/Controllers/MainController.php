<?php
//test
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\candidati;
use App\Models\regioni;
use App\Models\italy_cities;
use App\Models\tipoc;
use App\Models\voci_doc;
use App\Models\societa;
use App\Models\centri_costo;
use App\Models\area_impiego;
use App\Models\mansione;
use App\Models\ccnl;
use App\Models\tipologia_contr;
use App\Models\tipo_doc;
use App\Models\ref_doc;

use DB;



use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class mainController extends Controller
{
	public function dashboard() {
		$name="";
		return view('all_views/dashboard')->with('name', $name);
	}

	public function init_newcand() {
		$candidati=array();
		$candidati[0]['cognome']=null;
		$candidati[0]['nome']=null;
		$candidati[0]['sesso']=null;
		$candidati[0]['indirizzo']=null;
		$candidati[0]['comune']=null;
		$candidati[0]['cap']=null;
		$candidati[0]['provincia']=null;
		$candidati[0]['codfisc']=null;
		$candidati[0]['datanasc']=null;
		$candidati[0]['comunenasc']=null;
		$candidati[0]['pro_nasc']=null;
		$candidati[0]['email']=null;
		$candidati[0]['telefono']=null;
		$candidati[0]['pec']=null;
		$candidati[0]['iban']=null;
		$candidati[0]['file_curr']=null;
		$candidati[0]['stato_occ']=null;
		$candidati[0]['rdc']=null;
		$candidati[0]['cat_pro']=null;
		$candidati[0]['titolo_studio']=null;
		$candidati[0]['istituto_conseguimento']=null;
		$candidati[0]['anno_mese']=null;
		$candidati[0]['patenti']=null;
		$candidati[0]['capacita']=null;
		$candidati[0]['libero_p']=null;
		$candidati[0]['tipo_contratto']=null;
		$candidati[0]['ore_sett']=null;
		$candidati[0]['soc_ass']=null;
		//$candidati[0]['divisione']=null;
		$candidati[0]['area_impiego']=null;			
		$candidati[0]['mansione']=null;
		$candidati[0]['centro_costo']=null;
		$candidati[0]['contratto']=null;
		$candidati[0]['livello']=null;
		$candidati[0]['tipo_contr']=null;
		$candidati[0]['netto_concordato']=null;
		$candidati[0]['costo_azienda']=null;
		$candidati[0]['zona_lavoro']=null;
		$candidati[0]['n_scarpe']=null;
		$candidati[0]['taglia']=null;
		$candidati[0]['status_candidatura']=null;
		$candidati[0]['note']=null;

		return $candidati;
	}

	public function newcand($id=0) {
		$candidati=array();
		
		//in caso di nuovo form l'array candidati è vuoto...per cui lo inizializzo 
		$candidati=$this->init_newcand();
		if ($id!=0) $candidati=candidati::where('id', "=", $id)->get();

		$regioni = regioni::orderBy('regione')->get();
		$all_comuni = italy_cities::orderBy('comune')->get();
		
		
		$tipo_doc=tipo_doc::orderBy('descrizione')->get();
		
		$formazione=voci_doc::orderBy('descrizione')
		->when($id=="0", function ($formazione) {			
			return $formazione->where('dele', "=","0");
		})
		//ho messo un riferimento statico al codice corso di formazione (4)
		->where('id_corso','=',4)
		->get();

		$societa=societa::orderBy('descrizione')
		->when($id=="0", function ($societa) {
			return $societa->where('dele', "=","0");
		})
		->get();
		$centri_costo=centri_costo::orderBy('descrizione')
		->when($id=="0", function ($centri_costo) {
			return $centri_costo->where('dele', "=","0");
		})
		->get();
		$area_impiego=area_impiego::orderBy('descrizione')
		->when($id=="0", function ($area_impiego) {
			return $area_impiego->where('dele', "=","0");
		})
		->get();

		$mansione=mansione::orderBy('descrizione')
		->when($id=="0", function ($mansione) {
			return $mansione->where('dele', "=","0");
		})
		->get();		

		$ccnl=ccnl::orderBy('descrizione')
		->when($id=="0", function ($ccnl) {
			return $ccnl->where('dele', "=","0");
		})
		->get();				
		
		$tipoc=tipoc::orderBy('descrizione')
		->when($id=="0", function ($tipoc) {
			return $tipoc->where('dele', "=","0");
		})
		->get();

		$elenco_doc = DB::table('ref_doc as r')
		->join('tipo_doc as d', 'r.id_tipo_doc', '=', 'd.id')
		->join('voci_doc as v', 'r.id_sotto_tipo', '=', 'v.id')
		->select('r.id','r.id_cand','r.scadenza', 'r.nomefile', 'r.created_at', 'r.updated_at','d.descrizione as tipodocumento', 'v.descrizione as sottodocumento')
		->where('r.id_cand','=',$id)
		->orderByDesc('r.id')
		->take(5)
		->get();

		$tipologia_contr=tipologia_contr::orderBy('descrizione')
		->when($id=="0", function ($tipologia_contr) {
			return $tipologia_contr->where('dele', "=","0");
		})
		->get();
		
		return view('all_views/newcand')->with('regioni', $regioni)->with('all_comuni',$all_comuni)->with('tipoc',$tipoc)->with("candidati",$candidati)->with('id_cand',$id)->with('formazione', $formazione)->with("societa",$societa)->with("centri_costo",$centri_costo)->with("area_impiego",$area_impiego)->with("mansione",$mansione)->with("ccnl",$ccnl)->with("tipologia_contr",$tipologia_contr)->with('tipo_doc',$tipo_doc)->with("elenco_doc",$elenco_doc);
	}

	public function save_newcand(Request $request) {
			$id_user=Auth::user()->id;
			
			
			$id_cand=$request->input('id_cand');
			if ($id_cand!=0)
				$candidati = candidati::find($id_cand);
			else
				$candidati = new candidati;
			
			$attestati_arr=$request->input('attestato');
			$attestati=null;
			if (is_array($attestati_arr))
				$attestati=implode(";",$attestati_arr);

			
			
			//Dati Anagrafici
			$nominativo=$request->input('cognome')." ".$request->input('nome');
			if ($id_cand==0) {
				$candidati->tipo_init_anagr = "CAND";
				$candidati->tipo_anagr = "CAND";
			}
			$candidati->cognome = $request->input('cognome');
			$candidati->nome = $request->input('nome');
			$candidati->nominativo = $nominativo;
			$candidati->sesso = $request->input('sesso');
			$candidati->indirizzo = $request->input('indirizzo');
			$candidati->cap = $request->input('cap');
			$candidati->comune = $request->input('comune');
			$candidati->provincia = $request->input('provincia');
			$candidati->codfisc = $request->input('codfisc');
			$candidati->datanasc = $request->input('datanasc');
			$candidati->comunenasc = $request->input('comunenasc');
			$candidati->pro_nasc = $request->input('pro_nasc');
			$candidati->email = $request->input('email');
			$candidati->telefono = $request->input('telefono');
			$candidati->pec = $request->input('pec');
			$candidati->iban = $request->input('iban');
			
			$candidati->attestati = $attestati;
			
			//Dati Specifici
				
			if ($request->has("patenti")) 
				$patenti=implode(";",$request->input('patenti'));
			else
				$patenti="";
			$candidati->stato_occ = $request->input('stato_occ');
			$candidati->rdc = $request->input('rdc');
			$candidati->cat_pro = $request->input('cat_pro');
			$candidati->titolo_studio = $request->input('titolo_studio');
			$candidati->istituto_conseguimento = $request->input('istituto_conseguimento');
			
			
			$anno=trim($request->input('anno'));
			$mese=trim($request->input('mese'));
			$candidati->anno_mese = $anno."-".$mese;
			
			$candidati->patenti = $patenti;
			$candidati->capacita = $request->input('capacita');
			$candidati->libero_p = $request->input('libero_p');
			$candidati->tipo_contratto = $request->input('tipologia_contr');
			$candidati->ore_sett = $request->input('ore_sett');
			$candidati->soc_ass = $request->input('soc_ass');
			//$candidati->divisione = $request->input('divisione');
			$candidati->area_impiego = $request->input('area_impiego');
			$candidati->mansione = $request->input('mansione');
			$candidati->centro_costo = $request->input('centro_costo');
			$candidati->contratto = $request->input('contratto');
			$candidati->livello = $request->input('livello');
			$candidati->tipo_contr = $request->input('tipo_contr');

			$candidati->netto_concordato = $request->input('netto_concordato');
			$candidati->costo_azienda = $request->input('costo_azienda');
			$candidati->zona_lavoro = $request->input('zona_lavoro');
			$candidati->n_scarpe = $request->input('n_scarpe');
			$candidati->taglia = $request->input('taglia');
			
			$candidati->note = $request->input('note');
			$candidati->file_curr = $request->input('fx_curr');
			$candidati->appartenenza = $request->input('appartenenza');
			$candidati->subappalto = $request->input('subappalto');
			$candidati->affiancamento = $request->input('affiancamento');
			$candidati->data_inizio = $request->input('data_inizio');
			//$candidati->doc = $request->input('doc');
			
			
			
			if ($request->has("sub_assunzione")) {
				$sub_assunzione=$request->input('sub_assunzione');
				$candidati->status_candidatura = 3;
				$candidati->tipo_anagr = "ASS";
			}



			$candidati->save();		

			
		$name="";
		
		return $this->listcand($request);
		
	}

	public function listcand(Request $request) {
		$view_dele=0;
		if ($request->has("view_dele")) $view_dele=$request->input("view_dele");
		if ($view_dele=="on") $view_dele=1;
		$restore_cand=$request->input("restore_cand");
		$dele_cand=$request->input("dele_cand");

		if (strlen($dele_cand)!=0) {
			candidati::where('id', $dele_cand)
			  ->update(['dele' => 1]);			
		}		
		if (strlen($restore_cand)!=0) {
			candidati::where('id', $restore_cand)
			  ->update(['dele' => 0]);			
		}				
		
		$candidati=DB::table('candidatis')
		->when($view_dele=="0", function ($candidati) {
			return $candidati->where('dele', "=","0");
		})
		->orderBy('nominativo')->get();

		$mansione=mansione::orderBy('descrizione')->get();
		$mansioni=array();
		foreach($mansione as $mans){
			$id_m=$mans->id;$descrizione=$mans->descrizione;
			$mansioni[$id_m]=$descrizione;
		}

		return view('all_views/listcand')->with('candidati', $candidati)->with("view_dele",$view_dele)->with("mansioni",$mansioni);
	}

}
