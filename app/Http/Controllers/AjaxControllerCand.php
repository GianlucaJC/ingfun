<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\italy_provincies;
use App\Models\italy_cities;
use App\Models\italy_cap;

class AjaxControllerCand extends Controller
{
	public function lista_province(Request $request){

		$id_regione = $request->input('id_regione');;
        $province = italy_provincies::where('id_regione', '=', $id_regione)->get();
        return json_encode($province);

	}

	public function lista_comuni(Request $request){
		$comuni=array();
		
		$sigla = $request->input('sigla');
        $comuni = italy_cities::where('provincia', '=', $sigla)->orderBy('comune')->get();
		
        return json_encode($comuni);

	}

	public function lista_cap(Request $request){
		
		
		$istat = $request->input('istat');
        $cap = italy_cap::where('istat', '=', $istat)->skip(0)->take(1)->orderBy('cap')->get();
		
        return json_encode($cap);

	}
	

}
