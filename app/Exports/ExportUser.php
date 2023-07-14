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
	use App\Models\ccnl;
	use App\Models\tipoc;
	use DB;
	
    class ExportUser implements FromCollection { 
        public function collection() 
        { 
		
            $cands= candidati::select('id','tipo_anagr','nome','cognome','sesso', 'soc_ass','mansione','area_impiego','centro_costo','netto_concordato','costo_azienda','codfisc','datanasc','comunenasc','pro_nasc','indirizzo','comune','cap','provincia','appartenenza','subappalto','pec','contratto','livello','tipo_contr','data_inizio','data_fine','categoria_legale','ore_sett','codice_qualifica','qualificato','titolo_studio','anno_mese','istituto_conseguimento','email','telefono','iban','zona_lavoro','affiancamento','n_scarpe','taglia')
			->where('dele','=',0)
			->get(); 
			$arr_soc=$this->arr(1);
			$arr_mans=$this->arr(2);
			$arr_area=$this->arr(3);
			$arr_centro=$this->arr(4);
			$arr_loc=$this->arr(5);
			$arr_cap=$this->arr(6);
			$arr_ccnl=$this->arr(7);
			$arr_tipoc=$this->arr(8);
			$doc_cand=$this->doc_cand();
			$ido_sanitaria=$this->ido_sanitaria();
			
			


			$corsi_sic = DB::table('voci_doc as v')
			->select('v.id','v.descrizione','v.alias')
			->where('v.id_corso',"=",4)
			->get();
			/*
			print_r($doc_cand);
			exit;
			*/
			
			/*
			COGNOME	NOME	SOCIETA	DIVISIONE	MANSIONE	AREA DI IMPIEGO	CENTRO DI COSTO	NETTO CONCORDATO	COSTO AZIENDA	0-100	CF	DATA DI NASCITA	COMUNE DI NASCITA	PROVINCIA DI NASCITA	INDIRIZZO RESIDENZA	COMUNE DI RESIDENZA	CAP DI RESIDENZA	PROVINCIA RESIDENZA	APPARTENENZA SUBAPPALTO			
			EMAIL AZIENDALE?? (ho messo la pec)
			STATO ASSUNZIONE CONTRATTO  LIVELLO TIPO CONTRATTO DATA INIZIO DATA FINE CATEGORIA LEGALE  ORE SETTIMANALI CODICE QUALIFICA QUALIFICATO	TITOLO DI STUDIO CONSEGUITO IL	CONSEGUITO PRESSO MAIL PERSONALE TELEFONO PERSONALE IBAN
			AREA DI OPERATIVITA TUTOR NUMERO SCARPE TAGLIA
			----- tutti i corsi corredati da scadenza
			
			*/
			/*
			$corsi=array();
			$corsi[]=$corso->descrizione;
			$corsi[]="SCADENZA";
			*/

			$corsi = new Collection;

			$voci=array();
			array_push($voci,"COGNOME");
			array_push($voci,"NOME");
			array_push($voci,"SESSO");
			array_push($voci,"IDEONEITA' SANITARIA");
			array_push($voci,"SCADENZA");
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
			array_push($voci,"PROVINCIA");
			array_push($voci,"APPARTENENZA");
			array_push($voci,"SUBAPPALTO");
			array_push($voci,"PEC");
			array_push($voci,"STATUS");
			array_push($voci,"CONTRATTO");
			array_push($voci,"LIVELLO");
			array_push($voci,"TIPO CONTRATTO");
			array_push($voci,"DATA INIZIO");
			array_push($voci,"DATA FINE");
			array_push($voci,"CATEGORIA LEGALE");
			array_push($voci,"ORE SETTIMANALI");
			array_push($voci,"CODICE QUALIFICA");
			array_push($voci,"QUALIFICATO");
			array_push($voci,"TITOLO DI STUDIO");
			array_push($voci,"CONSEGUITO IL");
			array_push($voci,"CONSEGUITO PRESSO");
			array_push($voci,"MAIL PERSONALE");
			array_push($voci,"TELEFONO PERSONALE");
			array_push($voci,"IBAN");
			array_push($voci,"AREA OPERATIVITA'");
			array_push($voci,"TUTOR");
			array_push($voci,"NUMERO SCARPE");
			array_push($voci,"TAGLIA");


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
				$sesso=$cand->sesso;
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
				$provincia=$cand->provincia;
				$appartenenza=$cand->appartenenza;
				$appartenenza_descr="";
				if ($appartenenza=="1") $appartenenza_descr="SOCIALE";
				if ($appartenenza=="2") $appartenenza_descr="SUB APPALTO";
				$subappalto=$cand->subappalto;
				$pec=$cand->pec;
				$tipo_anagr=$cand->tipo_anagr;
				$contratto=$cand->contratto;
				$livello=$cand->livello;
				$tipoc=$cand->tipo_contr;
				$d_inizio=$cand->data_inizio;
				$d_fine=$cand->data_fine;
				$data_inizio="";$data_fine="";
				if ($d_inizio!=null) $data_inizio=substr($d_inizio,8,2)."-".substr($d_inizio,5,2)."-".substr($d_inizio,0,4);
				if ($d_fine!=null) $data_fine=substr($d_fine,8,2)."-".substr($d_fine,5,2)."-".substr($d_fine,0,4);
				$categoria_legale=$cand->categoria_legale;
				$cat_legale="";
				if ($categoria_legale=="0") $cat_legale="Operaio";
				if ($categoria_legale=="1") $cat_legale="Impiegato";
				$ore_sett=$cand->ore_sett;
				$codice_qualifica=$cand->codice_qualifica;
				$qualificato=$cand->qualificato;
				$qual="";
				if ($qualificato=="0") $qual="NO";
				if ($qualificato=="1") $qual="SI";
				$titolo_studio=$cand->titolo_studio;
				$titolo="";
				if ($titolo_studio=="1") $titolo="LICENZA MEDIA";
				if ($titolo_studio=="2") $titolo="DIPLOMA ISTITUTO SUPERIORE";
				if ($titolo_studio=="3") $titolo="LAUREA";
				
				$anno_mese=$cand->anno_mese;
				$arr=explode("-",$anno_mese);
				$mese_anno="";
				if (count($arr)>1) $mese_anno=$arr[1]."-".$arr[0];
				$istituto_conseguimento=$cand->istituto_conseguimento;
				$email=$cand->email;
				$telefono=$cand->telefono;
				$iban=$cand->iban;
				$zona_lavoro=$cand->zona_lavoro;
				$affiancamento=$cand->affiancamento;
				$n_scarpe=$cand->n_scarpe;
				$taglia=$cand->taglia;
				
				
				//-------------- dati indiretti da array
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
				$ccnl="";$tipo_contr="";
				if (isset($arr_ccnl[$contratto])) $ccnl=$arr_ccnl[$contratto];
				if (isset($arr_tipoc[$tipoc])) $tipo_contr=$arr_tipoc[$tipoc];

				$voci=array();
				array_push($voci,$cognome);
				array_push($voci,$nome);
				array_push($voci,$sesso);
				
				if (isset($ido_sanitaria[$id_cand])) {
					array_push($voci,"1");
					$scadenza=$ido_sanitaria[$id_cand];$scad="";
					if ($scadenza!=null) $scad=substr($scadenza,8,2)."-".substr($scadenza,5,2)."-".substr($scadenza,0,4);
					array_push($voci,$scad);
				} else {
					array_push($voci,"0");
					array_push($voci,"");
				}					
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
				array_push($voci,$provincia);
				array_push($voci,$appartenenza_descr);
				array_push($voci,$subappalto);
				array_push($voci,$pec);
				array_push($voci,$tipo_anagr);
				array_push($voci,$ccnl);
				array_push($voci,$livello);
				array_push($voci,$tipo_contr);
				array_push($voci,$data_inizio);
				array_push($voci,$data_fine);
				array_push($voci,$cat_legale);
				array_push($voci,$ore_sett);
				array_push($voci,$codice_qualifica);
				array_push($voci,$qual);
				array_push($voci,$titolo);
				array_push($voci,$mese_anno);
				array_push($voci,$istituto_conseguimento);
				array_push($voci,$email);
				array_push($voci,$telefono);
				array_push($voci,$iban);
				array_push($voci,$zona_lavoro);
				array_push($voci,$affiancamento);
				array_push($voci,$n_scarpe);
				array_push($voci,$taglia);

				
				$corsicand=array();
				//tutti i corsi di sicurezza del candidato in corso
				if (isset($doc_cand[$id_cand])) $corsicand=$doc_cand[$id_cand];
				
				for ($s=0;$s<=count($id_corsi)-1;$s++) {
					$fl_e=false;
					$id_corso=$id_corsi[$s];
					
					for ($sca=0;$sca<=count($corsicand)-1;$sca++) {
						$id_sotto_tipo=$corsicand[$sca]['id_sotto_tipo'];
						if ($id_sotto_tipo==$id_corso) {
							$fl_e=true;
							array_push($voci,"1");
							$scadenza=$corsicand[$sca]['scadenza'];
							$scad="";
							if ($scadenza!=null) $scad=substr($scadenza,8,2)."-".substr($scadenza,5,2)."-".substr($scadenza,0,4);					
							array_push($voci,$scad);
						} 
					}

					if ($fl_e==false) {
						array_push($voci,"0");
						array_push($voci,"");
					}
					
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
			if ($from=="7") $all= ccnl::select('id','descrizione');
			if ($from=="8") $all= tipoc::select('id','descrizione');
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
		
		public function ido_sanitaria() {
			//tutte le idoneità sanitarie (id 9) dei candidati
			$ref_doc = DB::table('ref_doc as r')
			->join('tipo_doc as d', 'r.id_tipo_doc', '=', 'd.id')
			->select('r.id','r.id_cand','r.scadenza')
			->where('r.id_tipo_doc',"=",9)
			->orderBy('r.id')
			->get();		
			
			
			
			$resp=array();
			$old="?";$indice=0;
			foreach ($ref_doc as $rec) {
				$id_cand=$rec->id_cand;
				$scadenza=$rec->scadenza;
				$resp[$id_cand]=$scadenza;
			}
			return $resp;
			
		}		



    }