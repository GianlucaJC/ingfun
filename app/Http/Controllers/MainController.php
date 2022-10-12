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
use App\Models\sicurezza;

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

		return $candidati;
	}

	public function newcand($id=0) {
		$candidati=array();
		
		//in caso di nuovo form l'array candidati è vuoto...per cui lo inizializzo 
		$candidati=$this->init_newcand();
		if ($id!=0) $candidati=candidati::where('id', "=", $id)->get();

		$regioni = regioni::orderBy('regione')->get();
		$all_comuni = italy_cities::orderBy('comune')->get();
		
		$sicurezza=sicurezza::orderBy('descrizione')
		->when($id=="0", function ($sicurezza) {
			return $sicurezza->where('dele', "=","0");
		})
		->get();
		
		$tipoc=tipoc::orderBy('descrizione')->where('dele', "=","0")->get();
		return view('all_views/newcand')->with('regioni', $regioni)->with('all_comuni',$all_comuni)->with('tipoc',$tipoc)->with("candidati",$candidati)->with('id_cand',$id)->with('sicurezza', $sicurezza);
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
			$candidati->anno_mese = $request->input('anno_mese');
			$candidati->patenti = $patenti;
			$candidati->capacita = $request->input('capacita');
			$candidati->libero_p = $request->input('libero_p');
			$candidati->tipo_contratto = $request->input('tipo_contratto');
			$candidati->ore_sett = $request->input('ore_sett');
			$candidati->soc_ass = $request->input('soc_ass');
			$candidati->divisione = $request->input('divisione');
			$candidati->area_impiego = $request->input('area_impiego');
			$candidati->mansione = $request->input('mansione');
			$candidati->centro_costo = $request->input('centro_costo');
			$candidati->netto_concordato = $request->input('netto_concordato');
			$candidati->costo_azienda = $request->input('costo_azienda');
			$candidati->zona_lavoro = $request->input('zona_lavoro');
			$candidati->n_scarpe = $request->input('n_scarpe');
			$candidati->taglia = $request->input('taglia');
			$candidati->status_candidatura = $request->input('status_candidatura');
			$candidati->note = $request->input('note');
			$candidati->file_curr = $request->input('fx_curr');
			$candidati->stato_occ = $request->input('stato_occ');

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

		

		return view('all_views/listcand')->with('candidati', $candidati)->with("view_dele",$view_dele);
	}

}
