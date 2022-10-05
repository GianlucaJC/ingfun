<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\italy_provincies;
use App\Models\italy_cities;
use App\Models\italy_cap;
use App\Models\tipoc;

class AjaxControllerCand extends Controller
{
	public function refresh_tipoc(){
		$tipoc = tipoc::where('dele','=',0)->orderBy('descrizione')->get();
        return json_encode($tipoc);
	}

	public function lista_province(Request $request){

		$id_regione = $request->input('id_regione');;
        $province = italy_provincies::where('id_regione', '=', $id_regione)->get();
        return json_encode($province);

	}

	public function lista_comuni(Request $request){
		$comuni=array();
		
		$sigla = $request->input('sigla');
		$comune_search = $request->input('comune_search');
		if ($comune_search=="0")
			$comuni = italy_cities::where('provincia', '=', $sigla)->orderBy('comune')->get();
		else {
			$comuni = italy_cities::where('comune', 'like', "%".$comune_search."%")->orderBy('comune')->get();
		}	
	
		
        return json_encode($comuni);

	}

	public function lista_cap(Request $request){
		
		
		$istat = $request->input('istat');
        $cap = italy_cap::where('istat', '=', $istat)->skip(0)->take(1)->orderBy('cap')->get();
		
        return json_encode($cap);

	}
	

}
