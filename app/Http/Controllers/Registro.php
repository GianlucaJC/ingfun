<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\candidati;
use App\Models\presenze;
use App\Models\giustificativi;
use App\Models\servizi_custom;
use DB;

class Registro extends Controller
{
	public function __construct()
	{
		$this->middleware('auth')->except(['index']);
	}

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
		$save_edit=$this->save_edit_giustificativi($request);
		$view_dele=$request->input("view_dele");
		$periodi=$this->periodi();
		$periodo=$request->input("periodo");
		$zoom_tbl=$request->input("zoom_tbl");
		if (strlen($zoom_tbl)==0) $zoom_tbl=0.65;
		if (strlen($periodo)==0) {
			$y=date("Y");$m=date("m");
			$periodo="$y-$m";
		}
		
		$mese=$this->mese_descr(substr($periodo,5,2))['descr_b'];
		$mese_num=substr($periodo,5,2);
		$per_da=$periodo."-01";
		$per_a=date("Y-m-t", strtotime($per_da));
		
		$giorni=$this->giorni($periodo);

		$lav_all=DB::table('candidatis as c')
		->select("c.id as id_lav","c.nominativo","c.status_candidatura","c.dele","c.data_fine")
		->orderBy("c.nominativo")
		->get();

		

		//lavoratori movimentati nel periodo di analisi
		$lavoratori=DB::table('appalti as a')
		->select("c.id as id_lav","c.nominativo")
		->join("lavoratoriapp as l","l.id_appalto","a.id")
		->join("candidatis as c","c.id","l.id_lav_ref")
		->where("a.dele","=",0)
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
				else {
					$periodo_ok=false;
					if ($lav_sn->data_fine!=null) {
						$perx=$lav_sn->data_fine;
						$newDate = date('Y-m-t', strtotime($perx. ' + 1 months'));
						if ($newDate>=$per_a) $periodo_ok=true;
					}
					
					if ($periodo_ok==true) {
						$lav_lista[$lav_sn->id_lav]['presenza']="only_view";
					}	
				}	
			}
		}
		

		//tutti i servizi svolti a prescindere dai lavoratori
		/* eliminato il riferimento al periodo
		->where("a.data_ref",">=",$per_da)
		->where("a.data_ref","<=",$per_a)
		*/
		$servizi_all=DB::table('appalti as a')
		->select("s1.id","s1.descrizione","s1.acronimo","s.importo_lavoratore as importo")
		->join("serviziapp as s","s.id_appalto","a.id")
		->join("servizi as s1","s1.id","s.id_servizio")
		->groupBy('s1.id')
		->orderBy('s1.descrizione')->get();
		$servizi=array();
		foreach($servizi_all as $s) {
			$servizi[$s->id]['id']=$s->id;
			$servizi[$s->id]['descrizione']=$s->descrizione;
			$servizi[$s->id]['acronimo']=$s->acronimo;
			$servizi[$s->id]['alias_ref']="";
			$servizi[$s->id]['importo']=$s->importo;
			$servizi[$s->id]['tipo_dato']=0; //tutti importi
			$servizi[$s->id]['pre_load']="S"; //sempre visualizzato nella table
			
		}
		
		//in servizi_custom (oltre elementi definiti da utente mediante procedura giustificativi), ci sono anche degli elementi con pre_load='S' da me definiti di default: Ferie, Permessi, Note
		$servizi_custom=DB::table('servizi_custom as s1')
		->select("s1.id","s1.descrizione","s1.alias_ref","s1.tipo_dato","s1.pre_load")
		->leftjoin("presenze as p","s1.id","p.id_servizio")
		->where("p.data",">=",$per_da)
		->where("p.data","<=",$per_a)
		->orWhere("s1.pre_load","=", "S")
		->orderByRaw( "FIELD(s1.descrizione, 'NOTE')" )
		->groupBy('s1.id')
		->get();
		//->orderBy('s1.descrizione')
		foreach($servizi_custom as $s) {
			$servizi[$s->id]['id']=$s->id;
			$servizi[$s->id]['descrizione']=$s->descrizione;
			$servizi[$s->id]['alias_ref']=$s->alias_ref;
			$servizi[$s->id]['importo']=null;
			$servizi[$s->id]['tipo_dato']=$s->tipo_dato;
			$servizi[$s->id]['pre_load']=$s->pre_load;
		}

		//servizi associati ai lavoratori nel periodo
		$servizi_lav=DB::table('appalti as a')
		->select("l.id_lav_ref","s1.id as id_service","a.data_ref")
		->join("serviziapp as s","s.id_appalto","a.id")
		->join("lavoratoriapp as l","l.id_appalto","a.id")
		->join("servizi as s1","s1.id","s.id_servizio")
		->where("a.dele","=",0)
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

		$servizi_custom=DB::table('servizi_custom as s1')
		->select("s1.id","s1.descrizione","s1.alias_ref","s1.tipo_dato")
		->orderBy('s1.descrizione')->get();

		$lavoratori=candidati::select('id','nominativo','tipo_contr','tipo_contratto')
		->where('status_candidatura','=',3)		
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
		return view('all_views/registro/presenze')->with('lavoratori_mov', $lavoratori_mov)->with('lav_all', $lav_all)->with('lav_lista',$lav_lista)->with('servizi',$servizi)->with('servizi_lav',$servizi_lav)->with("giorni",$giorni)->with('mese',$mese)->with('mese_num',$mese_num)->with('periodo',$periodo)->with('periodi',$periodi)->with('zoom_tbl',$zoom_tbl)->with('servizi_custom',$servizi_custom)->with('lavoratori',$lavoratori);
		
	}	
	
	private function giorni($periodo) {
		$info=explode("-",$periodo);
		if (count($info)<2) return;
		$anno=intval($info[0]);
		$mese=intval($info[1]);
		$d=cal_days_in_month(CAL_GREGORIAN,$mese,$anno);
		return $d;	
	}



	public function save_edit_giustificativi(Request $request) {
		/*
			N.B.:questa function può essere chiamata o da gestione giustificativi o dal registro servizi
		*/
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");
		$lavoratori=$request->input("lavoratori");
		$ore_gg=$request->input("ore_gg");		
		$value_descr=$request->input("value_descr");
		if (strlen($ore_gg)==0) $ore_gg=null;
		if (strlen($value_descr)==0) $value_descr=null;
		$tmp=$request->input("range_date");
		$tmp=str_replace(" - ",";",$tmp);
		$tmp=str_replace("/","-",$tmp);
		
		$arr=explode(";",$tmp);
		$descrizione=$request->input("descrizione");
		$tipo_d=$request->input("tipo_d");
		if (strlen($tipo_d)==0) {
			$tipo_d=1; //tipo testuale: potrà essere digitato qualiasi cosa nel registro....ammesso che ore_gg sia <>0, altrimenti viene preso il corrispettivo acronimo
		}
		$alias_ref=$request->input("alias_ref");
		$id_serv=$request->input("servizio_custom");
		if (strlen($descrizione)!=0) {
			$servizi_custom = new servizi_custom;
			$servizi_custom->descrizione=strtoupper($descrizione);
			$servizi_custom->alias_ref=$alias_ref;
			$servizi_custom->tipo_dato=$tipo_d;
			$servizi_custom->save();
			$id_serv=$servizi_custom->id;
		}
		if (strlen($value_descr)==0 && strlen($ore_gg)==0 && strlen($id_serv)!=0) {
			//in questo caso con id_servizio!=0 e ore_gg==0 verrà mostrato nel registro l'acronimo o alias del servizio (normale o custom)
			$ore_gg=0;
		}			
			
		if (is_array($lavoratori) && count($lavoratori)>0 && count($arr)>0) {

			$d1=$arr[0];$d2=$arr[1];
			$da_data=substr($d1,6,4)."-".substr($d1,3,2)."-".substr($d1,0,2);
			$a_data=substr($d2,6,4)."-".substr($d2,3,2)."-".substr($d2,0,2);


			$begin = strtotime( $da_data );
			$end   = strtotime( $a_data );
			for($sca=0;$sca<=count($lavoratori)-1;$sca++) {
				$giustificativi = new giustificativi;			
				$id_lav=$lavoratori[$sca];
				$giustificativi->id_cand=$id_lav;
				$giustificativi->da_data=$da_data;
				$giustificativi->a_data=$a_data;
				$giustificativi->ore_gg=$ore_gg;
				$giustificativi->value_descr=$value_descr;
				$giustificativi->save();	
				$id_giust=$giustificativi->id;
				
				//creazione ferie su presenze
				
				
				for($gg=$begin;$gg<=$end;$gg=$gg+86400) {
					$dx = date( 'Y-m-d', $gg );
					$presenze = new presenze;
					$periodo=substr($dx,0,7);
					$presenze->id_lav=$id_lav;
					$presenze->id_servizio=$id_serv;
					$presenze->id_giustificativo=$id_giust;
					$presenze->periodo=$periodo;
					$presenze->data=$dx;
					$presenze->importo=$ore_gg;
					$presenze->note=$value_descr;
					$presenze->save();
				}
				
			}
		}
		$resp=array();
		$resp['esito']=true;
		$resp['msg']="";

	
		if (strlen($dele_contr)!=0) {
			giustificativi::where('id', $dele_contr)->delete();
			presenze::where('id_giustificativo', $dele_contr)->delete();
			
		}
		
		return $resp;
	
	}	

	public function giustificativi(Request $request){
		
		$save_edit=$this->save_edit_giustificativi($request);
		$view_dele=$request->input("view_dele");
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;

		$lavoratori=candidati::select('id','nominativo','tipo_contr','tipo_contratto')
		->where('status_candidatura','=',3)		
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
		
		$servizi_custom=DB::table('servizi_custom as s1')
		->select("s1.id","s1.descrizione","s1.alias_ref","s1.tipo_dato")
		
		->orderBy('s1.descrizione')->get();	
	//->where("s1.pre_load","<>","S")
		
		$giustificativi=DB::table('giustificativi as g')
		->select("g.id","c.nominativo",DB::raw("DATE_FORMAT(g.da_data,'%d-%m-%Y') as da_data"),DB::raw("DATE_FORMAT(g.a_data,'%d-%m-%Y') as a_data"),"id_cand","ore_gg","value_descr")
		->join("candidatis as c","g.id_cand","c.id")
		->get();

		return view('all_views/gestione/giustificativi')->with('giustificativi',$giustificativi)->with('view_dele',$view_dele)->with('save_edit',$save_edit)->with('lavoratori',$lavoratori)->with("servizi_custom",$servizi_custom);		
	}


}
