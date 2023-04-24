<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use App\Models\lavoratori;
use App\Models\ditte;
use App\Models\presenze;
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
				if ($id_servizio==1000)
					$presenze->note=$value;
				else
					$presenze->importo=$value;

				$presenze->save();
			}
			
			
		}
		$info=array();
		$info['resp']="OK";
		return json_encode($info);
	}	

}
