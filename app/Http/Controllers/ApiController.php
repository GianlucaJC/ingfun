<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\appalti;
use App\Models\ditte;
use App\Models\lavoratoriapp;
use App\Models\mezzi;
use App\Models\rifornimenti;
use DB;



class ApiController extends Controller
{
	/*
	public function __construct() {
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
	}
	*/
	public function check_log($request) {
		if ($request->hasHeader('utente')) {
			$utente=$request->header("utente");
			$pw=$request->header("pw");
		} else {
			$utente=$request->input("utente");
			$pw=$request->input("pw");
		}
		$check=user::select('users.password','c.id','c.id as id_cand','users.id as userid','users.push_id')
		->join("candidatis as c","users.id","=","c.id_user")
		->where('users.email',"=",$utente);
		$count=$check->count();
		
		$mezzi=mezzi::select('id','tipologia','marca','modello','targa')
		->orderBy('marca')
		->orderBy('modello')
		->get();
		
		$resp=array();
		if ($count>0) {
			$c=$check->get();
			$hash=$c[0]->password;
			if (password_verify($pw, $hash)) {
				$resp['esito']="OK";
				$resp['id_user']=$c[0]->id;
				$resp['id_cand']=$c[0]->id_cand;
				$resp['mezzi']=$mezzi;
				
				//registrazione dispositivo per push notification:
				 //&& $c[0]->push_id==null
				if ($request->has("pushid")) {
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
		
		$check=$this->check_log($request); 
		if ($check['esito']=="KO") {
			$risp['header']=$check;
			 echo json_encode($risp);
			 exit;
		} 
		$id_lav_ref=$check['id_user'];
		$id_cand=$check['id_cand'];


		$risp=array();
		$login=array();
		$login['header']="OK";
		/* PUT data comes in on the stdin stream */
		$putdata = fopen("php://input", "r");
		$filename = $request->header('filename');
		if ($putdata) {
			/* Open a file for writing */
			$tmpfname = tempnam("dist/upload", "photo");
			$fp = fopen($tmpfname, "w");
			if ($fp) {
				
				/* Read the data 1 KB at a time and write to the file */
				while ($data = fread($putdata, 1024)) {
					fwrite($fp, $data);
				}
				/* Close the streams */
				fclose($fp);
				fclose($putdata);
				
				$result = rename($tmpfname, "dist/upload/" . $filename);  
				$idappalto = $request->header('idappalto');
				
				$importo = $request->header('importo');
				$km = $request->header('km');
				$note = $request->header('note');
				$mezzo = $request->header('mezzo');
				$info=explode("-",$mezzo);
				$targa=trim($info[1]);
				$data=date("Y-m-d");

				$rifornimenti = new rifornimenti;
				//riverso i dati nel DB-->Attenzione agli underscore!!!!
				$rifornimenti->id_user = $id_lav_ref;
				$rifornimenti->id_appalto=$idappalto;
				$rifornimenti->filename=$filename;
				$rifornimenti->importo=$importo;
				$rifornimenti->km=$km;
				$rifornimenti->note=$note;
				$rifornimenti->targa=$targa;
				$rifornimenti->data=$data;
				$rifornimenti->save();
				$risp['header']="OK";
				$risp['message']="File e dati riversati sul server";
				echo json_encode($risp);
				exit;
			}
		}
		$risp['header']="KO";
		$risp['message']="Dati non riversati";
		echo json_encode($risp);
		
   }


	public function lavori(Request $request) {
		$check=$this->check_log($request); 
		if ($check['esito']=="KO") {
			$risp['header']=$check;
			 echo json_encode($risp);
			 exit;
		} 
		$id_lav_ref=$check['id_user'];


		$lavori=appalti::select(DB::raw("DATE_FORMAT(appalti.data_ref,'%d-%m-%Y') as data_ref"),'appalti.id','appalti.descrizione_appalto','appalti.orario_ref','appalti.targa','l.status')
		->join('lavoratoriapp as l','appalti.id','l.id_appalto')
		->where('appalti.dele', "=","0")
		->where('l.id_lav_ref',"=",$id_lav_ref)
		->orderBy('appalti.id','desc')
		->get();

			
		$risp['header']=$check;
		$risp['lavori']=$lavori;
		echo json_encode($risp);
		
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
		$wh=99;$id_ref_a=0;
		if ($request->has("from")) {
			$from=$request->input("from");
			if ($from=="menu") $wh=0;
			if ($from=="storico") {
				$wh=1;
				$id_ref_a=$request->input("id_ref_a");
			}
		}	

		if ($wh==99) {
			$risp['header']=$check;
			$risp['info']=array();
			echo json_encode($risp);
			exit;
		}

		//tutti gli appalti appena assegnati ad un operatore
		if ($wh==0) {
			$allinfo=appalti::select('appalti.*','l.status')
			->join('lavoratoriapp as l','appalti.id','l.id_appalto')
			->where('appalti.dele', "=",0)
			->where('l.status', "=",0)
			->where('l.id_lav_ref',"=",$id_lav_ref)
			->get();
		}
		
		//chiatama da storico appalti su un appalto
		if ($wh==1) {
			$allinfo=appalti::select('appalti.*','l.status')
			->join('lavoratoriapp as l','appalti.id','l.id_appalto')
			->where('appalti.id', "=",$id_ref_a)
			->groupBy('appalti.id')
			->get();
		}
		
		
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
			$info[$sc]['status']=$record->status;
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
