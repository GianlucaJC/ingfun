<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\prod_categorie;
use App\Models\prod_sottocategorie;

use Mail;
use DB;


class AjaxControllerAcquisti extends Controller
	{


	public function elenco_sottocategorie(Request $request){		
		$id_categoria = $request->input('id_categoria');
		$sottoc=DB::table('prod_sottocategorie as sc')
		->select('sc.id as id_sc','sc.descrizione as descr_sc')
		->where('sc.id_categoria', '=', $id_categoria)
		->orderBy('sc.descrizione')
		->get();
        return json_encode($sottoc);
	}	

}
