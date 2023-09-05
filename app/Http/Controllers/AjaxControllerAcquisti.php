<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\prod_categorie;
use App\Models\prod_sottocategorie;
use App\Models\prod_prodotti;
use App\Models\fornitori;

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

	public function refresh_prodotti(Request $request){		
		
		$prodotti=prod_prodotti::from('prod_prodotti as p')
		->select('p.*')
		->orderBy('descrizione')
		->get();
        return json_encode($prodotti);
	}

	public function refresh_forn(Request $request){		
		
		$fornitori=fornitori::from('fornitori as f')
		->select('f.*')
		->orderBy('ragione_sociale')
		->get();
        return json_encode($fornitori);
	}

}
