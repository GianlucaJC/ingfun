<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\lavoratori;
use App\Models\societa;
use App\Models\ditte;
use App\Models\ref_doc_ditte;
use App\Models\aliquote_iva;
use App\Models\presenze;
use App\Models\candidati;
use App\Models\log_presenze;
use Mail;
use DB;


class AjaxControllerServ extends Controller
	{


	public function refresh_servizi(Request $request){
		$ditta=$request->input('ditta');
		$all_servizi=DB::table('servizi_ditte as sd')
		->join('servizi as s','sd.id_servizio','s.id')
		->join('aliquote_iva as a','sd.aliquota','a.id')
		->select('s.descrizione','sd.id_servizio','sd.importo_ditta','sd.aliquota','a.aliquota as aliquota_v')
		->where('sd.id_ditta','=',$ditta)
		->groupBy('sd.id')
		->get();
		return json_encode($all_servizi);
	}
	
	public function refresh_servizi_ditte(Request $request) {
		$id_ditta=$request->input('id_ditta');
		$servizi=DB::table('servizi as s')
		->join('servizi_ditte as d','s.id','d.id_servizio')
		->select('s.descrizione','d.id_servizio','d.importo_ditta','d.aliquota','d.importo_lavoratore')
		->where('d.id_ditta', '=', $id_ditta)
		->get();
		return json_encode($servizi);
	}
	
	public function refresh_aliquota(){
		$aliquote = aliquote_iva::where('dele','=',0)->orderBy('aliquota')->get();
        return json_encode($aliquote);
	}
	
	public function update_doc_ditte() {
		$filename=$_POST['filename'];
		$id_ditta=$_POST['id_ditta'];
		$descr_file=$_POST['descr_file'];

		$ref_doc_ditte= new ref_doc_ditte;
		$ref_doc_ditte->id_ditta=$id_ditta;
		$ref_doc_ditte->nomefile=$filename;
		$ref_doc_ditte->descr_file=$descr_file;
		$ref_doc_ditte->save();
		$status['status']="OK";
		$status['message']="Dati inseriti con successo!";
		return json_encode($status);
	}

	public function load_contatti_soc(){
		$contatti = societa::where('dele','=',0)
		->orderBy('descrizione')->get();
        return json_encode($contatti);
	}
	

	public function popola_servizi(Request $request){		
		$id_ditta = $request->input('id_ditta');
		$infoditta=DB::table('servizi as s')
		->join('servizi_ditte as d','s.id','d.id_servizio')
		->select('s.descrizione','d.id_servizio','d.importo_ditta','d.aliquota','d.importo_lavoratore')
		->where('d.id_ditta', '=', $id_ditta)
		->get();
        return json_encode($infoditta);
	}	

	public function lavoratori_sezionali(Request $request){		
		$id_sezionale = $request->input('id_sezionale');
		
		$lavoratori=candidati::select('id','nominativo','tipo_contr','tipo_contratto')
		->where('status_candidatura','=',3)
		->when($id_sezionale!="all", function ($lavoratori) use ($id_sezionale) {
			return $lavoratori->where('soc_ass','=',$id_sezionale);
		})
		->orderByRaw('case 
			when `tipo_contr` = "2" and `tipo_contratto`="1"  then 1 
			when `tipo_contr` = "2" and `tipo_contratto`="2"  then 2
			when `tipo_contr` = "2" and (`tipo_contratto`<>"1" and `tipo_contratto`<>"2")  then 3
			when `tipo_contr` = "1" and `tipo_contratto`="1"  then 4
			when `tipo_contr` = "1" and `tipo_contratto`="2"  then 5
			when `tipo_contr` = "1" and (`tipo_contratto`<>"1" and `tipo_contratto`<>"2")  then 6
			else 7 end')
		->orderBy('nominativo')	
		->get();

        return json_encode($lavoratori);
	}	


	public function getditta(Request $request){		
		$id_ditta = $request->input('id_ditta');
		$infoditta=DB::table('ditte as d')
		->select('d.*')
		->where('d.id', '=', $id_ditta)
		->get();
        return json_encode($infoditta);
	}	

	public function remove_doc_ditta(Request $request){
		$nomefile=$request->input("nomefile");
		$id_ditta=$request->input("id_ditta");
		
		$id_doc=$request->input("id_doc");
		$data=['dele'=>1];

		$dele=DB::table('ref_doc_ditte')
		->where('id', $id_doc)
		->update($data);
		//@unlink ("allegati/ditte/$id_ditta/$nomefile");
		/*
			ho optato per una cancellazione virtuale(dele=1) in caso in futuro si voglia implementare il ripristino
		*/
		$info=array();
		$info['resp']="OK";
		return json_encode($info);			
	}

	public function get_doc_ditta(Request $request){		
		$id_ditta = $request->input('id_ditta');
		$elenco_doc = DB::table('ref_doc_ditte as r')
		->select('r.id','r.nomefile', 'r.descr_file','r.created_at', 'r.updated_at')
		->where('r.id_ditta','=',$id_ditta)
		->where('r.dele','=',0)
		->get();
        return json_encode($elenco_doc);
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
