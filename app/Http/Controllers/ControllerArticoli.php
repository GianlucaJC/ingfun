<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\user;

use App\Models\fornitori;
use App\Models\aliquote_iva;

use DB;

class ControllerArticoli extends Controller
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
	
	public function definizione_articolo($id_articolo=0) {
		$request=request();
		$btn_save_fornitore=$request->input("btn_save_fornitore");
		$save_fornitore=0;
		if ($btn_save_fornitore=="save") 
			$save_fornitore=$this->save_fornitore();

		$info_articolo=array();

		if ($id_articolo!=0) {
			/*
			$info_articolo=fornitori::from('fornitori as f')
			->select('f.*')
			->where('id', "=", $id_fornitore)
			->get();
			*/
		}
		

		$aliquote_iva=aliquote_iva::select('id','aliquota','descrizione')
		->get();
		
		$arr_aliquota=array();
		foreach ($aliquote_iva as $aliquota) {
			if (isset($aliquota->id))
				$arr_aliquota[$aliquota->id]=$aliquota->aliquota;
		}		

		$data=array("info_articolo"=>$info_articolo,"aliquote_iva"=>$aliquote_iva,"arr_aliquota"=>$arr_aliquota);

		
		if ($request->has("btn_save_fornitore")) {
			/*
			if ($save_fornitore!=0) $id_fornitore=$save_fornitore;
			return redirect()->route("scheda_fornitore",['id'=>$id_fornitore]);
			*/
		}
		else
		
			return view('all_views/articoli/definizione_articolo')->with($data);
		
	}
}

