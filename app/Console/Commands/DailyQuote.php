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
        
		 
			$list_push=appalti::select('l.id_lav_ref')
			->join("lavoratoriapp as l","appalti.id","l.id_appalto")
			->where('l.status','=',0)
			->groupby('l.id_lav_ref')
			->get();
			$num_send=0;
			foreach ($list_push as $list ){
				$send=false;
				$id_ref=$list->id_lav_ref;
				$user_ref=candidati::select('id_user')
				->where('id','=',$id_ref)->get()->first();
				if ($user_ref->id_user!=null) {
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
