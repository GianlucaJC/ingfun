<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\servizi;
use App\Models\serviziapp;
use App\Models\appalti;
use App\Models\ditte;
use App\Models\lavoratoriapp;
use App\Models\italy_cities;
use App\Models\candidati;
use App\Models\servizi_ditte;
use App\Models\user;
use App\Models\mezzi;
use App\Models\aliquote_iva;
use App\Models\societa;

use DB;

class ControllerServizi extends Controller
{
public function __construct()
	{
		//$this->middleware('auth')->except(['index']);
	}		


	public function save_edit_sezionali(Request $request) {
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$descr_contr=$request->input("descr_contr");
		$mail_scadenze=$request->input("mail_scadenze");
		$mail_fatture=$request->input("mail_fatture");
		$mail_pec=$request->input("mail_pec");
		$mail_azienda=$request->input("mail_azienda");
		$telefono=$request->input("telefono");
		$mail_scadenze=strtolower($mail_scadenze);
		$mail_fatture=strtolower($mail_fatture);
		
		$descr_contr=strtoupper($descr_contr);
		
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");
		
		
		$data=['dele'=>0, 'descrizione' => $descr_contr,'mail_scadenze'=>$mail_scadenze,'mail_fatture'=>$mail_fatture,'mail_pec'=>$mail_pec,'mail_azienda'=>$mail_azienda,'telefono'=>$telefono];
		

		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0 && $edit_elem==0) {
			DB::table("societa")->insert($data);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			societa::where('id', $edit_elem)			
			  ->update($data);
		}
		if (strlen($dele_contr)!=0) {
			societa::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			societa::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
	
	}

	public function sezionali(Request $request){
		//check save/edit
		$this->save_edit_sezionali($request);
			
		$view_dele=$request->input("view_dele");
		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		
		$sezionali=DB::table('societa as s')
		->select('s.dele','s.id','s.descrizione','s.mail_scadenze','s.mail_fatture','s.mail_azienda','s.mail_pec','s.telefono')
		->when($view_dele=="0", function ($sezionali) {
			return $sezionali->where('s.dele', "=","0");
		})
		->orderBy('s.descrizione')
		->get();

		
		return view('all_views/gestioneservizi/sezionali')->with('view_dele',$view_dele)->with('sezionali', $sezionali);		
	}



	public function save_edit(Request $request) {
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		
		$pf_pi=$request->input("pf_pi");
		$descr_contr=$request->input("descr_contr");
		$cognome=$request->input("cognome");
		$nome=$request->input("nome");
		$cap=$request->input("cap");
		$comune=$request->input("comune");
		$provincia=$request->input("provincia");
		$piva=$request->input("piva");
		$cf=$request->input("cf");
		$email=$request->input("email");
		$pec=$request->input("pec");
		$telefono=$request->input("telefono");
		$fax=$request->input("fax");
		$sdi=$request->input("sdi");
		$tipo_pagamento=$request->input("tipo_pagamento");
		$str_pagamento=null;
		if (is_array($tipo_pagamento))
			$str_pagamento=implode(";",$tipo_pagamento);

		
		$descr_contr=strtoupper($descr_contr);
		$descr_contr=strtoupper($descr_contr);
		$cap=strtoupper($cap);
		$cf=strtoupper($cf);
		$comune=strtoupper($comune);
		$provincia=strtoupper($provincia);		
		
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");
		
		$data=['dele'=>0,'pf_pi'=>$pf_pi, 'denominazione' => $descr_contr,'cognome'=>$cognome,'nome'=>$nome,'cap' => $cap,'comune' => $comune,'provincia' => $provincia,'piva' => $piva,'cf' => $cf,'email' => $email,'pec' => $pec,'telefono' => $telefono,'fax' => $fax, 'sdi'=>$sdi,'tipo_pagamento'=>$str_pagamento];

		$id_ref=0;
		//Creazione nuovo elemento
		if ((strlen($descr_contr)!=0 || $cognome!=0) && $edit_elem==0) {
			ditte::insert($data);
			$id_ref = DB::getPdo()->lastInsertId();
		}
		
		//Modifica elemento
		if ((strlen($descr_contr)!=0 || $cognome!=0) && $edit_elem!=0) {
			ditte::where('id', $edit_elem)			
			  ->update($data);
			 $id_ref=$edit_elem;
		}
		if (strlen($dele_contr)!=0) {
			ditte::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			ditte::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}
		return $id_ref;
	}

	public function ditte(Request $request){
		//check save/edit
		$id_ref=$this->save_edit($request);
			
		$refr=$request->input("refr");
		if ($id_ref!=0) $refr=$id_ref;
		
		$view_dele=$request->input("view_dele");
		$all_comuni = italy_cities::orderBy('comune')->get();

		
		$lista_pagamenti=$this->lista_pagamenti();

		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		$ditte=DB::table('ditte as d')
		->select("d.id","d.dele","d.denominazione","d.cognome","d.nome")
		->when($view_dele=="0", function ($ditte) {
			return $ditte->where('d.dele', "=","0");
		})
		->orderBy('denominazione')->get();


		return view('all_views/gestioneservizi/ditte')->with('view_dele',$view_dele)->with('ditte', $ditte)->with('all_comuni',$all_comuni)->with('lista_pagamenti',$lista_pagamenti)->with('refr',$refr);		
	}	


	public function save_edit_servizi_ditte(Request $request) {
		/*
			N.B.: $ditta_ref essendo derivante da una select multiple, è un'array e quindi prendo solo il primo elemento (via js non permetto la modifica o l'inserimento di un servizio riferito a più ditte)
		*/
		$ditta_ref=$request->input("ditta_ref");
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$save_ds=$request->input("save_ds");
		$esito_saveds=0;
		if ($save_ds=="1" && strlen($request->input("importo"))!=0) {
			$service=$request->input("service");
			$importo=$request->input("importo");
			$aliquota=$request->input("aliquota");
			$importo_lavoratore=$request->input("importo_lavoratore");
		}

		if ($save_ds=="1" && $edit_elem!=0 && strlen($request->input("importo"))!=0) {
			$data=['dele'=>0, 'importo_ditta' => $importo,'aliquota' => $aliquota,'importo_lavoratore' => $importo_lavoratore];			

			$esito_saveds=3;
			$up=DB::table('servizi_ditte')
			->where('id', $edit_elem)
			->update($data);
		}			
		if ($save_ds=="1" && $edit_elem==0 && strlen($request->input("importo"))!=0) {
			$d_ref=$ditta_ref[0];
			$data=['dele'=>0, 'id_ditta' => $d_ref,'id_servizio' => $service,'importo_ditta' => $importo,'aliquota' => $aliquota,'importo_lavoratore' => $importo_lavoratore];			

			$esito_saveds=1;		
			$check=DB::table('servizi_ditte as s')
			->where('s.dele', "=","0")
			->where('s.id_ditta',"=",$d_ref)
			->where('s.id_servizio',"=",$service)
			->count();
			if ($check==0) {
				DB::table("servizi_ditte")->insert($data); 
			} else $esito_saveds=2;	
		}
		return $esito_saveds;
	}

	public function servizi($id_ref=0){
		$request=request();
		$esito_saveds=$this->save_edit_servizi_ditte($request);
		
		$ditta_ref=$request->input("ditta_ref");
		if (request()->has("id_ref")) {
			$ditta_ref[0]=request()->input("id_ref");
		}	
		if ($id_ref!=0) {
			$ditta_ref[0]=$id_ref;
		}
		
		$ditta_from_frm1=$request->input("ditta_from_frm1");
		if (strlen($ditta_from_frm1)!=0) {
			$ditta_ref[0]=$ditta_from_frm1;
		}	
		
		
		$service=$request->input("service");


		$view_dele=$request->input("view_dele");
		$descr_contr=$request->input("descr_contr");
		
		$dele_ds=$request->input("dele_ds");
		$restore_contr=$request->input("restore_contr");
		
		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0) {
			$descr_contr=strtoupper($descr_contr);
			$arr=array();
			$arr['dele']=0;
			$arr['descrizione']=$descr_contr;
			
			DB::table("servizi")->insert($arr);
		}
		
		//Modifica elemento
		/*
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=strtoupper($descr_contr);
			
			servizi::where('id', $edit_elem)
			  ->update(['descrizione' => $descr_contr]);
		}
		*/
		if (strlen($dele_ds)!=0) {
			servizi_ditte::where('id', $dele_ds)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			servizi_ditte::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;

		$servizi_ditte=DB::table('servizi as s')
		->select("d.id","d.dele","d.id_ditta","d.id_servizio","s.descrizione","d.importo_ditta","d.aliquota","d.importo_lavoratore")
		->join('servizi_ditte as d','s.id','d.id_servizio')
		->when($view_dele=="0", function ($servizi) {
			return $servizi->where('d.dele', "=","0");
		});

		$arr_ditta=$ditta_ref;
		if (!is_array($ditta_ref)) {
			$arr_ditta=array();
			if (strlen($ditta_ref)!=0) $arr_ditta[0]=$ditta_ref;
			else $arr_ditta=array();
			$servizi_ditte=$servizi_ditte->where('d.id','=',0);
		}
		
		$servizi_ditte=$servizi_ditte->where(function ($query) use ($arr_ditta)  {
			for ($sca=0;$sca<count($arr_ditta);$sca++) {
				$d_ref=$arr_ditta[$sca];
				if ($sca==0) 
					$query->where('d.id_ditta','=',$d_ref);
				else
					$query->orWhere('d.id_ditta','=',$d_ref);
			}
		});	
		
		$servizi_ditte=$servizi_ditte->orderBy('s.descrizione');
		$servizi_ditte=$servizi_ditte->get();
		
		if (is_array($ditta_ref) && count($ditta_ref)==1) {
			$azienda_prop=DB::table('societa as s')
			->join('ditte as d','s.id','d.id_azienda_prop')
			->select('s.id','s.descrizione as azienda_prop')
			->where('d.id','=',$ditta_ref)
			->get();
		}
		$azienda=null;
		if (isset($azienda_prop[0])) $azienda=$azienda_prop[0];
			
		
		
		$servizi=DB::table('servizi as s')
		->where('s.dele', "=","0")
		->orderBy('s.descrizione')->get();



		$ditte=DB::table('ditte as d')
		->select("*")
		->where('d.dele', "=","0")
		->orderBy('d.denominazione')
		->get();
		
		$info_d=array();
		foreach ($ditte as $arr_d) {
			$info_d[$arr_d->id]=$arr_d->denominazione;
		}
		
		$aliquote_iva=aliquote_iva::select('id','aliquota','descrizione')
		->get();		
		$arr_aliquota=array();
		foreach ($aliquote_iva as $aliquota) {
			if (isset($aliquota->id))
				$arr_aliquota[$aliquota->id]=$aliquota->aliquota;
		}

		$ditta_ref=$arr_ditta;
		

		return view('all_views/gestioneservizi/servizi')->with('servizi_ditte', $servizi_ditte)->with('ditte',$ditte)->with("view_dele",$view_dele)->with('ditta_ref',$ditta_ref)->with('servizi',$servizi)->with('service',$service)->with('esito_saveds',$esito_saveds)->with('aliquote_iva',$aliquote_iva)->with('arr_aliquota',$arr_aliquota)->with('azienda',$azienda)->with('info_d',$info_d);

	}


	public function save_edit_servizi($request) {
		$esito_saves=0;
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$descr_contr=$request->input("descr_contr");
		$acronimo=$request->input("acronimo");
		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0 && $edit_elem==0) {			
			$descr_contr=strtoupper($descr_contr);
			$data=['dele'=>0, 'descrizione' => $descr_contr, 'acronimo' => $acronimo];	
			DB::table("servizi")->insert($data);
			$esito_saves=1;
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=strtoupper($descr_contr);
			$data=['dele'=>0, 'descrizione' => $descr_contr, 'acronimo' => $acronimo];
			servizi::where('id', $edit_elem)			
			  ->update($data);
			 $esito_saves=2;
		}		
		
		$dele_contr=$request->input("dele_contr");
		if (strlen($dele_contr)!=0) {
			servizi::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}		
		$restore_contr=$request->input("restore_contr");		
		if (strlen($restore_contr)!=0) {
			servizi::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}
		return $esito_saves;
	}

	public function gestione_servizi(Request $request) {


		$esito_saves=$this->save_edit_servizi($request);
		
		$view_dele=$request->input("view_dele");
		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		$servizi=DB::table('servizi')
		->select("*")
		->when($view_dele=="0", function ($servizi) {
			return $servizi->where('dele', "=","0");
		})
		->orderBy('descrizione')->get();

		return view('all_views/gestioneservizi/gestione_servizi')->with('servizi', $servizi)->with("view_dele",$view_dele)->with('esito_saves',$esito_saves);

		
	}
	
	function lista_pagamenti() {
		$lista=array();
		$lista[0]['id']=1;
		$lista[0]['descrizione']="Contanti";
		$lista[1]['id']=2;
		$lista[1]['descrizione']="Bancomat";
		$lista[2]['id']=3;
		$lista[2]['descrizione']="Assegno";
		$lista[3]['id']=4;
		$lista[3]['descrizione']="Bonifico";
		return $lista;
	}	
	

	



}
