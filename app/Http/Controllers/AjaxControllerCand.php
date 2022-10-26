<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use App\Models\italy_provincies;
use App\Models\italy_cities;
use App\Models\italy_cap;
use App\Models\candidati;
use App\Models\tipoc;
use App\Models\societa;
use App\Models\centri_costo;
use App\Models\area_impiego;
use App\Models\mansione;
use App\Models\ccnl;
use App\Models\tipologia_contr;
use App\Models\tipo_doc;
use App\Models\voci_doc;
use App\Models\ref_doc;
use App\Models\story_all;
use DB;


class AjaxControllerCand extends Controller
{

	public function storia_campo() {
		$id_cand=$_POST['id_cand'];
		$id_campo=$_POST['id_campo'];
		
		$story=story_all::select('value','created_at')
		->where("id_campo","=", $id_campo)
		->where("id_cand","=", $id_cand)
		->groupBy("value")
		->orderByDesc("created_at")
		->get();
		//->groupBy("value",DB::raw('Date(created_at)'))
		return json_encode($story);
	}

	public function azzera_notif() {
		$up=candidati::where("notif_contr_web","<>", null)
		->update(['notif_contr_web' => 1]);
		$status['status']="OK";
		$status['message']="Notifica eliminata con successo!";
		return json_encode($status);
	}
	
	public function update_doc() {
		$filename=$_POST['filename'];
		$tipo_doc=$_POST['tipo_doc'];
		$sotto_tipo_doc=$_POST['sotto_tipo_doc'];
		$scadenza=$_POST['scadenza'];
		$id_cand=$_POST['id_cand'];
		$ref_doc= new ref_doc;
		$ref_doc->id_cand=$id_cand;
		$ref_doc->id_tipo_doc=$tipo_doc;
		$ref_doc->id_sotto_tipo=$sotto_tipo_doc;
		if (strlen($scadenza)!=0) $ref_doc->scadenza=$scadenza;
		$ref_doc->nomefile=$filename;
		$ref_doc->save();
		$status['status']="OK";
		$status['message']="Dati inseriti con successo!";
		return json_encode($status);
	}
	
	public function sottotipo(){
		$tipodoc=$_POST['tipodoc'];
		$elenco = voci_doc::where('dele','=',0)
		->where('id_corso',"=",$tipodoc)
		->orderBy('descrizione')->get();
        return json_encode($elenco);
	}

	public function refresh_sotto_tipo_doc(){
		$tipo_doc=$_POST['tipo_doc'];
		$sotto_tipo_doc = voci_doc::where('dele','=',0)
		->where('id_corso',"=",$tipo_doc)
		->orderBy('descrizione')->get();
        return json_encode($sotto_tipo_doc);
	}

	public function refresh_tipo_doc(){
		$tipo_doc = tipo_doc::where('dele','=',0)->orderBy('descrizione')->get();
        return json_encode($tipo_doc);
	}
	public function refresh_tipologia_contr(){
		$tipologia_contr = tipologia_contr::where('dele','=',0)->orderBy('descrizione')->get();
        return json_encode($tipologia_contr);
	}
	public function refresh_ccnl(){
		$ccnl = ccnl::where('dele','=',0)->orderBy('descrizione')->get();
        return json_encode($ccnl);
	}
	public function refresh_mansione(){
		$mansione = mansione::where('dele','=',0)->orderBy('descrizione')->get();
        return json_encode($mansione);
	}
	public function refresh_area(){
		$area_impiego = area_impiego::where('dele','=',0)->orderBy('descrizione')->get();
        return json_encode($area_impiego);
	}
	public function refresh_costo(){
		$centri_costo = centri_costo::where('dele','=',0)->orderBy('descrizione')->get();
        return json_encode($centri_costo);
	}
	public function refresh_tipoc(){
		$tipoc = tipoc::where('dele','=',0)->orderBy('descrizione')->get();
        return json_encode($tipoc);
	}
	public function refresh_soc(){
		$societa = societa::where('dele','=',0)->orderBy('descrizione')->get();
        return json_encode($societa);
	}
	public function remove_doc(){
		
		$doc_id=$_POST['doc_id'];
		$id_cand=$_POST['id_cand'];
		ref_doc::where('nomefile',"=",$doc_id)->delete();

		
		try {
			$path = base_path();
			/*
			candidati::where('id', $id_cand)->update(['file_curr' => null]);			
			
			$fx_dele=$path."/public/allegati/curr/".$file_curr;
			unlink($fx_dele);
			*/
			$fx_dele=$path."/public/allegati/doc/$id_cand/".$doc_id;
			@unlink($fx_dele);
			echo json_encode([
				'status' => 'OK',
				'message' => "File eliminato"
			]);
			

			
		} catch (RuntimeException $e) {
			// Something went wrong, send the err message as JSON
			http_response_code(400);

			echo json_encode([
				'status' => 'KO',
				'message' => $e->getMessage()
			]);
		}

	}

	public function dele_curr(){
		
		$file_curr=$_POST['file_curr'];
		$id_cand=$_POST['id_cand'];

		try {
			
			candidati::where('id', $id_cand)->update(['file_curr' => null]);			
			$path = base_path();
			$fx_dele=$path."/public/allegati/curr/".$file_curr;
			unlink($fx_dele);
			
			echo json_encode([
				'status' => 'OK',
				'message' => "File eliminato"
			]);
			

			
		} catch (RuntimeException $e) {
			// Something went wrong, send the err message as JSON
			http_response_code(400);

			echo json_encode([
				'status' => 'error',
				'message' => $e->getMessage()
			]);
		}

	}


	public function lista_province(Request $request){

		$id_regione = $request->input('id_regione');;
        $province = italy_provincies::where('id_regione', '=', $id_regione)->get();
        return json_encode($province);

	}

	public function lista_comuni(Request $request){
		$comuni=array();
		
		$sigla = $request->input('sigla');
		$comune_search = $request->input('comune_search');
		if ($comune_search=="0")
			$comuni = italy_cities::where('provincia', '=', $sigla)->orderBy('comune')->get();
		else {
			$comuni = italy_cities::where('comune', 'like', "%".$comune_search."%")->orderBy('comune')->get();
		}	
	
		
        return json_encode($comuni);

	}

	public function lista_cap(Request $request){
		
		
		$istat = $request->input('istat');
        $cap = italy_cap::where('istat', '=', $istat)->skip(0)->take(1)->orderBy('cap')->get();
		
        return json_encode($cap);

	}
	

}
