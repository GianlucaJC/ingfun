<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\user;

use App\Models\appalti;
use App\Models\parco_scheda_mezzo;
use App\Models\sinistri;
use DB;

class ControllerSinistri extends Controller
{
	public function __construct() {
		//$this->middleware('auth')->except(['index']);
	}
	
	public function sinistri($id_appalto=0) {
		$request=request();
		$btn_save=$request->input("btn_save");
		
		if ($btn_save=="save") {
			$sin = new sinistri;
			$sin->id_mezzo=$request->input("mezzo_coinvolto");
			$sin->dataora=$request->input("dataora");
			$sin->mezzo_marciante=$request->input("mezzo_marciante");
			$sin->citta=$request->input("citta");
			$sin->provincia=$request->input("provincia");
			$sin->indirizzo=$request->input("indirizzo");
			$sin->descrizione=$request->input("descrizione");
			$sin->save();
		}		
		
		
		$allinfo=appalti::select('appalti.*')
		->join('lavoratoriapp as l','appalti.id','l.id_appalto')
		->where('appalti.id', "=",$id_appalto)
		->groupBy('appalti.id')
		->get();
		
		$mezzi=parco_scheda_mezzo::from('parco_scheda_mezzo as m')
		->select('m.id','m.targa')
		->where('m.dele','=',0)
		->orderBy('targa')
		->get();

		
		$request=request();
		return view('all_views/sinistri/sinistri',compact('id_appalto','allinfo','mezzi'));		
	}
	
	
}

