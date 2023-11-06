<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\user;

use App\Models\appalti;
use App\Models\parco_scheda_mezzo;
use App\Models\sinistri;
use App\Models\candidati;
use App\Models\support_sinistri;

use DB;

class ControllerSinistri extends Controller
{
	public function __construct() {
		//$this->middleware('auth')->except(['index']);
	}
	
	
	public function elenco_sinistri($id_rif=0) {

		$dele_contr=request()->input("dele_contr");
		$restore_contr=request()->input("restore_contr");
		$view_dele=request()->input("view_dele");
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;


		if (strlen($dele_contr)!=0) {
			sinistri::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			sinistri::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}	

		$sinistri=sinistri::from('sinistri as s')
		->select('s.id','s.dele','s.id_appalto',DB::raw("DATE_FORMAT(s.dataora,'%d-%m-%Y %H:%i') as dataora"),'a.responsabile_mezzo','a.targa','s.file_cid')
		->join('appalti as a','s.id_appalto','a.id')
		->when($view_dele=="0", function ($sinistri) {
			return $sinistri->where('s.dele', "=","0");
		})	
		->where("a.dele","=",0)		
		->orderBy('s.id','desc')
		->get();


		$support_sinistri=support_sinistri::from('support_sinistri as s')
		->select('s.id_sinistro','s.filename')
		->where('dele','=',0)
		->get();
		$support_ref=array();
		foreach ($support_sinistri as $support) {
			$support_ref[$support->id_sinistro][]=$support->filename;
		}
		
		
		
		$lavoratori=candidati::from('candidatis as c')
		->select('c.id','c.nominativo')
		->get();
		$all_lav=array();
		foreach ($lavoratori as $lavoratore) {
			$all_lav[$lavoratore->id]['nominativo']=$lavoratore->nominativo;
		}



		return view('all_views/sinistri/elenco_sinistri',compact('sinistri','view_dele','all_lav','support_ref'));		
		
	}	
	
	
	public function sinistri($id_appalto=0,$from=0,$id_sinistro=0) {
		$request=request();
		$delefoto=$request->input("delefoto");
		if (strlen($delefoto)!=0) {
			support_sinistri::where('id', $delefoto)
			  ->update(['dele' => 1]);
		}
		$dele_cid=$request->input("dele_cid");
		if (strlen($dele_cid)!=0) {
			sinistri::where('id', $dele_cid)
			  ->update(['file_cid' => null]);
		}

		
		$btn_save=$request->input("btn_save");
		if ($request->has('from')) $from=$request->input('from');
		if ($btn_save=="save") {
			if ($id_sinistro==0)
				$sin = new sinistri;
			else
				$sin=sinistri::find($id_sinistro);
			
			$sin->id_appalto=$id_appalto;
			$sin->id_mezzo=$request->input("mezzo_coinvolto");
			$sin->dataora=$request->input("dataora");
			$sin->mezzo_marciante=$request->input("mezzo_marciante");
			$sin->citta=$request->input("citta");
			$sin->provincia=$request->input("provincia");
			$sin->indirizzo=$request->input("indirizzo");
			$sin->descrizione=$request->input("descrizione");
			$sin->save();
			$id_sinistro=$sin->id;
			return redirect()->route("sinistri",['id_appalto'=>$id_appalto,'id_sinistro'=>$id_sinistro,'from'=>$from]);			
		}		
		
		
		$allinfo=appalti::select('appalti.*')
		->join('lavoratoriapp as l','appalti.id','l.id_appalto')
		->where('appalti.id', "=",$id_appalto)
		->groupBy('appalti.id')
		->get();
		
		$responsabile_mezzo="";
		if (isset($allinfo[0])) {
			$rxc=candidati::from('candidatis as c')
			->select('c.id','c.nominativo')
			->where('id','=',$allinfo[0]->responsabile_mezzo);

			if ($rxc->count()>0) 
				$responsabile_mezzo=$rxc->get()->first()->nominativo;
			
		}
		
		$last_appalti=appalti::select('id',DB::raw("DATE_FORMAT(data_ref,'%d-%m-%Y %H:%i') as data_ref"),'orario_ref','chiesa')
		->orderBy('id','desc')
        ->skip(0)
        ->take(100)
		->get();
		
		$mezzi=parco_scheda_mezzo::from('parco_scheda_mezzo as m')
		->select('m.id','m.targa')
		->where('m.dele','=',0)
		->orderBy('targa')
		->get();

		$info_sinistro=array();
		$support_sinistri=array();
		if ($id_sinistro!=0) {
			$info_sinistro=sinistri::from('sinistri as s')
			->select('s.*')
			->where('s.id','=',$id_sinistro)
			->get();

			$support_sinistri=support_sinistri::from('support_sinistri as s')
			->select('s.id','s.id_sinistro','s.filename')
			->where('id_sinistro','=',$id_sinistro)
			->where('dele','=',0)
			->get();
		}

	

		
		return view('all_views/sinistri/sinistri',compact('id_appalto','allinfo','mezzi','info_sinistro','id_sinistro','from','last_appalti','responsabile_mezzo','support_sinistri'));		
	}
	
	
}

