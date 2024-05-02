<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\appalti;
use App\Models\ditte;
use App\Models\candidati;
use App\Models\lavoratoriapp;
use App\Models\mezzi;
use App\Models\rifornimenti;
use App\Models\reperibilita;
use App\Models\parco_scheda_mezzo;
use App\Models\set_global;
use App\Models\support_sinistri;
use App\Models\sinistri;
use App\Models\contatti;
use OneSignal;

use DB;
use Image;
use Mail;

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
		
		/*
		$mezzi=mezzi::select('id','tipologia','marca','modello','targa')
		->orderBy('marca')
		->orderBy('modello')
		->get();
		*/
		
		
		$mezzi=parco_scheda_mezzo::from('parco_scheda_mezzo as sm')
		->select('sm.id','mm.marca','mom.modello','sm.targa')
		->join('parco_marca_mezzo as mm','sm.marca','mm.id')
		->join('parco_modello_mezzo as mom','sm.modello','mom.id')
		->orderBy('mm.marca')
		->orderBy('sm.targa')
		->groupBy('sm.id')
		->get();		
		
		$resp=array();
		if ($count>0) {
			$c=$check->get();
			$hash=$c[0]->password;
			if (password_verify($pw, $hash)) {
				$resp['esito']="OK";
				$resp['id_user']=$c[0]->id;
				$resp['id_cand']=$c[0]->id_cand;
				//id_cand e id_user sono identici! Non l'ho tolto...
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

	public function send_foto_inc(Request $request) {
		
		$check=$this->check_log($request); 
		if ($check['esito']=="KO") {
			$risp['header']=$check;
			 echo json_encode($risp);
			 exit;
		} 
		$id_lav_ref=$check['id_user'];
		


		$risp=array();
		$login=array();
		$login['header']="OK";
		/* PUT data comes in on the stdin stream */
		$putdata = fopen("php://input", "r");
		$filename = $request->header('filename');
		$idsinistro = $request->header('idsinistro');
		if ($putdata) {
			/* Open a file for writing */
			$tmpfname = tempnam("dist/upload/sinistri", "photo");
			$fp = fopen($tmpfname, "w");
			if ($fp) {
				
				/* Read the data 1 KB at a time and write to the file */
				while ($data = fread($putdata, 1024)) {
					fwrite($fp, $data);
				}
				/* Close the streams */
				fclose($fp);
				fclose($putdata);
				
				$path="dist/upload/sinistri/";
				$small = "dist/upload/sinistri/thumbnail/small/";
				$medium = "dist/upload/sinistri/thumbnail/medium/";
				$result = rename($tmpfname, $path . $filename);  
				copy($path.$filename, $small.$filename);
				copy($path.$filename, $medium.$filename);
				
				$this->createThumbnail($small.$filename, 150, 93);
				$this->createThumbnail($medium.$filename, 300, 185);
						
				$idappalto = $request->header('idappalto');
				$notificasin = $request->header('notificasin');

				
				$tiposend = $request->header('tiposend');
				if ($tiposend=="inc") {
					$support = new support_sinistri;
					//riverso i dati nel DB-->Attenzione alle variabili header con underscore!!!!
					$support->id_sinistro = $idsinistro;
					$support->filename=$filename;
					$support->save();
				}
				if ($tiposend=="cid") {
					$sinistri = sinistri::find($idsinistro);
					//riverso i dati nel DB-->Attenzione alle variabili header con underscore!!!!
					$sinistri->file_cid=$filename;
					$sinistri->save();					
				}
				if ($notificasin=="0") {
					$mails=contatti::select('mail')->get();
					foreach ($mails as $mail) {
						$email=$mail->mail;
						$this->send_m($email,$idappalto,$idsinistro);
					}				
				}

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
   
	public function send_m($email,$id_appalto,$id_sinistro){
		$titolo="";$body_msg="";
		$d=date("Y-m-d");
		$href="https://217.18.125.177/ingfun/public/sinistri/$id_appalto/1/$id_sinistro";
		$titolo="Notifica creazione sinistro da APP";
		$body_msg="Un nuovo sinistro è stato creato via APP.\nPer prenderne visione cliccare sul link $href";
		
		
		try {

			$data["email"] = $email;
			$data["title"] = $titolo;
			$data["body"] = $body_msg;


			Mail::send('emails.notifdoc', $data, function($message)use($data) {
				$message->to($data["email"], $data["email"])
				->subject($data["title"]);

			});
			$status['status']="OK";
			$status['message']="Mail inviata con successo!";

		} catch (Throwable $e) {
			$status['status']="KO";
			$status['message']="Errore occorso durante l'invio! $e";
		}		
			
			
		
		return json_encode($status);
	}	   

	
	public function send_foto(Request $request) {
		
		$check=$this->check_log($request); 
		if ($check['esito']=="KO") {
			$risp['header']=$check;
			 echo json_encode($risp);
			 exit;
		} 
		$id_lav_ref=$check['id_user'];
		


		$risp=array();
		$login=array();
		$login['header']="OK";
		/* PUT data comes in on the stdin stream */
		$putdata = fopen("php://input", "r");
		$filename = $request->header('filename');
		if ($putdata) {
			/* Open a file for writing */
			$tmpfname = tempnam("dist/upload/rifornimenti", "photo");
			$fp = fopen($tmpfname, "w");
			if ($fp) {
				
				/* Read the data 1 KB at a time and write to the file */
				while ($data = fread($putdata, 1024)) {
					fwrite($fp, $data);
				}
				/* Close the streams */
				fclose($fp);
				fclose($putdata);
				
				$path="dist/upload/rifornimenti/";
				$small = "dist/upload/rifornimenti/thumbnail/small/";
				$medium = "dist/upload/rifornimenti/thumbnail/medium/";
				$result = rename($tmpfname, $path . $filename);  
				copy($path.$filename, $small.$filename);
				copy($path.$filename, $medium.$filename);
				
				$this->createThumbnail($small.$filename, 150, 93);
				$this->createThumbnail($medium.$filename, 300, 185);
						
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
				
				
				//aggiornamenti per eventuale noleggio
				$info_id=parco_scheda_mezzo::select('id','km_alert_mail','notifica_alert_noleggio')
				->where('targa', "=",$targa)
				->where('proprieta','=',1)
				->get()->first();
				
				$id_mezzo=$info_id->id;
				if ($id_mezzo!=null && strlen($id_mezzo)!=0) {
					$psm=parco_scheda_mezzo::find($id_mezzo);
					$psm->km_noleggio_remote=$km;
					$psm->save();
					
					$notifica_alert_noleggio=$info_id->notifica_alert_noleggio;
					if ($notifica_alert_noleggio==NULL) {
						$km_alert_mail=$info_id->km_alert_mail;
						if ($km>=$km_alert_mail) {
							/*
								Notifica mail parco macchine per km alert superati durante il noleggio
							*/
							$this->send_mail_parco($id_mezzo,$targa,$km);
						}
					}
				}
				///
				
				
				echo json_encode($risp);
				exit;
			}
		}
		$risp['header']="KO";
		$risp['message']="Dati non riversati";
		echo json_encode($risp);
		
   }

	function send_mail_parco($id_mezzo,$targa,$km) {
		$status=array();
		
		$set_global=set_global::where('id', "=", 1)->get();
		$email=array();
		if (isset($set_global[0]['email_parco']))
			$email[]=$set_global[0]['email_parco'];		
		if (isset($set_global[0]['email_acquisti'])) {
			$email[]=$set_global[0]['email_acquisti'];
		}	
		if (count($email)>0) {
			$msg="Il mezzo targato <b>$targa</b> attualmento in noleggio ha superato la soglia prevista di $km Km";
			try {
				$data["email"] = $email;					
				$data["title"] = "Alert Noleggio";
				
				$data["body"]=$msg;
				

				Mail::send('emails.alert_noleggio', $data, function($message)use($data) {
					$message->to($data["email"], $data["email"])
					->subject($data["title"]);

				});
				
				parco_scheda_mezzo::where('id', $id_mezzo)->update(['notifica_alert_noleggio' => 1]);
		
				$status['status']="OK";
				$status['message']="Mail inviata con successo";
				
				
				
			} catch (Throwable $e) {
				$status['status']="KO";
				$status['message']="Errore occorso durante l'invio! $e";
			}
		} else {
			$status['status']="KO";
			$status['message']="Non risultato definite mail parco auto e ufficio acquisti";			
		}
		return $status;
	}


	public function createThumbnail($path, $width, $height)
	{
		$img = Image::make($path)->resize($width, $height, function ($constraint) {
			$constraint->aspectRatio();
		});
		$img->save($path);
	}

	public function lavori(Request $request) {
		$from="ALL";
		if (Auth::user()) {
			$id=Auth::user()->id;
			$from=$request->input('from');
			$candidati=candidati::select("id")
			->where('id_user', "=", $id)->get();
			
			if (isset($candidati[0]))
				$id_lav_ref=$candidati[0]['id'];
			else
				$id_lav_ref=0;
			$check=array();
		}
		else {
			$check=$this->check_log($request); 
			if ($check['esito']=="KO") {
				$risp['header']=$check;
				 echo json_encode($risp);
				 exit;
			} 
			$id_lav_ref=$check['id_user'];
		}
		
		if ($id_lav_ref==0) return array();		


		$lavori=appalti::select(DB::raw("DATE_FORMAT(appalti.data_ref,'%d-%m-%Y') as data_ref"),'appalti.id','appalti.descrizione_appalto','appalti.orario_ref','appalti.targa','l.status')
		->join('lavoratoriapp as l','appalti.id','l.id_appalto')
		->where('appalti.dele', "=","0")
		->when($from=="New", function ($lavori) {			
			return $lavori->where('l.status', "=","0");
		})
		->when($from=="Acc", function ($lavori) {			
			return $lavori->where('l.status', "=","1");
		})
		->when($from=="Rif", function ($lavori) {			
			return $lavori->where('l.status', "=","2");
		})				
		->where('l.id_lav_ref',"=",$id_lav_ref)
		->orderBy('appalti.id','desc')
		->get();

		if (Auth::user()) {
			$lav_new=array();$indice=0;
			foreach($lavori as $lavoro) {
				$lavoro['indice']=$indice;
				$lav_new[]=$lavoro;
				$indice++;
			}
			$lavori=$lav_new;
		}

		$risp['header']=$check;
		$risp['lavori']=$lavori;
		echo json_encode($risp);
		
	}	
	
	public function countappalti(Request $request) {
		if (Auth::user()) {
			$id=Auth::user()->id;
			
			$candidati=candidati::select("id")
			->where('id_user', "=", $id)->get();
			
			if (isset($candidati[0]))
				$id_lav_ref=$candidati[0]['id'];
			else
				$id_lav_ref=0;
			$check=array();
		}
		else {
			$check=$this->check_log($request); 
			if ($check['esito']=="KO") {
				$risp['header']=$check;
				 echo json_encode($risp);
				 exit;
			} 
			$id_lav_ref=$check['id_user'];
		}
		
		if ($id_lav_ref==0) return array();
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

		////////////reperibilità
		$count_newrep=reperibilita::select('reperibilita.id')
		->where('reperibilita.dele', "=","0")
		->where('reperibilita.status', "=","0")
		->where('reperibilita.id_user',"=",$id_lav_ref)
		->count();
		
		$numrepsi=reperibilita::select('reperibilita.id')
		->where('reperibilita.dele', "=","0")
		->where('reperibilita.status', "=","1")
		->where('reperibilita.id_user',"=",$id_lav_ref)
		->count();

		$numrepno=reperibilita::select('reperibilita.id')
		->where('reperibilita.dele', "=","0")
		->where('reperibilita.status', "=","2")
		->where('reperibilita.id_user',"=",$id_lav_ref)
		->count();

		
		$risp['count_newrep']=$count_newrep;
		$risp['numrepsi']=$numrepsi;
		$risp['numrepno']=$numrepno;
		
		if (Auth::user()) return $risp;
		else echo json_encode($risp);
		
	}	

	public function infoappalti(Request $request) {

		if (Auth::user()) {
			$id=Auth::user()->id;
			
			$candidati=candidati::select("id")
			->where('id_user', "=", $id)->get();
			
			if (isset($candidati[0]))
				$id_lav_ref=$candidati[0]['id'];
			else
				$id_lav_ref=0;
			$check=array();
			$wh=1;
			$id_ref_a=$request->input("id_ref_a");
		}
		else {
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
		}

	

		if ($wh==99) {
			$risp['header']=$check;
			$risp['info']=array();
			echo json_encode($risp);
			exit;
		}

		//tutti gli appalti assegnati ad un operatore
		if ($wh==0) {
			$allinfo=appalti::select('appalti.*')
			->join('lavoratoriapp as l','appalti.id','l.id_appalto')
			->where('appalti.dele', "=",0)
			->where('l.status', "=",0)
			->where('l.id_lav_ref',"=",$id_lav_ref)
			->get();
		}
		
		//chiamata da storico appalti relativi ad un particolare appalto
		if ($wh==1) {
			$allinfo=appalti::select('appalti.*')
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
			
			$info[$sc]['luogo_incontro']=$record->luogo_incontro;
			$info[$sc]['orario_incontro']=$record->orario_incontro;
			$info[$sc]['chiesa']=$record->chiesa;
			
			$info[$sc]['variazione']=$record->variazione;
			
			$d=$record->data_ref;
			$date=date_create($d);
			$data_ref=date_format($date,"d-m-Y");
			$info[$sc]['data_ref']=$data_ref;
			$info[$sc]['note']=$record->note;
			
			//lavoratori presenti nell'appalto
			$id_appalto=$record->id;
			$lavoratori=lavoratoriapp::select('c.nominativo','c.id','lavoratoriapp.status')
			->join('candidatis as c','lavoratoriapp.id_lav_ref','c.id')
			->where('lavoratoriapp.id_appalto', "=",$id_appalto)
			->get();
			$lav="";$ent=false;
			foreach($lavoratori as $lavoratore) {
				if (strlen($lav)!=0) $lav.=", ";
				$lav.=$lavoratore->nominativo;
				if ($lavoratore->id==$id_lav_ref) {
					$info[$sc]['status']=$lavoratore->status;
					$ent=true;
				}
			}
			if ($ent==false) $info[$sc]['status']=-1;
			$info[$sc]['lavoratori']=$lav;
			
			$r_mezzo=appalti::from('appalti as a')
			->select('c.nominativo','c.id')
			->join('candidatis as c','a.responsabile_mezzo','c.id')
			->where('a.id', "=",$id_appalto)
			->get()->first();
			$responsabile_mezzo="";
			if (isset($r_mezzo->nominativo)) 
				$responsabile_mezzo=$r_mezzo->nominativo;
		
			$info[$sc]['responsabile_mezzo']=$responsabile_mezzo;
			$info[$sc]['id_appalto']=$id_appalto;
		
			
			
			$sc++;
		}
			
		$risp['header']=$check;
		$risp['info']=$info;

		
		echo json_encode($risp);
		
	}
	

	public function lavori_rep(Request $request) {
		$check=$this->check_log($request); 
		if ($check['esito']=="KO") {
			$risp['header']=$check;
			 echo json_encode($risp);
			 exit;
		} 
		$id_lav_ref=$check['id_user'];


		$lavori=reperibilita::select(DB::raw("DATE_FORMAT(reperibilita.data,'%d-%m-%Y') as data"),'reperibilita.id','reperibilita.fascia','reperibilita.status')
		->where('reperibilita.dele', "=","0")
		->where('reperibilita.id_user',"=",$id_lav_ref)
		->orderBy('reperibilita.id','desc')
		->get();

			
		$risp['header']=$check;
		$risp['lavori']=$lavori;
		echo json_encode($risp);
		
	}	
	
	public function info_rep(Request $request) {

		$check=$this->check_log($request); 
		if ($check['esito']=="KO") {
			$risp['header']=$check;
			 echo json_encode($risp);
			 exit;
		} 
		$id_lav_ref=$check['id_user'];
		$wh=99;$id_ref_rep=0;
		if ($request->has("from")) {
			$from=$request->input("from");
			if ($from=="menu") $wh=0;
			if ($from=="storico_rep") {
				$wh=1;
				$id_ref_rep=$request->input("id_ref_rep");
			}
		}	


		if ($wh==99) {
			$risp['header']=$check;
			$risp['info']=array();
			echo json_encode($risp);
			exit;
		}
		

		//tutte le reperibilità assegnate ad un operatore
		if ($wh==0) {
			$allinfo=reperibilita::select('reperibilita.id',DB::raw("DATE_FORMAT(reperibilita.data,'%d-%m-%Y') as data"),'reperibilita.fascia','reperibilita.status')
			->where('reperibilita.dele', "=",0)
			->where('reperibilita.status', "=",0)
			->where('reperibilita.id_user',"=",$id_lav_ref)
			->get();
		}
		
		//chiamata da storico appalti relativi ad un particolare appalto
		if ($wh==1) {
			$allinfo=reperibilita::select('reperibilita.*')
			->where('reperibilita.id', "=",$id_ref_rep)
			->get();
		}
		
		
			
		$risp['header']=$check;
		$risp['info']=$allinfo;

		
		echo json_encode($risp);
		
	}	
	
	public function risposta_user(Request $request) {
		if (Auth::user()) {
			$id=Auth::user()->id;
			
			$candidati=candidati::select("id")
			->where('id_user', "=", $id)->get();
			
			if (isset($candidati[0]))
				$id_lav_ref=$candidati[0]['id'];
			else
				$id_lav_ref=0;
			$check=array();
		}
		else {
			$check=$this->check_log($request); 
			if ($check['esito']=="KO") {
				$risp['header']=$check;
				 echo json_encode($risp);
				 exit;
			} 
			$id_lav_ref=$check['id_user'];
		}


		$id_appalto=$request->input("id_appalto");
		$sn=$request->input("sn");
		$status=0;
		if ($sn=="S") $status=1;
		if ($sn=="N") $status=2;
		
		$this->send_mail_creator($id_appalto,$id_lav_ref,$sn);
		if ($status==2) 
			$this->send_push($id_appalto,$tipo="alert",$id_lav_ref);
		
		
		lavoratoriapp::where('id_appalto', $id_appalto)
		->where('id_lav_ref', $id_lav_ref)
		->update(['status' => $status]);
		$risp['header']=$check;
		$risp['info']="Set risposta: $sn";
		echo json_encode($risp);
		
		
	}
	
	public function risposta_user_rep(Request $request) {
		$check=$this->check_log($request); 
		if ($check['esito']=="KO") {
			$risp['header']=$check;
			 echo json_encode($risp);
			 exit;
		} 
		$id_lav_ref=$check['id_user'];
		$id_rep=$request->input("id_rep");
		$sn=$request->input("sn");
		$status=0;
		if ($sn=="S") $status=1;
		if ($sn=="N") $status=2;
		
		
		reperibilita::where('id', $id_rep)->update(['status' => $status]);
		$risp['header']=$check;
		$risp['info']="Set risposta: $sn";
		echo json_encode($risp);
		
		
	}	
	
	public function send_mail_creator($id_appalto,$id_lav_ref,$sn) {
		$nome_lav= DB::table('candidatis as c')
		->where('c.id', "=",$id_lav_ref)
		->first()->nominativo;
		
		$info_app=appalti::select('u.email')
		->join('users as u','appalti.id_creator','u.id')
		->where('appalti.id', "=",$id_appalto)
		->first();
		if ($info_app->email) {
			$email=$info_app->email;
			
			//$email="morescogianluca@gmail.com";
			$status=array();
			try {
				$msg="";
				$data["email"] = $email;					
				$data["title"] = "Risposta lavoratore per partecipazione appalto";
				if ($sn=="S") 
					$msg = "Il lavoratore $nome_lav ha accettato la proposta di partecipazione all'appalto";
				else
					$msg = "Il lavoratore $nome_lav ha rifiutato la proposta di partecipazione all'appalto";

				//$prefix="http://localhost:8012";
				$prefix="https://217.18.125.177";

				$lnk=$prefix."/ingfun/public/newapp/$id_appalto/0/0";

				$msg.="\nCliccare quì $lnk per i dettagli sull'appalto";
				
				$data["body"]=$msg;
				

				Mail::send('emails.risposta_appalti', $data, function($message)use($data) {
					$message->to($data["email"], $data["email"])
					->subject($data["title"]);

				});
				
				$status['status']="OK";
				$status['message']="Mail inviata con successo";
				
				
				
			} catch (Throwable $e) {
				$status['status']="KO";
				$status['message']="Errore occorso durante l'invio! $e";
			}
//print_r($status);
			
		}	
	}
	
	public function send_push($id_appalto,$tipo="new",$id_lav_ref) {
		//$push_id="3863803b-eb7e-4ad4-aafd-958b85dff83f";
		$nome_lav= DB::table('candidatis as c')
		->where('c.id', "=",$id_lav_ref)
		->first()->nominativo;
		
		$info_app=appalti::select('u.push_id')
		->join('users as u','appalti.id_creator','u.id')
		->where('appalti.id', "=",$id_appalto)
		->first();
		
		if (!$info_app->push_id) return;
		$push_id=$info_app->push_id;
		echo "push_id $push_id";

		$params = []; 
		$params['include_player_ids'] = [$push_id]; 
		$headings = array(
			"it" => 'MisAPP News',
			"en" => 'MisAPP News'
			);
		
		if ($tipo=="new")
			$contents = [ 
			   "it" => "Nuova richiesta accettazione Servizio", 
			   "en" => "Request acceptance of new service"
			]; 

		if ($tipo=="alert")
			$contents = [ 
			   "it" => "Servizio non accettato da operatore $nome_lav", 
			   "en" => "Request not accepted from $nome_lav"
			]; 

		$params['priority'] = 10; 
		$params['contents'] = $contents; 
		$params['headings'] = $headings; 
		//$params['delayed_option'] = "timezone"; // Will deliver on user's timezone 
		//$params['delivery_time_of_day'] = "2:30PM"; // Delivery time

		$resp=OneSignal::sendNotificationCustom($params);		
	}	
  

}
