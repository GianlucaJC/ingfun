@extends('all_views.viewmaster.index')

@section('title', 'IngFUN')
@section('extra_style') 
<!-- x button export -->

<link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet">
 <!-- per upload -->
  <link href="{{ URL::asset('/') }}dist/css/upload/jquery.dm-uploader.min.css" rel="stylesheet">
  <!-- per upload -->  
  <link href="{{ URL::asset('/') }}dist/css/upload/styles.css?ver=1.1" rel="stylesheet">  
<!-- -->
@endsection



<style>
	tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }
</style>
@section('content_main')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">AREA DOCUMENTI</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			  <li class="breadcrumb-item">Archivi</li>
              <li class="breadcrumb-item active">Doc</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
		<!-- form new voce tipo doc !-->	
		
		
		

		<form method='POST' action="{{ route('documenti') }}" id='frm_documenti' name='frm_documenti' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
			<input type='hidden' name='id_cand' name='id_cand' value='{{$id_cand}}'>
			<input type="hidden" value="{{url('/')}}" id="url" name="url">
			
			<input type='hidden' name='allegato' id='allegato'>
				@if (session('status'))
					<div class="alert alert-success">
						{{ session('status') }}
					</div>
				@endif
				@if (session('dele_doc'))
					<div class="alert alert-success">
						{{ session('dele_doc') }}
						<?php session()->forget('dele_doc'); ?>
						
					</div>
				@endif

			<div class="row mb-3">
				<div class="col-md-12">
					<div class="form-floating mb-3 mb-md-0">
						<select class="form-select" name="id_cand" id="id_cand" onchange="$('#frm_documenti').submit()" >
							<option value=''>Select...</option>
							@foreach($candidati as $cand)
								<option value='{{ $cand->id }}' 	
								<?php 
									if ($cand->id==$id_cand || $cand->id==session('id_cand')) 
									echo " selected ";
									
								?>	
								>{{ $cand->nominativo}}</option>	
							@endforeach			
						</select>
						<label for="id_cand">Lavoratore</label>					
					</div>	
				</div>
			</div>	
		
		<button type="button" onclick="new_doc()" class="btn btn-primary btn-lg btn-block">Definizione Nuovo Documento</button>
		
		<?php
			$disp="display:none";
			if (isset($_POST['tipodoc']) && strlen($_POST['tipodoc'])!=0) $disp="display:block"; 
		?>
		<div id='div_new_doc' style='{{$disp}}' class='mt-2'>

			<div class="row mb-3">
			
				<div class="col-md-5">
					<div class="form-floating mb-3 mb-md-0">
						<select class="form-select" name="tipodoc" id="tipodoc" onchange="$('#frm_documenti').submit()">
							<option value=''>Select...</option>
							@foreach($tipo_doc as $tipologia)
								<option value='{{ $tipologia->id }}' 	
								<?php 
									if ($tipologia->id==$tipodoc) 
									echo " selected ";
									
								?>	
								>{{ $tipologia->descrizione}}</option>	
							@endforeach			 
							
						
						</select>
						<label for="tipodoc">Tipo Documento</label>
					</div>
				</div>
				<div class="col-md-5">
					<div class="form-floating mb-3 mb-md-0">
						<select class="form-select" name="sottotipodoc" id="sottotipodoc" onchange="$('#frm_documenti').submit()">
							<option value=''>Select...</option>
							@foreach($voci_doc as $tipologia)
								<option value='{{ $tipologia->id }}' 	
								<?php 
									if ($tipologia->id==$sottotipodoc) 
									echo " selected ";
									
								?>	
								>{{ $tipologia->descrizione}}</option>	
							@endforeach			 
							
						
						</select>
						<label for="sottotipodoc">Sotto Documento</label>
					</div>
				</div>	

				<div class="col-md-2">
					<div class="form-floating">
						<input class="form-control" id="scadenza" name='scadenza' type="date" />
						<label for="scadenza">Scadenza</label>
					</div>
					
				</div>			
				
			</div>
			
			<?php
			
				$check="";
				if ($view_dele=="1") $check="checked";
			?>

			<div class="row">
				<div class="col-lg-12">
					<div class="mb-3" id='body_dialog' style='display:none'>				
					</div>
				</div>	
			</div>	

			
			<div class="row">
				<div class="col-lg-12">
					<button type="button" class="btn btn-primary" {{$allow_new}} onclick="set_sezione({{$id_cand}})">
						<i class="fa fa-plus-circle"></i> Allega Documento
					</button>

					<button type="submit" class="btn btn-success" name='save_doc' style='display:none' id='btn_save_doc'>
						<i class="fa fa-save"></i> Salva documento
					</button>
					

				</div>
			</div>
		</div>
		
		<!--
		<div class="row">
			<div class="col-lg-12">
				<div class="form-check form-switch mt-3 ml-3">
				  <input class="form-check-input" type="checkbox" id="view_dele" name="view_dele" onchange="$('#frm_documenti').submit()" {{ $check }}>
				  <label class="form-check-label" for="view_dele">Mostra anche Documenti eliminati</label>
				</div>		
			</div>
		</div>
		!-->
		
		
		<hr>

        <div class="row">
          <div class="col-md-12">
		  
				<table id='tbl_list_documenti' class="display">
					<thead>
						<tr>
							<th>ID</th>
							<th>Tipo Documento</th>
							<th>Sotto Documento</th>
							<th>Scadenza</th>
							<th>Creato il</th>
							<th>Operazioni</th>
						</tr>
					</thead>
					<tbody>
						<?php $flx=0;?>
						@foreach($elenco_doc as $document)
							@if (session('status') && $flx==0 && count($elenco_doc)>1)	
								<?php $flx=1; ?>
								<tr style='background-color:yellow'>
							@else
								<tr>
							@endif
								<td>{{$document->id}}</td>
								<td>{{$document->tipodocumento}}</td>
								<td>{{$document->sottodocumento}}</td>
								<td>{{$document->scadenza}}</td>
								<td>{{$document->created_at}}</td>
								<td>
									<a href="{{url('allegati')}}/doc/{{$document->id_cand}}/{{$document->nomefile}}" target='_blank'>
										<button type="button" class="btn btn-info" alt='Edit'><i class="fa fa-file"></i></button>
									</a>
									<a href='#' onclick="dele_element({{$document->id}})">
										<button type="submit" name='dele_ele' class="btn btn-danger"><i class="fas fa-trash"></i></button>	
									</a>

								
								</td>
							</tr>	
						@endforeach
					</tbody>
					<tfoot>
						<tr>
							<th>ID</th>
							<th>Tipo Documento</th>
							<th>Sotto Documento</th>
							<th>Scadenza</th>
							<th>Creato il</th>
							<th></th>
						</tr>
					</tfoot>					
				</table>
				<input type='hidden' id='dele_contr' name='dele_contr'>
				<input type='hidden' id='restore_contr' name='restore_contr'>
			
          </div>

        </div>
			

					
		</form>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
 @endsection
 
 @section('content_plugin')
	<!-- jQuery -->
	<script src="{{ URL::asset('/') }}plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="{{ URL::asset('/') }}plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="{{ URL::asset('/') }}dist/js/adminlte.min.js"></script>


	
	<!-- inclusione standard
		per personalizzare le dipendenze DataTables in funzione delle opzioni da aggiungere: https://datatables.net/download/
	!-->
	
	<!-- dipendenze DataTables !-->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.css"/>
		 
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.js"></script>
	<!-- fine DataTables !-->
	
	
	<!-- per upload -->
	<script src="{{ URL::asset('/') }}dist/js/upload_doc/jquery.dm-uploader.min.js"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload_doc/demo-ui.js?ver=1.24"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload_doc/demo-config.js?ver=2.347"></script>
	<!-- fine upload -->		

	<script src="{{ URL::asset('/') }}dist/js/documenti.js?ver=1.54"></script>

@endsection