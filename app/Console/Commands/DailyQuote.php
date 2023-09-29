<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\lavoratoriapp;
use App\Models\appalti;
use App\Models\candidati;
use App\Models\user;
use OneSignal;

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
			$list_push=appalti::select('l.id_lav_ref','l.id_appalto')
			->join("lavoratoriapp as l","appalti.id","l.id_appalto")
			->where('l.status','=',0)
			->groupby('l.id_lav_ref')
			->get();
			$num_send=0;
			foreach ($list_push as $list ){
				$send=false;
				$id_ref=$list->id_lav_ref;
				$nominativo="";
				$user_ref=candidati::select('id_user','nominativo')
				->where('id','=',$id_ref)->get()->first();
				if ($user_ref->id_user!=null) {
					$nominativo=$user_ref->nominativo;
					$push=user::select('push_id')
					->where('id','=',$user_ref->id_user)->get()->first();
					$push_id=$push->push_id;
					if ($push_id==null || strlen($push_id)==0) $send=false;
					else $send=true;
					if ($send==true) {
						$num_send++;
						$this->send_push($push_id,"alert","");
					}	
				}
				
				//Invio push a chi ha creato l'appalto per notificare che
				//l'utente corrente della lista, non ha ancora accettato
				$id_appalto=$list->id_appalto;
				$info_app=appalti::select('u.name','u.push_id')
				->join('users as u','appalti.id_creator','u.id')
				->where('appalti.id', "=",$id_appalto)
				->first();
				if ($info_app->push_id) {
					$push_id=$push->push_id;
					$name=$push->name;
					
					//test push
					//$push_id="a06dd418-1884-4233-8736-4beb3d51b783";
					
					if ($push_id==null || strlen($push_id)==0) $send=false;
					else $send=true;
					
					if ($send==true && strlen($nominativo)>0) {
						$message="L'utente $nominativo ($name) non ha ancora accettato/rifiutato la richiesta per la partecipazione all'appalto $id_appalto";
						$this->send_push($push_id,"alert_creator",$message);
					}
				}
				
			}
			$this->info("Inviate ".$num_send." notifiche push di sollecito!");			
		
    }
	
	public function send_push($userId,$tipo="new",$message_extra="") {
		//$userId="90cab005-1db0-4f7e-9ab0-d68b5a9f9e60";
		if (strlen($userId)==0) return;
		$params = []; 
		$params['include_player_ids'] = [$userId]; 
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
}
