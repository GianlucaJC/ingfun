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

use DB;

class ControllerRifornimenti extends Controller
{
	public function rifornimenti(Request $request) {

		$mezzi=mezzi::select('id','tipologia','marca','modello','targa')
		->orderBy('marca')
		->orderBy('targa')
		->get();
		$targhe=array();
		foreach($mezzi as $mezzo) {
			$targhe[$mezzo->targa]=$mezzo->marca." - ".$mezzo->modello." - ".$mezzo->targa;
		}			
		$rifornimenti=DB::table('rifornimenti as r')
		->select("r.*",DB::raw("DATE_FORMAT(r.data,'%d-%m-%Y') as data_it"), 'c.nominativo','a.descrizione_appalto')
		->join("candidatis as c","r.id_user","c.id")
		->join("appalti as a","r.id_appalto","a.id")
		->where('r.dele', "=","0")
		->orderBy('r.id','desc')
		->get();
		return view('all_views/rifornimenti/rifornimenti')->with('rifornimenti', $rifornimenti)->with('targhe',$targhe);
		
	}
}
