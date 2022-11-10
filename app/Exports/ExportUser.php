<?php 
    namespace App\Exports; 
    use App\Models\candidati;
    use Maatwebsite\Excel\Concerns\FromCollection; 
	use Illuminate\Support\Collection;
	use App\Models\societa;
	use App\Models\mansione;
	use App\Models\area_impiego;
	use App\Models\centri_costo;
	use DB;
	
    class ExportUser implements FromCollection { 
        public function collection() 
        { 
		
            $cands= candidati::select('nome','cognome','soc_ass','mansione','area_impiego','centro_costo','netto_concordato','costo_azienda','codfisc','datanasc','comunenasc','pro_nasc','indirizzo','comune','cap')->get(); 
			$arr_soc=$this->arr(1);
			$arr_mans=$this->arr(2);
			$arr_area=$this->arr(3);
			$arr_centro=$this->arr(4);
			$arr_loc=$this->arr(5);
			$arr_cap=$this->arr(6);
	/*
	COGNOME	NOME	SOCIETA	DIVISIONE	MANSIONE	AREA DI IMPIEGO	CENTRO DI COSTO	NETTO CONCORDATO	COSTO AZIENDA	0-100	CF	DATA DI NASCITA	COMUNE DI NASCITA	PROVINCIA DI NASCITA	INDIRIZZO RESIDENZA	COMUNE DI RESIDENZA	CAP DI RESIDENZA	PROVINCIA RESIDENZA	APPARTENENZA	SUBAPPALTO	EMAIL AZIENDALE	STATO ASSUNZIONE	CONTRATTO	LIVELLO	TIPO CONTRATTO	DATA INIZIO	DATA FINE	CATEGORIA LEGALE	ORE SETTIMANALI	CODICE QUALIFICA	QUALIFICATO	TITOLO DI STUDIO	CONSEGUITO IL	CONSEGUITO PRESSO	MAIL PERSONALE	TELEFONO PERSONALE	IBAN	AREA DI OPERATIVITA	TUTOR	NUMERO SCARPE	TAGLIA	BADGE BIANCO	BADGE ROSSO	IDONEITA' SANITARIA	FGS RISCHIO BASSO	FGS RISCHIO ALTO	RLS	LAVORI IN QUOTA	SPAZI CONFINATI	GRU SU AUTOCARRO	CARRELLI SEMOVENTI	MOVIMENTO TERRA	PLE	SICUREZZA STRADALE	PREPOSTO ALLA SICUREZZA	ANTINCENDIO RISCHIO BASSO	ANTINCENDIO RISCHIO ALTO	PRIMO SOCCORSO RISCHIO BASSO	PRIMO SOCCORSO RISCHIO ALTO	DPI 3 CAT	CONDUZIONE LAVORO ELETTRICO IFT	PES E PAV
	*/

			$collection = new Collection;
			//head
			$collection->push((object)[	
				'nome' => "Nome",
				'cognome' => "COGNOME",
				'societa' => "SOCIETÀ",
				'mansione' => "MANSIONE",
				'area_impiego' => "AREA DI IMPIEGO",
				'centro_costo' => "CENTRO DI COSTO",
				'netto_concordato' => "NETTO CONCORDATO",
				'costo_azienda' => "COSTO AZIENDA",
				'codfisc' => "CF",
				'datanasc' => "DATA DI NASCITA",
				'comunenasc' => "COMUNE DI NASCITA",
				'pro_nasc' => "PROVINCIA DI NASCITA",
				'indirizzo' => "INDIRIZZO RESIDENZA",
				'comune' => "COMUNE DI RESIDENZA",
				'cap' => "CAP DI RESIDENZA",
			]);				

		
			foreach ($cands as $cand) {
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

				
				$collection->push((object)[	
					'nome' => $nome,
					'cognome' => $cognome,
					'societa' => $societa,
					'mansione' => $mansione,
					'area_impiego' => $area_impiego,
					'centro_costo' => $centro_costo,
					'centro_costo' => $centro_costo,
					'netto_concordato' => $netto_concordato,
					'costo_azienda' => $costo_azienda,
					'codfisc' => $codfisc,
					'datanasc' => $datanasc,
					'comunenasc' => $comunenasc,
					'pro_nasc' => $pro_nasc,
					'indirizzo' => $indirizzo,
					'comune' => $comune_res,
					'cap' => $cap,
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



    }