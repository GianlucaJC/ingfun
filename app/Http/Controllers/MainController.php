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

use DB;


class mainController extends Controller
{
	public function dashboard() {
		$name="";
		return view('all_views/dashboard')->with('name', $name);
	}

	public function newcand() {		
		$regioni = regioni::orderBy('regione')->get();
		$all_comuni = italy_cities::orderBy('comune')->get();

		return view('all_views/newcand')->with('regioni', $regioni)->with('all_comuni',$all_comuni);
	}

	public function save_newcand(Request $request) {		
			$id_user=Auth::user()->id;
			$candidati = new candidati;
			//Dati Anagrafici
			$nominativo=$request->input('cognome')." ".$request->input('nome');
			$candidati->cognome = $request->input('cognome');
			$candidati->nome = $request->input('nome');
			$candidati->nominativo = $nominativo;
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



			
			
			
			$candidati->save();		
		$name="";
		return view('all_views/listcand')->with('name', $name);

	}

	public function listcand() {
		$candidati = candidati::orderBy('nominativo')->get();
		return view('all_views/listcand')->with('candidati', $candidati);
	}

}
