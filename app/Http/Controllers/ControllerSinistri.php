<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\user;
use App\Models\main_menu;
use App\Models\appalti;
use App\Models\parco_scheda_mezzo;
use DB;

class ControllerSinistri extends Controller
{
	public function __construct() {
		//$this->middleware('auth')->except(['index']);
	}
	
	public function sinistri($id_appalto=0) {
		$allinfo=appalti::select('appalti.*')
		->join('lavoratoriapp as l','appalti.id','l.id_appalto')
		->where('appalti.id', "=",$id_appalto)
		->groupBy('appalti.id')
		->get();
		
		$mezzi=parco_scheda_mezzo::from('parco_scheda_mezzo as m')
		->select('m.id','m.targa')
		->where('m.dele','=',0)
		->orderBy('targa')
		->get();

		
		$request=request();
		return view('all_views/sinistri/sinistri',compact('id_appalto','allinfo','mezzi'));		
	}
	
	public function order_global() {
		$voci_old=DB::table('main_menu as a')		
		->select("a.id","a.ordine","a.parent_id")
		->orderBy('a.parent_id')
		->orderBy('a.ordine')
		->get();
		
		$re_ord=0;
		$old_pid="?";
		foreach ($voci_old as $old) {
			$id_up=$old->id;
			$pid=$old->parent_id;
			if ($old_pid!=$pid) {
				$old_pid=$pid;
				$re_ord=0;
			}
			$re_ord++;
			main_menu::where('id','=',$id_up)
			->update(['ordine'=>$re_ord]);			
		}			
	}	
	public function sposta() {
		$request=request();
		$id_up_schema=$request->input("id_up_schema");	
		$parent_id_dest=$request->input("parent_id_dest");
		$id_get=$id_up_schema;$parent_get=$parent_id_dest;
		$parent_id_origin=$request->input("parent_id_origin");
		$ordine_origine=$request->input("ordine_origine");
		$voci_old=DB::table('main_menu as a')		
		->select("a.id","a.ordine")
		->where("a.parent_id","=",$parent_id_dest)
		->orderBy('a.ordine')
		->get();
		
		$re_ord=0;$fl=false;
		foreach ($voci_old as $old) {
			$re_ord++;
			$id_up=$old->id;
			if ($old->ordine==$ordine_origine && $fl==false) {
				$fl=true;
				
				main_menu::where('id','=',$id_up_schema)
				->update(['parent_id' => $parent_id_dest,'ordine'=>$re_ord]);
				
				$re_ord++;
				
				main_menu::where('id','=',$id_up)
				->update(['ordine'=>$re_ord]);
				
			} else {
				
				if ($parent_id_origin!=$parent_id_dest) {
					main_menu::where('id','=',$id_up)
					->update(['ordine'=>$re_ord]);
				}
			}	
		}
		$this->order_global();
	}
	

	
	public function adminmenu($id_get=0,$parent_get=0) {
		$request=request();
		$btn_save_menu=$request->input("btn_save_menu");
		$tipo_view=$request->input("tipo_view");
		
		if ($btn_save_menu=="save") {
			main_menu::where('id','>=',1)
			->update(['back_parent'=> DB::raw('parent_id'),'back_ordine' => DB::raw('ordine') ]);
		}

		$btn_ripr_menu=$request->input("btn_ripr_menu");
		if ($btn_ripr_menu=="ripr") {
			main_menu::where('id','>=',1)
			->update(['parent_id'=> DB::raw('back_parent'),'ordine' => DB::raw('back_ordine') ]);
		}
		
		$btn_save=$request->input("btn_save");
		
		if ($btn_save=="save") {
			$id_mod=$request->input("id_mod");
				
			$save = main_menu::find($id_mod);
			$save->voce=$request->input("voce_edit");
			$save->note=$request->input("note");
			$save->class_btn_action=$request->input("button_color");
			$save->visible=$request->input("btn_visible");
			$save->disable=$request->input("btn_disable");
			$ruolo=implode("|",$request->input("ruolo"));
			$save->roles=$ruolo;
			$permesso="";
			if (is_array($request->input("permesso")) || strlen($request->input("permesso"))!=0)
				$permesso=implode("|",$request->input("permesso"));
			$save->permissions=$permesso;

			$save->save();
		}
		
		$btn_sposta=$request->input("btn_sposta");
		
		if ($btn_sposta=="save") {
			$this->sposta();
			$id_up_schema=$request->input("id_up_schema");	
			$parent_id_dest=$request->input("parent_id_dest");
			$id_get=$id_up_schema;$parent_get=$parent_id_dest;			
		}
		
		
		$view_dele=$request->input("view_dele");
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		
		if (strlen($tipo_view)==0) $tipo_view="zzz";
		$voci_menu=DB::table('main_menu as a')		
		->select("a.*")
		->where("a.reserved","=",0)
		->where("a.roles","like","%$tipo_view%")
		->orderBy('a.parent_id')
		->orderBy('a.ordine')
		->get();

		$menu_info=DB::table('main_menu as a')		
		->select("a.*")
		->where("a.reserved","=",0)
		->orderBy('a.parent_id')
		->orderBy('a.ordine')
		->get();

		$info_menu=array();
		foreach($menu_info as $info) {
			$info_menu[$info->id]=$info->voce;
		}


		$arr_menu=array();
		foreach($voci_menu as $titoli) {
			$dati['id']=$titoli->id;
			$dati['title']=$titoli->voce;
			$dati['parent_id']=$titoli->parent_id;
			$dati['class_icon']=$titoli->class_icon;
			$dati['route']=$titoli->route;
			$dati['params_route']=$titoli->params_route;
			$dati['visible']=$titoli->visible;
			$dati['disable']=$titoli->disable;
			$dati['ordine']=$titoli->ordine;
			$arr_menu[]=$dati;
		}
		
		
		$roles=DB::table('roles as r')
		->select("r.id","r.name")
		->orderBy('r.name')->get();
		$permissions=DB::table('permissions as p')
		->select("p.id","p.name")
		->orderBy('p.name')->get();


		return view('all_views/menuadmin/adminmenu',compact('voci_menu','info_menu','tipo_view','arr_menu','roles','permissions','id_get','parent_get'));		
	}	
	
}

