<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\prod_categorie;
use App\Models\prod_sottocategorie;
use App\Models\prod_prodotti;
use App\Models\fornitori;
use App\Models\prod_giacenze;
use App\Models\prod_magazzini;

use Mail;
use DB;


class AjaxControllerAcquisti extends Controller
	{


	public function check_disp_maga(Request $request){
		$id_fattura = $request->input('id_fattura');
		$articoli=DB::table('articoli_fattura as af')
		->select('af.codice','af.quantita as qta','af.mag_sca')
		->where('af.id_doc', '=', $id_fattura)
		->get();
		$info=array();
		foreach($articoli as $articolo) {
			if (isset($info[$articolo->codice]))
				$info[$articolo->codice]['qta']+=$articolo->qta;
			else	
				$info[$articolo->codice]['qta']=$articolo->qta;
			
			$info[$articolo->codice]['mag_sca']=$articolo->mag_sca;
		}

		$resp=array();
		
		$esito_globale=true;

		foreach($info as $codice=>$value) {
			$mag_sca=$info[$codice]['mag_sca'];
			$qta=$info[$codice]['qta'];

			$magazzini=prod_magazzini::select('id','descrizione')
			->get();
			$info_mag=array();
			foreach ($magazzini as $magazzino) {
				$info_mag[$magazzino->id]=$magazzino->descrizione;
			}
			
			$gi=prod_giacenze::select('giacenza','id_magazzino')
			->where('id_prodotto','=',$codice)
			->get();

			
			$esito_codice=0;$entr=false;
			foreach($gi as $g) {
				$entr=true;
				$giac_ref=$g->giacenza;
				$mag_ref=$g->id_magazzino;

			    $resp['risp'][$codice][$mag_ref]['giacenza']=$giac_ref;
			    $resp['risp'][$codice][$mag_ref]['qta']=$qta;
				$resp['risp'][$codice][$mag_ref]['magazzino']=$info_mag[$mag_ref];
				
				if ($mag_ref==$mag_sca) {
					$resp['risp'][$codice][$mag_ref]['tipo_mag']="MAGREQ";
					if ($giac_ref<$qta) {
					   $resp['risp'][$codice][$mag_ref]['esito']="NODISP";
					} else {
					   $esito_codice=1;
					   $resp['risp'][$codice][$mag_ref]['esito']="DISP";
					}	
				} else {
					$resp['risp'][$codice][$mag_ref]['tipo_mag']="MAGALT";
					if ($giac_ref>=$qta)  {
						if ($esito_codice==0) $esito_codice=2;
						$resp['risp'][$codice][$mag_ref]['esito']="DISP";
					} else {
						$resp['risp'][$codice][$mag_ref]['esito']="NODISP";
					}
				}
			}
			if ($entr==false) $esito_codice=3;
			if ($esito_codice==0 || $esito_codice==2) $esito_globale=false;
			$resp['esito_globale']=$esito_globale;
			$resp['esito_codice'][$codice]=$esito_codice;

		}
		
		return json_encode($resp);
		
	}

	public function elenco_categorie(Request $request){		
		$categ=DB::table('prod_categorie as c')
		->select('c.id','c.descrizione')
		->orderBy('c.descrizione')
		->get();
        return json_encode($categ);
	}

	public function elenco_sottocategorie(Request $request){		
		$id_categoria = $request->input('id_categoria');
		$sottoc=DB::table('prod_sottocategorie as sc')
		->select('sc.id as id_sc','sc.descrizione as descr_sc')
		->where('sc.id_categoria', '=', $id_categoria)
		->orderBy('sc.descrizione')
		->get();
        return json_encode($sottoc);
	}	

	public function refresh_prodotti(Request $request){		
		
		$prodotti=prod_prodotti::from('prod_prodotti as p')
		->select('p.*')
		->orderBy('descrizione')
		->get();
        return json_encode($prodotti);
	}

	public function refresh_forn(Request $request){		
		
		$fornitori=fornitori::from('fornitori as f')
		->select('f.*')
		->orderBy('ragione_sociale')
		->get();
        return json_encode($fornitori);
	}

}
