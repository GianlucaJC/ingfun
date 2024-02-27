<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ditte;
use App\Models\articoli_fattura;
use App\Models\aliquote_iva;
use App\Models\pagamenti;
use App\Models\fatture;
use App\Models\preventivi;
use App\Models\candidati;
use App\Models\servizi_ditte;
use App\Models\servizi;
use App\Models\appalti;
use App\Models\prod_prodotti;
use App\Models\prod_magazzini;

use DB;
use PDF;

class ControllerInvito extends Controller
{
public function __construct()
	{
		$this->middleware('auth')->except(['index']);
	}		

	public function save_edit_aliquote($request) {
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$descr_contr=$request->input("descr_contr");
		$aliquota=$request->input("aliquota");
		
		
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");
		
		
		$data=['dele'=>0, 'descrizione' => $descr_contr,'aliquota'=>$aliquota];
		

		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0 && $edit_elem==0) {
			DB::table("aliquote_iva")->insert($data);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			aliquote_iva::where('id', $edit_elem)			
			  ->update($data);
		}
		if (strlen($dele_contr)!=0) {
			aliquote_iva::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			aliquote_iva::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}

	}
	
	public function aliquote(Request $request) {
		$esito_saves=$this->save_edit_aliquote($request);
		$view_dele=$request->input("view_dele");
		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		
		$aliquote=DB::table('aliquote_iva')
		->select("*")
		->when($view_dele=="0", function ($aliquote) {
			return $aliquote->where('dele', "=","0");
		})
		->orderBy('aliquota')->get();

		return view('all_views/invitofatt/aliquote')->with('aliquote', $aliquote)->with("view_dele",$view_dele)->with('esito_saves',$esito_saves);		
	}
	
	
	public function import_from_appalti() {
		$request=request();
		$app_sel=$request->input('app_sel');
		$id_doc=$request->input('id_doc');
		$importi=array();
		$range_da=$request->input('range_da');
		$range_a=$request->input('range_a');

		$aliquote_iva=aliquote_iva::select('id','aliquota','descrizione')
		->get();
		
		$arr_aliquota=array();
		foreach ($aliquote_iva as $aliquota) {
			if (isset($aliquota->id))
				$arr_aliquota[$aliquota->id]=$aliquota->aliquota;
		}
		
		if (is_array($app_sel)) {
			$indice=0;
			for ($sca=0;$sca<=count($app_sel)-1;$sca++) {
				$id_app=$app_sel[$sca];
				$deleted = articoli_fattura::where('id_appalto', $id_app)
				->where('id_doc',$id_doc)
				->delete();
		
				$appalti=DB::table('appalti as a')
				->join("serviziapp as s","a.id","s.id_appalto")
				->select("a.id_ditta",DB::raw("DATE_FORMAT(a.data_ref,'%d-%m-%Y') as data_ref"),"s.id_servizio","a.km_percorrenza","a.orario_fine_servizio")
				->where('a.id', "=",$id_app)	
				->get();

				appalti::where('id', $id_app)->update(['status' => 1]);					
				
				foreach ($appalti as $appalto) {
					$data_ref=$appalto->data_ref;
					$id_ditta=$appalto->id_ditta;
					$id_servizio=$appalto->id_servizio;
					$km=$appalto->km_percorrenza;
					
					$servizi_ditte=DB::table('servizi_ditte as sd')
					->join('servizi as s','sd.id_servizio','s.id')
					->select("s.descrizione","sd.importo_ditta","sd.aliquota")
					->where('sd.id_ditta', "=",$id_ditta)	
					->where('sd.id_servizio', "=",$id_servizio)	
					->get(); 
					foreach ($servizi_ditte as $servizio) {
						$importo_ditta=$servizio->importo_ditta;
						$aliquota=$servizio->aliquota;
						$descr=$servizio->descrizione."($data_ref)";
						$subtotale=$importo_ditta;
						if(strpos($descr,'RIMBORSO KM') !== false) {
							$descr.=" ".$km."*".$importo_ditta;
							$importo_ditta=floatval($km)*floatval($importo_ditta);
							$subtotale=$importo_ditta;
						}
						if (isset($arr_aliquota[$aliquota])) 
							$subtotale=$importo_ditta*(($arr_aliquota[$aliquota]/100)+1);
							
						DB::table('articoli_fattura')->insert([
							'id_appalto' => $id_app,
							'id_doc' => $id_doc,
							'descrizione' =>$descr,
							'quantita' => 1,
							'prezzo_unitario' =>$importo_ditta,
							'aliquota' =>$aliquota,
							'subtotale' =>$subtotale,
							'created_at'=>now(),
							'updated_at'=>now()
						]);							
					}	
				}
			}
		}
		return $importi;
	}

	public function edit_riga($id_row) {
		$request=request();
		$id_doc=$request->input('id_doc');
		if ($id_row!=0)
			$art = articoli_fattura::find($id_row);
		else 
			$art = new articoli_fattura;
		$art->ordine = $request->input('ordine');
		
		
		$art->codice = $request->input('codice');
		$art->mag_sca = $request->input('mag_sca');
		
		$art->descrizione = $request->input('prodotto');
		$art->quantita = $request->input('quantita');
		$art->um = $request->input('um');
		$art->prezzo_unitario = $request->input('prezzo_unitario');
		$art->subtotale = $request->input('subtotale');
		if ($id_row==0) $art->id_doc=$id_doc;
		$art->aliquota = $request->input('aliquota');
		
		
		$art->save();
	}

	public function pagamenti() {
		$request=request();
		$id_doc=$request->input('id_doc');
		
		$tipo_pagamento=$request->input('tipo_pagamento');
		$data_scadenza=$request->input('data_scadenza');
		$importo=$request->input('importo');
		$persona=$request->input('persona');
		$coordinate=$request->input('coordinate');
		
		$deleted = pagamenti::where('id_doc',$id_doc)->delete();

		
		if (is_array($data_scadenza)) {
			for ($sca=0;$sca<=count($data_scadenza)-1;$sca++) {
				$pagamenti = new pagamenti;
				$pagamenti->id_doc=$id_doc;
				$tipo=$tipo_pagamento[$sca];
				$ds=$data_scadenza[$sca];
				$im=$importo[$sca];
				$ps=$persona[$sca];
				$co=$coordinate[$sca];
				
				$pagamenti->tipo_pagamento=$tipo;
				$pagamenti->data_scadenza=$ds;
				$pagamenti->importo=$im;
				$pagamenti->persona=$ps;
				$pagamenti->coordinate=$co;
				$pagamenti->save();
			}
		}
		
		
		
	}
	
	public function crea_fattura() {
		$request=request();
		$id_doc=$request->input('id_doc');
		$new_f=false;
		if (strlen($id_doc)==0) {
			$new_f=true;
			$fattura = new fatture;
		}
		else 
			$fattura = fatture::find($id_doc);

		$fattura->id_ditta = $request->input('ditta');
		$fattura->data_invito = $request->input('data_invito');
		$fattura->id_sezionale = $request->input('sezionale');
		$fattura->save();
		$id_doc=$fattura->id;
		$resp=array();
		$resp['id_doc']=$id_doc;


		$info=DB::table('ditte as d')
		->select('d.tipo_pagamento')
		->where('d.id','=',$request->input('ditta'))
		->get();
		if (isset($info[0]->tipo_pagamento) && $new_f==true)
			$resp['tipo_pagamento']=$info[0]->tipo_pagamento;
		else
			$resp['tipo_pagamento']="?";
		
		return $resp;
	}

    public function Invoice($dati)
    {
		$request=request();
		
		$id_doc=$dati['id_doc'];
		$tipo_pagamento=$dati['tipo_pagamento'];
		$elenco_pagamenti_presenti=$dati['elenco_pagamenti_presenti'];
		
		$load_fattura=fatture::select('id_ditta',DB::raw("DATE_FORMAT(data_invito,'%d-%m-%Y') as data_invito"),"id_sezionale")
		->where('id','=',$id_doc)
		->get();
		$ditta=$load_fattura[0]->id_ditta;
		$data_invito=$load_fattura[0]->data_invito;
		$sezionale=$load_fattura[0]->id_sezionale;

		$info=DB::table('societa')
		->select('descrizione')
		->where("id","=",$sezionale)
		->get();
		
		$azienda_prop="";
		if (isset($info[0])) $azienda_prop=$info[0]->descrizione;

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
		->select('d.denominazione','d.piva','d.cf','d.cap','d.comune','d.provincia','d.pec','d.sdi')
		->where("d.id","=",$ditta)
		->get();
		$denominazione="";$piva="";$cf="";
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
		
		$articoli_fattura=DB::table('articoli_fattura as a')
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
		$data['sezionale']=$sezionale;
		$data['data_invito']=$data_invito;
		$data['denominazione']=$denominazione;
		$data['piva']=$piva;
		$data['cf']=$cf;
		$data['cap']=$cap;
		$data['comune']=$comune;
		$data['provincia']=$provincia;
		$data['azienda_prop']=$azienda_prop;
		$data['articoli_fattura']=$articoli_fattura;
		$data['arr_aliquota']=$arr_aliquota;
		$data['tipo_pagamento']=$tipo_pagamento;
		$data['sdi']=$sdi;
		$data['pec']=$pec;
		$data['elenco_pagamenti_presenti']=$elenco_pagamenti_presenti;
		

		$pdf = PDF::loadView('all_views/invitofatt/invoice_pdf' ,$data);
		
		$genera_pdf=$request->input('genera_pdf');
		if ($genera_pdf=="genera") {
			$fileName = "allegati/fatture/".$id_doc.".pdf";
			$pdf->save($fileName);
			$info=fatture::select('status')
			->where('id','=',$id_doc)
			->get();
			$stato=$info[0]->status;
			if ($stato<2) {
				fatture::where('id', $id_doc)
				  ->update(['status' => 1]);					
			}
		}
		$preview_pdf=$request->input('preview_pdf');
		if ($preview_pdf=="preview") return $pdf->download('test.pdf');
    }


	public function import_prev() {
		$request=request();
		$prev_sel=$request->input('prev_sel');
		$id_doc=$request->input('id_doc');
		
		if (isset($prev_sel[0])) {
			$id_prev=$prev_sel[0];
			$select = DB::table('articoli_preventivo as ap')
				->select(DB::raw("$id_doc as active"),'codice','descrizione','quantita','um','id_um','prezzo_unitario','sconto','subtotale','aliquota','id_aliquota')
				->where('ap.id_doc', $id_prev);
				
			$rows_inserted = DB::table('articoli_fattura')
				->insertUsing(['id_doc','codice','descrizione', 'quantita','um','id_um','prezzo_unitario','sconto','subtotale','aliquota','id_aliquota'], $select);
				
			preventivi::where('id', $id_prev)->update(['status'=>5]);
		}
	}
	
	public function invito($id=0) {		
		$request=request();
		
		$preview_pdf=$request->input('preview_pdf');
		$genera_pdf=$request->input('genera_pdf');
		

		$step_active=$request->input('step_active');
		if (strlen($step_active)==0) $step_active=0;
		$ditta=$request->input('ditta');
		$data_invito = $request->input('data_invito');
		$sezionale = $request->input('sezionale');
		
		if (!$request->has('data_invito')) $data_invito=date("Y-m-d");
		$range_da = $request->input('range_da');
		$range_a = $request->input('range_a');

		$btn_filtro=$request->input('btn_filtro');
		$import_prev=$request->input('import_prev');
		
		if ($import_prev=="importa") $this->import_prev();
		
		$filtroa=false;
		if ($btn_filtro=="filtro_appalti") $filtroa=true;

		$id_doc=$request->input('id_doc');
		if ($id!=0) {
			$id_doc=$id;
			$load_fattura=fatture::select('id_ditta','data_invito','id_sezionale')
			->where('id','=',$id_doc)
			->get();
			$ditta=$load_fattura[0]->id_ditta;
			$data_invito=$load_fattura[0]->data_invito;
			$sezionale=$load_fattura[0]->id_sezionale;
			
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



		$preventivi=DB::table('preventivi as p')
		->join('ditte as d','p.id_ditta','d.id')
		->select("p.status","p.id","p.dele",DB::raw("DATE_FORMAT(p.data_preventivo,'%d-%m-%Y') as data_preventivo"),"p.totale","d.denominazione")
		->where('p.dele', "=","0")
		->where('p.id_ditta','=',$ditta)
		->where('p.status','<>',5)
		->groupBy('p.id')
		->orderBy('p.id','desc')->get();

		$edit_riga=$request->input('edit_riga');
		if (strlen($edit_riga)!=0) $this->edit_riga($edit_riga);

		$all=candidati::select('id','nominativo')->get();
		$all_lav=array();
		foreach($all as $single) {
			$all_lav[$single->id]=$single->nominativo;
		}
		$all_s=DB::table('servizi_ditte as sd')
		->join('servizi as s','sd.id_servizio','s.id')
		->select('s.descrizione','sd.id_servizio','sd.importo_ditta','sd.aliquota')
		->where('sd.id_ditta','=',$ditta)
		->get();
		$all_servizi=array();
		foreach($all_s as $single) {
			$all_servizi[$single->id_servizio]['descrizione']=$single->descrizione;
			$all_servizi[$single->id_servizio]['importo_ditta']=$single->importo_ditta;
		}	
		
		
		$ditteinapp=DB::table('appalti as a')
		->join("ditte as d","a.id_ditta","d.id")
		->join('serviziapp as sa','a.id','sa.id_appalto')
		->join('lavoratoriapp as la','a.id','la.id_appalto')
		->select("sa.id_servizio","la.id_lav_ref","la.status","d.id as id_ditta","d.denominazione","a.id as id_appalto",DB::raw("DATE_FORMAT(a.data_ref,'%d-%m-%Y') as data_ref"),"a.km_percorrenza","a.orario_fine_servizio")
		->where(function ($query) use($ditta){	
			$query->where('a.id_ditta', "=",$ditta);	
		})
		->where('a.dele','=',0)
		->where('a.status','<>',1)
		->when(strlen($range_da)!=0, function ($ditteinapp) use($range_da) {
			return $ditteinapp->where('a.data_ref','>=',"$range_da");
		})
		->when(strlen($range_a)!=0, function ($ditteinapp) use($range_a) {
			return $ditteinapp->where('a.data_ref','<=',"$range_a");
		})
		->orderBy('a.id')
		->get();

		$id_servizi=array();$ids_lav=array();
		$indice_s=1;
		
		foreach($ditteinapp as $appalto) {
			if (isset($id_servizi[$appalto->id_appalto])) {
				if (!in_array($appalto->id_servizio,$id_servizi[$appalto->id_appalto])) {
					$id_servizi[$appalto->id_appalto][$indice_s]=$appalto->id_servizio;
					$indice_s++;
				}
			} else 	{
				$id_servizi[$appalto->id_appalto][0]=$appalto->id_servizio;
				$indice_s=1;
			}
			

			if (isset($ids_lav[$appalto->id_appalto])) {
				if (!in_array($appalto->id_lav_ref,$ids_lav[$appalto->id_appalto]))
					$ids_lav[$appalto->id_appalto][]=$appalto->id_lav_ref;
			} else 	$ids_lav[$appalto->id_appalto][]=$appalto->id_lav_ref;

		}
		
		//->toSql() - dd($appalti);exit;




		$ditte=DB::table('ditte as d')
		->select('d.id','d.denominazione')
		->where('d.dele','=',0)
		->orderBy('d.denominazione')	
		->get();				


		$sezionali=DB::table('societa as s')
		->select('s.id','s.descrizione','s.info_iban')
		->where('s.dele','=',0)
		->orderBy('s.descrizione')	
		->get();
		
		$info=DB::table('societa as s')
		->select('s.info_iban')
		->where('s.id','=',$sezionale)
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
		->select('a.id','a.id_doc','a.ordine','a.id_temp','a.codice','a.descrizione','a.mag_sca','a.quantita','a.um','a.prezzo_unitario','a.sconto','a.subtotale','a.aliquota')
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
		
		$codici=prod_prodotti::select("id","descrizione")
		->orderBy('descrizione')->get();
		
		$magazzini=prod_magazzini::select('id','descrizione')->orderBy('descrizione')->get();
		
		
		$dati=array();
		$dati['id_doc']=$id_doc;
		$dati['tipo_pagamento']=$tipo_pagamento;
		$dati['elenco_pagamenti_presenti']=$elenco_pagamenti_presenti;
		if ($preview_pdf=="preview") return $this->Invoice($dati);
		if ($genera_pdf=="genera") $this->Invoice($dati);
	
		return view('all_views/invitofatt/invito')->with('id_doc',$id_doc)->with("ditte",$ditte)->with("ditteinapp",$ditteinapp)->with('ditta',$ditta)->with('data_invito',$data_invito)->with('step_active',$step_active)->with('articoli_fattura',$articoli_fattura)->with('aliquote_iva',$aliquote_iva)->with('range_da',$range_da)->with('range_a',$range_a)->with('filtroa',$filtroa)->with('arr_aliquota',$arr_aliquota)->with('lista_pagamenti',$lista_pagamenti)->with('elenco_pagamenti_presenti',$elenco_pagamenti_presenti)->with('id_fattura',$id)->with('info_iban',$info_iban)->with('genera_pdf',$genera_pdf)->with('ids_lav',$ids_lav)->with('id_servizi',$id_servizi)->with('all_lav',$all_lav)->with('all_servizi',$all_servizi)->with('preventivi',$preventivi)->with('all_s',$all_s)->with('sezionali',$sezionali)->with('sezionale',$sezionale)->with('codici',$codici)->with('magazzini',$magazzini);
	}

	function lista_pagamenti() {
		$lista=array();
		$lista[0]['id']=1;
		$lista[0]['descrizione']="Contanti";
		$lista[1]['id']=2;
		$lista[1]['descrizione']="Bancomat";
		$lista[2]['id']=3;
		$lista[2]['descrizione']="Assegno";
		$lista[3]['id']=4;
		$lista[3]['descrizione']="Bonifico";

		return $lista;
	}

	public function save_newapp(Request $request) {
		$id_user=Auth::user()->id;
		$id_app=$request->input('id_app');
		if ($id_app!=0)
			$appalti = appalti::find($id_app);
		else {
			$appalti = new appalti;
			$appalti->id_creator = $id_user;
		}	
		$id_ditta=$request->input('ditta');

		$appalti->descrizione_appalto = $request->input('descrizione_appalto');
		$appalti->data_ref = $request->input('data_app');
		$appalti->orario_ref = $request->input('ora_app');
		$appalti->id_ditta = $id_ditta;
		$appalti->targa = $request->input('mezzo');
		$appalti->note = $request->input('note');
		$appalti->variazione = $request->input('variazione');
		$appalti->save();
		if ($id_app==0) $id_app=$appalti->id;

		$deleted = serviziapp::where('id_appalto', $id_app)->delete();
		$servizi=$request->input('servizi');
		for ($sca=0;$sca<=count($servizi)-1;$sca++) {
			$id_servizio=$servizi[$sca];
			
			$importo_lavoratore=DB::table('servizi_ditte')
			->select('importo_lavoratore')
			->where('id_ditta', "=", $id_ditta)
			->where('id_servizio', "=", $id_servizio)
			->get()->first()->importo_lavoratore;
			if ($importo_lavoratore==null || strlen($importo_lavoratore)==0) $importo_lavoratore=0;
			
			DB::table('serviziapp')->insert([
				'id_appalto' => $id_app,
				'id_servizio' => $id_servizio,
				'importo_lavoratore' => $importo_lavoratore,
				'created_at'=>now(),
				'updated_at'=>now()
			]);			
		}
		
		
		
		$to_delete = lavoratoriapp::where('id_appalto', $id_app)->update(['to_delete'=>1]);
		$lavoratori=$request->input('lavoratori');
		$num_send=0;
		for ($sca=0;$sca<=count($lavoratori)-1;$sca++) {
			$send=false;
			$id_lav_ref=$lavoratori[$sca];
			$count=lavoratoriapp::where('id_appalto','=',$id_app)
			->where('id_lav_ref','=',$id_lav_ref)
			->count();
			if ($count==0) {
				DB::table('lavoratoriapp')->insert([
					'id_appalto' => $id_app,					
					'id_lav_ref' => $id_lav_ref,
					'created_at'=>now(),
					'updated_at'=>now()
				]);
				//in caso di nuovi lavoratori inseriti, invio push
				$send=true;
				$resp=candidati::select('u.push_id')
				->join('users as u','candidatis.id_user','u.id')
				->where("candidatis.id","=", $id_lav_ref);
				if ($resp->count()==0) $send=false;
				else {
					$push_id=$resp->get()->first()->push_id;
					if ($push_id==null || strlen($push_id)==0) $send=false;
				}				
			} else {
				$data=['to_delete' => 0];
				lavoratoriapp::where('id_appalto', $id_app)			
				->where('id_lav_ref','=',$id_lav_ref)
				->update($data);
				
			}	
			if ($send==true) {
				$num_send++;
				$this->send_push($push_id,"new","");
			}	

		}
		
		//push per eventuali estromessi dall'appalto
		$resp=candidati::select('u.push_id')
		->join('users as u','candidatis.id_user','u.id')
		->join('lavoratoriapp as l','l.id_lav_ref','candidatis.id')		
		->where("l.to_delete","=", 1)
		->where("l.id_appalto","=", $id_app)
		->groupBy('candidatis.id');
		if ($resp->count()!=0){
			$all_push=$resp->get();
			foreach($all_push as $single) {
				$push_id=$single->push_id;
				$this->send_push($push_id,"dele",$request->input('descrizione_appalto'));
			}					

		}		
		$deleted = lavoratoriapp::where('to_delete','=',1)
		->where('id_appalto','=',$id_app)->delete();
		
		//push per eventuale variazione (max una)
		$flag_variazione=$request->input('flag_variazione');
		if ($flag_variazione=="1" && strlen($request->input('variazione'))!=0) {
			$resp=candidati::select('u.push_id')
			->join('users as u','candidatis.id_user','u.id')
			->join('lavoratoriapp as l','l.id_lav_ref','candidatis.id')		
			->where("l.id_appalto","=", $id_app)
			->groupBy('candidatis.id');	
			if ($resp->count()!=0){
				$all_push=$resp->get();
				foreach($all_push as $single) {
					$push_id=$single->push_id;
					$this->send_push($push_id,"edit",$request->input('descrizione_appalto'));
				}					

			}			
		}
			
		//
		
		return redirect()->route("newapp",['id'=>$id_app,'from'=>1,'num_send'=>$num_send]);

	}
	
	public function lista_inviti(Request $request) {
		if ($request->has("btn_change_state")) {
			$btn_change_state=$request->input("btn_change_state");
			if ($btn_change_state=="change") {
				$id_fatt_change=$request->input("id_fatt_change");
				$stato_fattura=$request->input("stato_fattura");
				fatture::where('id', $id_fatt_change)
				  ->update(['status' => $stato_fattura]);			
			}
		}

		$view_dele=$request->input("view_dele");
		
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");
		
		if (strlen($dele_contr)!=0) {
			fatture::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			fatture::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;

		
		$fatture=DB::table('fatture as f')
		->join('ditte as d','f.id_ditta','d.id')
		->join('societa as s','f.id_sezionale','s.id')
		->select("f.status","f.id","f.dele",DB::raw("DATE_FORMAT(f.data_invito,'%d-%m-%Y') as data_invito"),"f.totale","d.denominazione","s.descrizione as sezionale")
		->when($view_dele=="0", function ($fatture) {
			return $fatture->where('f.dele', "=","0");
		})
		->groupBy('f.id')
		->orderBy('f.id','desc')->get();
		

		return view('all_views/invitofatt/lista_inviti')->with("view_dele",$view_dele)->with('fatture',$fatture);

		
	}	




}
