<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\user;
use App\Models\italy_cities;
use App\Models\fornitori;
use App\Models\ordini_fornitore;
use App\Models\prod_magazzini;
use App\Models\prod_prodotti;
use App\Models\prodotti_ordini;
use App\Models\aliquote_iva;


use DB;

class ControllerAcquisti extends Controller
{
	public function __construct() {
		$this->middleware('auth')->except(['index']);
	}
	
	public function save_fornitore() {
		$request=request();
		$id_fornitore=$request->input('id_fornitore');
		if ($id_fornitore!=0) 
			$fornitori = fornitori::find($id_fornitore);
		else 
			$fornitori = new fornitori;
		
		$fornitori->ragione_sociale = strtoupper($request->input('ragione_sociale'));
		$fornitori->partita_iva = $request->input('partita_iva');
		$fornitori->codice_fiscale = $request->input('codice_fiscale');
		$fornitori->indirizzo = $request->input('indirizzo');
		$fornitori->cap = $request->input('cap');
		$fornitori->comune = strtoupper($request->input('comune'));
		$fornitori->provincia = strtoupper($request->input('provincia'));
		$fornitori->pec = $request->input('pec');

		$fornitori->telefono = $request->input('telefono');
		$fornitori->sdi = $request->input('sdi');
		$fornitori->iban = $request->input('iban');

		$tipo_pagamento=$request->input("tipo_pagamento");
		$str_pagamento=null;
		if (is_array($tipo_pagamento))
			$str_pagamento=implode(";",$tipo_pagamento);

		$fornitori->tipo_pagamento = $str_pagamento;
		
		
		$fornitori->cognome_referente = $request->input('cognome_referente');
		$fornitori->nome_referente = $request->input('nome_referente');
		$fornitori->telefono_referente = $request->input('telefono_referente');

		
		$fornitori->save();
		$id_fornitore=$fornitori->id;
		return $id_fornitore;
	}
	
	public function scheda_fornitore($id_fornitore=0) {
		$request=request();
		$btn_save_fornitore=$request->input("btn_save_fornitore");
		$save_fornitore=0;
		if ($btn_save_fornitore=="save") 
			$save_fornitore=$this->save_fornitore();

		$info_fornitore=array();

		if ($id_fornitore!=0) {
			$info_fornitore=fornitori::from('fornitori as f')
			->select('f.*')
			->where('id', "=", $id_fornitore)
			->get();
		}
		

		$lista_pagamenti=$this->lista_pagamenti();

		$all_comuni = italy_cities::orderBy('comune')->get();		

		

		$data=array("id_fornitore"=>$id_fornitore,'info_fornitore'=>$info_fornitore,'all_comuni'=>$all_comuni,'lista_pagamenti'=>$lista_pagamenti);

		
		if ($request->has("btn_save_fornitore")) {
			if ($save_fornitore!=0) $id_fornitore=$save_fornitore;
			return redirect()->route("scheda_fornitore",['id'=>$id_fornitore]);
		}
		else
		
			return view('all_views/fornitori/scheda_fornitore')->with($data);
		
	}
	
	public function save_ordine($id_ordine) {
		$request=request();
	
		if ($id_ordine!=0) 
			$ordine = ordini_fornitore::find($id_ordine);
		else 
			$ordine = new ordini_fornitore;
		
		$ordine->id_fornitore = $request->input('id_fornitore');
		$ordine->data_ordine = $request->input('data_ordine');
		$ordine->data_presunta_arrivo_merce = $request->input('data_presunta_arrivo_merce');
		$ordine->stato_ordine = $request->input('stato_ordine');
		$ordine->id_sede_consegna = $request->input('id_sede_consegna');

		
		$ordine->save();
		$id_ordine=$ordine->id;

		
		return $id_ordine;
	}

	public function save_art($id_ordine,$id_riga) {
		$request=request();

		if (strlen($id_riga)!=0 && $id_riga!="0")
			$po = prodotti_ordini::find($id_riga);
		else 
			$po = new prodotti_ordini;
		
		$po->id_ordine = $id_ordine;
		$po->id_magazzino = 0; ///da valorizzare correttamente!
		$po->codice_articolo = $request->input('codice');
		$po->quantita = $request->input('quantita');
		$po->prezzo_unitario = $request->input('prezzo_unitario');
		$po->aliquota = $request->input('aliquota');
		$po->subtotale = $request->input('subtotale');

		$po->save();
	}

	public function ordini_fornitore($id_ordine_init=0) {
		$request=request();
		$btn_save_ordine=$request->input("btn_save_ordine");
		$btn_save_art=$request->input("btn_save_art");
		$id_ordine=$request->input("id_ordine");
		if (strlen($id_ordine)==0) $id_ordine=$id_ordine_init;
		
		if ($btn_save_ordine=="save") {
			$id_ref=$this->save_ordine($id_ordine);
			return redirect()->route("ordini_fornitore",['id'=>$id_ref]);
		}	
		if ($btn_save_art=="save") {
			$id_ordine_modal=$request->input("id_ordine_modal");
			$id_riga=$request->input("id_riga");
			$this->save_art($id_ordine_modal,$id_riga);
			return redirect()->route("ordini_fornitore",['id'=>$id_ordine_modal]);
		}	
		$info_ordine=array();


	
		if ($id_ordine!=0) {
			$info_ordine=ordini_fornitore::from('ordini_fornitore as o')
			->join('fornitori as f','o.id_fornitore','f.id')
			->select('o.*','f.ragione_sociale')
			->where('o.id', "=", $id_ordine)
			->get();
		}
		$fornitori=fornitori::select('*')
		->orderBy('ragione_sociale')
		->where('dele','=',0)
		->get();
		$magazzini = prod_magazzini::orderBy('descrizione')->get();

		$aliquote_iva=aliquote_iva::select('id','aliquota','descrizione')
		->get();
		

		$prodotti=prod_prodotti::from('prod_prodotti as p')
		->select('p.*')
		->orderBy('descrizione')
		->get();


		$dele_riga=$request->input("dele_riga");
		if (strlen($dele_riga)!=0) 
			prodotti_ordini::where('id', $dele_riga)->delete();

		$prodotti_ordini=prodotti_ordini::from('prodotti_ordini as p')
		->select('p.*')
		->where('p.id_ordine','=',$id_ordine)
		->get();

		$aliquote_iva=aliquote_iva::select('id','aliquota','descrizione')
		->get();
		
		$arr_aliquota=array();
		foreach ($aliquote_iva as $aliquota) {
			if (isset($aliquota->id))
				$arr_aliquota[$aliquota->id]=$aliquota->aliquota;
		}	
		
		$data=array("id_ordine"=>$id_ordine,"info_ordine"=>$info_ordine,'magazzini'=>$magazzini,"fornitori"=>$fornitori,'prodotti'=>$prodotti,"aliquote_iva"=>$aliquote_iva,"prodotti_ordini"=>$prodotti_ordini,'arr_aliquota'=>$arr_aliquota);


		
		/*
		if ($save_fornitore!=0) $id_fornitore=$save_fornitore;
		return redirect()->route("scheda_fornitore",['id'=>$id_fornitore]);
		*/

		return view('all_views/fornitori/ordini_fornitore')->with($data);
		
	}	
	
	
	public function elenco_ordini_fornitori(Request $request){

		$view_dele=$request->input("view_dele");
		
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");

		if (strlen($dele_contr)!=0) {
			ordini_fornitore::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			ordini_fornitore::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		
		$elenco_ordini=DB::table('ordini_fornitore as o')
		->join('fornitori as f','o.id_fornitore','f.id')
		->select('o.*',DB::raw("DATE_FORMAT(o.data_ordine,'%d-%m-%Y') as data_ordine_it"),'f.ragione_sociale')
		->when($view_dele=="0", function ($elenco_ordini) {
			return $elenco_ordini->where('o.dele', "=","0");
		})
		->orderBy('o.id','desc')->get();

		return view('all_views/fornitori/elenco_ordini_fornitori')->with("view_dele",$view_dele)->with("elenco_ordini",$elenco_ordini);

	}	


	public function elenco_fornitori(Request $request){

		$view_dele=$request->input("view_dele");
		
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");

		if (strlen($dele_contr)!=0) {
			fornitori::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			fornitori::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		
		$elenco_fornitori=DB::table('fornitori')
		->when($view_dele=="0", function ($elenco_fornitori) {
			return $elenco_fornitori->where('dele', "=","0");
		})
		->orderBy('id','desc')->get();

		return view('all_views/fornitori/elenco_fornitori')->with("view_dele",$view_dele)->with("elenco_fornitori",$elenco_fornitori);

	}		
		
	
	function lista_pagamenti() {
		$lista=array();
		$lista[0]['id']=1;
		$lista[0]['descrizione']="Bonifico";
		$lista[1]['id']=2;
		$lista[1]['descrizione']="Ri.ba";
		$lista[2]['id']=3;
		$lista[2]['descrizione']="Assegno";
		return $lista;
	}	
	
}

