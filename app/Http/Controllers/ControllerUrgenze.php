<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\candidati;
use App\Models\user;
use App\Models\servizi;
use App\Models\urgenze;
use OneSignal;
use Mail;

use DB;

class ControllerUrgenze extends Controller
{
public function __construct()
	{
		//$this->middleware('auth')->except(['index']);
	}		


	
	public function listurg($id_urg=0) {
		
		
		$dx=date("Y-m-d");
		
		$view_dele=0;
		if (request()->has("view_dele")) $view_dele=request()->input("view_dele");
		if ($view_dele=="on") $view_dele=1;


		$restore_cand=request()->input("restore_cand");
		$dele_cand=request()->input("dele_cand");
		$push_urgenza=request()->input("push_urgenza");

		
		if (strlen($dele_cand)!=0) {
			urgenze::where('id', $dele_cand)
			  ->update(['dele' => 1]);			
		}		
		if (strlen($restore_cand)!=0) {
			urgenze::where('id', $restore_cand)
			  ->update(['dele' => 0]);			
		}
		
		$num_send=0;
		if (strlen($push_urgenza)!=0) {
			$info=candidati::select('email')->where('id','=',$push_urgenza)->get();
			$email="";
			if (isset($info[0]->email)) $email=$info[0]->email;
			if (strlen($email)!=0) {
				$num_send=1;
			 	@$this->send_mail($email,$tipo="alert",$info);
			}
		}

		$urgenze=urgenze::select('urgenze.*',DB::raw("DATE_FORMAT(urgenze.dataora,'%d-%m-%Y %H:%i:%s') as data_it"),'c.nominativo','urgenze.id_user','urgenze.id_servizio')
		->join('candidatis as c','c.id','urgenze.id_user')
		->when($view_dele=="0", function ($urg) {
			return $urg->where('urgenze.dele', "=","0");
		})		
		->orderBy('id',"desc")
		->get();

        $ditte=DB::table('ditte as d')
		->select("*")
		->where('d.dele', "=","0")
		->orderBy('d.denominazione')
		->get();
		
		$info_d=array();
		foreach ($ditte as $arr_d) {
			$info_d[$arr_d->id]=$arr_d->denominazione;
		}
        
		$servizi=DB::table('servizi as s')
		->select("s.id","s.descrizione","s.id_cod_servizi_ext")
		->where('s.dele', "=","0")
		->orderBy('s.descrizione')
		->get();
		
		$info_s=array();
		foreach ($servizi as $arr_s) {
			$info_s[$arr_s->id]['id']=$arr_s->id;
			$info_s[$arr_s->id]['descrizione']=$arr_s->descrizione;
			$info_s[$arr_s->id]['id_cod_servizi_ext']=$arr_s->id_cod_servizi_ext;
		}

		
  

		return view('all_views/listurg')->with('view_dele',$view_dele)->with('urgenze',$urgenze)->with("num_send",$num_send)->with('info_d',$info_d)->with('info_s',$info_s);

	}
	
	public function newurg($id_urg=0) {

		$lavoratori=candidati::select('id','nominativo','tipo_contr','tipo_contratto')
		->where('status_candidatura','=',3)		
		->where('dele','=',0)
		->where('hide_appalti','=',0)
		->orderBy('nominativo')	
		->get();
		
		$edit_urg=array();
		$id_servizio=0;$servizio_ref="";
		if ($id_urg!=0) {
			$edit_urg=urgenze::select('urgenze.*','c.nominativo')
			->join('candidatis as c','c.id','urgenze.id_user')
			->where('urgenze.id','=',$id_urg)
			->get();
			
			if (isset($edit_urg[0]->id_servizio))  {
				$id_servizio=$edit_urg[0]->id_servizio;
				$servizio_ref=DB::table('servizi as s')
				->select("s.descrizione")
				->where('s.id', "=",$id_servizio)
				->first()->descrizione;
				if ($servizio_ref==null || strlen($servizio_ref)==0) $servizio_ref="";
		
			}
		}			
        $ditte=DB::table('ditte as d')
		->select("*")
		->where('d.dele', "=","0")
		->orderBy('d.denominazione')
		->get();
		
		$info_d=array();
		foreach ($ditte as $arr_d) {
			$info_d[$arr_d->id]=$arr_d->denominazione;
		}
		return view('all_views/newurg')->with("lavoratori",$lavoratori)->with('id_urg',$id_urg)->with('edit_urg',$edit_urg)->with('info_d',$info_d)->with('id_servizio',$id_servizio)->with('servizio_ref',$servizio_ref);

	}
	
	
	public function save_urg(Request $request) {			
		$id_user=Auth::user()->id;
		$id_urg=$request->input('id_urg');
		$id_ref_push=0;
		if ($id_urg==0) {
			$lavoratori=$request->input('lavoratori');
			for ($sca=0;$sca<=count($lavoratori)-1;$sca++) {
				$lavoratore=$lavoratori[$sca];
				$info=candidati::select('email')->where('id','=',$lavoratore)->get();
				$email="";
				if (isset($info[0]->email)) $email=$info[0]->email;
				$urg = new urgenze;
				$urg->id_user = $lavoratore;
				$urg->dataora = $request->input('data_urg');
				$urg->descrizione= $request->input('descrizione');
				$urg->id_ditta= $request->input('ditta');
				$urg->id_servizio= $request->input('id_servizio');
				$urg->save();
				if (strlen($email)!=0) @$this->send_mail($email,$tipo="new",$info);
			}
		} else {
			$urg = urgenze::find($id_urg);
			$urg->dataora= $request->input('data_urg');
			$urg->descrizione= $request->input('descrizione');
			$urg->id_ditta= $request->input('ditta');
			$urg->id_servizio= $request->input('id_servizio');
			$urg->save();
		}

		
		return redirect()->route("listurg");

	}	




	public function send_mail($email,$tipo="new",$info) {

		$titolo="Nuova richiesta accettazione urgenza";
		if ($tipo=="new")
			$titolo="Nuova richiesta accettazione urgenza";

		if ($tipo=="alert")
			$titolo="Sollecito accettazione urgenza";
		
		try {
			$data["title"] = $titolo;
			

			Mail::send('emails.urgenza_notif', $data, function($message)use($data,$email) {
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
