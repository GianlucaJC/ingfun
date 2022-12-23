<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Models\candidati;

use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;
use Spatie\PdfToText\Pdf;
use DB;


class ControllerPdf extends Controller
	{
		
		public function analisi_pdf(Request $request) {
			$pagecount=$request->input('pagecount');
			$tipo_cedolino=$request->input('tipo_cedolino');
			$periodo=$request->input('periodo');
			$allcf=candidati::select('codfisc','nominativo')			
			->orderBy('codfisc')
			->groupBy('codfisc')
			->get();
			$path = "pdftotext.exe";
			$filename="allegati/cedolini/$tipo_cedolino/$periodo/busta.pdf";
			
			//$testo= Pdf::getText($new_filename, '/');
			exec("pdftotext.exe ".$filename." pdf_read.txt");
			$testo = file_get_contents("pdf_read.txt");	


			$pattern='/(?:[A-Z][AEIOU][AEIOUX]|[AEIOU]X{2}|[B-DF-HJ-NP-TV-Z]{2}[A-Z]){2}(?:[\dLMNP-V]{2}(?:[A-EHLMPR-T](?:[04LQ][1-9MNP-V]|[15MR][\dLMNP-V]|[26NS][0-8LMNP-U])|[DHPS][37PT][0L]|[ACELMRT][37PT][01LM]|[AC-EHLMPR-T][26NS][9V])|(?:[02468LNQSU][048LQU]|[13579MPRTV][26NS])B[26NS][9V])(?:[A-MZ][1-9MNP-V][\dLMNP-V]{2}|[A-M][0L](?:[1-9MNP-V][\dLMNP-V]|[0L][1-9MNP-V]))[A-Z]/';
			preg_match_all($pattern, $testo, $matches);
			
			try {
				$status['status']="OK";
				$status['message']=$matches;
				$status['allcf']=$allcf;
			} catch (Exception $e) {
				$status['status']="KO";
				$status['message']=$e->getMessage();
				$status['allcf']=$allcf;
			}

			return json_encode($status);	
		
			
		}

		public function split_pdf(Request $request) {
			$page=$request->input('page');
			$pagecount=$request->input('pagecount');
			
			$periodo=$request->input('periodo');
			$tipo_cedolino=$request->input('tipo_cedolino');
			
			$path = "pdftotext.exe";
			
			$filename="allegati/cedolini/$tipo_cedolino/$periodo/busta.pdf";
			
			$new_pdf = new FPDI();
			
			$new_pdf->AddPage();
			$new_pdf->setSourceFile($filename);
			$new_pdf->useTemplate($new_pdf->importPage($page));
			
			$temp_file ="allegati/cedolini/$tipo_cedolino/$periodo/temp.pdf";
			$new_pdf->Output($temp_file, "F");
			
			
			exec("pdftotext.exe ".$temp_file." pdf_read.txt");
			
			$testo = file_get_contents("pdf_read.txt");			
			$pattern='/(?:[A-Z][AEIOU][AEIOUX]|[AEIOU]X{2}|[B-DF-HJ-NP-TV-Z]{2}[A-Z]){2}(?:[\dLMNP-V]{2}(?:[A-EHLMPR-T](?:[04LQ][1-9MNP-V]|[15MR][\dLMNP-V]|[26NS][0-8LMNP-U])|[DHPS][37PT][0L]|[ACELMRT][37PT][01LM]|[AC-EHLMPR-T][26NS][9V])|(?:[02468LNQSU][048LQU]|[13579MPRTV][26NS])B[26NS][9V])(?:[A-MZ][1-9MNP-V][\dLMNP-V]{2}|[A-M][0L](?:[1-9MNP-V][\dLMNP-V]|[0L][1-9MNP-V]))[A-Z]/';
			
			preg_match_all($pattern, $testo, $matches);
			if (count($matches[0])>0) $cf_ref=$matches[0][0];

			if (strlen($cf_ref)!=0) {
				
				$new_pdf = new FPDI();
				$new_filename ="allegati/cedolini/$tipo_cedolino/$periodo/".$cf_ref.".pdf";

				if (file_exists($new_filename)==true) {
	
					$pageCount=$new_pdf->setSourceFile($new_filename);
					for($i=1;$i<=$pageCount;$i++) {
						$new_pdf->AddPage();
						$pageCount=$new_pdf->setSourceFile($new_filename);
						$tplId = $new_pdf->importPage($i);
						$new_pdf->useTemplate($tplId);
					}
					$new_pdf->AddPage();
					$new_pdf->setSourceFile($temp_file);
					$tplId = $new_pdf->importPage(1);
					$new_pdf->useTemplate($tplId);
					$new_pdf->Output($new_filename, "F");
					
				} else	{		
					rename($temp_file,$new_filename);
				}
				
				
				
				
				
				@unlink("allegati/cedolini/$tipo_cedolino/$periodo/temp.pdf");

				$status['status']="OK";
				$status['cf_ref']=$cf_ref;
			}	
			else {
				$status['status']="KO";
				$status['cf_ref']=$cf_ref;
				
			}

			return json_encode($status);
		
			
		}
		
		
		public function count_pdf(Request $request) {
			$tipo_cedolino=$request->input('tipo_cedolino');
			$periodo=$request->input('periodo');
			
			$pdf = new FPDI();
			$filename="allegati/cedolini/$tipo_cedolino/$periodo/busta.pdf";
			$rename="allegati/cedolini/$tipo_cedolino/$periodo/busta_old.pdf";
			$f1="allegati/cedolini/$tipo_cedolino/$periodo/busta1.pdf";
			
			$filepdf = fopen($filename,"r");
			if($filepdf) {
				$line_first = fgets($filepdf);
				fclose($filepdf);
			}
			// extract number such as 1.4,1.5 from first read line of pdf file
			preg_match_all('!\d+!', $line_first, $matches);
			// save that number in a variable
			$pdfversion = implode('.', $matches[0]);
			if($pdfversion > "1.4"){
				$result = exec('gswin32c -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dBATCH -sOutputFile="'.$f1.'" "'.$filename.'"');
				rename($filename,$rename);
				rename($f1,$filename);
				$pagecount=$pdf->setSourceFile($filename);
			} else 
				$pagecount=$pdf->setSourceFile($filename);
				
			$status['status']="OK";
			$status['pagecount']=$pagecount;
			return json_encode($status);		
		}
	}	
?>	