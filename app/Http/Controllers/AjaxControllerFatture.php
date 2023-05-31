<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\lavoratori;
use App\Models\ditte;
use App\Models\presenze;
use App\Models\log_presenze;
use Mail;
use DB;


class AjaxControllerFatture extends Controller
	{

	public function edit_row_fattura(Request $request){		
		$id_riga = $request->input('id_riga');
		/*
		$infoditta=DB::table('servizi as s')
		->join('servizi_ditte as d','s.id','d.id_servizio')
		->select('s.descrizione','d.id_servizio','d.importo_ditta','d.aliquota','d.importo_lavoratore')
		->where('d.id_ditta', '=', $id_ditta)
		->get();
        return json_encode($infoditta);
		*/
		echo "id_riga $id_riga";
	}	



}
