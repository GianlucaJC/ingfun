<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\tipoc;
use DB;

class ControllerArchivi extends Controller
{
	public function tipo_contratto(Request $request){
		$view_dele=$request->has("view_dele");
		$dele_contr="";
		if (isset($_POST["dele_contr"])) $dele_contr=$_POST["dele_contr"];
		if (strlen($dele_contr)!=0) {
			tipoc::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		$restore_contr="";
		if (isset($_POST["restore_contr"])) $restore_contr=$_POST["restore_contr"];
		if (strlen($restore_contr)!=0) {
			tipoc::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		
		$tipoc=DB::table('tipoc')
		->when($view_dele=="0", function ($tipoc) {
			return $tipoc->where('dele', "=","0");
		})
		->orderBy('descrizione')->get();

		return view('all_views/gestione/tipocontratto')->with('tipoc', $tipoc)->with("view_dele",$view_dele);

	}


}
