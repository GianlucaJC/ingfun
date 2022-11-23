<?php

use setasign\Fpdi\Fpdi;
use App\Models\User;
	$id = Auth::user()->id;
	$user = User::find($id);

?>

@extends('all_views.viewmaster.index')

@section('title', 'IngFUN')

@section('extra_style')  
 <!-- per upload -->
  <link href="{{ URL::asset('/') }}dist/css/upload/jquery.dm-uploader.min.css" rel="stylesheet">
  <!-- per upload -->  
  <link href="{{ URL::asset('/') }}dist/css/upload/styles.css?ver=1.1" rel="stylesheet">  

<link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet">

@endsection

@section('content_main')
  <input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
  <input type="hidden" value="{{url('/')}}" id="url" name="url">
  <!-- Content Wrapper. Contains page content -->


  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
	
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">UPLOAD Cedolini</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Upload Cedolini</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
		<form method='post' action="{{ route('cedolini_up') }}" id='frm_cedoliniup' name='frm_cedoliniup' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>	  
			<input type='hidden' name='dele_pdf' id='dele_pdf'>
		@if ($user->hasRole('admin'))			
			<?php 
				$periodo=$mese_busta.$anno_busta;
				$dir = "allegati/cedolini/$periodo/";
				$distr_run=false;
				if (file_exists("allegati/cedolini/$periodo/distr.ddd")==true)
					$distr_run=true;
				$numfile = count(glob($dir . "*.pdf"));				
				
			
			?>	
			
			<div class="row mb-3">
				<div class="col-md-4">
				  <div class="form-floating mb-3 mb-md-0">
					
					<select class="form-select" id="mese_busta" aria-label="mese busta" name='mese_busta' onchange='set_step()' >
						<option value=''>Select...</option>
						<option value='gen'
						<?php if ($mese_busta=="gen") echo " selected ";?>
						>Gennaio</option>
						<option value='feb'
						<?php if ($mese_busta=="feb") echo " selected ";?>
						>Febbraio</option>
						<option value='mar'
						<?php if ($mese_busta=="mar") echo " selected ";?>
						>Marzo</option>
						<option value='apr'
						<?php if ($mese_busta=="apr") echo " selected ";?>
						>Aprile</option>
						<option value='mag'
						<?php if ($mese_busta=="mag") echo " selected ";?>
						>Maggio</option>
						<option value='giu'
						<?php if ($mese_busta=="giu") echo " selected ";?>
						>Giugno</option>
						<option value='lug'
						<?php if ($mese_busta=="lug") echo " selected ";?>
						>Luglio</option>
						<option value='ago'
						<?php if ($mese_busta=="ago") echo " selected ";?>
						>Agosto</option>
						<option value='set'
						<?php if ($mese_busta=="set") echo " selected ";?>
						>Settembre</option>
						<option value='ott'
						<?php if ($mese_busta=="ott") echo " selected ";?>
						>Ottobre</option>
						<option value='nov'
						<?php if ($mese_busta=="nov") echo " selected ";?>
						>Novembre</option>
						<option value='dic'
						<?php if ($mese_busta=="dic") echo " selected ";?>
						>Dicembre</option>


					</select>
					<label for="mese_busta">Mese di riferimento</label>
				  </div>
				 </div> 
				<div class="col-md-4">
					<div class="form-floating">
						<select class="form-select" id="anno_busta" aria-label="anno busta" name='anno_busta' onchange='set_step()'>
						<option value=''>Select...</option>
						<?php
							$inizio=date("Y");$fine=$inizio-3;
							for ($sca=$inizio;$sca>=$fine;$sca--) {
								echo "<option value='$sca'";
								if ($anno_busta==$sca) echo " selected ";
								echo ">$sca</option>";
							}
						?>	
						</select>
						<label for="anno_busta">Anno di riferimento</label>
						

					</div>
				</div>
				
				<?php 
					$dis_step="disabled";
					if ($dele_pdf=="1") $dis_step="";
				?>	

				<div class="col-md-4">
					<button type="submit" id='btn_step' name='btn_step' class="btn btn-primary btn-lg btn-block" {{$dis_step}}>Step Successivo</button>
				</div>				
		

			</div>	
		
			<?php
				$vis="display:none";$dis="disabled";
				if (isset($_POST['btn_step'])) $vis="display:block";
				$vis_allegati=$vis;
				
				if (file_exists("allegati/cedolini/$periodo/busta.pdf")==true)  {
					$dis="";$vis_allegati="display:none";
					echo "<div class='alert alert-warning' role='alert' id='div_alert_exist'>";
					   echo "Esiste già un File accorpato inviato in precedenza per questo periodo. Se vuoi inviarne uno diverso e cancellare l'attuale <a class='alert-link' href='#' onclick='canc_pdf()'> clicca quì</a>  <hr>";
					   echo "<a href='allegati/cedolini/$periodo/busta.pdf' class='alert-link' target='_blank'>Clicca quì per visionare il file</a><hr>";
					   if ($distr_run==true) echo "<i>I cedolini sono stati distributi per competenza e per periodo nella sezione per i dipendenti</i>";

					echo "</div>";

					
				}

				$vis_up="display:none";$vis_procedi="display:block";
				
				if ($numfile>1 && $distr_run==false) {
					$vis_up="display:block";$vis_procedi="display:none";
				}


			?>		
			<input type='hidden' name='pagecount' id='pagecount'>
			<div class="row mb-3" id='div_allegati' style='{{$vis_allegati}}'>
				<div class="col-md-12">
					<!-- l'upload viene fatto dal plugin  dist/js/upload/demo-config.js !-->
					<?php include("class_allegati.php"); ?>
					
					
					
					
				</div>
			</div>
			<div id='div_azioni' style='{{$vis}}'>
				
				<div class="text-center mb-3" id='div_wait' style='display:none'>
				  <div class="spinner-border" role="status">
					<span class="sr-only">Loading...</span>
				  </div>
				  <div class="ml-3">
					Attendere. Il processo potrebbe richiedere anche qualche minuto...
				  </div>
				</div>	
				<div id='div_analisi' class='mt-2 mb-2'></div>
			
				
				<button type="button" class="btn btn-primary  btn-lg btn-block" {{$dis}} id='btn_analisi' onclick='analisi_pdf()'>Analisi PDF (Estrapolazione dei CF)</button>


				<button type="button" class="btn btn-success  btn-lg btn-block" {{$dis}} style='{{$vis_procedi}}' id='btn_split' onclick='split_pdf(1,0)'>Procedi con la suddivisione</button>

						
				<button type="submit" name='distr' id='distr' class="btn btn-success  btn-lg btn-block" style='{{$vis_up}}' onclick="if (!confirm('Sicuri di aggiornare la sezione per i dipendenti?')) event.preventDefault()" value='distr' >Aggiorna Sezione Cedolini per dipendenti</button>
						
				



				<div class="progress mt-2" id='div_progr' style='display:none'>
				</div>		
			</div>
		
		@endif	
		<i class="fa fa-file-pdf-o" style="font-size:36px"></i>

		</form>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 @endsection
 
@section('content_plugin')
	<!-- jQuery -->
	<script src="plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="dist/js/adminlte.min.js"></script>
	
	<!-- per upload -->
	<script src="{{ URL::asset('/') }}dist/js/upload/jquery.dm-uploader.min.js"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload/demo-ui.js?ver=1.24"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload/demo-config.js?ver=2.356"></script>	

	<!-- inclusione standard
		per personalizzare le dipendenze DataTables in funzione delle opzioni da aggiungere: https://datatables.net/download/
	!-->
	<!-- dipendenze DataTables !-->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.css"/>
		 
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.js"></script>
	<!-- fine DataTables !-->

	
	<script src="{{ URL::asset('/') }}dist/js/cedolini_up.js?ver=1.549"></script>
	

	<!-- fine upload -->		

	<!-- esempio di utilizzo da js...quì implementato in cedolini_up.js	
	$("#drop-area").dmUploader({
	  url: '/path/to/backend/upload.asp',
	  //... More settings here...
	  
	  onInit: function(){
		console.log('Callback: Plugin initialized');
	  }
	  
	  // ... More callbacks
	});	
	<!-- -->



	

	
@endsection
