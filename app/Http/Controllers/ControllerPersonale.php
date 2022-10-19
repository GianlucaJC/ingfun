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
use App\Models\societa;
use App\Models\centri_costo;
use App\Models\area_impiego;
use App\Models\mansione;
use App\Models\ccnl;
use App\Models\tipologia_contr;

use DB;



use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class ControllerPersonale extends Controller
{


	public function listpers(Request $request) {
		
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
		->where("tipo_anagr","ASS")
		->orderBy('nominativo')->get();

		$mansione=mansione::orderBy('descrizione')->get();
		$mansioni=array();
		foreach($mansione as $mans){
			$id_m=$mans->id;$descrizione=$mans->descrizione;
			$mansioni[$id_m]=$descrizione;
		}

		return view('all_views/listpers')->with('candidati', $candidati)->with("view_dele",$view_dele)->with("mansioni",$mansioni);
	}

}
