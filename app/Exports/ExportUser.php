<?php 
    namespace App\Exports; 
    use App\Models\candidati;
    use Maatwebsite\Excel\Concerns\FromCollection; 
	use Illuminate\Support\Collection;
	use App\Models\societa;
	use App\Models\mansione;
	use App\Models\area_impiego;
	use App\Models\centri_costo;
	
    class ExportUser implements FromCollection { 
        public function collection() 
        { 
		
            $cands= candidati::select('nome','cognome','soc_ass','mansione','area_impiego','centro_costo','netto_concordato','costo_azienda','codfisc')->get(); 
			$arr_soc=$this->arr(1);
			$arr_mans=$this->arr(2);
			$arr_area=$this->arr(3);
			$arr_centro=$this->arr(4);

	
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
				
				$societa="";$mansione="";$area_impiego="";$centro_costo="";
				if (isset($arr_soc[$soc_ass])) $societa=$arr_soc[$soc_ass];
				if (isset($arr_mans[$mans])) $mansione=$arr_mans[$mans];
				if (isset($arr_area[$area])) $area_impiego=$arr_area[$area];
				if (isset($arr_centro[$centro])) $centro_costo=$arr_centro[$centro];
				
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