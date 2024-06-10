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
use Twilio\Rest\Client;
use Mail;


use DB;

class ControllerAppalti extends Controller
{
public function __construct()
	{
		$this->middleware('auth')->except(['index']);
	}		

	public function save_newapp(Request $request) {			
		$id_user=Auth::user()->id;
		$id_app=$request->input('id_app');
		if ($id_app!=0)
			$appalti = appalti::find($id_app);
		else {
			$appalti = new appalti;
			$appalti->id_creator = $id_user;
		}	
		$id_ditta=$request->input('ditta');

		//$appalti->descrizione_appalto = $request->input('descrizione_appalto');
		$appalti->id_azienda_proprieta = $request->input('azienda_proprieta');
		
		$appalti->luogo_incontro = $request->input('luogo_incontro');
		$appalti->km_percorrenza = $request->input('km_percorrenza');
		$appalti->orario_incontro = $request->input('orario_incontro');
		$appalti->chiesa = $request->input('chiesa');
		$appalti->data_ref = $request->input('data_app');
		$appalti->orario_ref = $request->input('ora_app');
		$appalti->orario_fine_servizio = $request->input('orario_fine_servizio');
		$appalti->id_ditta = $id_ditta;
		$appalti->targa = $request->input('mezzo');
		$appalti->note = $request->input('note');
		$appalti->variazione = $request->input('variazione');
		$appalti->responsabile_mezzo=$request->input('responsabile_mezzo');
		$appalti->save();
		if ($id_app==0) $id_app=$appalti->id;
		
		$appalti = appalti::find($id_app);
		$appalti->descrizione_appalto =$id_app;
		$appalti->save();
		
		
		$deleted = serviziapp::where('id_appalto', $id_app)->delete();
		$servizi=$request->input('servizi');
		for ($sca=0;$sca<=count($servizi)-1;$sca++) {
			$id_servizio=$servizi[$sca];
			
			$importo_lavoratore=DB::table('servizi_ditte')
			->select('importo_lavoratore')
			->where('id_ditta', "=", $id_ditta)
			->where('id_servizio', "=", $id_servizio)
			->get()->first()->importo_lavoratore;
			if ($importo_lavoratore==null || strlen($importo_lavoratore)==0) $importo_lavoratore=0;
			
			DB::table('serviziapp')->insert([
				'id_appalto' => $id_app,
				'id_servizio' => $id_servizio,
				'importo_lavoratore' => $importo_lavoratore,
				'created_at'=>now(),
				'updated_at'=>now()
			]);			
		}
		
		
		
		$to_delete = lavoratoriapp::where('id_appalto', $id_app)->update(['to_delete'=>1]);
		$info_lavoratori=$request->input('lavoratori');
		$lavoratori=explode(";",$info_lavoratori);
		$num_send=0;
		for ($sca=0;$sca<=count($lavoratori)-1;$sca++) {
			$send=false;
			$id_lav_ref=$lavoratori[$sca];
			$count=lavoratoriapp::where('id_appalto','=',$id_app)
			->where('id_lav_ref','=',$id_lav_ref)
			->count();
			if ($count==0) {
				DB::table('lavoratoriapp')->insert([
					'id_appalto' => $id_app,					
					'id_lav_ref' => $id_lav_ref,
					'created_at'=>now(),
					'updated_at'=>now()
				]);
				//in caso di nuovi lavoratori inseriti, invio push
				$send=true;
				$resp=candidati::select('u.push_id')
				->join('users as u','candidatis.id_user','u.id')
				->where("candidatis.id","=", $id_lav_ref);
				if ($resp->count()==0) $send=false;
				else {
					$push_id=$resp->get()->first()->push_id;
					if ($push_id==null || strlen($push_id)==0) $send=false;
				}				
			} else {
				$data=['to_delete' => 0];
				lavoratoriapp::where('id_appalto', $id_app)			
				->where('id_lav_ref','=',$id_lav_ref)
				->update($data);
				
			}	
			
		   //$send=true;$push_id="XX"; //decommentare per test (ed abilitare un ID fittizio in this->send_push())
			if ($send==true) {
				$num_send++;
				$this->send_push($push_id,"new","");
			}	

		}


		//push per eventuali estromessi dall'appalto
		$resp=candidati::select('u.push_id')
		->join('users as u','candidatis.id_user','u.id')
		->join('lavoratoriapp as l','l.id_lav_ref','candidatis.id')		
		->where("l.to_delete","=", 1)
		->where("l.id_appalto","=", $id_app)
		->groupBy('candidatis.id');
		if ($resp->count()!=0){
			$all_push=$resp->get();
			foreach($all_push as $single) {
				$push_id=$single->push_id;
				$this->send_push($push_id,"dele",$request->input('descrizione_appalto'));
			}					

		}		
		$deleted = lavoratoriapp::where('to_delete','=',1)
		->where('id_appalto','=',$id_app)->delete();
		
		//push per eventuale variazione (max una)
		$flag_variazione=$request->input('flag_variazione');
		if ($flag_variazione=="1" && strlen($request->input('variazione'))!=0) {
			$resp=candidati::select('u.push_id')
			->join('users as u','candidatis.id_user','u.id')
			->join('lavoratoriapp as l','l.id_lav_ref','candidatis.id')		
			->where("l.id_appalto","=", $id_app)
			->groupBy('candidatis.id');	
			if ($resp->count()!=0){
				$all_push=$resp->get();
				foreach($all_push as $single) {
					$push_id=$single->push_id;
					$this->send_push($push_id,"edit",$request->input('descrizione_appalto'));
				}					

			}			
		}
			
		//
		
		return redirect()->route("newapp",['id'=>$id_app,'from'=>1,'num_send'=>$num_send]);

	}

	public function newapp($id=0,$from=0,$num_send) {
		$appalti=array();
		
		$servizi=array();
		$ids_lav=array();
		$id_servizi=array();
		$view_dele="0";
		$today=date("Y-m-d");
		$sezionali=societa::select('id','descrizione')
		->where('dele','=',0)
		->orderBy('descrizione')
		->get();
		
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
		
		
		$mezzi=parco_scheda_mezzo::from('parco_scheda_mezzo as sm')
		->select('sm.id','mm.marca','mom.modello','sm.targa')
		->join('parco_marca_mezzo as mm','sm.marca','mm.id')
		->join('parco_modello_mezzo as mom','sm.modello','mom.id')
		->orderBy('mm.marca')
		->orderBy('sm.targa')
		->groupBy('sm.id')
		->get();

		if ($id!=0) {
			$view_dele="1";
			$appalti=DB::table('appalti AS a')
			->select('a.*','sa.id_servizio','la.id_lav_ref','la.status')
			->join('serviziapp as sa','a.id','sa.id_appalto')
			->leftjoin('lavoratoriapp as la','a.id','la.id_appalto')			
			->where('a.id', "=", $id)
			->get();
			
			//->toSql() - dd($appalti);exit;
			
			foreach($appalti as $appalto) {
				if (!in_array($appalto->id_servizio,$id_servizi)) 
					$id_servizi[]=$appalto->id_servizio;
				if (!array_key_exists($appalto->id_lav_ref,$ids_lav))  
					$ids_lav[$appalto->id_lav_ref]=$appalto->status;

			}	
			

			$id_ditta=$appalti[0]->id_ditta;
			
			$servizi=DB::table('servizi as s')
			->join('servizi_ditte as d','s.id','d.id_servizio')
			->select('s.descrizione','d.id_servizio','d.importo_ditta','d.aliquota','d.importo_lavoratore')
			->where('d.id_ditta', '=', $id_ditta)
			->get();
			
		}	
		

		$ditte=ditte::select('id','denominazione')
		->when($view_dele=="0", function ($ditte) {
			return $ditte->where('dele', "=","0");
		})
		->orderBy('denominazione')	
		->get();				

	
		
		return view('all_views/newapp')->with("appalti",$appalti)->with("ditte",$ditte)->with("servizi",$servizi)->with('id_app',$id)->with('id_servizi',$id_servizi)->with('id_servizi',$id_servizi)->with("lavoratori",$lavoratori)->with("ids_lav",$ids_lav)->with("num_send",$num_send)->with('mezzi',$mezzi)->with('sezionali',$sezionali);

	}

	public function listapp($id_appalto=0) {
		

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
		$send_wa=request()->input("send_wa");
		
		if (strlen($send_wa)!=0) {
			$this->send();
		}

		$push_appalti=request()->input("push_appalti");

		
		if (strlen($dele_cand)!=0) {
			appalti::where('id', $dele_cand)
			  ->update(['dele' => 1]);			
		}		
		if (strlen($restore_cand)!=0) {
			appalti::where('id', $restore_cand)
			  ->update(['dele' => 0]);			
		}
		
		$num_send=0;$num_send_mail=0;
		if (strlen($push_appalti)!=0) {
			$list_push=appalti::select('appalti.*','l.id_lav_ref','appalti.id')
			->join("lavoratoriapp as l","appalti.id","l.id_appalto")
			->where('appalti.id', $push_appalti)
			->where('l.status','=',0)
			->groupby('l.id_lav_ref')
			->get();

			$ids_lav=array();
			foreach($list_push as $list) {
				if (!in_array($list->id_lav_ref,$ids_lav))  
					$ids_lav[]=$list->id_lav_ref;
			}	

			

			$id_ditta=$list_push[0]->id_ditta;


			$ditta_ref="";
			$ditta_info=ditte::select('denominazione')->where('id', "=",$id_ditta)->get()->first();
			if ($ditta_info->denominazione!=null) $ditta_ref=$ditta_info->denominazione;

			$lavs=candidati::select('id','nominativo')->get();
			$lav_id=array();
			foreach($lavs as $lav) {
				$lav_id[$lav->id]=$lav->nominativo;
			}
			
			foreach ($list_push as $list ){
				$send=false;$send_m=false;
				$id_ref=$list->id_lav_ref;
				$user_ref=candidati::select('id_user','email','email_az','nominativo')
				->where('id','=',$id_ref)->get()->first();
				if ($user_ref->id_user!=null) {
					$push=user::select('push_id')
					->where('id','=',$user_ref->id_user)->get()->first();
					$push_id=$push->push_id;
					if ($push_id==null || strlen($push_id)==0) $send=false;
					else $send=true;

					
					$email=$user_ref->email;
					$email_az=$user_ref->email_az;
					if ($email_az!=null) $email=$email_az;
					
$email="morescogianluca@gmail.com";	
					$list->ditta_ref=$ditta_ref;
					$list->lav_id=$lav_id;
					$list->ids_lav=$ids_lav;
					$list->nominativo=$user_ref->nominativo;
			
					if ($email==null || strlen($email)==0) $send_m=false;
					else $send_m=true;
					
					if ($send_m==true) {
						$num_send_mail++;
						$this->send_mail($email,"alert",$list);
					}

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

		

		return view('all_views/listappalti')->with('view_dele',$view_dele)->with('gestione',$gestione)->with('num_send',$num_send)->with('targhe',$targhe)->with('azienda_proprieta',$azienda_proprieta);

	}

	public function send()
    {
		$request=request();
		echo env('TWILIO_SID')."<hr>";
		echo $request->to.": ".$request->message."<hr>";
        try {
            $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));

            $message = $twilio->messages->create("whatsapp:".$request->to, [
                'from' => "whatsapp:".env('TWILIO_PHONE_NUMBER'),
                'body' => $request->message,
            ]);

           dd($message);
        } catch (\Exception $e) {
            dd($e);
        }
		
    }

	public function send_mail($email,$tipo="new",$appalto) {

		$titolo="Nuova richiesta accettazione Servizio";
		if ($tipo=="new")
			$titolo="Nuova richiesta accettazione Servizio";

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


	public function send_push($userId,$tipo="new",$message_extra="") {
		//$userId="3863803b-eb7e-4ad4-aafd-958b85dff83f"; // test push MisAPP mobile
		//$userId="5a6b39e0-9fb3-4b73-9cd3-971de475894b"; //test Push web - Firefox
		//$userId="be4f0ff4-76e4-4b0c-b316-bbf1fb023bf0"; //test Push web - chrome mobile
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
