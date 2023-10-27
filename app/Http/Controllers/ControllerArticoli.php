<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\user;

use App\Models\fornitori;
use App\Models\aliquote_iva;
use App\Models\prod_prodotti;
use App\Models\prod_categorie;
use App\Models\prod_sottocategorie;
use App\Models\prod_magazzini;
use App\Models\prod_giacenze;
use App\Models\prod_spostamenti;
use App\Models\movimenti_carico;
use App\Models\ordini_fornitore;

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
		$prodotto->scorta_minima = $request->input('scorta_minima');

		
		
		$prodotto->save();
		$id_articolo=$prodotto->id;
		return $id_articolo;
	}
	
	public function sposta() {
		$request=request();
		$id_articolo=$request->input('id_articolo');
		$btn_sposta=$request->input('btn_sposta');
		$mag_orig=$btn_sposta[0];
		$mag_dest=$request->input('mag_dest'.$mag_orig);
		$qta_sposta=$request->input('qta_sposta'.$mag_orig);

		//salvataggio movimento del db tracciamento spostamenti
		$sposta = new prod_spostamenti;
		
		$sposta->id_prodotto = $id_articolo;		
		$sposta->id_magazzino_orig = $mag_orig;
		$sposta->id_magazzino_dest = $mag_dest;
		$sposta->qta_spostata = $qta_sposta;
		$sposta->save();
		
		//aggiornamento giacenze nei relativi magazzini
		
		$giacenze=prod_giacenze::select('id')
		->where('id_prodotto','=',$id_articolo)
		->where('id_magazzino','=',$mag_orig);
		
		$id_ref = $giacenze->get()->first()->id;
		$up_orig = prod_giacenze::find($id_ref);
		$up_orig->giacenza=$up_orig->giacenza-$qta_sposta;
		$up_orig->save();
	
		
		$giacenze=prod_giacenze::select('id')
		->where('id_prodotto','=',$id_articolo)
		->where('id_magazzino','=',$mag_dest);
		
		if ($giacenze->count()>0) {
			$id_ref = $giacenze->get()->first()->id;
			$up_dest = prod_giacenze::find($id_ref);
		} else {
			$up_dest= new prod_giacenze;
			$up_dest->id_prodotto=$id_articolo;
			$up_dest->id_magazzino=$mag_dest;
		}	
	
		$up_dest->giacenza=$up_dest->giacenza+$qta_sposta;
		$up_dest->save();
		

		return redirect()->route("definizione_articolo",['id_articolo'=>$id_articolo]);
		
	}
	public function definizione_articolo($id_articolo_init=0) {
		$request=request();
		$btn_save_articolo=$request->input("btn_save_articolo");
		$save_articolo=0;
		if ($btn_save_articolo=="save") 
			$save_articolo=$this->save_articolo();

		$btn_sposta=$request->input("btn_sposta");
		if (is_array($btn_sposta)) $sposta=$this->sposta();

		$id_articolo=$request->input("id_articolo");
		if (strlen($id_articolo)==0) $id_articolo=$id_articolo_init;
		
		$info_articolo=array();
		$sotto_categorie=array();
		$sc=null;
		$info_giacenze=array();$info_mag=array();
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
			$giacenze=prod_giacenze::select('id_prodotto','id_magazzino','giacenza')
			->where("id_prodotto","=",$id_articolo)
			->get();
			
			foreach($giacenze as $giacenza) {
				$info_giacenze[$giacenza->id_magazzino]=$giacenza->giacenza;
			}			
		}
		
		$categorie=prod_categorie::select('id','descrizione')
		->orderBy('descrizione')
		->get();

		$magazzini=prod_magazzini::select('id','descrizione')->orderBy('descrizione')->get();
		
		foreach($magazzini as $mag) {
			$info_mag[$mag->id]=$mag->descrizione;
		}				

		//verifico se il prodotto è in arrivo
		$in_arrivo=ordini_fornitore::from('ordini_fornitore as o')
		->join('prodotti_ordini as p','o.id','p.id_ordine')
		->select('o.data_presunta_arrivo_merce')
		->where('p.codice_articolo', "=", $id_articolo)
		->where('o.stato_ordine',"=",0)
		->first();
		
		$data_presunta_arrivo_merce="";
		if (isset($in_arrivo)) {
			$dp=$in_arrivo->data_presunta_arrivo_merce;
			$datxx=date_create($dp);
			$data_presunta_arrivo_merce=date_format($datxx,"d/m/Y");
		}
		

		$data=array("info_articolo"=>$info_articolo,"categorie"=>$categorie,"id_articolo"=>$id_articolo,"sotto_categorie"=>$sotto_categorie,"info_giacenze"=>$info_giacenze,"info_mag"=>$info_mag,"magazzini"=>$magazzini,"data_presunta_arrivo_merce"=>$data_presunta_arrivo_merce);

		return view('all_views/articoli/definizione_articolo')->with($data);
		
	}
	
	public function elenco_articoli(Request $request){

		$view_dele=$request->input("view_dele");
		
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");
		$sede_magazzino=$request->input("sede_magazzino");

		if (strlen($dele_contr)!=0) {
			prod_prodotti::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			prod_prodotti::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		
		if (strlen($sede_magazzino)!=0) {
			$elenco_articoli=DB::table('prod_prodotti as p')
			->select('p.*','c.descrizione as categoria','sc.descrizione as sottocategoria')
			->join('prod_giacenze as g','p.id','g.id_prodotto')
			->join('prod_categorie as c','p.id_categoria','c.id')
			->join('prod_sottocategorie as sc','p.id_sottocategoria','sc.id')
			->where('g.id_magazzino','=',$sede_magazzino)
			->when($view_dele=="0", function ($elenco_articoli) {
				return $elenco_articoli->where('p.dele', "=","0");
			})
			->groupBy('p.id')
			->orderBy('p.id','desc')->get();
		} else {
			$elenco_articoli=DB::table('prod_prodotti as p')
			->select('p.*','c.descrizione as categoria','sc.descrizione as sottocategoria')
			->join('prod_categorie as c','p.id_categoria','c.id')
			->join('prod_sottocategorie as sc','p.id_sottocategoria','sc.id')
			->when($view_dele=="0", function ($elenco_articoli) {
				return $elenco_articoli->where('p.dele', "=","0");
			})
			->groupBy('p.id')
			->orderBy('p.id','desc')->get();
		}

		$prodotti_fornitori = movimenti_carico::
			select("id_fornitore","id_prodotto")
			->groupBy("id_fornitore","id_prodotto")
			->orderBy("id_prodotto")
			->orderBy("id_fornitore")
			->get();
		$info_prod=array();
		$old_p="?";$sca=0;
		foreach($prodotti_fornitori as $prod_f) {
			$pr=$prod_f->id_prodotto;
			if ($old_p!=$pr) $sca=0;			
			$info_prod[$pr][$sca]=$prod_f->id_fornitore;
			$sca++;
			$old_p=$pr;
		}		

		$fornitori=fornitori::select('id','ragione_sociale')
		->orderBy('ragione_sociale')
		->get();
		$arr_forn=array();
		foreach ($fornitori as $fornitore) {
			$arr_forn[$fornitore->id]=$fornitore->ragione_sociale;
		}		
		
		

		$giacenze=prod_giacenze::select('id_prodotto','id_magazzino','giacenza')
		->get();
		$info_giacenze=array();
		foreach($giacenze as $giacenza) {
			$info_giacenze[$giacenza->id_prodotto][$giacenza->id_magazzino]=$giacenza->giacenza;
		}
		
		$magazzini=prod_magazzini::select('id','descrizione')->orderBy('descrizione')->get();

		return view('all_views/articoli/elenco_articoli')->with("view_dele",$view_dele)->with("elenco_articoli",$elenco_articoli)->with("magazzini",$magazzini)->with('info_giacenze',$info_giacenze)->with("info_prod",$info_prod)->with('arr_forn',$arr_forn)->with("sede_magazzino",$sede_magazzino);

	}	
	

	public function categorie_prodotti(Request $request){
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$view_dele=$request->input("view_dele");
		$descr_contr=$request->input("descr_contr");
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");


		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0 && $edit_elem==0) {
			$descr_contr=$descr_contr;
			$arr=array();
			$arr['dele']=0;
			$arr['descrizione']=$descr_contr;
			DB::table("prod_categorie")->insert($arr);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=$descr_contr;
			prod_categorie::where('id', $edit_elem)
			  ->update(['descrizione' => $descr_contr]);
		}
		if (strlen($dele_contr)!=0) {
			prod_categorie::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			prod_categorie::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		
		
		$categorie_prodotti=DB::table('prod_categorie')
		->when($view_dele=="0", function ($categorie_prodotti) {
			return $categorie_prodotti->where('dele', "=","0");
		})
		->orderBy('descrizione')->get();

		return view('all_views/articoli/categorie')->with('categorie_prodotti',$categorie_prodotti)->with("view_dele",$view_dele);
		
	}

	public function sottocategorie_prodotti(Request $request){
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$view_dele=$request->input("view_dele");
		$id_categoria=$request->input("id_categoria");
		$descr_contr=$request->input("descr_contr");
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");


		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0 && $edit_elem==0) {
			$arr=array();
			$arr['dele']=0;
			$arr['id_categoria']=$id_categoria;
			$arr['descrizione']=$descr_contr;
			DB::table("prod_sottocategorie")->insert($arr);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			prod_sottocategorie::where('id', $edit_elem)
			  ->update(['descrizione' => $descr_contr,'id_categoria' => $id_categoria]);
		}
		if (strlen($dele_contr)!=0) {
			prod_sottocategorie::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			prod_sottocategorie::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		
		$categorie=prod_categorie::select('id','descrizione')
		->orderBy('descrizione')
		->get();		
		
		$sottocategorie_prodotti=DB::table('prod_sottocategorie as sc')
		->select('sc.*','c.id as id_categoria','c.descrizione as categoria')
		->join('prod_categorie as c','sc.id_categoria','c.id')
		->when($view_dele=="0", function ($sottocategorie_prodotti) {
			return $sottocategorie_prodotti->where('sc.dele', "=","0");
		})
		->orderBy('descrizione')->get();

		return view('all_views/articoli/sottocategorie')->with('categorie',$categorie)->with('sottocategorie_prodotti',$sottocategorie_prodotti)->with("view_dele",$view_dele);
		
	}	
	

	public function magazzini(Request $request){
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$view_dele=$request->input("view_dele");
		$descr_contr=$request->input("descr_contr");
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");


		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0 && $edit_elem==0) {
			$descr_contr=$descr_contr;
			$arr=array();
			$arr['dele']=0;
			$arr['descrizione']=$descr_contr;
			DB::table("prod_magazzini")->insert($arr);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=$descr_contr;
			prod_magazzini::where('id', $edit_elem)
			  ->update(['descrizione' => $descr_contr]);
		}
		if (strlen($dele_contr)!=0) {
			prod_magazzini::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			prod_magazzini::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		
		
		$magazzini=DB::table('prod_magazzini')
		->when($view_dele=="0", function ($magazzini) {
			return $magazzini->where('dele', "=","0");
		})
		->orderBy('descrizione')->get();

		return view('all_views/articoli/magazzini')->with('magazzini',$magazzini)->with("view_dele",$view_dele);
		
	}	
	
	
}

