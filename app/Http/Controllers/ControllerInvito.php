<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ditte;
use App\Models\articoli_fattura;
use App\Models\aliquote_iva;
use App\Models\pagamenti;
use App\Models\fatture;
use DB;

class ControllerInvito extends Controller
{
public function __construct()
	{
		$this->middleware('auth')->except(['index']);
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
				->select("a.id_ditta","s.id_servizio")
				->where('a.id', "=",$id_app)	
				->get();
				foreach ($appalti as $appalto) {
					$id_ditta=$appalto->id_ditta;
					$id_servizio=$appalto->id_servizio;
					
					$servizi_ditte=DB::table('servizi_ditte as sd')
					->join('servizi as s','sd.id_servizio','s.id')
					->select("s.descrizione","sd.importo_ditta","sd.aliquota")
					->where('sd.id_ditta', "=",$id_ditta)	
					->where('sd.id_servizio', "=",$id_servizio)	
					->get(); 
					foreach ($servizi_ditte as $servizio) {
						$importo_ditta=$servizio->importo_ditta;
						$aliquota=$servizio->aliquota;
						$subtotale=$importo_ditta;
						if (isset($arr_aliquota[$aliquota])) 
							$subtotale=$importo_ditta*(($arr_aliquota[$aliquota]/100)+1);
							
						DB::table('articoli_fattura')->insert([
							'id_appalto' => $id_app,
							'id_doc' => $id_doc,
							'descrizione' =>$servizio->descrizione,
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

		if (strlen($id_doc)==0)
			$fattura = new fatture;
		else 
			$fattura = fatture::find($id_doc);

		$fattura->id_ditta = $request->input('ditta');
		$fattura->data_invito = $request->input('data_invito');		
		$fattura->save();
		$id_doc=$fattura->id;
		return $id_doc;
	}

	public function invito($id=0) {		
		$request=request();

		
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
		if ($btn_ditta=="btn_ditta") $id_doc=$this->crea_fattura();
		
		$btn_import_app=$request->input('btn_import_app');
		if ($btn_import_app=="import_a") $this->import_from_appalti();

		$btn_pagamenti=$request->input('btn_pagamenti');
		if ($btn_pagamenti=="btn_pagamenti") $this->pagamenti();


		$edit_riga=$request->input('edit_riga');
		if (strlen($edit_riga)!=0) $this->edit_riga($edit_riga);

		
		$ditteinapp=DB::table('appalti as a')
		->join("ditte as d","a.id_ditta","d.id")
		->select("d.id as id_ditta","d.denominazione","a.id as id_appalto",DB::raw("DATE_FORMAT(a.data_ref,'%d-%m-%Y') as data_ref"))
		->where(function ($query) use($ditta){	
			$query->where('a.id_ditta', "=",$ditta);	
		})
		->where('a.dele','=',0)
		->where('a.data_ref','>=',"$range_da")
		->where('a.data_ref','<=',"$range_a")
		->orderBy('a.data_ref','desc')
		->orderBy('d.denominazione')
		->get();

		$ditte=DB::table('ditte as d')
		->join('societa as s','d.id_azienda_prop','s.id')
		->select('d.id','d.denominazione','s.id as id_azienda','s.descrizione as azienda')
		->orderBy('d.denominazione')	
		->get();				

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
		
		$elenco_pagamenti_presenti=DB::table('pagamenti as p')
		->select('p.id','p.id_doc','p.tipo_pagamento','p.data_scadenza','p.importo','p.persona','p.coordinate')
		->where('p.id_doc', "=",$id_doc)
		->get();		
	
	
		return view('all_views/invitofatt/invito')->with('id_doc',$id_doc)->with("ditte",$ditte)->with("ditteinapp",$ditteinapp)->with('ditta',$ditta)->with('data_invito',$data_invito)->with('step_active',$step_active)->with('articoli_fattura',$articoli_fattura)->with('aliquote_iva',$aliquote_iva)->with('range_da',$range_da)->with('range_a',$range_a)->with('filtroa',$filtroa)->with('arr_aliquota',$arr_aliquota)->with('lista_pagamenti',$lista_pagamenti)->with('elenco_pagamenti_presenti',$elenco_pagamenti_presenti)->with('id_fattura',$id);
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
		->join('societa as s','d.id','s.id')
		->select("f.status","f.id","f.dele",DB::raw("DATE_FORMAT(f.data_invito,'%d-%m-%Y') as data_invito"),"f.totale","d.denominazione","s.descrizione as sezionale")
		->when($view_dele=="0", function ($fatture) {
			return $fatture->where('f.dele', "=","0");
		})
		->groupBy('f.id')
		->orderBy('f.id','desc')->get();
		

		return view('all_views/invitofatt/lista_inviti')->with("view_dele",$view_dele)->with('fatture',$fatture);

		
	}	




}
