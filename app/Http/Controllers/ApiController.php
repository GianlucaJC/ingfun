<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\appalti;
use App\Models\ditte;
use App\Models\lavoratoriapp;





class ApiController extends Controller
{
	/*
	public function __construct() {
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
	}
	*/
	public function check_log($request) {
		$utente=$request->input("utente");
		$pw=$request->input("pw");
		$check=user::select('users.password','c.id','c.id as id_cand','users.id as userid','users.push_id')
		->join("candidatis as c","users.id","=","c.id_user")
		->where('users.email',"=",$utente);
		$count=$check->count();
		
		$resp=array();
		if ($count>0) {
			$c=$check->get();
			$hash=$c[0]->password;
			if (password_verify($pw, $hash)) {
				$resp['esito']="OK";
				$resp['id_user']=$c[0]->id;
				$resp['id_cand']=$c[0]->id_cand;
				
				//registrazione dispositivo per push notification:
				//in caso di registrazione non avvenuta con successo o 
				//push_id non valido e quindi mancata ricezione del push, rendere a mano nullo il campo push_id nella tabella users
				if ($request->has("pushid") && $c[0]->push_id==null) {
					$pushid=$request->input("pushid");
					if ($pushid!="?") {
						user::where('id', $c[0]->userid)->update(['push_id' => $pushid]);
					}
				}
			} else {
			   $resp['esito']="KO";
			}
		}	
		else {
			$resp['esito']="KO";
		}		
		return $resp;
	}
	
	public function login(Request $request) {
		$login=array();
		$risp=$this->check_log($request);
		$login['header']=$risp;
		echo json_encode($login);
	}
	
	public function send_foto(Request $request) {
		$login=array();
		
		 if ((isset($_SERVER["HTTP_FILENAME"])) && (isset($_SERVER["CONTENT_TYPE"])) && (isset($_SERVER["CONTENT_LENGTH"]))) {
			 $login['header']="OK1";
			 echo json_encode($login);
			 exit;
		}
		
		$file = $request->file('filename');
		
		echo json_encode($file);exit;
	
/*
      $file = $request->file('filename');
   
      //Display File Name
      echo 'File Name: '.$file->getClientOriginalName();
      echo '<br>';
   
      //Display File Extension
      echo 'File Extension: '.$file->getClientOriginalExtension();
      echo '<br>';
   
      //Display File Real Path
      echo 'File Real Path: '.$file->getRealPath();
      echo '<br>';
   
      //Display File Size
      echo 'File Size: '.$file->getSize();
      echo '<br>';
   
      //Display File Mime Type
      echo 'File Mime Type: '.$file->getMimeType();
   
      //Move Uploaded File
	  /*
      $destinationPath = 'uploads';
      $file->move($destinationPath,$file->getClientOriginalName());
	  */
   }



	
	public function countappalti(Request $request) {

		$check=$this->check_log($request); 
		if ($check['esito']=="KO") {
			$risp['header']=$check;
			 echo json_encode($risp);
			 exit;
		} 
		$id_lav_ref=$check['id_user'];
		/*
		$count=appalti::select('appalti.id','appalti.dele','appalti.descrizione_appalto','appalti.data_ref','appalti.id_ditta','d.denominazione')
		->join('ditte as d', 'd.id','=','appalti.id_ditta')
		->join('lavoratoriapp as l','l.id_ditta_ref','d.id')
		->where('appalti.dele', "=","0")
		->where('l.id_lav_ref',"=",$id_lav_ref)
		->groupBy('appalti.id')
		->orderByDesc('appalti.id')
		->count();
		*/
		
		$count=appalti::select('appalti.id')
		->join('lavoratoriapp as l','appalti.id','l.id_appalto')
		->where('appalti.dele', "=","0")
		->where('l.status', "=","0")
		->where('l.id_lav_ref',"=",$id_lav_ref)
		->count();

		$storici_si=appalti::select('appalti.id')
		->join('lavoratoriapp as l','appalti.id','l.id_appalto')
		->where('appalti.dele', "=","0")
		->where('l.status', "=","1")
		->where('l.id_lav_ref',"=",$id_lav_ref)
		->count();

		$storici_no=appalti::select('appalti.id')
		->join('lavoratoriapp as l','appalti.id','l.id_appalto')
		->where('appalti.dele', "=","0")
		->where('l.status', "=","2")
		->where('l.id_lav_ref',"=",$id_lav_ref)
		->count();
			
		$risp['header']=$check;
		$risp['count']=$count;
		$risp['storici_si']=$storici_si;
		$risp['storici_no']=$storici_no;

		
		echo json_encode($risp);
		
	}	

	public function infoappalti(Request $request) {

		$check=$this->check_log($request); 
		if ($check['esito']=="KO") {
			$risp['header']=$check;
			 echo json_encode($risp);
			 exit;
		} 
		$id_lav_ref=$check['id_user'];

		$allinfo=appalti::select('appalti.*')
		->join('lavoratoriapp as l','appalti.id','l.id_appalto')
		->where('appalti.dele', "=","0")
		->where('l.status', "=","0")
		->where('l.id_lav_ref',"=",$id_lav_ref)
		->get();
		
		$info=array();$sc=0;
		foreach($allinfo as $record) {			
			$id_ditta=$record->id_ditta;
			$ditta=ditte::select('denominazione','cap','comune','provincia')
			->where('id', "=",$id_ditta)
			->get()
			->first();
			$info_ditta="";
			if ($ditta->denominazione) $info_ditta.=$ditta->denominazione;
			if ($ditta->cap) $info_ditta.=" - ".$ditta->cap;
			if ($ditta->comune) $info_ditta.=" - ".$ditta->comune;
			if ($ditta->provincia) $info_ditta.=" - ".$ditta->provincia;
			$info[$sc]['ditta']=$info_ditta;
			$info[$sc]['descrizione_appalto']=$record->descrizione_appalto;
			$d=$record->data_ref;
			$date=date_create($d);
			$data_ref=date_format($date,"d-m-Y");
			$info[$sc]['data_ref']=$data_ref;
			$info[$sc]['note']=$record->note;
			
			//lavoratori presenti nell'appalto
			$id_appalto=$record->id;
			$lavoratori=lavoratoriapp::select('c.nominativo')
			->join('candidatis as c','lavoratoriapp.id_lav_ref','c.id')
			->where('lavoratoriapp.id_appalto', "=",$id_appalto)
			->get();
			$lav="";
			foreach($lavoratori as $lavoratore) {
				if (strlen($lav)!=0) $lav.=", ";
				$lav.=$lavoratore->nominativo;
			}
			$info[$sc]['lavoratori']=$lav;
			
			$info[$sc]['id_appalto']=$id_appalto;
		
			
			
			$sc++;
		}
			
		$risp['header']=$check;
		$risp['info']=$info;

		
		echo json_encode($risp);
		
	}
	
	public function risposta_user(Request $request) {
		$check=$this->check_log($request); 
		if ($check['esito']=="KO") {
			$risp['header']=$check;
			 echo json_encode($risp);
			 exit;
		} 
		$id_lav_ref=$check['id_user'];
		$id_appalto=$request->input("id_appalto");
		$sn=$request->input("sn");
		$status=0;
		if ($sn=="S") $status=1;
		if ($sn=="N") $status=2;
		lavoratoriapp::where('id_appalto', $id_appalto)
		->where('id_lav_ref', $id_lav_ref)
		->update(['status' => $status]);
		$risp['header']=$check;
		$risp['info']="Set risposta: $sn";
		echo json_encode($risp);	
	}
  

}
