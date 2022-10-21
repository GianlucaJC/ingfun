<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\candidatis;
use App\Models\tipoc;
use App\Models\voci_doc;
use App\Models\societa;
use App\Models\centri_costo;
use App\Models\area_impiego;
use App\Models\mansione;
use App\Models\ccnl;
use App\Models\tipologia_contr;
use App\Models\tipo_doc;
use App\Models\ref_doc;

use DB;

class ControllerArchivi extends Controller
{

	public function documenti(){
		
		$id_cand=request()->input("id_cand");
		if (strlen($id_cand)==0) $id_cand=0;
		if (session('id_cand')) $id_cand=session('id_cand');

		if (request()->has("save_doc")) {
			$ref_doc = new ref_doc;
			$ref_doc->dele=0;
			$ref_doc->id_cand = $id_cand;
			$ref_doc->id_tipo_doc = request()->input('tipodoc');
			$ref_doc->id_sotto_tipo = request()->input('sottotipodoc');
			if (strlen(request()->input('scadenza')!=0))
				$ref_doc->scadenza = request()->input('scadenza');
			$ref_doc->nomefile = request()->input('allegato');
			$ref_doc->save();

			return redirect("/documenti")->with('status', 'Documento aggiunto con successo!')->with('id_cand',$id_cand);
			
		}
		
		$edit_elem=0;
		if (request()->has("edit_elem")) $edit_elem=request()->input("edit_elem");
		$view_dele=request()->input("view_dele");
		$descr_contr=request()->input("descr_contr");
		
		$dele_contr=request()->input("dele_contr");
		$restore_contr=request()->input("restore_contr");
		$tipodoc=request()->input("tipodoc");
		if (strlen($tipodoc)==0) $tipodoc=0;
		$sottotipodoc=request()->input("sottotipodoc");
		if (strlen($sottotipodoc)==0) $sottotipodoc=0;

		$allow_new="disabled";
		if ($tipodoc!=0 && $sottotipodoc!=0) $allow_new="";

		$tipo_doc=DB::table('tipo_doc')
		->where('dele', "=","0")
		->orderBy('descrizione')->get();

		
		$voci_doc=DB::table('voci_doc')
		->where('id_corso',"=",$tipodoc)
		->orderBy('descrizione')->get();

		$candidati=DB::table('candidatis')
		/*
		->when((strlen($id_cand)!=0), function ($candidati) use($id_cand) {
			return $candidati->where('id', "=",$id_cand);
		})
		*/		
		->orderBy('nominativo')->get();
		
		
		if (strlen($dele_contr)!=0) {
			session(['dele_doc' => 'Elemento rimosso con successo!']);
			ref_doc::where('id', $dele_contr)
			  ->delete();			
		}	
	
		
		$elenco_doc = DB::table('ref_doc as r')
		->join('tipo_doc as d', 'r.id_tipo_doc', '=', 'd.id')
		->join('voci_doc as v', 'r.id_sotto_tipo', '=', 'v.id')
		->select('r.id','r.id_cand','r.scadenza', 'r.nomefile', 'r.created_at', 'r.updated_at','d.descrizione as tipodocumento', 'v.descrizione as sottodocumento')
		->where('r.id_cand','=',$id_cand)
		->orderByDesc('r.id')
		->get();		
		

		
		return view('all_views/gestione/documenti')->with('tipo_doc',$tipo_doc)->with('voci_doc', $voci_doc)->with("view_dele",$view_dele)->with('tipodoc',$tipodoc)->with('sottotipodoc',$sottotipodoc)->with('allow_new',$allow_new)->with('candidati',$candidati)->with('id_cand',$id_cand)->with('elenco_doc',$elenco_doc);
		
	}

	public function sotto_tipo_documento(Request $request){
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$view_dele=$request->input("view_dele");
		$descr_contr=$request->input("descr_contr");
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");
		$tipodoc=$request->input("tipodoc");
		$tipodoc1=$request->input("tipodoc1");
		if (strlen($tipodoc)==0 && strlen($tipodoc1)!=0) $tipodoc=$tipodoc1;
		if (strlen($tipodoc)==0) $tipodoc=0;
		$tipodoc1=$tipodoc;

		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0 && $edit_elem==0) {
			$descr_contr=strtoupper($descr_contr);
			$arr=array();
			$arr['dele']=0;
			$arr['id_corso']=$tipodoc;
			$arr['descrizione']=$descr_contr;
			DB::table("voci_doc")->insert($arr);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=strtoupper($descr_contr);
			voci_doc::where('id', $edit_elem)
			  ->update(['descrizione' => $descr_contr]);
		}
		if (strlen($dele_contr)!=0) {
			voci_doc::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			voci_doc::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		
		
		$voci_doc=DB::table('voci_doc')
		->when($view_dele=="0", function ($voci_doc) {
			return $voci_doc->where('dele', "=","0");
		})
		->where('id_corso',"=",$tipodoc)
		->orderBy('descrizione')->get();


		$tipo_doc=DB::table('tipo_doc')
		->where('dele', "=","0")
		->orderBy('descrizione')->get();
		return view('all_views/gestione/sotto_tipo_doc')->with('tipo_doc',$tipo_doc)->with('sotto_tipo_doc', $voci_doc)->with("view_dele",$view_dele)->with('tipodoc',$tipodoc)->with('tipodoc1',$tipodoc1);
		
	}

	public function tipo_documento(Request $request){
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$view_dele=$request->input("view_dele");
		$descr_contr=$request->input("descr_contr");
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");


		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0 && $edit_elem==0) {
			$descr_contr=strtoupper($descr_contr);
			$arr=array();
			$arr['dele']=0;
			$arr['descrizione']=$descr_contr;
			DB::table("tipo_doc")->insert($arr);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=strtoupper($descr_contr);
			tipo_doc::where('id', $edit_elem)
			  ->update(['descrizione' => $descr_contr]);
		}
		if (strlen($dele_contr)!=0) {
			tipo_doc::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			tipo_doc::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		
		
		$tipo_doc=DB::table('tipo_doc')
		->when($view_dele=="0", function ($tipo_doc) {
			return $tipo_doc->where('dele', "=","0");
		})
		->orderBy('descrizione')->get();

		return view('all_views/gestione/tipo_doc')->with('tipo_doc', $tipo_doc)->with("view_dele",$view_dele);
		
	}
	public function tipologia_contr(Request $request){
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$view_dele=$request->input("view_dele");
		$descr_contr=$request->input("descr_contr");
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");


		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0 && $edit_elem==0) {
			$descr_contr=strtoupper($descr_contr);
			$arr=array();
			$arr['dele']=0;
			$arr['descrizione']=$descr_contr;
			DB::table("tipologia_contr")->insert($arr);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=strtoupper($descr_contr);
			tipologia_contr::where('id', $edit_elem)
			  ->update(['descrizione' => $descr_contr]);
		}
		if (strlen($dele_contr)!=0) {
			tipologia_contr::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			tipologia_contr::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		
		
		$tipologia_contr=DB::table('tipologia_contr')
		->when($view_dele=="0", function ($tipologia_contr) {
			return $tipologia_contr->where('dele', "=","0");
		})
		->orderBy('descrizione')->get();

		return view('all_views/gestione/tipologia_contr')->with('tipologia_contr', $tipologia_contr)->with("view_dele",$view_dele);
		
	}

	public function ccnl(Request $request){
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$view_dele=$request->input("view_dele");
		$descr_contr=$request->input("descr_contr");
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");


		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0 && $edit_elem==0) {
			$descr_contr=strtoupper($descr_contr);
			$arr=array();
			$arr['dele']=0;
			$arr['descrizione']=$descr_contr;
			DB::table("ccnl")->insert($arr);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=strtoupper($descr_contr);
			ccnl::where('id', $edit_elem)
			  ->update(['descrizione' => $descr_contr]);
		}
		if (strlen($dele_contr)!=0) {
			ccnl::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			ccnl::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		
		
		$ccnl=DB::table('ccnl')
		->when($view_dele=="0", function ($ccnl) {
			return $ccnl->where('dele', "=","0");
		})
		->orderBy('descrizione')->get();

		return view('all_views/gestione/ccnl')->with('ccnl', $ccnl)->with("view_dele",$view_dele);
		
	}

	public function mansione(Request $request){
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$view_dele=$request->input("view_dele");
		$descr_contr=$request->input("descr_contr");
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");


		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0 && $edit_elem==0) {
			$descr_contr=strtoupper($descr_contr);
			$arr=array();
			$arr['dele']=0;
			$arr['descrizione']=$descr_contr;
			DB::table("mansione")->insert($arr);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=strtoupper($descr_contr);
			mansione::where('id', $edit_elem)
			  ->update(['descrizione' => $descr_contr]);
		}
		if (strlen($dele_contr)!=0) {
			mansione::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			mansione::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		
		
		$mansione=DB::table('mansione')
		->when($view_dele=="0", function ($mansione) {
			return $mansione->where('dele', "=","0");
		})
		->orderBy('descrizione')->get();

		return view('all_views/gestione/mansione')->with('mansione', $mansione)->with("view_dele",$view_dele);
		
	}

	public function area_impiego(Request $request){
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$view_dele=$request->input("view_dele");
		$descr_contr=$request->input("descr_contr");
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");


		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0 && $edit_elem==0) {
			$descr_contr=strtoupper($descr_contr);
			$arr=array();
			$arr['dele']=0;
			$arr['descrizione']=$descr_contr;
			DB::table("area_impiego")->insert($arr);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=strtoupper($descr_contr);
			area_impiego::where('id', $edit_elem)
			  ->update(['descrizione' => $descr_contr]);
		}
		if (strlen($dele_contr)!=0) {
			area_impiego::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			area_impiego::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		
		
		$area_impiego=DB::table('area_impiego')
		->when($view_dele=="0", function ($area_impiego) {
			return $area_impiego->where('dele', "=","0");
		})
		->orderBy('descrizione')->get();

		return view('all_views/gestione/area_impiego')->with('area_impiego', $area_impiego)->with("view_dele",$view_dele);
		
	}

	public function costo(Request $request){
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$view_dele=$request->input("view_dele");
		$descr_contr=$request->input("descr_contr");
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");


		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0 && $edit_elem==0) {
			$descr_contr=strtoupper($descr_contr);
			$arr=array();
			$arr['dele']=0;
			$arr['descrizione']=$descr_contr;
			DB::table("centri_costo")->insert($arr);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=strtoupper($descr_contr);
			centri_costo::where('id', $edit_elem)
			  ->update(['descrizione' => $descr_contr]);
		}
		if (strlen($dele_contr)!=0) {
			centri_costo::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			centri_costo::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		
		
		$centri_costo=DB::table('centri_costo')
		->when($view_dele=="0", function ($centri_costo) {
			return $centri_costo->where('dele', "=","0");
		})
		->orderBy('descrizione')->get();

		return view('all_views/gestione/costo')->with('centri_costo', $centri_costo)->with("view_dele",$view_dele);
		
	}
	public function societa_assunzione(Request $request){
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$view_dele=$request->input("view_dele");
		$descr_contr=$request->input("descr_contr");
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");


		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0 && $edit_elem==0) {
			$descr_contr=strtoupper($descr_contr);
			$arr=array();
			$arr['dele']=0;
			$arr['descrizione']=$descr_contr;
			DB::table("societa")->insert($arr);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=strtoupper($descr_contr);
			societa::where('id', $edit_elem)
			  ->update(['descrizione' => $descr_contr]);
		}
		if (strlen($dele_contr)!=0) {
			societa::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			societa::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		
		
		$societa=DB::table('societa')
		->when($view_dele=="0", function ($societa) {
			return $societa->where('dele', "=","0");
		})
		->orderBy('descrizione')->get();

		return view('all_views/gestione/societa')->with('societa', $societa)->with("view_dele",$view_dele);
		
	}

	public function frm_attestati(Request $request){
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$view_dele=$request->input("view_dele");
		$descr_contr=$request->input("descr_contr");
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");

		//ho messo un riferimento statico al codice corso di sicurezza (4)
		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0 && $edit_elem==0) {
			$descr_contr=strtoupper($descr_contr);
			$arr=array();
			$arr['dele']=0;
			$arr['id_corso']=4;
			$arr['descrizione']=$descr_contr;
			DB::table("voci_doc")->insert($arr);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=strtoupper($descr_contr);
			voci_doc::where('id', $edit_elem)
			  ->update(['descrizione' => $descr_contr]);
		}
		if (strlen($dele_contr)!=0) {
			voci_doc::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			voci_doc::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		
		
		$sicurezza=DB::table('voci_doc')
		->when($view_dele=="0", function ($sicurezza) {
			//ho messo un riferimento statico al codice corso di sicurezza (4)
			return $sicurezza->where('dele', "=","0");
		})
		->where('id_corso','=',4)
		->orderBy('descrizione')->get();

		return view('all_views/gestione/sicurezza')->with('sicurezza', $sicurezza)->with("view_dele",$view_dele);
		
	}

	public function tipo_contratto(Request $request){
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$view_dele=$request->input("view_dele");
		$descr_contr=$request->input("descr_contr");
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");

		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0 && $edit_elem==0) {
			$descr_contr=strtoupper($descr_contr);
			$arr=array();
			$arr['dele']=0;
			$arr['descrizione']=$descr_contr;
			DB::table("tipoc")->insert($arr);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=strtoupper($descr_contr);
			tipoc::where('id', $edit_elem)
			  ->update(['descrizione' => $descr_contr]);
		}
		if (strlen($dele_contr)!=0) {
			tipoc::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			tipoc::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		$tipoc=DB::table('tipoc')
		->when($view_dele=="0", function ($tipoc) {
			return $tipoc->where('dele', "=","0");
		})
		->orderBy('descrizione')->get();

		return view('all_views/gestione/tipocontratto')->with('tipoc', $tipoc)->with("view_dele",$view_dele);

	}


}
