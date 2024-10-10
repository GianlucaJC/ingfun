<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\candidati;
use App\Models\rimborsi;
use App\Models\presenze;
use App\Models\rimborsi_tipologie;
use DB;
use Image;
use Mail;

class ControllerRimborsi extends Controller
{
	/*
	public function __construct() {
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
	}
	*/

	public function elenco_rimborsi() {
		$elenco=rimborsi_tipologie::select('id','descrizione','obbligo_foto',DB::raw("CONCAT(id,'|',obbligo_foto) AS id_ref"))
		->orderBy('descrizione')
		->get();		
		echo json_encode($elenco);
	}

	public function load_rimborso($id_rimborso=0) {
		$elenco=rimborsi::select('id_user','id_rimborso','dataora','importo','stato')
		->when($id_rimborso!="0", function ($elenco) use ($id_rimborso) {
			return $elenco->where('id', "=",$id_rimborso);
		})
		->get();		
		
		echo json_encode($elenco);
	}

	public function send_rimborso(Request $request) {
		$id_lav=Auth::user()->id;
		$id_rimborso = $request->input('id_rimborso');
		$obbligo_foto = $request->input('obbligo_foto');
		$filename=null;
		if ($obbligo_foto=="1") {
			$filename=uniqid().".jpg";
		
			//$filename = $request->header('filename');
			$file = $_FILES['file'];
			$temp = $file['tmp_name'];
			
			$target_file = tempnam("dist/upload/rimborsi", "photo");
		
			if (move_uploaded_file($temp, $target_file)) {
				$path="dist/upload/rimborsi/";
				$small = "dist/upload/rimborsi/thumbnail/small/";
				$medium = "dist/upload/rimborsi/thumbnail/medium/";
				$result = rename($target_file, $path . $filename);  
				copy($path.$filename, $small.$filename);
			 	copy($path.$filename, $medium.$filename);
				$this->createThumbnail($small.$filename, 150, 93);
				$this->createThumbnail($medium.$filename, 300, 185);
			} else {
				$risp['header']="KO";
				$risp['temp']=$temp;
				$risp['target_file']=$target_file;
				$risp['message']="Attenzione - Dati non riversati per errore riscontrato";
				echo json_encode($risp);
				exit;
			}		
		}	
		
		$tipo_rimborso = $request->input('tipo_rimborso');
		$importo = $request->input('importo');
		$data_ora = $request->input('data_ora');
		if ($id_rimborso!="0" && $id_rimborso!=0) 
			$rimborsi = rimborsi::find($id_rimborso);
		else
			$rimborsi = new rimborsi;
		$rimborsi->id_user = $id_lav;
		$rimborsi->id_rimborso=$tipo_rimborso;
		$rimborsi->dataora=$data_ora;
		$rimborsi->importo=$importo;
		$rimborsi->filename=$filename;
		if ($id_rimborso!="0" && $id_rimborso!=0) $rimborsi->stato=0;
		$rimborsi->save();
		$tipo_mail="edit";
		if ($id_rimborso=="0" || $id_rimborso==0) {
			$tipo_mail="new";
			$id_rimborso=$rimborsi->id; //in caso di new viene generato
		}

		//invio mail ai coordinatori
		$info=candidati::select('candidatis.email_az')
			->join('model_has_roles as m','candidatis.id_user','m.model_id')
			->where('m.role_id',"=",4)
			->where("m.role_id","<>", null)
			->first();
		$risp_send=array();
		if($info) {
			$email=$info->email_az;
			$risp_send=$this->send_mail($email,$tipo_mail,$id_rimborso,"");
		}	

		$risp['header']="OK";
		$risp['message']="File e dati riversati sul server";
		$risp['risp_send']=$risp_send;
		echo json_encode($risp);

   }

   public function createThumbnail($path, $width, $height)
   {
	   $img = Image::make($path)->resize($width, $height, function ($constraint) {
		   $constraint->aspectRatio();
	   });
	   $img->save($path);
   }

   public function rimborsi_coord(Request $request) {
		$elenco_rimborsi=rimborsi::select('u.name','rimborsi.id','rimborsi.id_rimborso','r.descrizione','rimborsi.dataora','rimborsi.importo','rimborsi.stato','rimborsi.filename')
		->join('rimborsi_tipologie as r','rimborsi.id_rimborso','r.id')
		->join('users as u','rimborsi.id_user','u.id')
		->groupBy('rimborsi.id')
		->orderBy('id','desc')
		->get();
		return view('all_views/rimborsi/rimborsi_coord')->with('elenco_rimborsi',$elenco_rimborsi);			
   }
   
 
   public function save_rettifica(Request $request){
	$id_coord=Auth::user()->id;
	$id_ref = $request->input('id_ref');
	$testo_rettifica = $request->input('testo_rettifica');
	

	
	$info=rimborsi::select('c.email')
		->join('candidatis as c','c.id_user','rimborsi.id_user')
		->where('rimborsi.id',"=", $id_ref)
		->first();
	
	$risp_send=array();	
	if($info) {
		$email=$info->email;
		$risp_send=$this->send_mail($email,"R",$id_ref,$testo_rettifica);
	}	
	rimborsi::where('id','=',$id_ref)->update(['testo_rettifica' => $testo_rettifica, 'stato'=>3]);
	
	$risp['header']="OK";
	$risp['risp_send']=$risp_send;
	echo json_encode($risp);

   }

   public function risposta_rimborso(Request $request){
	$id_coord=Auth::user()->id;
	$value = $request->input('value');
	$id_ref = $request->input('id_ref');
	$importo = $request->input('importo');
	$dataora = $request->input('dataora');

	$risp=array();
	$v=0;
	if ($value=="A") $v=1;
	if ($value=="S") $v=2;
	$risp_send=array();
	
	if ($value=="SR") {
		//sollecito rettifica
		$info=rimborsi::select('c.email')
			->join('candidatis as c','c.id_user','rimborsi.id_user')
			->where('rimborsi.id',"=", $id_ref)
			->first();
		if($info) {
			$email=$info->email;
			$risp_send=$this->send_mail($email,$value,$id_ref,"");
		}
		$risp['header']="OK";
		$risp['risp_send']=$risp_send;
		echo json_encode($risp);		
		exit;
	}

	if ($value=="A" || $value=="S")	{
		//In caso di accettazione del rimborso, creo una nuovo record (con id_servizio statico 5006-rimborsi vari) nel registro presenze
		if ($value=="A") {
			$info=rimborsi::select('c.id')
			->join('candidatis as c','c.id_user','rimborsi.id_user')
			->where('rimborsi.id', $id_ref)
			->first();
			if($info) {
				$periodo=substr($dataora,0,7);
				$data_r=substr($dataora,0,10);
				$id_lav=$info->id;

				//se nel registro esiste già un rimborso per il periodo e per il lavoratore...
				$info=presenze::select('id','importo')
				->where('id_servizio', '=',5006)
				->where('data','=',$data_r)
				->where('id_lav','=',$id_lav)
				->first();
				
				if($info) {
					$pre_importo=$info->importo;
					$importo_tot=floatval($pre_importo)+floatval($importo);
					$id_pre=$info->id;
					presenze::where('id','=',$id_pre)->update(['importo' => $importo_tot]);
				}
				else {	
					//..altrimenti crea un nuovo rimborso nel registro
					$presenze = new presenze;
					$presenze->id_lav = $id_lav;
					$presenze->id_servizio = 5006; //statico: rimborsi vari (tabella servizi_custom)
					$presenze->importo=floatval($importo);
					$presenze->periodo=$periodo;
					$presenze->data=$data_r;
					$presenze->save();
				}
			}
		}

		//invio mail di accettazione/diniego rimborso
		rimborsi::where('id', $id_ref)->update(['stato' => $v,'sign_coord'=>$id_coord]);
		$info=rimborsi::select('c.email')
			->join('candidatis as c','c.id_user','rimborsi.id_user')
			->where('rimborsi.id',"=", $id_ref)
			->first();
		if($info) {
			$email=$info->email;
			$risp_send=$this->send_mail($email,$value,$id_ref,"");
		}	
	}	


	$risp['header']="OK";
	$risp['risp_send']=$risp_send;
	echo json_encode($risp);
   }

   public function send_mail($email,$tipo,$id_richiesta,$testo_rettifica) {

	$titolo="";$stato_r="";
	//tipo new - nuovo rimborso - gestito nella view della mail
	//tipo R - rettifica gestito direttamente nella view della mail
	if ($tipo=="A") {
		$titolo="Richiesta di rimborso accettata";
		$stato_r="accettata";
	}
	if ($tipo=="new") {
		$titolo="Nuova richiesta di rimborso";
	}	
	if ($tipo=="edit") {
		$titolo="Modifica richiesta di rimborso dopo rettifica";
	}	
	if ($tipo=="R") {
		$titolo="Richiesta di rettifica rimborso";
	}
	if ($tipo=="S"){
		$titolo="Richiesta di rimborso respinta";
		$stato_r="respinta";
	}
	if ($tipo=="SR"){
		$titolo="Sollecito rettifica";
	}	
	
	$body_msg="Caro lavoratore,\nla presente per informarti che la tua richiesta (ID: $id_richiesta) di rimborso è stata $stato_r";
	$id_mask=base64_encode("xzx".$id_richiesta);
	try {
		$data["tipo"] = $tipo;
		$data["title"] = $titolo;
		$data["id_richiesta"] = $id_richiesta;
		$data["id_mask"] = $id_mask;
		$data["testo_rettifica"] = $testo_rettifica;
		$data["body_msg"] = $body_msg;
		

		Mail::send('emails.rimborsi_notif', $data, function($message)use($data,$email) {
			$message->to($email, $email)
			->subject("Risposta alla richiesta di rimborso");
		});


		
		$status['status']="OK";
		$status['message']="Mail inviata con successo";
		return $status;
		
	} catch (Throwable $e) {
		$status['status']="KO";
		$status['message']="Errore occorso durante l'invio! $e";
		return $status;
	}		
}   

}
