<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\user;
use App\Models\italy_cities;
use App\Models\fornitori;

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
		$modello="";
	
		if ($id_fornitore!=0) {
			$info_fornitore=fornitori::from('fornitori as f')
			->select('f.*')
			->where('id', "=", $id_fornitore)
			->get();
		}
		
		$lista_pagamenti=$this->lista_pagamenti();
		/*
		$carta_carburante=parco_carta_carburante::select('id','id_carta')
		->orderBy('id_carta')
		->get();
		*/
		$all_comuni = italy_cities::orderBy('comune')->get();		

		$data=array("id_fornitore"=>$id_fornitore,'info_fornitore'=>$info_fornitore,'all_comuni'=>$all_comuni,'lista_pagamenti'=>$lista_pagamenti);

		
		if ($request->has("btn_save_fornitore")) {
			if ($save_fornitore!=0) $id_fornitore=$save_fornitore;
			return redirect()->route("scheda_fornitore",['id'=>$id_fornitore]);
		}
		else
		
			return view('all_views/fornitori/scheda_fornitore')->with($data);
		
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

