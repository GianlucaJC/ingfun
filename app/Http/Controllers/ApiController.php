<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\appalti;




class ApiController extends Controller
{
	
	public function check_log($request) {
		$utente=$request->input("utente");
		$pw=$request->input("pw");
		$check=user::select('c.id_user','c.id as id_cand')
		->join("candidatis as c","users.id","=","c.id_user")
		//->where('password',"=",$pw_c)
		->where('users.email',"=",$utente);
		$count=$check->count();
		
		$resp=array();
		if ($count>0) {
			$c=$check->get();
			$resp['esito']="OK";
			$resp['id_user']=$c[0]->id_user;
			$resp['id_cand']=$c[0]->id_cand;
		}	
		else {
			$resp['esito']="KO";
		}		
		return $resp;
	}
	
	public function login(Request $request) {
		$login=array();
		$risp=$this->check_log($request);
		$login['header']=$risp;
		echo json_encode($login);
	}
	public function countappalti(Request $request) {

		$check=$this->check_log($request); 
		if ($check['esito']=="KO") {
			$risp['header']=$check;
			 echo json_encode($risp);
			 exit;
		} 
		$id_lav_ref=$check['id_user'];
		/*
		$count=appalti::select('appalti.id','appalti.dele','appalti.descrizione_appalto','appalti.data_ref','appalti.id_ditta','d.denominazione')
		->join('ditte as d', 'd.id','=','appalti.id_ditta')
		->join('lavoratoriapp as l','l.id_ditta_ref','d.id')
		->where('appalti.dele', "=","0")
		->where('l.id_lav_ref',"=",$id_lav_ref)
		->groupBy('appalti.id')
		->orderByDesc('appalti.id')
		->count();
		*/
		
		$count=appalti::select('appalti.id')
		->join('lavoratoriapp as l','appalti.id','l.id_appalto')
		->where('appalti.dele', "=","0")
		->where('l.id_lav_ref',"=",$id_lav_ref)
		->count();
		
	
		$risp['header']=$check;
		$risp['count']=$count;

		
		echo json_encode($risp);
		
	}	
  
}
