<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\lavoratoriapp;
use App\Models\appalti;
use App\Models\candidati;
use App\Models\user;
use App\Models\ditte;
use OneSignal;
use Mail;

class DailyQuote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quote:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'espectively send an exclusive quote to everyone daily via email';

    /**
     * Execute the console command.
     *
     * @return int
     */


    public function handle()
    {   
		/*
			Lista di push_id riferiti a lavoratori che non
			hanno ancora accettato la/e richiesta/e
		*/
		$list_push=$this->send_list_push_mail(0,"alert",array());
		$num_send_mail=$list_push['num_send_mail'];
		$num_send=$list_push['num_send'];
		$this->info("Inviate ".$num_send." notifiche push e ".$num_send_mail." mail di sollecito!");			
	
    }
	
	public function send_list_push_mail($id_app,$type,$only_send=array()) {
		$list_push=appalti::select('appalti.*','l.id_lav_ref','appalti.id')
		->join("lavoratoriapp as l","appalti.id","l.id_appalto")
		->where('l.status','=',0)
		->where('appalti.dele','=','0')
		->when($id_app!="0", function ($list_push) {
			return $list_push->where('appalti.id', $id_app);
		})
		->groupby('l.id_lav_ref')
		->get();

		$ids_lav=array();
		foreach($list_push as $list) {
			if (!in_array($list->id_lav_ref,$ids_lav))  
				$ids_lav[]=$list->id_lav_ref;
		}	


		$lavs=candidati::select('id','nominativo')->get();
		$lav_id=array();
		foreach($lavs as $lav) {
			$lav_id[$lav->id]=$lav->nominativo;
		}


		$lavs=candidati::select('id','nominativo')->get();
		$lav_id=array();
		foreach($lavs as $lav) {
			$lav_id[$lav->id]=$lav->nominativo;
		}
		$num_send_mail=0;$num_send=0;
		foreach ($list_push as $list ){

			$ditta_ref="";
			$id_ditta=$list->id_ditta;
			$ditta_info=ditte::select('denominazione')->where('id', "=",$id_ditta)->get()->first();
			if ($ditta_info->denominazione!=null) $ditta_ref=$ditta_info->denominazione;
			$list->ditta_ref=$ditta_ref;
			$list->lav_id=$lav_id;
			$list->ids_lav=$ids_lav;
			

			$send=false;$send_m=false;
			$id_ref=$list->id_lav_ref;
			if (count($only_send)>0) {
				if (!in_array($id_ref,$only_send)) continue;
			}
			$user_ref=candidati::select('id_user','email','email_az','nominativo')->where('id','=',$id_ref)->get()->first();
			if ($user_ref->id_user!=null) {
				$push=user::select('push_id')
				->where('id','=',$user_ref->id_user)->get()->first();
				$push_id=$push->push_id;
				if ($push_id==null || strlen($push_id)==0) $send=false;
				else $send=true;

				if ($send==true) {
					$num_send++;
					$this->send_push($push_id,$type,"");
				}					
				
				$email=$user_ref->email;
				$email_az=$user_ref->email_az;
				$list->nominativo=$user_ref->nominativo;

				if ($email_az!=null) $email=$email_az;

		
				if ($email==null || strlen($email)==0) $send_m=false;
				else $send_m=true;
				
				if ($send_m==true) {
					$num_send_mail++;
					$this->send_mail($email,$type,$list);
					//invio notifica a chi ha creato l'appalto per informare che l'utente corrente 
					//non ha ancora accettato
					$id_appalto=$list->id;
					$creator=appalti::select('appalti.id_creator')->where('appalti.id', "=",$id_appalto)->first();
						
					if ($creator->id_creator) {
						$info_app=candidati::select('id_user','email','email_az')->where('id_user', "=",$creator->id_creator)->first();

						if (isset($info_app->id_user)) {
							$push=user::select('push_id')->where('id','=',$info_app->id_user)->get()->first();
							$push_id=null;
							if (isset($info_app->push_id)) $push_id=$push->push_id;
							if ($push_id==null || strlen($push_id)==0) $send=false;
							else $send=true;

							if ($send==true) {
								$num_send++;
								$this->send_push($push_id,"alert_creator","");
							}
							
							if ($info_app->email || $info_app->email_az) { 
								$email=$user_ref->email;
								$email_az=$user_ref->email_az;
								if ($email_az!=null) $email=$email_az;
				
								$num_send_mail++;
								$this->send_mail($email,"alert_creator",$list);
							}
						}
					}

				}



			}



						
		}		
		$arr['num_send_mail']=$num_send_mail;
		$arr['num_send']=$num_send;
		return $arr;
	}

	public function send_push($userId,$tipo="new",$message_extra="") {
		//$userId="90cab005-1db0-4f7e-9ab0-d68b5a9f9e60";
		if (strlen($userId)==0) return;
		$params = []; 
		$params['include_player_ids'] = [$userId]; 
		$url="ingfun/public/misapp";
		$params['url'] = $url;		
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
			   "it" => "Sollecito accettazione Servizio $message_extra", 
			   "en" => "Request acceptance of new service"
			]; 

		if ($tipo=="alert_creator")
			$contents = [ 
			   "it" => $message_extra, 
			   "en" => "Request acceptance of new service"
			]; 

		if ($tipo=="edit")
			$contents = [ 
			   "it" => "Segnalazione di variazione su appalto $message_extra", 
			   "en" => "Edit service"
			]; 
		if ($tipo=="dele")
			$contents = [ 
			   "it" => "Estromissione da appalto $message_extra", 
			   "en" => "Dele from service"
			]; 
			
		$params['priority'] = 10; 
		$params['contents'] = $contents; 
		$params['headings'] = $headings; 
		//$params['delayed_option'] = "timezone"; // Will deliver on user's timezone 
		//$params['delivery_time_of_day'] = "2:30PM"; // Delivery time

		$resp=OneSignal::sendNotificationCustom($params);		
	}
	public function send_mail($email,$tipo="new",$appalto) {

		$titolo="Nuova richiesta accettazione Servizio";
		if ($tipo=="new")
			$titolo="Nuova richiesta accettazione Servizio";

		if ($tipo=="alert_creator")
			$titolo="Servizio non ancora accettato/rifiutato";			
			
		if ($tipo=="alert")
			$titolo="Sollecito accettazione Servizio";

		if ($tipo=="edit")
			$titolo="Segnalazione di variazione su appalto";

		if ($tipo=="dele")
			$titolo="Estromissione da appalto"; 

		$body_msg="Appalto ID: ".$appalto->id;

		try {
			
			
			$data["title"] = $titolo;
			$data["appalto"] = $appalto;
			

			Mail::send('emails.misapp_notif', $data, function($message)use($data,$email) {
				$message->to($email, $email)
				->subject($data["title"]);
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
