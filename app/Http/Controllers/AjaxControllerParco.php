<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\parco_marca_mezzo;
use App\Models\parco_modello_mezzo;

use Mail;
use DB;


class AjaxControllerParco extends Controller
	{


	public function refresh_marca(Request $request){
		$marche=parco_marca_mezzo::select('id','marca')
		->orderBy('marca')
		->get();
		return json_encode($marche);
	}

	public function refresh_modello(Request $request){
		$modelli=parco_modello_mezzo::select('id','modello')
		->orderBy('modello')
		->get();
		return json_encode($modelli);
	}

	public function popola_modelli(Request $request){		
		$id_marca = $request->input('id_marca');
		$modelli=DB::table('parco_modello_mezzo as m')
		->select('m.id as id_modello','m.modello')
		->where('m.id_marca', '=', $id_marca)
		->where('m.dele', '=', 0)
		->get();
        return json_encode($modelli);
	}	

}
