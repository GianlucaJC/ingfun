<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\servizi;
use App\Models\serviziapp;
use App\Models\appalti;
use App\Models\ditte;
use App\Models\lavoratoriapp;
use App\Models\candidati;
use App\Models\user;
use App\Models\mezzi;
use App\Models\rifornimenti;
use App\Models\parco_scheda_mezzo;

use DB;

class ControllerRifornimenti extends Controller
{
	public function __construct() {
		//$this->middleware('auth')->except(['index']);
	}
	
	
	public function rifornimenti($id_rif=0) {

		$dele_contr=request()->input("dele_contr");
		$restore_contr=request()->input("restore_contr");
		$view_dele=request()->input("view_dele");
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;

		$mezzi=parco_scheda_mezzo::from('parco_scheda_mezzo as sm')
		->select('sm.id','mm.marca','mom.modello','sm.targa')
		->join('parco_marca_mezzo as mm','sm.marca','mm.id')
		->join('parco_modello_mezzo as mom','sm.modello','mom.id')
		->orderBy('mm.marca')
		->orderBy('sm.targa')
		->groupBy('sm.id')
		->get();
		
		$lavoratori=candidati::from('candidatis as c')
		->join('societa as s','c.soc_ass','s.id')
		->select('c.id','c.nominativo','s.descrizione as societa')
		->get();
		$all_lav=array();
		foreach ($lavoratori as $lavoratore) {
			$all_lav[$lavoratore->id]['nominativo']=$lavoratore->nominativo;
			$all_lav[$lavoratore->id]['societa']=$lavoratore->societa;
		}
		
		
		$targhe=array();
		foreach($mezzi as $mezzo) {
			$targhe[$mezzo->targa]=$mezzo->marca." - ".$mezzo->modello." - ".$mezzo->targa;
		}			




		if (strlen($dele_contr)!=0) {
			rifornimenti::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			rifornimenti::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		
		$rifornimenti=DB::table('rifornimenti as r')
		->select("r.*",DB::raw("DATE_FORMAT(r.data,'%d-%m-%Y') as data_it"), 'c.nominativo','a.descrizione_appalto','a.responsabile_mezzo')
		->join("candidatis as c","r.id_user","c.id")
		->join("appalti as a","r.id_appalto","a.id")
		->when($id_rif!="0", function ($rifornimenti) use($id_rif) {
			return $rifornimenti->where('a.id', "=",$id_rif);
		})
		->when($view_dele=="0", function ($rifornimenti) {
			return $rifornimenti->where('r.dele', "=","0");
		})	
		->where("a.dele","=",0)
		->orderBy('r.id','desc')
		->get();
		return view('all_views/rifornimenti/rifornimenti')->with('rifornimenti', $rifornimenti)->with('targhe',$targhe)->with('view_dele',$view_dele)->with('all_lav',$all_lav);
		
	}
}
