<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\candidati;
use App\Models\rimborsi;
use App\Models\rimborsi_tipologie;
use DB;
use Image;
use Mail;

class ControllerRimborsi extends Controller
{
	/*
	public function __construct() {
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
	}
	*/

	public function elenco_rimborsi() {
		$elenco=rimborsi_tipologie::select('id','descrizione','obbligo_foto',DB::raw("CONCAT(id,'|',obbligo_foto) AS id_ref"))
		->orderBy('descrizione')
		->get();		
		echo json_encode($elenco);
	}

	public function send_rimborso(Request $request) {
		
		$id_lav=Auth::user()->id;
		$obbligo_foto = $request->input('obbligo_foto');
		$filename=null;
		if ($obbligo_foto=="1") {
			$filename=uniqid().".jpg";
		
			//$filename = $request->header('filename');
			$file = $_FILES['file'];
			$temp = $file['tmp_name'];
			
			$target_file = tempnam("dist/upload/rimborsi", "photo");
		
			if (move_uploaded_file($temp, $target_file)) {
				$path="dist/upload/rimborsi/";
				$small = "dist/upload/rimborsi/thumbnail/small/";
				$medium = "dist/upload/rimborsi/thumbnail/medium/";
				$result = rename($target_file, $path . $filename);  
				copy($path.$filename, $small.$filename);
			 	copy($path.$filename, $medium.$filename);
				$this->createThumbnail($small.$filename, 150, 93);
				$this->createThumbnail($medium.$filename, 300, 185);
			} else {
				$risp['header']="KO";
				$risp['temp']=$temp;
				$risp['target_file']=$target_file;
				$risp['message']="Attenzione - Dati non riversati per errore riscontrato";
				echo json_encode($risp);
				exit;
			}		
		}	
		
		$tipo_rimborso = $request->input('tipo_rimborso');
		$importo = $request->input('importo');
		$data_ora = $request->input('data_ora');

		$rimborsi = new rimborsi;
		$rimborsi->id_user = $id_lav;
		$rimborsi->id_rimborso=$tipo_rimborso;
		$rimborsi->dataora=$data_ora;
		$rimborsi->importo=$importo;
		$rimborsi->filename=$filename;
		$rimborsi->save();
		$risp['header']="OK";
		$risp['message']="File e dati riversati sul server";
		echo json_encode($risp);

   }

   public function createThumbnail($path, $width, $height)
   {
	   $img = Image::make($path)->resize($width, $height, function ($constraint) {
		   $constraint->aspectRatio();
	   });
	   $img->save($path);
   }

}
