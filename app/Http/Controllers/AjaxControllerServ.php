<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use App\Models\lavoratori;
use App\Models\ditte;
use Mail;
use DB;


class AjaxControllerServ extends Controller
	{

	public function popola_lav(Request $request){
		$lavoratori=array();		
		$id_ditta = $request->input('id_ditta');
		$lavoratori = lavoratori::where('id_ditta', '=', $id_ditta)->orderBy('nominativo')->get();
        return json_encode($lavoratori);
	}

	public function getditta(Request $request){		
		$id_ditta = $request->input('id_ditta');
		$infoditta=DB::table('ditte as d')
		->select('d.*')
		->where('d.id', '=', $id_ditta)
		->get();
        return json_encode($infoditta);
	}	

}
