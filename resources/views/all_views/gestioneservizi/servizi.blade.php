@extends('all_views.viewmaster.index')

@section('title', 'IngFUN')
@section('extra_style') 
<!-- x button export -->

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
            <h1 class="m-0">LISTINO CLIENTI</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			  <li class="breadcrumb-item">Amministrazione</li>
              <li class="breadcrumb-item active">Listino Clienti</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
		@if($esito_saveds=="1")
			<div class="alert alert-success" role="alert">
				Dati salvati con successo
			</div>		
		@endif	
		@if($esito_saveds=="2")
			<div class="alert alert-warning" role="alert">
				Servizio già associato alla ditta. Dati non salvati
			</div>		
		@endif	
		@if($esito_saveds=="3")
			<div class="alert alert-success" role="alert">
				Dati aggiornati con successo
			</div>		
		@endif
		
		@include('all_views.gestioneservizi.newservizi')

		
		<?php

		if ($ditta_ref!='0' && strlen($ditta_ref)>0 && isset($azienda)) {
			echo "<div class='alert alert-secondary' role='alert'>";
				echo "Azienda di proprietà: <b>".$azienda->azienda_prop."</b>";
			echo "</div>";
		}
		?>
		
		<form method='post' action="{{ route('servizi') }}" id='frm_newservice' name='frm_newservice' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
			@include('all_views.gestioneservizi.newservizi_ditte')
			<input type='hidden' name='save_ds' id='save_ds'>
		</form>
		
		
		<form method='post' action="{{ route('servizi') }}" id='frm_tb_list' name='frm_tb_list' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
		<input type='hidden' id='ditta_from_frm1' name='ditta_from_frm1'>
		<div id='div_table_servizi'>
			<div class="d-grid gap-2 d-md-flex justify-content-md-begin">
				<button type="button" class="btn btn-primary" onclick="check_associa()">
					<i class="fa fa-plus-circle"></i> Associa Servizi alla ditta
				</button>
				<button type="button" class="btn btn-primary" onclick="$('#descr_contr').val('');$('#div_definition').show(150)">
					<i class="fa fa-plus-circle"></i> Nuovo Servizio
				</button>
			</div>			
			
			<hr>
			<div class="row mb-2 mt-4">
			  <div class="col-sm-6">
				<h3 class="m-0">Servizi associati alla ditta</h3>
			  </div><!-- /.col -->
			</div>  
			
			<div class="row">
			  <div class="col-md-12">
			  
					<table id='tbl_list_servizi' class="display">
						<thead>
							<tr>
								<th>ID</th>
								<th>Descrizione</th>
								<th>Importo Ditta</th>
								<th>Aliquota</th>
								<th>Importo Lavoratori</th>
								<th>Operazioni</th>
							</tr>
						</thead>
						<tbody>
							@foreach($servizi_ditte as $tipo)
								<tr>
									<td>{{ $tipo->id }}</td>	
									<td>
									 @if ($tipo->dele=="1") 
										<font color='red'><del> 
									 @endif
										<span id='id_descr{{$tipo->id}}' data-descr='{{ $tipo->descrizione }}'>
											{{ $tipo->descrizione }}
										</span>	
									 @if ($tipo->dele=="1") 
										 </del></font>
									 @endif	
									</td>	
									<td>
										{{ $tipo->importo_ditta }}€
									</td>
									<td>
										<span id='id_importo{{$tipo->id}}' data-importo='{{ $tipo->aliquota }}'>
										
										@if (isset($arr_aliquota[$tipo->aliquota]))
											{{$arr_aliquota[$tipo->aliquota]}}%
										@endif									
										</span>	
									</td>
									<td>
										<span id='id_importo{{$tipo->id}}' data-importo='{{ $tipo->importo_lavoratore }}'>
											{{ $tipo->importo_lavoratore }}€
										</span>	
									</td>
									<td>
									<!--informazioni sulla riga per js !-->
									<span id='info_s{{$tipo->id}}' 
									data-id_ditta='{{$tipo->id_ditta}}'
									data-id_servizio='{{$tipo->id_servizio}}'
									data-importo_ditta='{{ $tipo->importo_ditta }}'
									data-aliquota='{{$tipo->aliquota}}'
									data-importo_lavoratore='{{$tipo->importo_lavoratore}}'>
									</span>
									
										@if ($tipo->dele=="0") 
											<a href='#' onclick="edit_elem({{$tipo->id}})">
												<button type="button" class="btn btn-info" alt='Edit'><i class="fas fa-edit"></i></button>
											</a>
											<a href='#' onclick="dele_element({{$tipo->id}})">
												<button type="submit" name='dele_ele' class="btn btn-danger"><i class="fas fa-trash"></i></button>	
											</a>
										@endif
										@if ($tipo->dele=="1") 
											<a href='#'onclick="restore_element({{$tipo->id}})" >
												<button type="submit" class="btn btn-warning" alt='Restore'><i class="fas fa-trash-restore"></i></button>
											</a>
										@endif
										
										
									</td>	
								</tr>
							@endforeach
							
						</tbody>
						<tfoot>
							<tr>
								<th>ID</th>
								<th>Descrizione</th>
								<th>Importo Ditta</th>
								<th>Aliquota</th>
								<th>Importo Lavoratori</th>
								<th></th>
							</tr>
						</tfoot>					
					</table>
					<input type='hidden' id='dele_ds' name='dele_ds'>
					<input type='hidden' id='restore_contr' name='restore_contr'>
				
			  </div>
			  



			</div>
			<?php
			
				$check="";
				if ($view_dele=="1") $check="checked";
			?>
				<div class="row">
					<div class="col-lg-12">
						<div class="form-check form-switch mt-3 ml-3">
						  <input class="form-check-input" type="checkbox" id="view_dele" name="view_dele" onchange="$('#ditta_from_frm1').val($('#ditta_ref').val());$('#frm_tb_list').submit()" {{ $check }}>
						  <label class="form-check-label" for="view_dele">Mostra anche elementi eliminati</label>
						</div>
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
	
	

	<script src="{{ URL::asset('/') }}dist/js/servizi.js?ver=1.018"></script>

@endsection
