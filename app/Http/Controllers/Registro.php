<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\candidatis;
use App\Models\presenze;
use DB;

class Registro extends Controller
{

	private function mese_descr($mese) {
		$mese=intval($mese);$descr_b="";$descr_e="";
		if ($mese==1) {$descr_b="Gen";$descr_e="Gennaio";}
		if ($mese==2) {$descr_b="Feb";$descr_e="Febbraio";}
		if ($mese==3) {$descr_b="Mar";$descr_e="Marzo";}
		if ($mese==4) {$descr_b="Apr";$descr_e="Aprile";}
		if ($mese==5) {$descr_b="Mag";$descr_e="Maggio";}
		if ($mese==6) {$descr_b="Giu";$descr_e="Giugno";}
		if ($mese==7) {$descr_b="Lug";$descr_e="Luglio";}
		if ($mese==8) {$descr_b="Ago";$descr_e="Agosto";}
		if ($mese==9) {$descr_b="Set";$descr_e="Settembre";}
		if ($mese==10) {$descr_b="Ott";$descr_e="Ottobre";}
		if ($mese==11) {$descr_b="Nov";$descr_e="Novembre";}
		if ($mese==12) {$descr_b="Dic";$descr_e="Dicembre";}
		$resp=array();
		$resp['descr_b']=$descr_b;
		$resp['descr_e']=$descr_e;
		return $resp;
	}
	private function periodi() {
		$y=intval(date("Y"));$mese="";
		$periodo=array();
		for($anno=$y;$anno>=2023;$anno--) {
			for ($m=12;$m>=1;$m--) {
				$mm=$m;
				if (strlen($m)==1) $mm="0$m";
				
				if ($m==12) $descr_m="Dic";
				if ($m==11) $descr_m="Nov";
				if ($m==10) $descr_m="Ott";
				if ($m==9) $descr_m="Set";
				if ($m==8) $descr_m="Ago";
				if ($m==7) $descr_m="Lug";
				if ($m==6) $descr_m="Giu";
				if ($m==5) $descr_m="Mag";
				if ($m==4) $descr_m="Apr";
				if ($m==3) $descr_m="Mar";
				if ($m==2) $descr_m="Feb";
				if ($m==1) $descr_m="Gen";
				
				$indice="$anno-$mm";
				$ref_periodo="$descr_m-$anno";
				$periodo[$indice]=$ref_periodo;
			}
		}
		return $periodo;
	}
	
	public function presenze(Request $request){
		$view_dele=$request->input("view_dele");
		$periodi=$this->periodi();
		$periodo=$request->input("periodo");
		
		
		$mese=$this->mese_descr(substr($periodo,5,2))['descr_b'];
		$mese_num=substr($periodo,5,2);
		$per_da=$periodo."-01";
		$per_a=date("Y-m-t", strtotime($per_da));
		
		$giorni=$this->giorni($periodo);

		$lav_all=DB::table('candidatis as c')
		->select("c.id as id_lav","c.nominativo","c.status_candidatura","c.dele")
		->orderBy("c.nominativo")
		->get();

		

		//lavoratori movimentati nel periodo di analisi
		$lavoratori=DB::table('appalti as a')
		->select("c.id as id_lav","c.nominativo")
		->join("lavoratoriapp as l","l.id_appalto","a.id")
		->join("candidatis as c","c.id","l.id_lav_ref")
		->where("a.data_ref",">=",$per_da)
		->where("a.data_ref","<=",$per_a)
		->groupBy('c.id')
		->orderBy('nominativo')->get();
		$lavoratori_mov=array();
		foreach($lavoratori as $lav) {
			$lavoratori_mov[$lav->id_lav]['nominativo']=$lav->nominativo;
		}
		
		//$lav_lista: solo lavoratori attualmente assunti o che hanno partecipato ad almeno un appalto nel periodo prescelto
		$lav_lista=array();
		foreach ($lav_all as $lav_sn) {
			//status_candidatura==3 - solo assunti: preso da $count listpers 
			if ($lav_sn->status_candidatura==3) {
				if ($lav_sn->dele==1) {
					if (isset($lavoratori_mov[$lav_sn->id_lav]))
						$lav_lista[$lav_sn->id_lav]['presenza']="canc";					
				} else $lav_lista[$lav_sn->id_lav]['presenza']="ass";
			} else {
				if (isset($lavoratori_mov[$lav_sn->id_lav]))
					$lav_lista[$lav_sn->id_lav]['presenza']="movim";
			}
		}

		//tutti i servizi svolti a prescindere dai lavoratori
		/* eliminato il riferimento al periodo
		->where("a.data_ref",">=",$per_da)
		->where("a.data_ref","<=",$per_a)
		*/
		$servizi_all=DB::table('appalti as a')
		->select("s1.id","s1.descrizione","s1.importo")
		->join("serviziapp as s","s.id_appalto","a.id")
		->join("servizi as s1","s1.id","s.id_servizio")
		
		->groupBy('s1.id')
		->orderBy('s1.descrizione')->get();
		$servizi=array();
		foreach($servizi_all as $s) {
			$servizi[$s->id]['id']=$s->id;
			$servizi[$s->id]['descrizione']=$s->descrizione;
			$servizi[$s->id]['importo']=$s->importo;
		}
		
		$servizi[1000]['id']=1000;
		$servizi[1000]['descrizione']="NOTE";
		$servizi[1000]['importo']="";
		
		
		$servizi[1001]['id']=1001;
		$servizi[1001]['descrizione']="FERIE";
		$servizi[1001]['importo']="";


		//servizi associati ai lavoratori nel periodo
		$servizi_lav=DB::table('appalti as a')
		->select("l.id_lav_ref","s1.id as id_service","a.data_ref")
		->join("serviziapp as s","s.id_appalto","a.id")
		->join("lavoratoriapp as l","l.id_appalto","a.id")
		->join("servizi as s1","s1.id","s.id_servizio")
		->where("l.status","=",1)
		->where("a.data_ref",">=",$per_da)
		->where("a.data_ref","<=",$per_a)
		->orderBy('s1.descrizione')->get();
		foreach ($servizi_lav as $service) {
			if (isset($lav_lista[$service->id_lav_ref])) 
				$lav_lista[$service->id_lav_ref]['service'][]=$service;
		}
		
		/*
		verifica sovrascrizioni importi effettuati tramite tabella presenze:
		in pratica tutti i servizi con relativi importi vengono pre-caricati tramite query e assegnati alla variabile $servizi_lav. C'è  poi facoltà di modificare gli importi tramite tabella presenze
		*/
		$presenze=presenze::select('id_lav','id_servizio','periodo','data','importo','note')
		->where("data",">=",$per_da)
		->where("data","<=",$per_a)
		->get();
		foreach ($presenze as $presenza) {
			if (isset($lav_lista[$presenza->id_lav])) 
				$lav_lista[$presenza->id_lav]['presenze'][]=$presenza;
		}
		
		if (strlen($periodo)==0) $lav_all=array();

		return view('all_views/registro/presenze')->with('lavoratori_mov', $lavoratori_mov)->with('lav_all', $lav_all)->with('lav_lista',$lav_lista)->with('servizi',$servizi)->with('servizi_lav',$servizi_lav)->with("giorni",$giorni)->with('mese',$mese)->with('mese_num',$mese_num)->with('periodo',$periodo)->with('periodi',$periodi);
		
	}	
	
	private function giorni($periodo) {
		$info=explode("-",$periodo);
		if (count($info)<2) return;
		$anno=intval($info[0]);
		$mese=intval($info[1]);
		$d=cal_days_in_month(CAL_GREGORIAN,$mese,$anno);
		return $d;	
	}


}
