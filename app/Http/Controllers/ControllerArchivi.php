<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\tipoc;
use App\Models\sicurezza;
use App\Models\societa;
use App\Models\centri_costo;
use App\Models\area_impiego;
use App\Models\mansione;
use App\Models\ccnl;
use DB;

class ControllerArchivi extends Controller
{

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


		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0 && $edit_elem==0) {
			$descr_contr=strtoupper($descr_contr);
			$arr=array();
			$arr['dele']=0;
			$arr['descrizione']=$descr_contr;
			DB::table("sicurezza")->insert($arr);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=strtoupper($descr_contr);
			sicurezza::where('id', $edit_elem)
			  ->update(['descrizione' => $descr_contr]);
		}
		if (strlen($dele_contr)!=0) {
			sicurezza::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			sicurezza::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		
		
		$sicurezza=DB::table('sicurezza')
		->when($view_dele=="0", function ($sicurezza) {
			return $sicurezza->where('dele', "=","0");
		})
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
