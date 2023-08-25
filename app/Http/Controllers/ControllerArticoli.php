<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\user;

use App\Models\fornitori;
use App\Models\aliquote_iva;
use App\Models\prod_prodotti;
use App\Models\prod_categorie;
use App\Models\prod_sottocategorie;

use DB;

class ControllerArticoli extends Controller
{
	public function __construct() {
		$this->middleware('auth')->except(['index']);
	}
	
	public function save_articolo() {
		$request=request();
		$id_articolo=$request->input('id_articolo');
		if ($id_articolo!=0) 
			$prodotto = prod_prodotti::find($id_articolo);
		else 
			$prodotto = new prod_prodotti;
		
		$prodotto->descrizione = $request->input('descrizione');
		$prodotto->id_categoria = $request->input('id_categoria');
		$prodotto->id_sottocategoria = $request->input('id_sotto_categoria');
		$prodotto->da_riordinare = $request->input('da_riordinare');
		$prodotto->um_conf = $request->input('um_conf');
		$prodotto->um = $request->input('um');

		
		
		$prodotto->save();
		$id_articolo=$prodotto->id;
		return $id_articolo;
	}
	
	public function definizione_articolo($id_articolo=0) {
		$request=request();
		$btn_save_articolo=$request->input("btn_save_articolo");
		$save_articolo=0;
		if ($btn_save_articolo=="save") 
			$save_articolo=$this->save_articolo();

		$info_articolo=array();
		$sotto_categorie=array();
		$sc=null;
		if ($id_articolo!=0) {
			$info_articolo=prod_prodotti::from('prod_prodotti as p')
			->select('p.*')
			->where('p.id', "=", $id_articolo)
			->get();
			
			if (isset($info_articolo[0])) {
				$cat=$info_articolo[0]->id_categoria;
				$sc=$info_articolo[0]->id_sottocategoria;
				$sotto_categorie=prod_sottocategorie::select('id','descrizione')
				->where('id_categoria','=',$cat)
				->get();
			}	
		}
		
		$categorie=prod_categorie::select('id','descrizione')
		->get();


		$data=array("info_articolo"=>$info_articolo,"categorie"=>$categorie,"id_articolo"=>$id_articolo,"sotto_categorie"=>$sotto_categorie);

		
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

