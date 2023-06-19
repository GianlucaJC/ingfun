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

use DB;

class ControllerServizi extends Controller
{
public function __construct()
	{
		$this->middleware('auth')->except(['index']);
	}		

	public function save_edit(Request $request) {
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$azienda_prop=$request->input("azienda_prop");
		$descr_contr=$request->input("descr_contr");
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
		$cap=strtoupper($cap);
		$cf=strtoupper($cf);
		$comune=strtoupper($comune);
		$provincia=strtoupper($provincia);		
		
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");
		
		$data=['id_azienda_prop'=>$azienda_prop,'dele'=>0, 'denominazione' => $descr_contr,'cap' => $cap,'comune' => $comune,'provincia' => $provincia,'piva' => $piva,'cf' => $cf,'email' => $email,'pec' => $pec,'telefono' => $telefono,'fax' => $fax, 'sdi'=>$sdi,'tipo_pagamento'=>$str_pagamento];

		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0 && $edit_elem==0) {
			$descr_contr=strtoupper($descr_contr);
			DB::table("ditte")->insert($data);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			ditte::where('id', $edit_elem)			
			  ->update($data);
		}
		if (strlen($dele_contr)!=0) {
			ditte::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			ditte::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
	
	}

	public function ditte(Request $request){
		//check save/edit
		$this->save_edit($request);
			
		$view_dele=$request->input("view_dele");
		$all_comuni = italy_cities::orderBy('comune')->get();
		
		$aziende_prop=DB::table('societa as s')
		->select('s.id','s.descrizione as azienda_prop')
		->where('s.dele','=',0)
		->orderBy('s.descrizione')
		->get();
		
		$lista_pagamenti=$this->lista_pagamenti();

		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		$ditte=DB::table('ditte as d')
		->join('societa as s','d.id_azienda_prop','s.id')
		->select("d.id","d.dele","d.denominazione","s.descrizione as azienda_prop")
		->when($view_dele=="0", function ($ditte) {
			return $ditte->where('d.dele', "=","0");
		})
		->orderBy('denominazione')->get();


		return view('all_views/gestioneservizi/ditte')->with('view_dele',$view_dele)->with('ditte', $ditte)->with('all_comuni',$all_comuni)->with('aziende_prop',$aziende_prop)->with('lista_pagamenti',$lista_pagamenti);		
	}	


	public function save_edit_servizi_ditte(Request $request) {
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$save_ds=$request->input("save_ds");
		$esito_saveds=0;
		if ($save_ds=="1" && strlen($request->input("importo"))!=0) {
			$ditta_ref=$request->input("ditta_ref");
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
			$data=['dele'=>0, 'id_ditta' => $ditta_ref,'id_servizio' => $service,'importo_ditta' => $importo,'aliquota' => $aliquota,'importo_lavoratore' => $importo_lavoratore];			

			$esito_saveds=1;		
			$check=DB::table('servizi_ditte as s')
			->where('s.dele', "=","0")
			->where('s.id_ditta',"=",$ditta_ref)
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
		//if ($id_ref!=0) $ditta_ref=$id_ref;
		if (request()->has("id_ref")) $ditta_ref=request()->input("id_ref");
		
		$ditta_from_frm1=$request->input("ditta_from_frm1");
		if (strlen($ditta_from_frm1)!=0) $ditta_ref=$ditta_from_frm1;
		
		$service=$request->input("service");
		if (strlen($ditta_ref)==0) $ditta_ref=0;
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
		})
		->where('d.id_ditta','=',$ditta_ref)
		->orderBy('s.descrizione')->get();

		$azienda_prop=DB::table('societa as s')
		->join('ditte as d','s.id','d.id_azienda_prop')
		->select('s.id','s.descrizione as azienda_prop')
		->where('d.id','=',$ditta_ref)
		->get();
		$azienda=array();
		if (isset($azienda_prop[0])) $azienda=$azienda_prop[0];
			
		
		
		$servizi=DB::table('servizi as s')
		->where('s.dele', "=","0")
		->orderBy('s.descrizione')->get();

		$ditte=DB::table('ditte as d')
		->select("*")
		->where('d.dele', "=","0")
		->orderBy('d.denominazione')
		->get();
		
		$aliquote_iva=aliquote_iva::select('id','aliquota','descrizione')
		->get();		
		$arr_aliquota=array();
		foreach ($aliquote_iva as $aliquota) {
			if (isset($aliquota->id))
				$arr_aliquota[$aliquota->id]=$aliquota->aliquota;
		}

		return view('all_views/gestioneservizi/servizi')->with('servizi_ditte', $servizi_ditte)->with('ditte',$ditte)->with("view_dele",$view_dele)->with('ditta_ref',$ditta_ref)->with('servizi',$servizi)->with('service',$service)->with('esito_saveds',$esito_saveds)->with('aliquote_iva',$aliquote_iva)->with('arr_aliquota',$arr_aliquota)->with('azienda',$azienda);

	}


	public function save_edit_servizi($request) {
		$esito_saves=0;
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$descr_contr=$request->input("descr_contr");
		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0 && $edit_elem==0) {			
			$descr_contr=strtoupper($descr_contr);
			$data=['dele'=>0, 'descrizione' => $descr_contr];	
			DB::table("servizi")->insert($data);
			$esito_saves=1;
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=strtoupper($descr_contr);
			$data=['dele'=>0, 'descrizione' => $descr_contr];
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
