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
use App\Models\contatti;
use App\Models\fatture;
use App\Models\preventivi;

use Mail;
use setasign\Fpdi\Fpdi;
use Spatie\PdfToText\Pdf;
use DB;


class AjaxControllerCand extends Controller
	{
		public function check_url(Request $request) {
			$url=$request->input('url');
			if (file_exists($url)==true) 
				echo "OK";
			else
				echo "KO";
			//return json_encode($status);			
		}
		


		public function azione(Request $request) {
			$id_cand=$request->input('id_cand');
			$tipo=$request->input('tipo');
			
			$candidati = candidati::find($id_cand);
			
			if ($tipo=="3") {
				$candidati->tipo_anagr = "ASS";
				$candidati->status_candidatura = 3;
			}	
			if ($tipo=="4") {
				$candidati->tipo_anagr = "DIM";
				$candidati->status_candidatura = 4;
			}	
			if ($tipo=="5") {
				$candidati->tipo_anagr = "LIC";
				$candidati->status_candidatura = 5;
			}	
			if ($tipo=="6") {
				$candidati->tipo_anagr = "SCAD";
				$candidati->status_candidatura = 6;
			}					
			
			$candidati->save();
			$status['status']="OK";
			$status['message']="azione ok";
			return json_encode($status);		
		}
	
	public function send_mail(Request $request){

		$titolo = $request->input('titolo');
		$id_cand = $request->input('id_cand');
		$body_msg = $request->input('body_msg');
		
		$nome_file="";
		if ($request->has('nome_file')) $nome_file = $request->input('nome_file');
		$id_fattura="";
		if ($request->has('id_fattura')) $id_fattura = $request->input('id_fattura');
		$id_preventivo="";
		if ($request->has('id_preventivo')) $id_preventivo = $request->input('id_preventivo');
		$email = $request->input('email');


		try {

			$data["email"] = $email;
			$data["title"] = $titolo;
			$data["body"] = $body_msg;
			$files=array();
			if (strlen($nome_file)!=0) {
				$files = [
					public_path("allegati/doc/$id_cand/$nome_file"),
				];
			}
			if (strlen($id_fattura)!=0) {
				fatture::where('id', $id_fattura)
				  ->update(['status' => 2]);					
				$files = [
					public_path("allegati/fatture/".$id_fattura.".pdf"),
				];
			}
			if (strlen($id_preventivo)!=0) {
				preventivi::where('id', $id_preventivo)
				  ->update(['status' => 2]);					
				$files = [
					public_path("allegati/preventivi/".$id_preventivo.".pdf"),
				];
			}
			Mail::send('emails.notifdoc', $data, function($message)use($data, $files) {
				$message->to($data["email"], $data["email"])
				->subject($data["title"]);
				if (count($files)!=0) {
					foreach ($files as $file){
						$message->attach($file);
					}
				}
			});
			$status['status']="OK";
			$status['message']="Mail inviata con successo!";

		} catch (Throwable $e) {
			$status['status']="KO";
			$status['message']="Errore occorso durante l'invio! $e";
		}		
			
			
		
		return json_encode($status);
	}

	public function storia_campo() {
		$id_cand=$_POST['id_cand'];
		$id_campo=$_POST['id_campo'];
		
		$story=story_all::select('value','created_at')
		->where("id_campo","=", $id_campo)
		->where("id_cand","=", $id_cand)
		->orderByDesc("created_at")
		->get();
		
		$candidato = DB::table('candidatis as c')
		->select('c.nominativo','c.data_inizio','c.data_fine')
		->where("id","=", $id_cand)
		->get();		
	
		$risp['story']=$story;
		$risp['candidato']=$candidato;
		return json_encode($risp);
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
	
	public function load_contatti(){
		
		$contatti = contatti::where('dele','=',0)
		->orderBy('descrizione')->get();
        return json_encode($contatti);
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
