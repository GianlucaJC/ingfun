<?php 
    namespace App\Exports; 
    use Maatwebsite\Excel\Concerns\FromCollection; 
	use Illuminate\Support\Collection;
	use App\Models\parco_scheda_mezzo;
	use App\Models\parco_marca_mezzo;
	use App\Models\parco_modello_mezzo;
	use App\Models\parco_servizi_noleggio;
	use App\Models\parco_carta_carburante;
	use App\Models\parco_badge_cisterna;
	use App\Models\parco_telepass;

	use DB;
	
    class ExportParco implements FromCollection { 
        public function collection() 
        { 

			$arr_marca=$this->arr('marca');
			$arr_modello=$this->arr('modello');
			$arr_servizi=$this->arr('servizi');
			$arr_carta=$this->arr('cartac');
			$arr_badge=$this->arr('badge');
			$arr_telepass=$this->arr('telepass');


			//voci_doc=sotto_tipo_doc
			$parco_scheda_mezzo = DB::table('parco_scheda_mezzo as p')
			->where('dele','=',0)
			->get();

			$voci=array();
			array_push($voci,"TARGA");
			array_push($voci,"NUMERO INTERNO");
			array_push($voci,"TIPOLOGIA");
			array_push($voci,"MARCA");
			array_push($voci,"MODELLO");
			array_push($voci,"TELAIO");
			array_push($voci,"ALIMENTAZIONE");
			array_push($voci,"PROPRIETA");
			array_push($voci,"DA DATA NOLEGGIO");
			array_push($voci,"A DATA NOLEGGIO");
			array_push($voci,"KM NOLEGGIO");
			array_push($voci,"IMPORTO NOLEGGIO");
			array_push($voci,"KM ALERT MAIL");
			array_push($voci,"GG ALERT MAIL");
			array_push($voci,"SERVIZI NOLEGGIO");
			array_push($voci,"POSTI");
			array_push($voci,"CHILOMETRAGGIO");
			array_push($voci,"CATENE");
			array_push($voci,"CARTA CARBURANTE");
			array_push($voci,"BADGE CISTERNA");
			array_push($voci,"TELEPASS");
			array_push($voci,"DATA IMMATRICOLAZIONE");
			array_push($voci,"ULTIMA REVISIONE");
			array_push($voci,"SCADENZA ASSICURAZIONE");
			array_push($voci,"SCADENZA BOLLO");
			array_push($voci,"PROSSIMO TAGLIANDO");
			array_push($voci,"MARCA MODELLO PENUMATICO");
			array_push($voci,"MISURA PENUMATICO");
			array_push($voci,"PRIMO EQUIPAGGIAMENTO");
			array_push($voci,"KM INSTALLAZIONE");
			array_push($voci,"OFFICINA INSTALLAZIONE");
			array_push($voci,"ANOMALIA NOTE");
			array_push($voci,"MEZZO MARCIANTE");
			array_push($voci,"MEZZO IN MANUTENZIONE");
			array_push($voci,"MEZZO IN RIPARAZIONE");
			array_push($voci,"OFFICINA DI RIFERIMENTO");
			array_push($voci,"DATA CONSEGNA RIPARAZIONE");
			array_push($voci,"IMPORTO PREVENTIVO");
			array_push($voci,"IMPORTO FATTURA");


				
			$collection = new Collection;
			//head
			$collection->push((object)[	
				$voci
			]);				


			foreach ($parco_scheda_mezzo as $psm) {
				$tipo="";
				$tipologia=$psm->tipologia;
				if ($tipologia==1) $tipo="Carro funebre";
				if ($tipologia==2) $tipo="Furgone";
				if ($tipologia==3) $tipo="Auto";
				if ($tipologia==4) $tipo="Furgone attrezzato";
				$marca="";
				if (isset($arr_marca[$psm->marca])) $marca=$arr_marca[$psm->marca];
				$modello="";
				if (isset($arr_modello[$psm->modello])) $modello=$arr_modello[$psm->modello];
				
				$alimentazione=$psm->alimentazione;
				$alim="";
				if ($alimentazione==1) $alim="Benzina";
				if ($alimentazione==2) $alim="Diesel";
				
				$proprieta=$psm->proprieta;
				$prop="";
				if ($proprieta==1) $prop="Noleggio";
				if ($proprieta==2) $prop="Proprietà";
				if ($proprieta==3) $prop="Leasing";
				
				$serv=$psm->servizi_noleggio;
				$arr_s=explode(";",$serv);
				$servizi="";
				for ($sca=0;$sca<=count($arr_s)-1;$sca++) {
					if (strlen($servizi)!=0) $servizi.=", ";
					if (array_key_exists($arr_s[$sca],$arr_servizi))
						$servizi.=$arr_servizi[$arr_s[$sca]];
				}
				$cat=$psm->catene;
				$catene="";
				if ($cat==1) $catene="SI";
				if ($cat==2) $catene="NO";

				$cartac="";
				if (isset($arr_carta[$psm->carta_carburante])) $cartac=$arr_carta[$psm->modello];
				
				$badge_c="";
				if (isset($arr_badge[$psm->badge_cisterna])) $badge_c=$arr_badge[$psm->badge_cisterna];
				
				$telepass="";
				if (isset($arr_telepass[$psm->telepass])) $telepass=$arr_telepass[$psm->telepass];				
				
				$eq=$psm->primo_equipaggiamento;
				$equip="";
				if ($eq==1) $equip="SI";
				if ($eq==2) $equip="NO";
				
				$mm=$psm->mezzo_marciante;
				$mezzo_mar="";
				if ($mm==1) $mezzo_mar="SI";
				if ($mm==2) $mezzo_mar="NO";
				
				$mm=$psm->mezzo_manutenzione;
				$mezzo_man="";
				if ($mm==1) $mezzo_man="SI";
				if ($mm==2) $mezzo_man="NO";

				$mm=$psm->mezzo_riparazione;
				$mezzo_rip="";
				if ($mm==1) $mezzo_rip="SI";
				if ($mm==2) $mezzo_rip="NO";

				$voci=array();
				array_push($voci,$psm->targa);
				array_push($voci,$psm->numero_interno);
				array_push($voci,$tipo);
				array_push($voci,$marca);
				array_push($voci,$modello);
				array_push($voci,$psm->telaio);
				array_push($voci,$alim);
				array_push($voci,$prop);
				array_push($voci,$psm->da_data_n);
				array_push($voci,$psm->a_data_n);
				array_push($voci,$psm->km_noleggio);
				array_push($voci,$psm->importo_noleggio);
				array_push($voci,$psm->km_alert_mail);
				array_push($voci,$psm->gg_alert_mail);
				array_push($voci,$servizi);
				array_push($voci,$psm->posti);
				array_push($voci,$psm->chilometraggio);
				array_push($voci,$catene);
				array_push($voci,$cartac);
				array_push($voci,$badge_c);
				array_push($voci,$telepass);
				array_push($voci,$psm->data_immatricolazione);
				array_push($voci,$psm->ultima_revisione);
				array_push($voci,$psm->scadenza_assicurazione);
				array_push($voci,$psm->scadenza_bollo);
				array_push($voci,$psm->prossimo_tagliando);
				array_push($voci,$psm->marca_modello_pneumatico);
				array_push($voci,$psm->misura_pneumatico);
				array_push($voci,$equip);
				array_push($voci,$psm->km_installazione);
				array_push($voci,$psm->officina_installazione);
				array_push($voci,$psm->anomalia_note);
				array_push($voci,$mezzo_mar);
				array_push($voci,$mezzo_man);
				array_push($voci,$mezzo_rip);
				array_push($voci,$psm->officina_riferimento);
				array_push($voci,$psm->data_consegna_riparazione);
				array_push($voci,$psm->importo_preventivo);
				array_push($voci,$psm->importo_fattura);
				


				$collection->push((object)[	
					$voci
				]);				
			}
			return $collection; 
        }
		
		
		
		public function arr($from) {
			$all=array();
			if ($from=="marca") $all= parco_marca_mezzo::select('id','marca as descrizione');
			if ($from=="modello") $all= parco_modello_mezzo::select('id','modello as descrizione');
			if ($from=="servizi") $all= parco_servizi_noleggio::select('id','descrizione');
			if ($from=="cartac") $all= parco_carta_carburante::select('id','id_carta as descrizione');
			if ($from=="badge") $all= parco_badge_cisterna::select('id','id_badge as descrizione');
			if ($from=="telepass") $all= parco_telepass::select('id','id_telepass as descrizione');


			
			if (is_object($all)) $dati=$all->get(); 
			
			$resp=array();
			if (is_object($all)) {
				foreach ($dati as $rec) {
					$id=$rec->id;
					$descrizione=$rec->descrizione;
					$resp[$id]=$descrizione;
				}
			}
			return $resp;
		}
		
		
		public function doc_cand() {
			//tutti i corsi di sicurezza (id 4) frequentati dai candidati
			/*
			$ref_doc = DB::table('ref_doc as r')
			->join('tipo_doc as d', 'r.id_tipo_doc', '=', 'd.id')
			->leftJoin('voci_doc as v', 'r.id_sotto_tipo', '=', 'v.id')
			->select('r.id','r.id_tipo_doc','r.id_sotto_tipo','r.id_cand','r.scadenza', 'd.alias as alias_t', 'v.alias as alias_st')
			->where('r.id_tipo_doc',"=",4)
			->groupBy('r.id')
			->orderByDesc('r.id')
			->get();		
			*/

			$ref_doc = DB::table('ref_doc as r')
			->select('r.id','r.id_tipo_doc','r.id_sotto_tipo','r.id_cand','r.scadenza')
			->where('r.id_tipo_doc',"=",4)
			->groupBy('r.id')
			->orderByDesc('r.id_cand')
			->get();			
			
			
			$resp=array();
			$old="?";$indice=0;
			foreach ($ref_doc as $rec) {
				$id_cand=$rec->id_cand;
				if ($id_cand!=$old) $indice=0;
				else $indice++;
				$old=$id_cand;
				$id_sotto_tipo=$rec->id_sotto_tipo;
				//$alias_t=$rec->alias_t;
				//alias_st=$rec->alias_st;
				$scadenza=$rec->scadenza;
				$resp[$id_cand][$indice]['id_sotto_tipo']=$id_sotto_tipo;
				//$resp[$id_cand][$indice]['alias_t']=$alias_t;
				//$resp[$id_cand][$indice]['alias_st']=$alias_st;
				$resp[$id_cand][$indice]['scadenza']=$scadenza;
			}
			return $resp;
			
		}
		



    }