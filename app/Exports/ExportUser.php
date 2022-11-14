<?php 
    namespace App\Exports; 
    use App\Models\candidati;
    use Maatwebsite\Excel\Concerns\FromCollection; 
	use Illuminate\Support\Collection;
	use App\Models\societa;
	use App\Models\mansione;
	use App\Models\area_impiego;
	use App\Models\centri_costo;
	use App\Models\ref_doc;
	use DB;
	
    class ExportUser implements FromCollection { 
        public function collection() 
        { 
		
            $cands= candidati::select('id','nome','cognome','soc_ass','mansione','area_impiego','centro_costo','netto_concordato','costo_azienda','codfisc','datanasc','comunenasc','pro_nasc','indirizzo','comune','cap')
			->where('dele','=',0)
			->get(); 
			$arr_soc=$this->arr(1);
			$arr_mans=$this->arr(2);
			$arr_area=$this->arr(3);
			$arr_centro=$this->arr(4);
			$arr_loc=$this->arr(5);
			$arr_cap=$this->arr(6);
			$doc_cand=$this->doc_cand();


			$corsi_sic = DB::table('voci_doc as v')
			->select('v.id','v.descrizione','v.alias')
			->where('v.id_corso',"=",4)
			->get();
			/*
			print_r($doc_cand);
			exit;
			*/
			
			/*
			COGNOME	NOME	SOCIETA	DIVISIONE	MANSIONE	AREA DI IMPIEGO	CENTRO DI COSTO	NETTO CONCORDATO	COSTO AZIENDA	0-100	CF	DATA DI NASCITA	COMUNE DI NASCITA	PROVINCIA DI NASCITA	INDIRIZZO RESIDENZA	COMUNE DI RESIDENZA	CAP DI RESIDENZA	PROVINCIA RESIDENZA	APPARTENENZA	SUBAPPALTO	EMAIL AZIENDALE	STATO ASSUNZIONE	CONTRATTO	LIVELLO	TIPO CONTRATTO	DATA INIZIO	DATA FINE	CATEGORIA LEGALE	ORE SETTIMANALI	CODICE QUALIFICA	QUALIFICATO	TITOLO DI STUDIO	CONSEGUITO IL	CONSEGUITO PRESSO	MAIL PERSONALE	TELEFONO PERSONALE	IBAN	AREA DI OPERATIVITA	TUTOR	NUMERO SCARPE	TAGLIA	----- tutti i corsi corredati da scadenza
			
			*/
			/*
			$corsi=array();
			$corsi[]=$corso->descrizione;
			$corsi[]="SCADENZA";
			*/

			$corsi = new Collection;

			$voci=array();
			array_push($voci,"NOME");
			array_push($voci,"COGNOME");
			array_push($voci,"SOCIETÀ");
			array_push($voci,"MANSIONE");
			array_push($voci,"AREA DI IMPIEGO");
			array_push($voci,"CENTRO DI COSTO");
			array_push($voci,"NETTO CONCORDATO");
			array_push($voci,"COSTO AZIENDA");
			array_push($voci,"CF");
			array_push($voci,"DATA DI NASCITA");
			array_push($voci,"COMUNE DI NASCITA");
			array_push($voci,"PROVINCIA DI NASCITA");
			array_push($voci,"INDIRIZZO RESIDENZA");
			array_push($voci,"COMUNE DI RESIDENZA");
			array_push($voci,"CAP DI RESIDENZA");

			$id_corsi=array();
			foreach ($corsi_sic as $corso) {
				$id_corsi[]=$corso->id;
				array_push($voci,$corso->alias);
				array_push($voci,"SCADENZA");
			}

				
			$collection = new Collection;
			//head
			$collection->push((object)[	
				$voci
			]);				


		
			foreach ($cands as $cand) {
				$id_cand=$cand->id;
				$nome=$cand->nome;
				$cognome=$cand->cognome;
				$soc_ass=$cand->soc_ass;
				$mans=$cand->mansione;
				$area=$cand->area_impiego;
				$centro=$cand->centro_costo;
				$netto_concordato=$cand->netto_concordato;
				$costo_azienda=$cand->costo_azienda;
				$codfisc=$cand->codfisc;
				$datanasc=$cand->datanasc;
				$istat_nasc=$cand->comunenasc;
				$pro_nasc=$cand->pro_nasc;
				$indirizzo=$cand->indirizzo;
				
				$cap=$cand->cap;

				$societa="";$mansione="";$area_impiego="";$centro_costo="";
				$comunenasc="";
				if (isset($arr_soc[$soc_ass])) $societa=$arr_soc[$soc_ass];
				if (isset($arr_mans[$mans])) $mansione=$arr_mans[$mans];
				if (isset($arr_area[$area])) $area_impiego=$arr_area[$area];
				if (isset($arr_centro[$centro])) $centro_costo=$arr_centro[$centro];
				


				$info=explode("|",$istat_nasc);
				if (count($info)>1) {
					$istat=$info[0];
					if (isset($arr_loc[$istat])) $comunenasc=$arr_loc[$istat];
				}	

				if (strlen($datanasc)>8) 
					$datanasc=substr($datanasc,8,2)."-".substr($datanasc,5,2)."-".substr($datanasc,0,4);
				
				$istat_res="";$comune_res="";
				if (isset($arr_cap[$cap])) $istat_res=$arr_cap[$cap];
				if (isset($arr_loc[$istat_res])) $comune_res=$arr_loc[$istat_res];

				$voci=array();
				array_push($voci,$nome);
				array_push($voci,$cognome);
				array_push($voci,$societa);
				array_push($voci,$mansione);
				array_push($voci,$area_impiego);
				array_push($voci,$centro_costo);
				array_push($voci,$netto_concordato);
				array_push($voci,$costo_azienda);
				array_push($voci,$codfisc);
				array_push($voci,$datanasc);
				array_push($voci,$comunenasc);
				array_push($voci,$pro_nasc);
				array_push($voci,$indirizzo);
				array_push($voci,$comune_res);
				array_push($voci,$cap);
				
				$corsicand=array();
				//tutti i corsi di sicurezza del candidato in corso
				if (isset($doc_cand[$id_cand])) $corsicand=$doc_cand[$id_cand];
				$fl_e=false;
				for ($s=0;$s<=count($id_corsi)-1;$s++) {
					$id_corso=$id_corsi[$s];
					
					for ($sca=0;$sca<=count($corsicand)-1;$sca++) {
						$id_sotto_tipo=$corsicand[$sca]['id_sotto_tipo'];
						if ($id_sotto_tipo==$id_corso) {
							$fl_e=true;
							array_push($voci,"1");
							$scadenza=$corsicand[$sca]['scadenza'];
							array_push($voci,$scadenza);
						}
						
					}
					
				}
				if ($fl_e==false) {
					array_push($voci,"0");
					array_push($voci,"");
				}
					
				
				
				$collection->push((object)[	
					$voci
				]);				
			}
			return $collection; 
        }
		public function arr($from) {
			$all="";
			if ($from=="1") $all= societa::select('id','descrizione');
			if ($from=="2") $all= mansione::select('id','descrizione');
			if ($from=="3") $all= area_impiego::select('id','descrizione');
			if ($from=="4") $all= centri_costo::select('id','descrizione');
			if ($from=="5") $all= DB::table('italy_cities')->select("istat as id","comune as descrizione");
			if ($from=="6") $all= DB::table('italy_cap')->select("cap as id","istat as descrizione");

			$dati=$all->get(); 
			$resp=array();
			foreach ($dati as $rec) {
				$id=$rec->id;
				$descrizione=$rec->descrizione;
				$resp[$id]=$descrizione;
			}
			return $resp;
		}
		
		
		public function doc_cand() {
			//tutti i corsi di sicurezza (id 4) frequentati dai candidati
			$ref_doc = DB::table('ref_doc as r')
			->join('tipo_doc as d', 'r.id_tipo_doc', '=', 'd.id')
			->leftJoin('voci_doc as v', 'r.id_sotto_tipo', '=', 'v.id')
			->select('r.id','r.id_tipo_doc','r.id_sotto_tipo','r.id_cand','r.scadenza', 'd.alias as alias_t', 'v.alias as alias_st')
			->where('r.id_tipo_doc',"=",4)
			->groupBy('r.id')
			->orderByDesc('r.id')
			->get();		
			
			
			
			$resp=array();
			$old="?";$indice=0;
			foreach ($ref_doc as $rec) {
				$id_cand=$rec->id_cand;
				if ($id_cand!=$old) $indice=0;
				else $indice++;
				$old=$id_cand;
				$id_sotto_tipo=$rec->id_sotto_tipo;
				$alias_t=$rec->alias_t;
				$alias_st=$rec->alias_st;
				$scadenza=$rec->scadenza;
				$resp[$id_cand][$indice]['id_sotto_tipo']=$id_sotto_tipo;
				$resp[$id_cand][$indice]['alias_t']=$alias_t;
				$resp[$id_cand][$indice]['alias_st']=$alias_st;
				$resp[$id_cand][$indice]['scadenza']=$scadenza;
			}
			return $resp;
			
		}
		



    }