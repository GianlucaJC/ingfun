<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\servizi;
use App\Models\serviziapp;
use App\Models\appalti;
use App\Models\ditte;
use App\Models\lavoratori;
use App\Models\lavoratoriapp;
use App\Models\italy_cities;


use DB;

class ControllerServizi extends Controller
{

	public function save_edit(Request $request) {
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
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
		$descr_contr=strtoupper($descr_contr);
		$cap=strtoupper($cap);
		$cf=strtoupper($cf);
		$comune=strtoupper($comune);
		$provincia=strtoupper($provincia);		
		
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");
		
		$data=['dele'=>0, 'denominazione' => $descr_contr,'cap' => $cap,'comune' => $comune,'provincia' => $provincia,'piva' => $piva,'cf' => $cf,'email' => $email,'pec' => $pec,'telefono' => $telefono,'fax' => $fax];

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
		
		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		$ditte=DB::table('ditte as d')
		->select("*")
		->when($view_dele=="0", function ($ditte) {
			return $ditte->where('d.dele', "=","0");
		})
		->orderBy('denominazione')->get();

		$lavoratori=DB::table('lavoratori as l')
		->select('l.*')
		->orderBy('nominativo')
		->get();		

		$info_lav=array();
		foreach($lavoratori as $lavoratore) {
			$id_ditta=$lavoratore->id_ditta;
			$info_lav[$id_ditta][]=$lavoratore;
		}

		return view('all_views/gestioneservizi/ditte')->with('view_dele',$view_dele)->with('ditte', $ditte)->with('all_comuni',$all_comuni)->with('info_lav',$info_lav);		
	}	


	public function lavoratori(Request $request){
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		
		$view_dele=$request->input("view_dele");
		$ditta=$request->input("ditta");
		if ($request->has("old_ditta")) $ditta=$request->input("old_ditta");

		$cognome=$request->input("cognome");
		$cognome=strtoupper($cognome);
		$nome=$request->input("nome");
		$nome=strtoupper($nome);
		$nominativo=$cognome." ".$nome;
		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;

		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");
		
		$data=['dele'=>0, 'id_ditta'=>$ditta, 'cognome' => $cognome,'nome' => $nome,'nominativo' => $nominativo];

		//Creazione nuovo elemento
		if (strlen($cognome)!=0 && $edit_elem==0) {
			DB::table("lavoratori")->insert($data);
		}
		
		//Modifica elemento
		if (strlen($cognome)!=0 && $edit_elem!=0) {
			lavoratori::where('id', $edit_elem)			
			  ->update($data);
		}
		if (strlen($dele_contr)!=0) {
			lavoratori::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			lavoratori::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		
		$ditte=DB::table('ditte as d')
		->select("*")
		->orderBy('denominazione')
		->get();

		$lavoratori=DB::table('lavoratori as l')
		->select('l.*')
		->where('l.id_ditta','=',$ditta)
		->when($view_dele=="0", function ($lavoratori) {
			return $lavoratori->where('l.dele', "=","0");
		})
		->orderBy('nominativo')
		->get();		



		return view('all_views/gestioneservizi/lavoratori')->with('view_dele',$view_dele)->with('ditte', $ditte)->with('lavoratori',$lavoratori)->with('ditta',$ditta);		
	}

	public function servizi(Request $request){
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$view_dele=$request->input("view_dele");
		$descr_contr=$request->input("descr_contr");
		$importo=$request->input("importo");
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");

		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0 && $edit_elem==0) {
			$descr_contr=strtoupper($descr_contr);
			$arr=array();
			$arr['dele']=0;
			$arr['descrizione']=$descr_contr;
			$arr['importo']=$importo;
			DB::table("servizi")->insert($arr);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=strtoupper($descr_contr);
			
			servizi::where('id', $edit_elem)
			  ->update(['descrizione' => $descr_contr,'importo' => $importo]);
		}
		if (strlen($dele_contr)!=0) {
			servizi::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			servizi::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		$servizi=DB::table('servizi')
		->when($view_dele=="0", function ($servizi) {
			return $servizi->where('dele', "=","0");
		})
		->orderBy('descrizione')->get();

		return view('all_views/gestioneservizi/servizi')->with('servizi', $servizi)->with("view_dele",$view_dele);

	}
	
	public function listapp(Request $request) {
		$dx=date("Y-m-d");
		
		$view_dele=0;
		if ($request->has("view_dele")) $view_dele=$request->input("view_dele");
		if ($view_dele=="on") $view_dele=1;

		$restore_cand=$request->input("restore_cand");
		$dele_cand=$request->input("dele_cand");

		
		if (strlen($dele_cand)!=0) {
			appalti::where('id', $dele_cand)
			  ->update(['dele' => 1]);			
		}		
		if (strlen($restore_cand)!=0) {
			appalti::where('id', $restore_cand)
			  ->update(['dele' => 0]);			
		}

		$gestione=appalti::select('appalti.id','appalti.dele','appalti.descrizione_appalto','appalti.data_ref','appalti.id_ditta','d.denominazione')
		->join('ditte as d', 'd.id','=','appalti.id_ditta')
		->when($view_dele=="0", function ($gestione) {
			return $gestione->where('appalti.dele', "=","0");
		})
		->orderByDesc('appalti.id')	
		->get();		
		

		return view('all_views/listappalti')->with('view_dele',$view_dele)->with('gestione',$gestione);

	}
	
	public function save_newapp(Request $request) {			
		$id_app=$request->input('id_app');
		if ($id_app!=0)
			$appalti = appalti::find($id_app);
		else
			$appalti = new appalti;
	

		$appalti->descrizione_appalto = $request->input('descrizione_appalto');
		$appalti->data_ref = $request->input('data_app');
		$appalti->id_ditta = $request->input('ditta');
		$appalti->note = $request->input('note');
		$appalti->save();
		if ($id_app==0) $id_app=$appalti->id;

		$deleted = serviziapp::where('id_appalto', $id_app)->delete();
		$servizi=$request->input('servizi');
		for ($sca=0;$sca<=count($servizi)-1;$sca++) {
			DB::table('serviziapp')->insert([
				'id_appalto' => $id_app,
				'id_servizio' => $servizi[$sca],
				'created_at'=>now(),
				'updated_at'=>now()
			]);			
		}
		
		$deleted = lavoratoriapp::where('id_appalto', $id_app)->delete();
		$lavoratori=$request->input('lavoratori');
		for ($sca=0;$sca<=count($lavoratori)-1;$sca++) {
			DB::table('lavoratoriapp')->insert([
				'id_appalto' => $id_app,
				'id_ditta_ref' => $request->input('ditta'),
				'id_lav_ref' => $lavoratori[$sca],
				'created_at'=>now(),
				'updated_at'=>now()
			]);			
		}
		
		return redirect()->route("newapp",['id'=>$id_app,'from'=>1]);

	}	

	
	public function newapp($id=0,$from=0) {
		$appalti=array();
		$id_servizi=array();$ids_lav=array();
		$view_dele="0";
		
		if ($id!=0) {
			$view_dele="1";
			$appalti=DB::table('appalti AS a')
			->select('a.*','sa.id_servizio','la.id_lav_ref','l.nominativo')
			->join('serviziapp as sa','a.id','sa.id_appalto')
			->join('lavoratoriapp as la','a.id','la.id_appalto')
			->join('lavoratori as l','la.id_lav_ref','l.id')
			->where('a.id', "=", $id)
			->get();
			
			//->toSql() - dd($appalti);exit;
			
			foreach($appalti as $appalto) {
				if (!in_array($appalto->id_servizio,$id_servizi)) 
					$id_servizi[]=$appalto->id_servizio;
				if (!in_array($appalto->id_lav_ref,$ids_lav))  
					$ids_lav[$appalto->id_lav_ref]=$appalto->nominativo;
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
		
		return view('all_views/newapp')->with("appalti",$appalti)->with("ditte",$ditte)->with("servizi",$servizi)->with('id_app',$id)->with('id_servizi',$id_servizi)->with('id_servizi',$id_servizi)->with('ids_lav',$ids_lav);

	}

}
