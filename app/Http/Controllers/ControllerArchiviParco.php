<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\user;
use App\Models\mezzi;
use App\Models\parco_carta_carburante;
use App\Models\parco_marca_mezzo;
use App\Models\parco_modello_mezzo;
use DB;

class ControllerArchiviParco extends Controller
{
	public function __construct() {
		$this->middleware('auth')->except(['index']);
	}
	
	public function edit_save_marca($request) {
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
			$arr['marca']=$descr_contr;
			DB::table("parco_marca_mezzo")->insert($arr);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=strtoupper($descr_contr);
			parco_marca_mezzo::where('id', $edit_elem)
			  ->update(['marca' => $descr_contr]);
		}
		if (strlen($dele_contr)!=0) {
			parco_marca_mezzo::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			parco_marca_mezzo::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		

				
	}
	
	public function marca(Request $request) {
		$this->edit_save_marca($request);
		$view_dele=$request->input("view_dele");
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;		
		$marche=DB::table('parco_marca_mezzo')
		->when($view_dele=="0", function ($marca) {
			return $marca->where('dele', "=","0");
		})
		->orderBy('marca')->get();

		return view('all_views/parco/marca')->with('marche', $marche)->with("view_dele",$view_dele);		
	}


	public function edit_save_modello($request) {
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$view_dele=$request->input("view_dele");
		$marche=$request->input("marche");
		$descr_contr=$request->input("descr_contr");
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");

		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0 && $edit_elem==0) {
			$descr_contr=strtoupper($descr_contr);
			$arr=array();
			$arr['dele']=0;
			$arr['id_marca']=$marche;
			$arr['modello']=$descr_contr;
			DB::table("parco_modello_mezzo")->insert($arr);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=strtoupper($descr_contr);
			parco_modello_mezzo::where('id', $edit_elem)
			  ->update(['modello' => $descr_contr,'id_marca' => $marche]);
		}
		if (strlen($dele_contr)!=0) {
			parco_modello_mezzo::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			parco_modello_mezzo::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		

				
	}
	
	public function modello(Request $request) {
		$this->edit_save_modello($request);
		$view_dele=$request->input("view_dele");
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;		
		$modelli=DB::table('parco_modello_mezzo as m')
		->join('parco_marca_mezzo as ma','m.id_marca','ma.id')
		->select('m.dele','m.id','m.modello','ma.id as id_marca','ma.marca')
		->when($view_dele=="0", function ($modelli) {
			return $modelli->where('m.dele', "=","0");
		})
		->orderBy('ma.marca')
		->orderBy('m.modello')
		->get();

		$marche=DB::table('parco_marca_mezzo')
		->where('dele', "=","0")
		->orderBy('marca')->get();
		
		$data=array("modelli"=>$modelli,"marche"=>$marche,"view_dele"=>$view_dele);
		
		return view('all_views/parco/modello')->with($data);		
	}


}

