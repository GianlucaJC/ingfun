<?php
//test
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\candidati;
use App\Models\regioni;
use App\Models\italy_cities;
use App\Models\italy_cap;
use App\Models\tipoc;
use App\Models\societa;
use App\Models\centri_costo;
use App\Models\area_impiego;
use App\Models\mansione;
use App\Models\ccnl;
use App\Models\tipologia_contr;
use Mail;

use DB;




use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class ControllerPersonale extends Controller
{
	public function __construct()
	{
		if (!Auth::user())
		$this->middleware('auth')->except(['index','servizio_scadenze']);
	}	

	public function cedolini_view() {

		$id = Auth::user()->id;
		$user = User::find($id);
		$ref_lav=array();$ref_lav[0]['id_ref']=0;$ref_lav[0]['codfisc']="";

		
		if ($user->hasRole('user')) {
			$ref_lav=candidati::select('id as id_ref','codfisc')
			->where("id_user","=",$id)
			->get();
		}
		$tipo_cedolino=request()->input("tipo_cedolino");
		$periodo=request()->input("periodo");
		$k=$periodo;
		$m=substr($k,4,2);$m1="";
		$a=substr($k,0,4);
		if ($m=="01") $m1="GEN";
		if ($m=="02") $m1="FEB";
		if ($m=="03") $m1="MAR";
		if ($m=="04") $m1="APR";
		if ($m=="05") $m1="MAG";
		if ($m=="06") $m1="GIU";
		if ($m=="07") $m1="LUG";
		if ($m=="08") $m1="AGO";
		if ($m=="09") $m1="SET";
		if ($m=="10") $m1="OTT";
		if ($m=="11") $m1="NOV";
		if ($m=="12") $m1="DIC";
		$periodo_sel=$m1.$a;

		$id_cand=request()->input("id_cand");
		$dir="allegati/cedoliniview/$tipo_cedolino";

		$status="";
		if (request()->has("btn_dele")) {
			$dir1="allegati/cedoliniview/$tipo_cedolino/$periodo_sel";
			$dir_orig="allegati/cedolini/$tipo_cedolino/$periodo_sel";

			if(is_dir($dir1)) {
				$cedolino=request()->input("cedolino");
				if (is_array($cedolino)) {
					for ($sca=0;$sca<count($cedolino);$sca++) {
						$ced= $cedolino[$sca].".pdf";
						$ced_md5=md5($cedolino[$sca]).".pdf";
						@unlink ($dir_orig."/".$ced);
						@unlink ($dir1."/".$ced_md5);
					}
					$status="canc";
				}
			}
		}		
		if (request()->has("btn_dele_all")) {
			$dir = "allegati/cedolini/$tipo_cedolino/$periodo_sel/";
			array_map('unlink', glob("$dir/*.pdf"));
			array_map('unlink', glob("$dir/*.ddd"));
			@rmdir($dir);
			$dir = "allegati/cedoliniview/$tipo_cedolino/$periodo_sel/";
			array_map('unlink', glob("$dir/*.pdf"));
			@rmdir($dir);
			$status="canc";
		}


		$results = @scandir($dir);
		if (!is_array($results)) $results=array();
		
		$periodi_raw=array();
		foreach ($results as $result) {
			if ($result === '.' or $result === '..') continue;
			if (is_dir($dir . '/' . $result)) {
				if (strlen($result)>6) {
					$m=substr($result,0,3);$m=strtolower($m);
					$m1="";
					if ($m=="gen") $m1="01";
					if ($m=="feb") $m1="02";
					if ($m=="mar") $m1="03";
					if ($m=="apr") $m1="04";
					if ($m=="mag") $m1="05";
					if ($m=="giu") $m1="06";
					if ($m=="lug") $m1="07";
					if ($m=="ago") $m1="08";
					if ($m=="set") $m1="09";
					if ($m=="ott") $m1="10";
					if ($m=="nov") $m1="11";
					if ($m=="dic") $m1="12";
					$a=substr($result,3,4);
					$am=$a.$m1;
					$periodi_raw[]=$am;
				}
			}
		}
		
		rsort($periodi_raw);
		$periodi=array();
		for ($sca=0;$sca<count($periodi_raw);$sca++) {
			$k=$periodi_raw[$sca];
			$m=substr($k,4,2);$m1="";
			$a=substr($k,0,4);
			if ($m=="01") $m1="GEN";
			if ($m=="02") $m1="FEB";
			if ($m=="03") $m1="MAR";
			if ($m=="04") $m1="APR";
			if ($m=="05") $m1="MAG";
			if ($m=="06") $m1="GIU";
			if ($m=="07") $m1="LUG";
			if ($m=="08") $m1="AGO";
			if ($m=="09") $m1="SET";
			if ($m=="10") $m1="OTT";
			if ($m=="11") $m1="NOV";
			if ($m=="12") $m1="DIC";
			$periodi[$k]=$m1.$a;
		}
		$cand_cf=array();
		$candidati=DB::table('candidatis')		
		->orderBy('codfisc')->get();
		$indice=0;$cf_old="?";
		foreach ($candidati as $cand) {
			$cf=$cand->codfisc;
			if ($cf_old==$cf) $indice++;
			else {$cf_old=$cf;$indice=0;}
			if (strlen($cf)!=0) 
				$cand_cf[$cf][$indice]=$cand->nominativo;
		}

		$sub="allegati/cedolini/$tipo_cedolino/$periodo_sel";
		$elenco = @scandir($sub);
		if (!is_array($elenco)) $elenco=array();
		$tb_risp=array();
		
		for ($sca=0;$sca<count($elenco);$sca++) {
			$fx_src=$elenco[$sca];
			$fx=str_replace(".pdf","",$fx_src);
			
			if (strlen($id_cand)!=0) {
				$ref=explode("-",$id_cand);
				$cf_ref=$ref[1];
				$includi=false;
				if ($fx==$cf_ref) $includi=true;
			} else $includi=true;
			
			if (strlen($fx)==16 && $includi==true) {
				$fx_dest=md5($fx).".pdf";
				
				if (file_exists($sub."/".$fx_src)) {
					$tb_risp[$fx]=$fx_dest;
				}	
			}
		}

		$dir_ref=str_replace("cedolini","cedoliniview",$sub);
		return view('all_views/cedolini_view')->with('candidati', $candidati)->with('periodi',$periodi)->with('tipo_cedolino',$tipo_cedolino)->with('periodo',$periodo)->with('id_cand',$id_cand)->with('tb_risp',$tb_risp)->with('cand_cf',$cand_cf)->with('periodo_sel',$periodo_sel)->with('dir_ref',$dir_ref)->with('ref_lav',$ref_lav)->with('status',$status);
	}


	public function cedolini_up(Request $request) {
		$mese_busta=$request->input("mese_busta");
		$tipo_cedolino=$request->input("tipo_cedolino");
		$anno_busta=$request->input("anno_busta");
		$periodo=$mese_busta.$anno_busta;
		$dele_pdf=$request->input("dele_pdf");
		$distr=$request->input("distr");
		if ($distr=="distr") {
			$dir="allegati/cedolini/$tipo_cedolino/$periodo";
			$sub="allegati/cedoliniview/$tipo_cedolino";
			@mkdir($sub);
			$sub="allegati/cedoliniview/$tipo_cedolino/$periodo";
			@mkdir($sub);
			$elenco = scandir($dir);
			for ($sca=0;$sca<count($elenco);$sca++) {
				$fx_src=$elenco[$sca];
				$fx=str_replace(".pdf","",$fx_src);
				if (strlen($fx)==16) {
					$fx_dest=md5($fx).".pdf";
					copy($dir."/".$fx_src,$sub."/".$fx_dest);
				}
			}
			$f1 = fopen($dir."/distr.ddd", "w") or die("Unable to open file!");
			$txt = "Done!";
			fwrite($f1, $txt);
		}
		if ($dele_pdf=="1") {
			$dir = "allegati/cedolini/$tipo_cedolino/$periodo/";
			array_map('unlink', glob("$dir/*.pdf"));
			array_map('unlink', glob("$dir/*.ddd"));
			$dir = "allegati/cedoliniview/$tipo_cedolino/$periodo/";
			array_map('unlink', glob("$dir/*.pdf"));
		}
		
		
		return view('all_views/cedolini_up')->with('tipo_cedolino',$tipo_cedolino)->with('mese_busta',$mese_busta)->with('anno_busta',$anno_busta)->with("dele_pdf",$dele_pdf)->with('distr',$distr);
	}
	

	public function popola_array_info() {
		$info_soc=array();
		$info_area=array();
		$centri_costo=array();
		$ccnl=array();
		$tipoc=array();
		$comuni=array();
		$cap=array();
		for ($sca=1;$sca<=7;$sca++) {
			if ($sca==1) $info=societa::select('descrizione','id')->get();
			if ($sca==2) $info=area_impiego::select('descrizione','id')->get();
			if ($sca==3) $info=centri_costo::select('descrizione','id')->get();
			if ($sca==4) $info=ccnl::select('descrizione','id')->get();
			if ($sca==5) $info=ccnl::select('descrizione','id')->get();
			if ($sca==6) $info= italy_cities::select("istat as id","comune as descrizione")->get();
			if ($sca==7) $info= italy_cap::select("cap as id","istat as descrizione")->get();

			for ($sc=0;$sc<count($info);$sc++) {			
				if ($sca==1) $info_soc[$info[$sc]->id]=$info[$sc]->descrizione;
				if ($sca==2) $info_area[$info[$sc]->id]=$info[$sc]->descrizione;
				if ($sca==3) $centri_costo[$info[$sc]->id]=$info[$sc]->descrizione;
				if ($sca==4) $ccnl[$info[$sc]->id]=$info[$sc]->descrizione;
				if ($sca==5) $tipoc[$info[$sc]->id]=$info[$sc]->descrizione;
				if ($sca==6) $comuni[$info[$sc]->id]=$info[$sc]->descrizione;
				$id_cap=$info[$sc]->id;
				$z="";
				for ($l=strlen($id_cap);$l<5;$l++) {
					$z.="0";
				}
				$id_cap="$z$id_cap";
				if ($sca==7) $cap[$id_cap]=$info[$sc]->descrizione;
			}
		}

		$arr['info_soc']=$info_soc;
		$arr['info_area']=$info_area;
		$arr['centri_costo']=$centri_costo;
		$arr['ccnl']=$ccnl;
		$arr['tipoc']=$tipoc;
		$arr['comuni']=$comuni;
		$arr['cap']=$cap;

		return $arr;
	}
	
	public function scadenze_contratti(Request $request) {
		$arr=$this->popola_array_info();
		$info_soc=$arr['info_soc'];
		$info_area=$arr['info_area'];
		$centri_costo=$arr['centri_costo'];
		$ccnl=$arr['ccnl'];
		$tipoc=$arr['tipoc'];
		$arr_loc=$arr['comuni'];
		$arr_cap=$arr['cap'];

		
		//->where("data_fine","<=", $today)
		$today=date("Y-m-d");
		$scadenze=candidati::select('id', 'nominativo','status_candidatura', 'data_inizio', 'data_fine','soc_ass','area_impiego','centro_costo','appartenenza','contratto','livello','tipo_contr','categoria_legale','ore_sett','codice_qualifica','qualificato','titolo_studio','codfisc','datanasc','pro_nasc','indirizzo','cap','comune','comunenasc')
		->where("dele","=",0)
		->where("status_candidatura","=",3)
		->whereNotNull('data_fine')
		->orderBy('data_fine')
		->get();

		return view('all_views/scadenze_contratti')->with('scadenze', $scadenze)->with('info_soc',$info_soc)->with('info_area',$info_area)->with('centri_costo',$centri_costo)->with('ccnl',$ccnl)->with('tipoc',$tipoc)->with('arr_loc',$arr_loc)->with('arr_cap',$arr_cap);
	}

	public function listpers(Request $request) {
		
		
		$view_dele=0;
		if ($request->has("view_dele")) $view_dele=$request->input("view_dele");
		if ($view_dele=="on") $view_dele=1;
		
		$restore_cand=$request->input("restore_cand");
		$dele_cand=$request->input("dele_cand");

		/*
		if (strlen($dele_cand)!=0) {
			candidati::where('id', $dele_cand)
			  ->update(['dele' => 1]);			
		}		
		if (strlen($restore_cand)!=0) {
			candidati::where('id', $restore_cand)
			  ->update(['dele' => 0]);			
		}
		*/		

		$arr=$this->popola_array_info();
		$info_soc=$arr['info_soc'];
		$info_area=$arr['info_area'];
		$centri_costo=$arr['centri_costo'];
		$ccnl=$arr['ccnl'];
		$tipoc=$arr['tipoc'];
		$arr_loc=$arr['comuni'];
		$arr_cap=$arr['cap'];

		
		$count = candidati::where('status_candidatura', '=',3)->count();
		$all_ris = candidati::count();
		
		
		$scadenze=candidati::select('id', 'nominativo','status_candidatura', 'data_inizio', 'data_fine','soc_ass','area_impiego','centro_costo','appartenenza','contratto','livello','tipo_contr','categoria_legale','ore_sett','codice_qualifica','qualificato','titolo_studio','codfisc','datanasc','pro_nasc','indirizzo','cap','comune','comunenasc')
		->when($view_dele=="0", function ($scadenze) {
			return $scadenze->where('dele', "=","0");
		})		
		->where("status_candidatura","=",3)
		->orderBy('nominativo')	
		->get();
		
	

		return view('all_views/listpers')->with('scadenze', $scadenze)->with('info_soc',$info_soc)->with('info_area',$info_area)->with('centri_costo',$centri_costo)->with('ccnl',$ccnl)->with('tipoc',$tipoc)->with('arr_loc',$arr_loc)->with('arr_cap',$arr_cap)->with('view_dele',$view_dele)->with('count',$count)->with('all_ris',$all_ris);


	}
	
	function servizio_scadenze(Request $request) {
		$today=date("Y-m-d");
		$date = strtotime($today);
		$first_date=date('Y-m-01', $date);
		$first_date_i=date('01-m-Y', $date);
		$last_date = date("Y-m-t", $date);
		$last_date_i = date("t-m-Y", $date);

		$count=DB::table('candidatis as c')
		->join('societa as s', 'c.soc_ass', '=', 's.id')
		->where("c.dele","=",0)
		->where("c.status_candidatura","=",3)
		->where('c.data_fine','>=',$first_date)
		->where('c.data_fine','<=',$last_date)
		->where('s.mail_scadenze','like','%@%')
		->count();
		$status=array();
		$status['status']="OK";
		$status['message']="Non ci sono contratti in scadenza o non risultano definite mail_scadenze nelle societa'!";

		if ($count>0) {
			$scadenze=DB::table('candidatis as c')
			->join('societa as s', 'c.soc_ass', '=', 's.id')
			->select('c.soc_ass','c.nominativo','c.data_inizio', 'c.data_fine','s.descrizione','s.mail_scadenze')
			->where("c.dele","=",0)
			->where("c.status_candidatura","=",3)
			->where('c.data_fine','>=',$first_date)
			->where('c.data_fine','<=',$last_date)
			->where('s.mail_scadenze','like','%@%')
			->orderBy('c.soc_ass')
			->get();


			
			$resp=array();
			$old_soc="?";$indice=0;
			foreach ($scadenze as $scadenza) {
				$soc_ass=$scadenza->soc_ass;
				if ($old_soc!=$soc_ass) $indice=0;
				else $indice++;
				$old_soc=$soc_ass;
				$mail_scadenze=$scadenza->mail_scadenze;
				$resp[$mail_scadenze][$indice]['nominativo']=$scadenza->nominativo;
				$resp[$mail_scadenze][$indice]['data_inizio']=$scadenza->data_inizio;
				$resp[$mail_scadenze][$indice]['data_fine']=$scadenza->data_fine;
			}
			

			
			$titolo = "Reminder Scadenze contrattuali";
			$body_msg="Elenco dei nominativi con contratto in scadenza nel periodo $first_date_i - $last_date_i";

			try {
				$destinatari=array();
				foreach($resp as $email=>$v) {
					$scadenza=$resp[$email];
					$data["email"] = $email;
					$destinatari[]=$email;
					$data["title"] = $titolo;
					$data["body"] = $body_msg;
					$data["scadenza"] = $scadenza;

					Mail::send('emails.scadenze', $data, function($message)use($data) {
						$message->to($data["email"], $data["email"])
						->subject($data["title"]);

					});

				}
				$status['status']="OK";
				$status['message']="Mail inviata con successo";
				$status['destinatari']=$destinatari;
				
				
			} catch (Throwable $e) {
				$status['status']="KO";
				$status['message']="Errore occorso durante l'invio! $e";
			}		
		}
			
		
		return json_encode($status);
	}

}
