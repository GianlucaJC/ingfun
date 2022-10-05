<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\tipoc;


class ControllerArchivi extends Controller
{
	public function tipo_contratto(Request $request){

		$tipoc=tipoc::where('dele','=',0)->orderBy('descrizione')->get();
		return view('all_views/gestione/tipocontratto')->with('tipoc', $tipoc);

	}


}
