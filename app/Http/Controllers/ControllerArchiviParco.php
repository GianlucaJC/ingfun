<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\user;
use App\Models\mezzi;
use App\Models\parco_carta_carburante;
use App\Models\parco_marca_mezzo;
use App\Models\parco_modello_mezzo;
use App\Models\parco_badge_cisterna;
use App\Models\parco_telepass;
use App\Models\parco_servizi_noleggio;

use DB;

class ControllerArchiviParco extends Controller
{
	public function __construct() {
		$this->middleware('auth')->except(['index']);
	}

	public function edit_save_servizi_noleggio($request) {
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
			DB::table("parco_servizi_noleggio")->insert($arr);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=strtoupper($descr_contr);
			parco_servizi_noleggio::where('id', $edit_elem)
			  ->update(['descrizione' => $descr_contr]);
		}
		if (strlen($dele_contr)!=0) {
			parco_servizi_noleggio::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			parco_servizi_noleggio::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		

				
	}
	
	public function servizi_noleggio(Request $request) {
		$this->edit_save_servizi_noleggio($request);
		$view_dele=$request->input("view_dele");
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;		
		$elenco_servizi=DB::table('parco_servizi_noleggio')
		->when($view_dele=="0", function ($teles) {
			return $teles->where('dele', "=","0");
		})
		->orderBy('descrizione')->get();

		return view('all_views/parco/servizi_noleggio')->with('elenco_servizi', $elenco_servizi)->with("view_dele",$view_dele);		
	}


	public function edit_save_telepass($request) {
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
			$arr['id_telepass']=$descr_contr;
			DB::table("parco_telepass")->insert($arr);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=strtoupper($descr_contr);
			parco_telepass::where('id', $edit_elem)
			  ->update(['id_telepass' => $descr_contr]);
		}
		if (strlen($dele_contr)!=0) {
			parco_telepass::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			parco_telepass::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		

				
	}
	
	public function telepass(Request $request) {
		$this->edit_save_telepass($request);
		$view_dele=$request->input("view_dele");
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;		
		$teles=DB::table('parco_telepass')
		->when($view_dele=="0", function ($teles) {
			return $teles->where('dele', "=","0");
		})
		->orderBy('id_telepass')->get();

		return view('all_views/parco/telepass')->with('teles', $teles)->with("view_dele",$view_dele);		
	}

	public function edit_save_badge($request) {
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
			$arr['id_badge']=$descr_contr;
			DB::table("parco_badge_cisterna")->insert($arr);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=strtoupper($descr_contr);
			parco_badge_cisterna::where('id', $edit_elem)
			  ->update(['id_badge' => $descr_contr]);
		}
		if (strlen($dele_contr)!=0) {
			parco_badge_cisterna::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			parco_badge_cisterna::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		

				
	}
	
	public function badge(Request $request) {
		$this->edit_save_badge($request);
		$view_dele=$request->input("view_dele");
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;		
		$badges=DB::table('parco_badge_cisterna')
		->when($view_dele=="0", function ($badges) {
			return $badges->where('dele', "=","0");
		})
		->orderBy('id_badge')->get();

		return view('all_views/parco/badge')->with('badges', $badges)->with("view_dele",$view_dele);		
	}


	public function edit_save_cartac($request) {
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
			$arr['id_carta']=$descr_contr;
			DB::table("parco_carta_carburante")->insert($arr);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=strtoupper($descr_contr);
			parco_carta_carburante::where('id', $edit_elem)
			  ->update(['id_carta' => $descr_contr]);
		}
		if (strlen($dele_contr)!=0) {
			parco_carta_carburante::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			parco_carta_carburante::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		

				
	}
	
	public function cartac(Request $request) {
		$this->edit_save_cartac($request);
		$view_dele=$request->input("view_dele");
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;		
		$carte=DB::table('parco_carta_carburante')
		->when($view_dele=="0", function ($carte) {
			return $carte->where('dele', "=","0");
		})
		->orderBy('id_carta')->get();

		return view('all_views/parco/cartac')->with('carte', $carte)->with("view_dele",$view_dele);		
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

