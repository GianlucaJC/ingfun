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
		//$this->middleware('auth')->except(['index']);
	}		


    public function prev_view($dati)
    {
		$request=request();

		$id_doc=$dati['id_doc'];
		$note=$request->input('note');
		$p = preventivi::find($id_doc);
		$p->note=$note;
		$p->save();
		
		$tipo_pagamento=$dati['tipo_pagamento'];
		$elenco_pagamenti_presenti=$dati['elenco_pagamenti_presenti'];
		
		$load_prev=DB::table('preventivi as p')
		->leftJoin('societa as s','p.id_sezionale','s.id')
		->select('p.id_ditta',DB::raw("DATE_FORMAT(p.data_preventivo,'%d-%m-%Y') as data_preventivo"),'p.note',"s.descrizione","s.id as sezionale")
		->where('p.id','=',$id_doc)
		->get();
		$ditta=$load_prev[0]->id_ditta;
		$data_preventivo=$load_prev[0]->data_preventivo;
		$azienda_prop=$load_prev[0]->descrizione;
		$sezionale=$load_prev[0]->sezionale;
		
		$note=$load_prev[0]->note;

		$comuni=DB::table('italy_cities as c')
		->select('c.cap','c.provincia','c.comune')
		->get();
		$arr_comuni=array();
		foreach ($comuni as $com) {
			$cap=$com->cap;
			$pro=$com->provincia;
			$comune=$com->comune;
			$ref="$cap|$pro";
			$arr_comuni[$ref]=$comune;
		}


		$info=DB::table('ditte as d')
		->select('d.denominazione','d.piva','d.cf','d.cap','d.comune','d.provincia','d.sdi','d.pec')
		->where("d.id","=",$ditta)
		->get();
		$denominazione="";;$piva="";$cf="";
		$cap="";$comune="";$provincia="";
		$sdi="";$pec="";		
		if (isset($info[0])) {
			$denominazione=$info[0]->denominazione;
			$piva=$info[0]->piva;
			$cf=$info[0]->cf;
			$cap=$info[0]->cap;
			$provincia=$info[0]->provincia;
			$sdi=$info[0]->sdi;
			$pec=$info[0]->pec;
			$comune="";
			$ref="$cap|$provincia";
			if (isset($arr_comuni[$ref])) $comune=$arr_comuni[$ref];			
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
		$data['note']=$note;
		$data['denominazione']=$denominazione;
		$data['piva']=$piva;
		$data['cf']=$cf;
		$data['cap']=$cap;
		$data['comune']=$comune;
		$data['provincia']=$provincia;
		$data['sezionale']=$sezionale;
		$data['azienda_prop']=$azienda_prop;
		$data['articoli_preventivo']=$articoli_preventivo;
		$data['arr_aliquota']=$arr_aliquota;
		$data['tipo_pagamento']=$tipo_pagamento;
		$data['sdi']=$sdi;
		$data['pec']=$pec;		
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
		$preventivo->id_sezionale = $request->input('sezionale');
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
		$btn_dele_prev=$request->input('btn_dele_prev');
		$id_doc=$request->input('id_doc');

		if ($btn_dele_prev=="dele_prev") {
			$path = 'allegati/preventivi_firmati/*';
			foreach (glob($path) as $filename) {
				$info=explode(".",$filename);
				$ff=$info[0];
				$ff=str_replace("allegati/preventivi_firmati/","",$ff);
				if ($ff==$id_doc) 
					@unlink($filename);
			}			
		}		
		
		
		$ditta=$request->input('ditta');
		$data_preventivo=$request->input('data_preventivo');
		$sezionale=$request->input('sezionale');
		if (!$request->has('data_preventivo')) $data_preventivo=date("Y-m-d");
		$note="";
		if ($id!=0) {
			$id_doc=$id;
			$load_preventivo=preventivi::select('id_ditta','data_preventivo','note','id_sezionale')
			->where('id','=',$id_doc)
			->get();
			$ditta=$load_preventivo[0]->id_ditta;
			$data_preventivo=$load_preventivo[0]->data_preventivo;
			$note=$load_preventivo[0]->note;
			$sezionale=$load_preventivo[0]->id_sezionale;
		}		
		$ditte=DB::table('ditte as d')
		->select('d.id','d.denominazione')
		->orderBy('d.denominazione')	
		->get();
		
		$sezionali=DB::table('societa as s')
		->select('s.id','s.descrizione','s.info_iban')
		->where('s.dele','=',0)
		->orderBy('s.descrizione')	
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
		
		

		$dele_ele=$request->input('dele_ele');
		if (strlen($dele_ele)!=0) {
				$deleted = articoli_preventivo::where('id',$dele_ele)
				->delete();
		}		
		
		$articoli_preventivo=DB::table('articoli_preventivo as a')
		->select('a.id','a.id_doc','a.ordine','a.id_temp','a.codice','a.descrizione','a.quantita','a.um','a.prezzo_unitario','a.sconto','a.subtotale','a.aliquota','a.id_servizio')
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

		$all_servizi=DB::table('servizi_ditte as sd')
		->join('servizi as s','sd.id_servizio','s.id')
		->select('s.descrizione','sd.id_servizio','sd.importo_ditta','sd.aliquota')
		->where('sd.id_ditta','=',$ditta)
		->get();


		$tipo_pagamento="";$elenco_pagamenti_presenti="";
		$dati=array();
		$dati['id_doc']=$id_doc;
		$dati['tipo_pagamento']=$tipo_pagamento;
		$dati['elenco_pagamenti_presenti']=$elenco_pagamenti_presenti;
		if ($preview_pdf=="preview") return $this->prev_view($dati);
		if ($genera_pdf=="genera") $this->prev_view($dati);
		
		$data=['step_active'=>$step_active,'data_preventivo'=>$data_preventivo,'note'=>$note,'id_doc'=>$id_doc,'ditta'=>$ditta,'ditte'=>$ditte,'articoli_preventivo'=>$articoli_preventivo,'genera_pdf'=>$genera_pdf,'aliquote_iva'=>$aliquote_iva,'arr_aliquota'=>$arr_aliquota,'all_servizi'=>$all_servizi,'sezionali'=>$sezionali,'sezionale'=>$sezionale];
		
		return view('all_views/preventivi/preventivo')->with($data);
	}


	public function edit_riga($id_row) {
		$request=request();
		$service=$request->input("service");
			
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
		if (strlen($service)==0)
			$art->id_servizio=null;
		else {
			$info=explode("|",$service);
			if (count($info)>2) $art->id_servizio=$info[0];
		}
		

		$art->save();
	}



	public function lista_preventivi(Request $request) {
		if ($request->has("btn_change_state")) {
			$btn_change_state=$request->input("btn_change_state");
			if ($btn_change_state=="change") {
				$id_prev_change=$request->input("id_prev_change");
				$stato_prev=$request->input("stato_prev");
				preventivi::where('id', $id_prev_change)
				  ->update(['status' => $stato_prev]);			
			}
		}

		$view_dele=$request->input("view_dele");
		
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");
		
		if (strlen($dele_contr)!=0) {
			preventivi::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			preventivi::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;

		$preventivi=DB::table('preventivi as p')
		->join('ditte as d','p.id_ditta','d.id')
		->join('societa as s','p.id_sezionale','s.id')
		->select("p.status","p.id","p.dele",DB::raw("DATE_FORMAT(p.data_preventivo,'%d-%m-%Y') as data_preventivo"),"p.totale","d.denominazione","s.descrizione as sezionale")
		->when($view_dele=="0", function ($preventivi) {
			return $preventivi->where('p.dele', "=","0");
		})
		->groupBy('p.id')
		->orderBy('p.id','desc')->get();		

		return view('all_views/preventivi/lista_preventivi')->with("view_dele",$view_dele)->with('preventivi',$preventivi);

		
	}	


}
