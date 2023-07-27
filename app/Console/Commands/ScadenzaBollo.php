<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\parco_scheda_mezzo;
use App\Models\set_global;
use DB;
use Mail;

class ScadenzaBollo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scadenza:bollo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Invio mail al parco auto quando il bollo/assicurazione sta per scadere (2 mesi prima)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
		$today=date("Y-m-d");
		$scad=date('Y-m-d', strtotime($today. ' + 60 days'));
		
		$testo="";		
		for ($sca=1;$sca<=2;$sca++) {
			$campo="s.scadenza_bollo";
			if ($sca==2) $campo="s.scadenza_assicurazione";
			/*
			$info_mezzo=DB::table('parco_scheda_mezzo as s')
			->select('s.targa',$campo,DB::raw('DATEDIFF(DATE_FORMAT('.$campo.',"%Y-%m-%d"),CURDATE())  as days'))
			->whereRaw('DATEDIFF(DATE_FORMAT('.$campo.',"%Y-%m-%d"),CURDATE()) >=45 and DATEDIFF(DATE_FORMAT('.$campo.',"%Y-%m-%d"),CURDATE()) <=60')
			->get();
			*/
			
			$info_mezzo=DB::table('parco_scheda_mezzo as s')
			->select('s.targa', DB::raw("DATE_FORMAT($campo,'%d-%m-%Y') as scadenza"))
			->where($campo,"=",$scad)
			->get();

			
			$tipo="il Bollo";
			if ($sca==2) $tipo="l'Assicurazione";
			foreach ($info_mezzo as $ris) {
				$testo.="Il <b>".$ris->scadenza."</b> scade $tipo del mezzo targato <b>".$ris->targa."</b> <hr>";
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
				$data["title"] = "Scadenza Bollo/Assicurazione";
			

				//$prefix="http://localhost:8012";
				$prefix="https://217.18.125.177";

				//$lnk=$prefix."/ingfun/public/newapp/1/0/0";
				//$msg.="\nCliccare quì $lnk per i dettagli sull'appalto";
				
				$data["body"]=$msg;
				

				Mail::send('emails.scadenza_bollo_assocurazione', $data, function($message)use($data) {
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
