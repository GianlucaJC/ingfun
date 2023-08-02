<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\parco_scheda_mezzo;
use App\Models\set_global;
use DB;
use Mail;

class ScadenzaNoleggio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scadenza:noleggio';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Invio mail al parco auto quando un mezzo in noleggio si avvicina alla scadenza';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
		
		$testo="";		

		$campo="s.scadenza_bollo";
		
		/*
		$info_mezzo=DB::table('parco_scheda_mezzo as s')
		->select('s.targa',$campo,DB::raw('DATEDIFF(DATE_FORMAT('.$campo.',"%Y-%m-%d"),CURDATE())  as days'))
		->whereRaw('DATEDIFF(DATE_FORMAT('.$campo.',"%Y-%m-%d"),CURDATE()) >=45 and DATEDIFF(DATE_FORMAT('.$campo.',"%Y-%m-%d"),CURDATE()) <=60')
		->get();
		*/
		
		$info_mezzo=DB::table('parco_scheda_mezzo as s')
		->select('s.id','s.targa', DB::raw("DATE_FORMAT(s.a_data_n,'%d-%m-%Y') as scadenza"),'s.da_data_n','gg_alert_mail')
		->where('notifica_alert_noleggio',"<>",2)
		->get();

		$today=date("Y-m-d");
		foreach ($info_mezzo as $ris) {
			$id_mezzo=$ris->id;
			$inizio=$ris->da_data_n;
			$gg_alert_mail=$ris->gg_alert_mail;
			$scad=date('Y-m-d', strtotime($inizio. " + $gg_alert_mail days"));
			if ($today>=$scad) {
				$scadenza=$ris->scadenza;
				$testo.="Il <b>".$ris->scadenza."</b> scade il noleggio del mezzo targato <b>".$ris->targa."</b> <hr>";
				parco_scheda_mezzo::where('id', $id_mezzo)->update(['notifica_alert_noleggio' => 2]);				
			}
		}
		if (strlen($testo)!=0) $this->send_mail($testo);
		else {
			$status['status']="OK";
			$status['message']="Nessuna scadenza rilevata";
			print_r($status);			
		}
    }


	public function send_mail($msg) {
		$status=array();
		
		$set_global=set_global::where('id', "=", 1)->get();
		$email=array();
		if (isset($set_global[0]['email_parco']))
			$email[]=$set_global[0]['email_parco'];		
		if (isset($set_global[0]['email_acquisti'])) {
			$email[]=$set_global[0]['email_acquisti'];
		}	
		if (count($email)>0) {
			try {
				$data["email"] = $email;					
				$data["title"] = "Scadenza Noleggio";
			

				//$prefix="http://localhost:8012";
				$prefix="https://217.18.125.177";

				//$lnk=$prefix."/ingfun/public/newapp/1/0/0";
				//$msg.="\nCliccare quì $lnk per i dettagli sull'appalto";
				
				$data["body"]=$msg;
				

				Mail::send('emails.alert_noleggio', $data, function($message)use($data) {
					$message->to($data["email"], $data["email"])
					->subject($data["title"]);

				});
				
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
		print_r($status);	
	}
	
}
