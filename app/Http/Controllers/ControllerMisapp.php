<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\servizi;
use App\Models\serviziapp;
use App\Models\appalti;
use App\Models\ditte;
use App\Models\lavoratoriapp;
use App\Models\candidati;
use App\Models\user;
use App\Models\societa;
use App\Models\mezzi;
use App\Models\parco_scheda_mezzo;
use OneSignal;

use DB;

class ControllerMisapp extends Controller
{
public function __construct()
	{
		$this->middleware('auth')->except(['index']);
	}		

	

	

	public function misapp($id_appalto=0) {
		

		$dx=date("Y-m-d");
		
		$view_dele=0;
		if (request()->has("view_dele")) $view_dele=request()->input("view_dele");
		if ($view_dele=="on") $view_dele=1;

		$mezzi=mezzi::select('id','tipologia','marca','modello','targa')
		->orderBy('marca')
		->orderBy('targa')
		->get();
		$targhe=array();
		foreach($mezzi as $mezzo) {
			$targhe[$mezzo->targa]=$mezzo->marca." - ".$mezzo->modello." - ".$mezzo->targa;
		}	

		$restore_cand=request()->input("restore_cand");
		
		if (request()->has("clona")) {
			$id_clone_from=request()->input("clona");
			$clone_app = appalti::find($id_clone_from);
			$new = $clone_app->replicate();
			$new->orario_ref="";
			$new->status=0;
			$new->orario_incontro=null;
			$new->save();
			$newID=$new->id;
			
			$list_serv=serviziapp::select('id_servizio','importo_lavoratore')
			->where('id_appalto', $id_clone_from)
			->get();
			foreach($list_serv as $new_serv) {
				DB::table('serviziapp')->insert([
					'id_appalto' => $newID,
					'id_servizio' => $new_serv->id_servizio,
					'importo_lavoratore' => $new_serv->importo_lavoratore,
					'created_at'=>now(),
					'updated_at'=>now()
				]);					
			}

			$list_lav=lavoratoriapp::select('id_lav_ref')
			->where('id_appalto', $id_clone_from)
			->get();
			foreach($list_lav as $new_lav) {
				DB::table('lavoratoriapp')->insert([
					'id_appalto' => $newID,
					'id_lav_ref' => $new_lav->id_lav_ref,
					'status' => 0,
					'created_at'=>now(),
					'updated_at'=>now()
				]);					
			}
			

		}
		
		$dele_cand=request()->input("dele_cand");
		$push_appalti=request()->input("push_appalti");

		
		if (strlen($dele_cand)!=0) {
			appalti::where('id', $dele_cand)
			  ->update(['dele' => 1]);			
		}		
		if (strlen($restore_cand)!=0) {
			appalti::where('id', $restore_cand)
			  ->update(['dele' => 0]);			
		}
		
		$num_send=0;
		if (strlen($push_appalti)!=0) {
			$list_push=appalti::select('l.id_lav_ref')
			->join("lavoratoriapp as l","appalti.id","l.id_appalto")
			->where('appalti.id', $push_appalti)
			->where('status','=',0)
			->groupby('l.id_lav_ref')
			->get();
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
			
		}

		$sezionali=societa::select('id','descrizione')
		->orderBy('descrizione')
		->get();

		$aziende_proprieta=array();
		foreach($sezionali as $sezionale) {
			$azienda_proprieta[$sezionale->id]=$sezionale->descrizione;
		}


		$gestione=appalti::select('appalti.id','appalti.id_azienda_proprieta','appalti.status','appalti.dele','appalti.descrizione_appalto','appalti.targa','appalti.data_ref','orario_ref','appalti.id_ditta','d.denominazione')
		->join('ditte as d', 'd.id','=','appalti.id_ditta')
		->when($view_dele=="0", function ($gestione) {
			return $gestione->where('appalti.dele', "=","0");
		})
		->when($id_appalto!="0", function ($gestione) use ($id_appalto) {
			return $gestione->where('appalti.id', "=",$id_appalto);
		})
		->orderByDesc('appalti.id')	
		->get();		
		

		return view('all_views/misapp/misapp')->with('view_dele',$view_dele)->with('gestione',$gestione)->with('num_send',$num_send)->with('targhe',$targhe)->with('azienda_proprieta',$azienda_proprieta);

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
			
		$params['priority'] = 10; 
		$params['contents'] = $contents; 
		$params['headings'] = $headings; 
		//$params['delayed_option'] = "timezone"; // Will deliver on user's timezone 
		//$params['delivery_time_of_day'] = "2:30PM"; // Delivery time

		$resp=OneSignal::sendNotificationCustom($params);		
	}
}
