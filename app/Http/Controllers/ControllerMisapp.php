<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\candidati;
use App\Models\rimborsi;
use OneSignal;

use DB;

class ControllerMisapp extends Controller
{
public function __construct()
	{
		$this->middleware('auth')->except(['index']);
	}		

	

	

	public function misapp($id_appalto=0) {
		$request=request();
		$view_dele="";
		$id_user=Auth::user()->id;
		$result = (new ApiController)->countappalti($request);

		
		$id_lav=Auth::user()->id;

		$elenco_rimborsi=rimborsi::select('rimborsi.id','rimborsi.id_rimborso','r.descrizione','rimborsi.dataora','rimborsi.importo','rimborsi.stato','rimborsi.filename')
		->join('rimborsi_tipologie as r','rimborsi.id_rimborso','r.id')
		->where('rimborsi.id_user', "=",$id_lav)
		->get();

		return view('all_views/misapp/misapp')->with('result',$result)->with('id_user',$id_user)->with('elenco_rimborsi',$elenco_rimborsi);

	}

	public function send_push($userId,$tipo="new",$message_extra="") {
		//$userId="3863803b-eb7e-4ad4-aafd-958b85dff83f";
		if (strlen($userId)==0) return;
		$params = []; 
		$params['include_player_ids'] = [$userId]; 
		$headings = array(
			"it" => 'MisAPP News',
			"en" => 'MisAPP News'
			);
		
		if ($tipo=="new")
			$contents = [ 
			   "it" => "Nuova richiesta accettazione Servizio", 
			   "en" => "Request acceptance of new service"
			]; 

		if ($tipo=="alert")
			$contents = [ 
			   "it" => "Sollecito accettazione Servizio $message_extra", 
			   "en" => "Request acceptance of new service"
			]; 

		if ($tipo=="edit")
			$contents = [ 
			   "it" => "Segnalazione di variazione su appalto $message_extra", 
			   "en" => "Edit service"
			]; 
		if ($tipo=="dele")
			$contents = [ 
			   "it" => "Estromissione da appalto $message_extra", 
			   "en" => "Dele from service"
			]; 
			
		$params['priority'] = 10; 
		$params['contents'] = $contents; 
		$params['headings'] = $headings; 
		//$params['delayed_option'] = "timezone"; // Will deliver on user's timezone 
		//$params['delivery_time_of_day'] = "2:30PM"; // Delivery time

		$resp=OneSignal::sendNotificationCustom($params);		
	}
}
