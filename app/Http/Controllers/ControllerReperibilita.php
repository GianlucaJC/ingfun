<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\candidati;
use App\Models\user;
use App\Models\reperibilita;
use OneSignal;
use Mail;

use DB;

class ControllerReperibilita extends Controller
{
public function __construct()
	{
		//$this->middleware('auth')->except(['index']);
	}		


	
	public function listrep($id_reper=0) {
		
		
		$dx=date("Y-m-d");
		
		$view_dele=0;
		if (request()->has("view_dele")) $view_dele=request()->input("view_dele");
		if ($view_dele=="on") $view_dele=1;


		$restore_cand=request()->input("restore_cand");
		$dele_cand=request()->input("dele_cand");
		$push_reper=request()->input("push_reper");

		
		if (strlen($dele_cand)!=0) {
			reperibilita::where('id', $dele_cand)
			  ->update(['dele' => 1]);			
		}		
		if (strlen($restore_cand)!=0) {
			reperibilita::where('id', $restore_cand)
			  ->update(['dele' => 0]);			
		}
		
		$num_send=0;
		if (strlen($push_reper)!=0) {
			$info=candidati::select('email')->where('id','=',$push_reper)->get();
			$email="";
			if (isset($info[0]->email)) $email=$info[0]->email;
			if (strlen($email)!=0) {
				$num_send=1;
			 	@$this->send_mail($email,$tipo="alert",$info);
			}
			
		}

		$reperibilita=reperibilita::select('reperibilita.*',DB::raw("DATE_FORMAT(reperibilita.data,'%d-%m-%Y') as data_it"),'c.nominativo','reperibilita.id_user')
		->join('candidatis as c','c.id','reperibilita.id_user')
		->when($view_dele=="0", function ($ditte) {
			return $ditte->where('reperibilita.dele', "=","0");
		})		
		->orderBy('id',"desc")
		->get();


		return view('all_views/listarep')->with('view_dele',$view_dele)->with('reperibilita',$reperibilita)->with("num_send",$num_send);

	}
	
	public function newreper($id_reper=0) {

		$lavoratori=candidati::select('id','nominativo','tipo_contr','tipo_contratto')
		->where('status_candidatura','=',3)		
		->where('dele','=',0)
		->where('hide_appalti','=',0)
		->orderBy('nominativo')	
		->get();
		
		$edit_rep=array();
		if ($id_reper!=0) {
			$edit_rep=reperibilita::select('reperibilita.*','c.nominativo')
			->join('candidatis as c','c.id','reperibilita.id_user')
			->where('reperibilita.id','=',$id_reper)
			->get();
		}			
		
		return view('all_views/newreper')->with("lavoratori",$lavoratori)->with('id_reper',$id_reper)->with('edit_rep',$edit_rep);

	}
	
	
	public function save_reper(Request $request) {			
		$id_user=Auth::user()->id;
		$id_reper=$request->input('id_reper');
		$id_ref_push=0;
		if ($id_reper==0) {
			$lavoratori=$request->input('lavoratori');
			for ($sca=0;$sca<=count($lavoratori)-1;$sca++) {
				$lavoratore=$lavoratori[$sca];
				$info=candidati::select('email')->where('id','=',$lavoratore)->get();
				$email="";
				if (isset($info[0]->email)) $email=$info[0]->email;
				$reper = new reperibilita;
				$reper->id_user = $lavoratore;
				$reper->data = $request->input('data_reper');
				$reper->fascia= $request->input('fascia');
				$reper->save();
				if (strlen($email)!=0) @$this->send_mail($email,$tipo="new",$info);
			}
		} else {
			$reper = reperibilita::find($id_reper);
			$reper->data = $request->input('data_reper');
			$reper->fascia= $request->input('fascia');
			$reper->save();
		}


		return redirect()->route("listrep");

	}	
	
	public function send_push($userId,$tipo="new",$message_extra="") {
		//$userId="3863803b-eb7e-4ad4-aafd-958b85dff83f";
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
		
		if ($tipo=="new_rep")
			$contents = [ 
			   "it" => "Nuova richiesta accettazione Reperibilità", 
			   "en" => "Request acceptance of new availability"
			]; 
		if ($tipo=="alert_ref")
			$contents = [ 
			   "it" => "Sollecito accettazione Reperibilità", 
			   "en" => "Request acceptance of new availability"
			]; 			
			
			
			
		$params['priority'] = 10; 
		$params['contents'] = $contents; 
		$params['headings'] = $headings; 
		//$params['delayed_option'] = "timezone"; // Will deliver on user's timezone 
		//$params['delivery_time_of_day'] = "2:30PM"; // Delivery time

		$resp=OneSignal::sendNotificationCustom($params);		
	}

	
	public function newapp($id=0,$from=0,$num_send) {
		$appalti=array();
		$ids_lav=array();
		$id_servizi=array();
		$view_dele="0";
		$today=date("Y-m-d");
		$lavoratori=candidati::select('id','nominativo','tipo_contr','tipo_contratto')
		->where('status_candidatura','=',3)		
		->orderByRaw('case 
			when `tipo_contr` = "2" and `tipo_contratto`="1"  then 1 
			when `tipo_contr` = "2" and `tipo_contratto`="2"  then 2
			when `tipo_contr` = "2" and (`tipo_contratto`<>"1" and `tipo_contratto`<>"2")  then 3
			when `tipo_contr` = "1" and `tipo_contratto`="1"  then 4
			when `tipo_contr` = "1" and `tipo_contratto`="2"  then 5
			when `tipo_contr` = "1" and (`tipo_contratto`<>"1" and `tipo_contratto`<>"2")  then 6
			else 7 end')
		->orderBy('nominativo')	
		->get();
		
		$mezzi=mezzi::select('id','tipologia','marca','modello','targa')
		->orderBy('marca')
		->orderBy('targa')
		->get();

		if ($id!=0) {
			$view_dele="1";
			$appalti=DB::table('appalti AS a')
			->select('a.*','sa.id_servizio','la.id_lav_ref','la.status')
			->join('serviziapp as sa','a.id','sa.id_appalto')
			->join('lavoratoriapp as la','a.id','la.id_appalto')			
			->where('a.id', "=", $id)
			->get();
			
			//->toSql() - dd($appalti);exit;
			
			foreach($appalti as $appalto) {
				if (!in_array($appalto->id_servizio,$id_servizi)) 
					$id_servizi[]=$appalto->id_servizio;
				if (!array_key_exists($appalto->id_lav_ref,$ids_lav))  
					$ids_lav[$appalto->id_lav_ref]=$appalto->status;

			}			


		}	
		

		$ditte=ditte::select('id','denominazione')
		->when($view_dele=="0", function ($ditte) {
			return $ditte->where('dele', "=","0");
		})
		->orderBy('denominazione')	
		->get();				

		$servizi=servizi::select('id','descrizione')
		->when($view_dele=="0", function ($servizi) {
			return $servizi->where('dele', "=","0");
		})
		->orderBy('descrizione')	
		->get();	
		
		return view('all_views/newapp')->with("appalti",$appalti)->with("ditte",$ditte)->with("servizi",$servizi)->with('id_app',$id)->with('id_servizi',$id_servizi)->with('id_servizi',$id_servizi)->with("lavoratori",$lavoratori)->with("ids_lav",$ids_lav)->with("num_send",$num_send)->with('mezzi',$mezzi);

	}


	public function send_mail($email,$tipo="new",$info) {

		$titolo="Nuova richiesta accettazione reperibilità";
		if ($tipo=="new")
			$titolo="Nuova richiesta accettazione reperibilità";

		if ($tipo=="alert")
			$titolo="Sollecito accettazione Reperibilità";
		
		try {
			$data["title"] = $titolo;
			

			Mail::send('emails.reper_notif', $data, function($message)use($data,$email) {
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
