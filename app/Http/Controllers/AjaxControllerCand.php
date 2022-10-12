<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\italy_provincies;
use App\Models\italy_cities;
use App\Models\italy_cap;
use App\Models\candidati;
use App\Models\tipoc;
use App\Models\societa;
use App\Models\centri_costo;


class AjaxControllerCand extends Controller
{
	public function refresh_costo(){
		$centri_costo = centri_costo::where('dele','=',0)->orderBy('descrizione')->get();
        return json_encode($centri_costo);
	}
	public function refresh_tipoc(){
		$tipoc = tipoc::where('dele','=',0)->orderBy('descrizione')->get();
        return json_encode($tipoc);
	}
	public function refresh_soc(){
		$societa = societa::where('dele','=',0)->orderBy('descrizione')->get();
        return json_encode($societa);
	}
	public function dele_curr(){
		
		$file_curr=$_POST['file_curr'];
		$id_cand=$_POST['id_cand'];

		try {
			
			candidati::where('id', $id_cand)->update(['file_curr' => null]);			
			$path = base_path();
			$fx_dele=$path."/public/allegati/curr/".$file_curr;
			unlink($fx_dele);
			
			echo json_encode([
				'status' => 'OK',
				'message' => "File eliminato"
			]);
			

			
		} catch (RuntimeException $e) {
			// Something went wrong, send the err message as JSON
			http_response_code(400);

			echo json_encode([
				'status' => 'error',
				'message' => $e->getMessage()
			]);
		}

	}


	public function lista_province(Request $request){

		$id_regione = $request->input('id_regione');;
        $province = italy_provincies::where('id_regione', '=', $id_regione)->get();
        return json_encode($province);

	}

	public function lista_comuni(Request $request){
		$comuni=array();
		
		$sigla = $request->input('sigla');
		$comune_search = $request->input('comune_search');
		if ($comune_search=="0")
			$comuni = italy_cities::where('provincia', '=', $sigla)->orderBy('comune')->get();
		else {
			$comuni = italy_cities::where('comune', 'like', "%".$comune_search."%")->orderBy('comune')->get();
		}	
	
		
        return json_encode($comuni);

	}

	public function lista_cap(Request $request){
		
		
		$istat = $request->input('istat');
        $cap = italy_cap::where('istat', '=', $istat)->skip(0)->take(1)->orderBy('cap')->get();
		
        return json_encode($cap);

	}
	

}
