<?php
//test
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\candidati;
use App\Models\regioni;
use App\Models\italy_cities;
use App\Models\tipoc;
use App\Models\societa;
use App\Models\centri_costo;
use App\Models\area_impiego;
use App\Models\mansione;
use App\Models\ccnl;
use App\Models\tipologia_contr;


use DB;




use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class ControllerPersonale extends Controller
{
	public function __construct()
	{
		$this->middleware('auth')->except(['index']);
	}	

	public function cedolini_up(Request $request) {
		$mese_busta=$request->input("mese_busta");
		$anno_busta=$request->input("anno_busta");
		$periodo=$mese_busta.$anno_busta;
		$dele_pdf=$request->input("dele_pdf");
		$distr=$request->input("distr");
		if ($distr=="distr") {
			$dir="allegati/cedolini/$periodo";
			$sub="allegati/cedoliniview/$periodo";
			@mkdir($sub);
			$elenco = scandir($dir);
			for ($sca=0;$sca<count($elenco);$sca++) {
				$fx_src=$elenco[$sca];
				$fx=str_replace(".pdf","",$fx_src);
				if (strlen($fx)==16) {
					$fx_dest=md5($fx).".pdf";
					copy($dir."/".$fx_src,$sub."/".$fx_dest);
				}
			}
			$f1 = fopen($dir."/distr.ddd", "w") or die("Unable to open file!");
			$txt = "Done!";
			fwrite($f1, $txt);
		}
		if ($dele_pdf=="1") {
			$dir = "allegati/cedolini/$periodo/";
			array_map('unlink', glob("$dir/*.pdf"));
			array_map('unlink', glob("$dir/*.ddd"));
		}
		
		
		return view('all_views/cedolini_up')->with('mese_busta',$mese_busta)->with('anno_busta',$anno_busta)->with("dele_pdf",$dele_pdf)->with('distr',$distr);
	}
	

	
	public function scadenze_contratti(Request $request) {

		$today=date("Y-m-d");
		$scadenze=candidati::select('id', 'nominativo','status_candidatura', 'data_inizio', 'data_fine')
		->where("dele","=",0)
		->where("data_fine","<=", $today)		
		->where("status_candidatura","=",3)
		->get();

		return view('all_views/scadenze_contratti')->with('scadenze', $scadenze);
	}

	public function listpers(Request $request) {
		
		$view_dele=0;
		if ($request->has("view_dele")) $view_dele=$request->input("view_dele");
		if ($view_dele=="on") $view_dele=1;
		$restore_cand=$request->input("restore_cand");
		$dele_cand=$request->input("dele_cand");

		if (strlen($dele_cand)!=0) {
			candidati::where('id', $dele_cand)
			  ->update(['dele' => 1]);			
		}		
		if (strlen($restore_cand)!=0) {
			candidati::where('id', $restore_cand)
			  ->update(['dele' => 0]);			
		}				
		
		$candidati=DB::table('candidatis')
		->when($view_dele=="0", function ($candidati) {
			return $candidati->where('dele', "=","0");
		})
		->where("status_candidatura",">=","3")
		->orderBy('nominativo')->get();

		$mansione=mansione::orderBy('descrizione')->get();
		$mansioni=array();
		foreach($mansione as $mans){
			$id_m=$mans->id;$descrizione=$mans->descrizione;
			$mansioni[$id_m]=$descrizione;
		}

		return view('all_views/listpers')->with('candidati', $candidati)->with("view_dele",$view_dele)->with("mansioni",$mansioni);
	}

}
