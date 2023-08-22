@extends('all_views.viewmaster.index')

@section('title', 'IngFUN')
@section('extra_style') 
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ URL::asset('/') }}plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="{{ URL::asset('/') }}plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<!-- x button export -->

<!-- per upload -->
<link href="{{ URL::asset('/') }}dist/css/upload/jquery.dm-uploader.min.css" rel="stylesheet">
 <link href="{{ URL::asset('/') }}dist/css/upload/styles.css?ver=1.1" rel="stylesheet">  
<!-- per upload --> 

<link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet">
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
            <h1 class="m-0">ANAGRAFICHE</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			  <li class="breadcrumb-item">Amministrazione</li>
			  <li class="breadcrumb-item">Aziende di Proprietà</li>
              <li class="breadcrumb-item active">Anagrafiche</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
		<!-- form new ditte !-->	
		@include('all_views.gestioneservizi.newsezionali')

		<form method='post' action="{{ route('sezionali') }}" id='frm_sezionali' name='frm_sezionali' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
			

	
        <div class="row">
          <div class="col-md-12">
		  
				<table id='tbl_list_aziende' class="display">
					<thead>
						<tr>
							
							<th>Denominazione</th>
							<th>Mail Scadenze contratti</th>
							<th>Mail Fatture</th>
							<th>Operazioni</th>
						</tr>
					</thead>
					<tbody>
						@foreach($sezionali as $sezionale)
							<tr>
								
								<td>
								 @if ($sezionale->dele=="1") 
									<font color='red'><del> 
								 @endif
									{{ $sezionale->descrizione }}
									
								 @if ($sezionale->dele=="1") 
									 </del></font>
								 @endif	
									<span style='display:none' id='id_descr{{$sezionale->id}}' data-descr='{{ $sezionale->descrizione }}' data-mail_scadenze='{{ $sezionale->mail_scadenze }}' data-mail_fatture='{{ $sezionale->mail_fatture }}'
									data-mail_azienda='{{ $sezionale->mail_azienda }}'
									data-mail_pec='{{ $sezionale->mail_pec }}'
									data-telefono='{{ $sezionale->telefono }}'
									>
									
										{{ $sezionale->descrizione }}
									</span>									 
								</td>
								<td>
									{{ $sezionale->mail_scadenze }}
								</td>
								<td>
									{{ $sezionale->mail_fatture }}
								</td>
								<td>

									@if ($sezionale->dele=="0") 


										<a href='#' onclick="edit_elem({{$sezionale->id}})">
											<button type="button" class="btn btn-info" alt='Edit'><i class="fas fa-edit"></i></button>
										</a>
										<a href='#' onclick="dele_element({{$sezionale->id}})">
											<button type="submit" name='dele_ele' class="btn btn-danger"><i class="fas fa-trash"></i></button>	
										</a>
									@endif
									@if ($sezionale->dele=="1") 
										<a href='#'onclick="restore_element({{$sezionale->id}})" >
											<button type="submit" class="btn btn-warning" alt='Restore'><i class="fas fa-trash-restore"></i></button>
										</a>
									@endif
									
									
								</td>	
							</tr>
						@endforeach
						
					</tbody>
					<tfoot>
						<tr>
							
							<th>Denominazione</th>
							<th>Mail Scadenze contratti</th>
							<th>Mail Fatture</th>
							<th></th>
						</tr>
					</tfoot>					
				</table>
				<input type='hidden' id='dele_contr' name='dele_contr'>
				<input type='hidden' id='restore_contr' name='restore_contr'>
			
          </div>

        </div>
		<?php
		
			$check="";
			if ($view_dele=="1") $check="checked";
		?>
			<div class="row">
			    <div class="col-lg-12">
					<button type="button" class="btn btn-primary" onclick="new_azienda()">
						<i class="fa fa-plus-circle"></i> Nuova Azienda
					</button>
					<div class="form-check form-switch mt-3 ml-3">
					  <input class="form-check-input" type="checkbox" id="view_dele" name="view_dele" onchange="$('#frm_sezionali').submit()" {{ $check }}>
					  <label class="form-check-label" for="view_dele">Mostra anche elementi eliminati</label>
					</div>
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
	<script src="plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="dist/js/adminlte.min.js"></script>

	<!--select2 !-->
	<script src="{{ URL::asset('/') }}plugins/select2/js/select2.full.min.js"></script>

	
	<!-- per upload -->
	<script src="{{ URL::asset('/') }}dist/js/upload/jquery.dm-uploader.min.js"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload/demo-ui.js?ver=1.25"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload/demo-config.js?ver=2.375"></script>
	<!-- fine upload -->
	
	<!-- inclusione standard
		per personalizzare le dipendenze DataTables in funzione delle opzioni da aggiungere: https://datatables.net/download/
	!-->
	
	<!-- dipendenze DataTables !-->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.css"/>
		 
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.js"></script>
	<!-- fine DataTables !-->
	
	

	<script src="{{ URL::asset('/') }}dist/js/sezionali.js?ver=1.010"></script>

@endsection