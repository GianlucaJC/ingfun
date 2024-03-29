<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\parco_marca_mezzo;
use App\Models\parco_modello_mezzo;
use App\Models\parco_carta_carburante;
use App\Models\parco_badge_cisterna;
use App\Models\parco_telepass;
use App\Models\parco_servizi_noleggio;
use App\Models\support_sinistri;
use App\Models\sinistri;
use Mail;
use DB;


class AjaxControllerParco extends Controller
	{
		
	function update_doc_sinistro(Request $request) {
		
		$filename=$request->input("filename");
		$id_sinistro=$request->input("id_sinistro");
		$tipo_allegato=$request->input("tipo_allegato");

		if ($tipo_allegato=="2")
			sinistri::where('id', $id_sinistro)
			->update(['file_cid' => $filename]);			
		else {
			$support = new support_sinistri;
			$support->filename=$filename;
			$support->id_sinistro=$id_sinistro;
			$support->save();
		}
		
		$risp=array();

		$risp['status']="OK";
		$risp['esito']="insert";
		echo json_encode($risp);		
	}		

	public function refresh_servizi_noleggio(Request $request){
		$marche=parco_servizi_noleggio::select('id','descrizione')
		->orderBy('descrizione')
		->get();
		return json_encode($marche);
	}
	public function refresh_carta(Request $request){
		$marche=parco_carta_carburante::select('id','id_carta')
		->orderBy('id_carta')
		->get();
		return json_encode($marche);
	}
	public function refresh_marca(Request $request){
		$marche=parco_marca_mezzo::select('id','marca')
		->orderBy('marca')
		->get();
		return json_encode($marche);
	}
	public function refresh_badge(Request $request){
		$badges=parco_badge_cisterna::select('id','id_badge')
		->orderBy('id_badge')
		->get();
		return json_encode($badges);
	}
	public function refresh_telepass(Request $request){
		$badges=parco_telepass::select('id','id_telepass')
		->orderBy('id_telepass')
		->get();
		return json_encode($badges);
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
