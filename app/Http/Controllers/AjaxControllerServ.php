<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\lavoratori;
use App\Models\ditte;
use App\Models\presenze;
use App\Models\log_presenze;
use Mail;
use DB;


class AjaxControllerServ extends Controller
	{


	public function getditta(Request $request){		
		$id_ditta = $request->input('id_ditta');
		$infoditta=DB::table('ditte as d')
		->select('d.*')
		->where('d.id', '=', $id_ditta)
		->get();
        return json_encode($infoditta);
	}	

	public function save_value_presenze(Request $request){
		$id_user=Auth::user()->id;
		$tipo_dato = $request->input('tipo_dato');
		$periodo = $request->input('periodo');
		$giorni = $request->input('giorni');
		$id_lav = $request->input('id_lav');
		$id_servizio = $request->input('id_servizio');

		for ($sca=1;$sca<=$giorni;$sca++) {
			$dato="dato$sca";
			
			$value=$request->input($dato);
	
			$gg=$sca;
			if (strlen($sca)==1) $gg="0$sca";
			$data="$periodo-$gg";
			$check=presenze::select('id')
			->where("periodo","=",$periodo)
			->where("id_lav","=",$id_lav)
			->where("id_servizio","=",$id_servizio)
			->where("data","=",$data)
			->get();
			
			if (strlen($value)==0) {
				if (isset($check[0]->id) && $check[0]->id!=null) {
					$dele=presenze::where("periodo","=",$periodo)
					->where("id_lav","=",$id_lav)
					->where("id_servizio","=",$id_servizio)
					->where("data","=",$data)
					->delete();						
				}	
			} else {
				


				if (isset($check[0]->id) && $check[0]->id!=null) {
					$presenze=presenze::find($check[0]->id);
				}
				else	
					$presenze= new presenze;
				$presenze->id_lav=$id_lav;
				$presenze->id_servizio=$id_servizio;
				$presenze->periodo=$periodo;
				$presenze->data=$data;
				if ($tipo_dato=="1") {
					$presenze->note=$value;
					$presenze->importo=null;
				}	
				else {
					$presenze->importo=$value;
					$presenze->note=null;
				}
				

				$presenze->save();
			}


			//Log operazione
			$log_presenze= new log_presenze;
			$log_presenze->id_user=$id_user;
			$log_presenze->id_lav=$id_lav;
			$log_presenze->id_servizio=$id_servizio;
			$log_presenze->periodo=$periodo;
			$log_presenze->data=$data;
			if ($tipo_dato=="1") {
				$log_presenze->note=$value;
				$log_presenze->importo=null;
			}	
			else {
				$log_presenze->importo=$value;
				$log_presenze->note=null;
			}

			$log_presenze->save();		
			
		}
		$info=array();
		$info['resp']="OK";
		return json_encode($info);
	}	

}
