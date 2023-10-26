<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\user;
use App\Models\italy_cities;
use App\Models\fornitori;
use App\Models\ordini_fornitore;
use App\Models\prod_magazzini;
use App\Models\prod_prodotti;
use App\Models\prodotti_ordini;
use App\Models\aliquote_iva;
use App\Models\movimenti_carico;
use App\Models\prod_giacenze;


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
		$id_user=Auth::user()->id;
		if ($id_ordine!=0) 
			$ordine = ordini_fornitore::find($id_ordine);
		else {
			$ordine = new ordini_fornitore;
			$ordine->id_user=$id_user;
		}
		
		$ordine->id_azienda_proprieta = $request->input('id_azienda_proprieta');
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
		$po->id_magazzino = 0; ///lasciato ma gestito da giacenze!
		$po->id_fornitore = $request->input('id_fornitore');
		$po->codice_articolo = $request->input('codice');
		$po->quantita = $request->input('quantita');
		$po->prezzo_unitario = $request->input('prezzo_unitario');
		$po->aliquota = $request->input('aliquota');
		$po->subtotale = $request->input('subtotale');
		$po->save();
		$this->save_prezzo_medio($id_ordine);
	}
	
	public function save_prezzo_medio($id_ordine=0) {
		//aggiorna tutti i prezzi medi dei prodotti a partire dall'ordine specificato
		
		//elenco tutti i prodotti dell'ordine o globali
		if ($id_ordine==0)
			$elenco=prod_prodotti::select('id as codice_articolo')
			->get();
		else
			$elenco=prodotti_ordini::select('codice_articolo')
			->when($id_ordine!=0, function ($elenco) use ($id_ordine) {
				return $elenco->where('id_ordine', "=", $id_ordine);	
			})
			->get();
		
		//per ogni prodotto dell'ordine calcolo il prezzo medio
		//e lo riverso nella tabella degli articoli
		foreach ($elenco as $articolo) {
			$codice_articolo=$articolo->codice_articolo;

			$info_prezzo=prodotti_ordini::select(DB::raw("SUM(prezzo_unitario) as prezzo"))
			->where('codice_articolo','=',$codice_articolo)
			->get();

			$num_rec=prodotti_ordini::where('codice_articolo','=',$codice_articolo)->count();
			if ($num_rec>0) {
				$prezzo_medio=($info_prezzo[0]->prezzo)/$num_rec;
			}
			else 
				$prezzo_medio=0;
			
			prod_prodotti::where('id', $codice_articolo)
			->update(['prezzo_medio' => $prezzo_medio]);			

		}
		
		
	}

	public function ordini_fornitore($id_ordine_init=0) {
		$request=request();
		$btn_save_ordine=$request->input("btn_save_ordine");
		$btn_save_art=$request->input("btn_save_art");
		$btn_canceled=$request->input("btn_canceled");
		
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

		$sezionali=DB::table('societa as s')
		->select('s.id','s.descrizione')
		->where('s.dele','=',0)
		->orderBy('s.descrizione')	
		->get();	
	
		if ($id_ordine!=0) {
			$info_ordine=ordini_fornitore::from('ordini_fornitore as o')
			->select('o.*')
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
		if (strlen($dele_riga)!=0)  {
			prodotti_ordini::where('id', $dele_riga)->delete();
			//aggiorno il prezzo medio dopo una cancellazione
			$this->save_prezzo_medio(0);
		}	

		if ($btn_canceled=="cancel") {
			$id_prod_canceled=$request->input("id_prod_canceled");
			prodotti_ordini::where('id', $id_prod_canceled)->update(['canceled' => 1,'motivazione_canc'=>$request->input("motivazione_canc")]);
		}

		$prodotti_ordini=prodotti_ordini::from('prodotti_ordini as p')
		->join('fornitori as f','p.id_fornitore','f.id')
		->select('p.*','f.ragione_sociale')
		->where('p.id_ordine','=',$id_ordine)
		->get();

		$aliquote_iva=aliquote_iva::select('id','aliquota','descrizione')
		->get();
		
		$arr_aliquota=array();
		foreach ($aliquote_iva as $aliquota) {
			if (isset($aliquota->id))
				$arr_aliquota[$aliquota->id]=$aliquota->aliquota;
		}	
		
		$data=array("id_ordine"=>$id_ordine,"info_ordine"=>$info_ordine,'magazzini'=>$magazzini,"fornitori"=>$fornitori,'prodotti'=>$prodotti,"aliquote_iva"=>$aliquote_iva,"prodotti_ordini"=>$prodotti_ordini,'arr_aliquota'=>$arr_aliquota,"sezionali"=>$sezionali);


		
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
			  //non aggiorno il prezzo medio perchè l'ordine viene cancellato solo logicamente ed il calcolo del prezzo medio non tiene conto degli ordini con dele=1
		}
		if (strlen($restore_contr)!=0) {
			ordini_fornitore::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;

		$prod_magazzini=prod_magazzini::from('prod_magazzini as p')
		->select('p.*')
		->get();
		$magazzini=array();
		foreach ($prod_magazzini as $mg) {
			$id_m=$mg->id;
			$mag=$mg->descrizione;
			$magazzini[$id_m]=$mag;
		}
		
		$elenco_ordini=DB::table('ordini_fornitore as o')
		->leftjoin('societa as s','o.id_azienda_proprieta','s.id')
		->select('o.*',DB::raw("DATE_FORMAT(o.data_ordine,'%d-%m-%Y') as data_ordine_it"),'s.descrizione as azienda_proprieta')
		->when($view_dele=="0", function ($elenco_ordini) {
			return $elenco_ordini->where('o.dele', "=","0");
		})
		->orderBy('o.id','desc')->get();
		
		$sca=0;
		$info_fornitori=array();
		$prodotti_ordini=prodotti_ordini::from('prodotti_ordini as p')
		->select('p.*')
		->orderBy('p.id_ordine')
		->get();	
		$id_old_o="?";$temp=array();
		foreach($prodotti_ordini as $mov) {
			$id_f=$mov->id_fornitore;
			$id_o=$mov->id_ordine;
			if ($id_old_o!=$id_o) {
				$temp=array();
				$sca=0;
			}	
			if (!in_array($id_f,$temp)) {
				$temp[]=$id_f;
				$info_fornitori[$id_o][$sca]=$id_f;				
				$sca++;
			}	
			$id_old_o=$id_o;
		}
		
		$fornitori=fornitori::select('id','ragione_sociale')
		->orderBy('ragione_sociale')
		->get();
		$arr_forn=array();
		foreach ($fornitori as $fornitore) {
			$arr_forn[$fornitore->id]=$fornitore->ragione_sociale;
		}		

		return view('all_views/fornitori/elenco_ordini_fornitori')->with("view_dele",$view_dele)->with("elenco_ordini",$elenco_ordini)->with('magazzini',$magazzini)->with("info_fornitori",$info_fornitori)->with('arr_forn',$arr_forn);

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
		

	public function evasione_ordini($id_ordine_init=0) {
		$request=request();
		$btn_save_qta=$request->input("btn_save_qta");
		
		$id_ordine=$request->input("id_ordine");
		if (strlen($id_ordine)==0) $id_ordine=$id_ordine_init;
		
		if ($btn_save_qta=="save") {
			
			if (strlen($id_ordine)!=0 && $id_ordine>0) {
				$id_magazzino=$request->input("id_magazzino");
				if (strlen($id_magazzino)!=0) {
					$id_prod=$request->input("id_prod");
					$id_fornitore=$request->input("id_forn");
					$qta_evasa=$request->input("qta_evasa");
					$ctrl_qta=$request->input("ctrl_qta");
					
					$close=true;
					for ($sca=0;$sca<=count($id_prod)-1;$sca++) {
						$id_prodotto=$id_prod[$sca];
						$ctrl=$ctrl_qta[$sca];
						$ref_fornitore=$id_fornitore[$sca];
						$qta=$qta_evasa[$sca];
						
						$info_qta=explode("-",$ctrl);
						$curr_qta=intval($info_qta[0])-intval($info_qta[1])-intval($qta);
						$canceled=$info_qta[2];
						if ($curr_qta>0 && $canceled!="1") $close=false;
						
						if (strlen($qta)>0) {
							//creazione dei movimenti nel DB
							$movimenti_carico = new movimenti_carico;
							$movimenti_carico->id_ordine = $id_ordine;
							$movimenti_carico->id_prodotto = $id_prodotto;
							$movimenti_carico->id_fornitore = $ref_fornitore;
							$movimenti_carico->qta = $qta;
							$movimenti_carico->id_magazzino = $id_magazzino;
							$movimenti_carico->save();
							
							//aggiornamento giacenze
							
							$giacenze=prod_giacenze::select('id')
							->where('id_prodotto','=',$id_prodotto)
							->where('id_magazzino','=',$id_magazzino);
							
							if ($giacenze->count()>0) {
								$id_ref = $giacenze->get()->first()->id;
								$prod_giacenze = prod_giacenze::find($id_ref);
							}
							else
								$prod_giacenze = new prod_giacenze;
							

							$prod_giacenze->id_prodotto=$id_prodotto;
							$prod_giacenze->id_magazzino=$id_magazzino;

							$prod_giacenze->giacenza = $prod_giacenze->giacenza+$qta;
							$prod_giacenze->save();
						}
					}
					if ($close==true) {
						$ordine = ordini_fornitore::find($id_ordine);
						$ordine->stato_ordine=2;
						$ordine->save();
					}
					
					
					return redirect()->route("evasione_ordini",['id'=>$id_ordine])->with('evasione_ok', 'Le quantità sono state correttamente evase');					
				}
			}
		}	

		$info_ordine=array();


		$info_movimenti=array();
		if ($id_ordine!=0) {
			$info_ordine=ordini_fornitore::from('ordini_fornitore as o')
			->leftjoin('societa as s','o.id_azienda_proprieta','s.id')
			->select('o.*','s.descrizione as azienda_proprieta')
			->where('o.id', "=", $id_ordine)
			->get();
			
			$info_m = movimenti_carico::
			select("id_prodotto","id_fornitore",DB::raw("SUM(qta) as totale"))
			->where("id_ordine","=",$id_ordine)
			->groupBy("id_prodotto","id_fornitore")
			->get();
			foreach($info_m as $mov) {
				$info_movimenti[$mov->id_prodotto][$mov->id_fornitore]=$mov->totale;
			}
		}
		$fornitori=fornitori::select('id','ragione_sociale')
		->orderBy('ragione_sociale')
		->where('dele','=',0)
		->get();
		$arr_forn=array();
		foreach ($fornitori as $fornitore) {
			$arr_forn[$fornitore->id]=$fornitore->ragione_sociale;
		}		
		
		$magazzini = prod_magazzini::orderBy('descrizione')->get();


		$prodotti=prod_prodotti::from('prod_prodotti as p')
		->select('p.*')
		->orderBy('descrizione')
		->get();
		
		$arr_prod=array();
		foreach ($prodotti as $prodotto) {
			$arr_prod[$prodotto->id]=$prodotto->descrizione;
		}


		$dele_riga=$request->input("dele_riga");
		if (strlen($dele_riga)!=0) 
			prodotti_ordini::where('id', $dele_riga)->delete();

		$prodotti_ordini=prodotti_ordini::from('prodotti_ordini as p')
		->select('p.*')
		->where('p.id_ordine','=',$id_ordine)
		->get();

		
		$data=array("id_ordine"=>$id_ordine,"info_ordine"=>$info_ordine,'magazzini'=>$magazzini,"fornitori"=>$fornitori,'prodotti'=>$prodotti,"prodotti_ordini"=>$prodotti_ordini,"arr_prod"=>$arr_prod,"info_movimenti"=>$info_movimenti,"arr_forn"=>$arr_forn);


		
		/*
		if ($save_fornitore!=0) $id_fornitore=$save_fornitore;
		return redirect()->route("scheda_fornitore",['id'=>$id_fornitore]);
		*/
		//

		return view('all_views/fornitori/evasione_ordini')->with($data);
		
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

