<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ditte;
use App\Models\preventivi;
use App\Models\aliquote_iva;
use App\Models\articoli_preventivo;

use DB;
use PDF;

class ControllerPreventivi extends Controller
{
public function __construct()
	{
		$this->middleware('auth')->except(['index']);
	}		


    public function prev_view($dati)
    {
		$request=request();

		$id_doc=$dati['id_doc'];
		$tipo_pagamento=$dati['tipo_pagamento'];
		$elenco_pagamenti_presenti=$dati['elenco_pagamenti_presenti'];
		
		$load_prev=preventivi::select('id_ditta',DB::raw("DATE_FORMAT(data_preventivo,'%d-%m-%Y') as data_preventivo"))
		->where('id','=',$id_doc)
		->get();
		$ditta=$load_prev[0]->id_ditta;
		$data_preventivo=$load_prev[0]->data_preventivo;

		$info=DB::table('ditte as d')
		->join('societa as s','d.id_azienda_prop','s.id')
		->select('s.descrizione','d.denominazione','d.piva','d.cf','d.cap','d.comune','d.provincia')
		->where("d.id","=",$ditta)
		->get();
		$denominazione="";$azienda_prop="";$piva="";$cf="";
		$cap="";$comune="";$provincia="";
		if (isset($info[0])) {
			$denominazione=$info[0]->denominazione;
			$azienda_prop=$info[0]->descrizione;
			$piva=$info[0]->piva;
			$cf=$info[0]->cf;
			$cap=$info[0]->cap;
			$comune=$info[0]->comune;
			$provincia=$info[0]->provincia;
		}	
	
		$articoli_preventivo=DB::table('articoli_preventivo as a')
		->select('a.id','a.id_doc','a.ordine','a.id_temp','a.codice','a.descrizione','a.quantita','a.um','a.prezzo_unitario','a.sconto','a.subtotale','a.aliquota')
		->where('a.id_doc', "=",$id_doc)
		->orderBy('a.ordine')
		->get();
		
		$aliquote_iva=aliquote_iva::select('id','aliquota','descrizione')
		->get();
		
		$arr_aliquota=array();
		foreach ($aliquote_iva as $aliquota) {
			if (isset($aliquota->id))
				$arr_aliquota[$aliquota->id]=$aliquota->aliquota;
		}		
		
		$data['id_doc']=$id_doc;
		$data['data_preventivo']=$data_preventivo;
		$data['denominazione']=$denominazione;
		$data['piva']=$piva;
		$data['cf']=$cf;
		$data['cap']=$cap;
		$data['comune']=$comune;
		$data['provincia']=$provincia;
		$data['azienda_prop']=$azienda_prop;
		$data['articoli_preventivo']=$articoli_preventivo;
		$data['arr_aliquota']=$arr_aliquota;
		$data['tipo_pagamento']=$tipo_pagamento;
		$data['elenco_pagamenti_presenti']=$elenco_pagamenti_presenti;


		$pdf = PDF::loadView('all_views/preventivi/prev_pdf' ,$data);
		
		$genera_pdf=$request->input('genera_pdf');
		if ($genera_pdf=="genera") {
			$fileName = "allegati/preventivi/".$id_doc.".pdf";
			$pdf->save($fileName);
			$info=preventivi::select('status')
			->where('id','=',$id_doc)
			->get();
			$stato=$info[0]->status;
			if ($stato<2) {
				preventivi::where('id', $id_doc)
				  ->update(['status' => 1]);					
			}
		}
		$preview_pdf=$request->input('preview_pdf');
		if ($preview_pdf=="preview") return $pdf->download('test.pdf');
    }

	public function crea_preventivo() {
		$request=request();
		$id_doc=$request->input('id_doc');
		$new_p=false;
		if (strlen($id_doc)==0) {
			$new_p=true;
			$preventivo = new preventivi;
		}
		else 
			$preventivo = preventivi::find($id_doc);

		$preventivo->id_ditta = $request->input('ditta');
		$preventivo->data_preventivo = $request->input('data_preventivo');
		$preventivo->save();
		$id_doc=$preventivo->id;
		$resp=array();
		$resp['id_doc']=$id_doc;
		
		return $resp;
	}

	public function preventivo($id=0) {
		$request=request();
		$preview_pdf=$request->input('preview_pdf');
		$genera_pdf=$request->input('genera_pdf');
		
		$id_doc=$request->input('id_doc');
		$ditta=$request->input('ditta');
		$data_preventivo=$request->input('data_preventivo');
		if ($id!=0) {
			$id_doc=$id;
			$load_preventivo=preventivi::select('id_ditta','data_preventivo')
			->where('id','=',$id_doc)
			->get();
			$ditta=$load_preventivo[0]->id_ditta;
			$data_preventivo=$load_preventivo[0]->data_preventivo;
		}		
		$ditte=DB::table('ditte as d')
		->join('societa as s','d.id_azienda_prop','s.id')
		->select('d.id','d.denominazione','s.id as id_azienda','s.descrizione as azienda')
		->orderBy('d.denominazione')	
		->get();
		
		$step_active=$request->input('step_active');
		if (strlen($step_active)==0) $step_active=0;
		
		
		
		$btn_ditta=$request->input('btn_ditta');
		$tipo_pagamento="?";
		
		if ($btn_ditta=="btn_ditta") {
			$info_preventivo=$this->crea_preventivo();
			$id_doc=$info_preventivo['id_doc'];
			
		}	
		$edit_riga=$request->input('edit_riga');
		if (strlen($edit_riga)!=0) $this->edit_riga($edit_riga);		
		
		$articoli_preventivo=DB::table('articoli_preventivo as a')
		->select('a.id','a.id_doc','a.ordine','a.id_temp','a.codice','a.descrizione','a.quantita','a.um','a.prezzo_unitario','a.sconto','a.subtotale','a.aliquota')
		->where('a.id_doc', "=",$id_doc)
		->get();
		
		//update totale in fattura from articoli_fattura
		if (strlen($id_doc)!=0) {
			$sum=DB::table('articoli_preventivo as a')
			->select(DB::raw('SUM(a.subtotale) AS somma'))
			->where('a.id_doc', "=",$id_doc)
			->get();
			$totale=$sum[0]->somma;
			preventivi::where('id', $id_doc)->update(['totale'=>$totale]);
		}
		//

		$aliquote_iva=aliquote_iva::select('id','aliquota','descrizione')
		->get();
		
		$arr_aliquota=array();
		foreach ($aliquote_iva as $aliquota) {
			if (isset($aliquota->id))
				$arr_aliquota[$aliquota->id]=$aliquota->aliquota;
		}		

		$tipo_pagamento="";$elenco_pagamenti_presenti="";
		$dati=array();
		$dati['id_doc']=$id_doc;
		$dati['tipo_pagamento']=$tipo_pagamento;
		$dati['elenco_pagamenti_presenti']=$elenco_pagamenti_presenti;
		if ($preview_pdf=="preview") return $this->prev_view($dati);
		if ($genera_pdf=="genera") $this->prev_view($dati);
		
		$data=['step_active'=>$step_active,'data_preventivo'=>$data_preventivo,'id_doc'=>$id_doc,'ditta'=>$ditta,'ditte'=>$ditte,'articoli_preventivo'=>$articoli_preventivo,'genera_pdf'=>$genera_pdf,'aliquote_iva'=>$aliquote_iva,'arr_aliquota'=>$arr_aliquota];
		
		return view('all_views/preventivi/preventivo')->with($data);
	}


	public function edit_riga($id_row) {
		$request=request();
		$id_doc=$request->input('id_doc');
		if ($id_row!=0)
			$art = articoli_preventivo::find($id_row);
		else 
			$art = new articoli_preventivo;
		$art->ordine = $request->input('ordine');
		$art->codice = $request->input('codice');
		$art->descrizione = $request->input('prodotto');
		$art->quantita = $request->input('quantita');
		$art->um = $request->input('um');
		$art->prezzo_unitario = $request->input('prezzo_unitario');
		$art->subtotale = $request->input('subtotale');
		if ($id_row==0) $art->id_doc=$id_doc;
		$art->aliquota = $request->input('aliquota');
		
		
		$art->save();
	}



	public function invito($id=0) {		
		$request=request();
		
		$preview_pdf=$request->input('preview_pdf');
		$genera_pdf=$request->input('genera_pdf');
		

		$step_active=$request->input('step_active');
		if (strlen($step_active)==0) $step_active=0;
		$ditta=$request->input('ditta');
		$data_invito = $request->input('data_invito');
		$range_da = $request->input('range_da');
		$range_a = $request->input('range_a');

		$btn_filtro=$request->input('btn_filtro');
		$filtroa=false;
		if ($btn_filtro=="filtro_appalti") $filtroa=true;

		$id_doc=$request->input('id_doc');
		if ($id!=0) {
			$id_doc=$id;
			$load_fattura=fatture::select('id_ditta','data_invito')
			->where('id','=',$id_doc)
			->get();
			$ditta=$load_fattura[0]->id_ditta;
			$data_invito=$load_fattura[0]->data_invito;
			
		}	
		
		
		$btn_ditta=$request->input('btn_ditta');
		$tipo_pagamento="?";
		if ($btn_ditta=="btn_ditta") {
			$info_fattura=$this->crea_fattura();
			$id_doc=$info_fattura['id_doc'];
			$tipo_pagamento=$info_fattura['tipo_pagamento'];
		}	
		
		
		$btn_import_app=$request->input('btn_import_app');
		if ($btn_import_app=="import_a") $this->import_from_appalti();

		$btn_pagamenti=$request->input('btn_pagamenti');
		if ($btn_pagamenti=="btn_pagamenti") $this->pagamenti();


		$edit_riga=$request->input('edit_riga');
		if (strlen($edit_riga)!=0) $this->edit_riga($edit_riga);

		$all=candidati::select('id','nominativo')->get();
		$all_lav=array();
		foreach($all as $single) {
			$all_lav[$single->id]=$single->nominativo;
		}
		$all=DB::table('servizi_ditte as sd')
		->join('servizi as s','sd.id_servizio','s.id')
		->select('s.descrizione','sd.id_servizio','sd.importo_ditta')
		->where('sd.id_ditta','=',$ditta)
		->get();
		$all_servizi=array();
		foreach($all as $single) {
			$all_servizi[$single->id_servizio]['descrizione']=$single->descrizione;
			$all_servizi[$single->id_servizio]['importo_ditta']=$single->importo_ditta;
		}		
		
		$ditteinapp=DB::table('appalti as a')
		->join("ditte as d","a.id_ditta","d.id")
		->join('serviziapp as sa','a.id','sa.id_appalto')
		->join('lavoratoriapp as la','a.id','la.id_appalto')
		->select("sa.id_servizio","la.id_lav_ref","la.status","d.id as id_ditta","d.denominazione","a.id as id_appalto",DB::raw("DATE_FORMAT(a.data_ref,'%d-%m-%Y') as data_ref"))
		->where(function ($query) use($ditta){	
			$query->where('a.id_ditta', "=",$ditta);	
		})
		->where('a.dele','=',0)
		->where('a.data_ref','>=',"$range_da")
		->where('a.data_ref','<=',"$range_a")
		->orderBy('a.id')
		->get();

		$id_servizi=array();$ids_lav=array();
		foreach($ditteinapp as $appalto) {
			if (isset($id_servizi[$appalto->id_appalto])) {
				if (!in_array($appalto->id_servizio,$id_servizi[$appalto->id_appalto]))
					$id_servizi[$appalto->id_appalto][]=$appalto->id_servizio;
			} else 	$id_servizi[$appalto->id_appalto][]=$appalto->id_servizio;
			

			if (isset($ids_lav[$appalto->id_appalto])) {
				if (!in_array($appalto->id_lav_ref,$ids_lav[$appalto->id_appalto]))
					$ids_lav[$appalto->id_appalto][]=$appalto->id_lav_ref;
			} else 	$ids_lav[$appalto->id_appalto][]=$appalto->id_lav_ref;

		}
		
		//->toSql() - dd($appalti);exit;




		$ditte=DB::table('ditte as d')
		->join('societa as s','d.id_azienda_prop','s.id')
		->select('d.id','d.denominazione','s.id as id_azienda','s.descrizione as azienda')
		->orderBy('d.denominazione')	
		->get();				

		$info=DB::table('ditte as d')
		->join('societa as s','d.id_azienda_prop','s.id')
		->select('s.info_iban')
		->where("d.id","=",$ditta)
		->get();
		$info_iban="";
		if (isset($info[0])) $info_iban=$info[0]->info_iban;
		


		$aliquote_iva=aliquote_iva::select('id','aliquota','descrizione')
		->get();
		
		$arr_aliquota=array();
		foreach ($aliquote_iva as $aliquota) {
			if (isset($aliquota->id))
				$arr_aliquota[$aliquota->id]=$aliquota->aliquota;
		}

		$dele_ele=$request->input('dele_ele');
		if (strlen($dele_ele)!=0) {
				$deleted = articoli_fattura::where('id',$dele_ele)
				->delete();
		}

		
		$articoli_fattura=DB::table('articoli_fattura as a')
		->select('a.id','a.id_doc','a.ordine','a.id_temp','a.codice','a.descrizione','a.quantita','a.um','a.prezzo_unitario','a.sconto','a.subtotale','a.aliquota')
		->where('a.id_doc', "=",$id_doc)
		->get();
		
		//update totale in fattura from articoli_fattura
		if (strlen($id_doc)!=0) {
			$sum=DB::table('articoli_fattura as a')
			->select(DB::raw('SUM(a.subtotale) AS somma'))
			->where('a.id_doc', "=",$id_doc)
			->get();
			$totale=$sum[0]->somma;
			fatture::where('id', $id_doc)->update(['totale'=>$totale]);			
		}
		//
		
		$lista_pagamenti=$this->lista_pagamenti();
		
		if ($tipo_pagamento!="?") {
			$arr_p=explode(";",$tipo_pagamento);
			
			$elenco_pagamenti_presenti=array();
			for ($sca=0;$sca<=count($arr_p)-1;$sca++) {
				$t=$arr_p[$sca];
				$elenco_pagamenti_presenti[]=[
					"tipo_pagamento"=>$t,
					"data_scadenza"=>"",
					"importo"=>"",
					"persona"=>"",
					"coordinate"=>$info_iban,
				];
			}
			
		}	
		else {
			$elenco=DB::table('pagamenti as p')
			->select('p.id','p.id_doc','p.tipo_pagamento','p.data_scadenza','p.importo','p.persona','p.coordinate')
			->where('p.id_doc', "=",$id_doc)
			->get();
			$elenco_pagamenti_presenti=array();
			foreach ($elenco as $info_pagamento) {
				$elenco_pagamenti_presenti[]=[
					"tipo_pagamento"=>$info_pagamento->tipo_pagamento,
					"data_scadenza"=>$info_pagamento->data_scadenza,
					"importo"=>$info_pagamento->importo,
					"persona"=>$info_pagamento->persona,
					"coordinate"=>$info_pagamento->coordinate,
				];
			}
		}
		
		$dati=array();
		$dati['id_doc']=$id_doc;
		$dati['tipo_pagamento']=$tipo_pagamento;
		$dati['elenco_pagamenti_presenti']=$elenco_pagamenti_presenti;
		if ($preview_pdf=="preview") return $this->Invoice($dati);
		if ($genera_pdf=="genera") $this->Invoice($dati);
	
		return view('all_views/invitofatt/invito')->with('id_doc',$id_doc)->with("ditte",$ditte)->with("ditteinapp",$ditteinapp)->with('ditta',$ditta)->with('data_invito',$data_invito)->with('step_active',$step_active)->with('articoli_fattura',$articoli_fattura)->with('aliquote_iva',$aliquote_iva)->with('range_da',$range_da)->with('range_a',$range_a)->with('filtroa',$filtroa)->with('arr_aliquota',$arr_aliquota)->with('lista_pagamenti',$lista_pagamenti)->with('elenco_pagamenti_presenti',$elenco_pagamenti_presenti)->with('id_fattura',$id)->with('info_iban',$info_iban)->with('genera_pdf',$genera_pdf)->with('ids_lav',$ids_lav)->with('id_servizi',$id_servizi)->with('all_lav',$all_lav)->with('all_servizi',$all_servizi);
	}



}
