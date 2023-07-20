<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\user;
use App\Models\mezzi;
use App\Models\parco_carta_carburante;
use App\Models\parco_marca_mezzo;
use App\Models\parco_modello_mezzo;
use DB;

class ControllerParco extends Controller
{
	public function __construct() {
		$this->middleware('auth')->except(['index']);
	}
	
	
	public function scheda_mezzo($id_mezzo=0) {
		$tipomezzo=$this->tipomezzi();

		$marche=parco_marca_mezzo::select('id','marca')
		->orderBy('marca')
		->get();		

		$carta_carburante=parco_carta_carburante::select('id','id_carta')
		->orderBy('id_carta')
		->get();
		
		$data=array("carte_c"=>$carta_carburante,"tipomezzo"=>$tipomezzo,"marche"=>$marche);
		
		
		return view('all_views/parco/scheda_mezzo')->with($data);
		
	}
	
	public function tipomezzi() {
		$mezzi=array();
		$mezzi[0]['id']=1;$mezzi[0]['descrizione']="Carro funebre";
		$mezzi[1]['id']=2;$mezzi[1]['descrizione']="Furgone";
		$mezzi[2]['id']=3;$mezzi[2]['descrizione']="Auto";
		$mezzi[3]['id']=4;$mezzi[3]['descrizione']="Furgone attrezzato";
		
		return $mezzi;
	}
	
}

