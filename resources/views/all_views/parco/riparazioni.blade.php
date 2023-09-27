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
            @if ($id_mezzo!="0")
				<h1 class="m-0">RIPARAZIONI DEL MEZZO SELEZIONATO</h1>
			@else
				<h1 class="m-0">ELENCO DELLE RIPARAZIONI</h1>
			@endif
			
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			  <li class="breadcrumb-item">Amministrazione</li>
			  <li class="breadcrumb-item">Parco Auto</li>
              <li class="breadcrumb-item active">Riparazioni</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">

		<form method='post' action="{{ route('riparazioni') }}" id='frm_riparazioni' name='frm_riparazioni' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>


        <div class="row">
          <div class="col-md-12">
		  
				<table id='tbl_riparazioni' class="display">
					<thead>
						<tr>
							<th>Mezzo</th>
							<th>Officina di riferimento</th>
							<th>Data consegna prevista</th>
							<th>Data consegna riparazione</th>
							<th>Importo preventivo</th>
							<th>Importo fattura</th>
							<th>Operazioni</th>
						</tr>
					</thead>
					<tbody>
						@foreach($riparazioni as $riparazione)
							<tr>
								<td>
								 @if ($riparazione->dele=="1") 
									<font color='red'><del> 
								 @endif
									<span id='id_descr' data-descr=''>
										{{ $riparazione->targa }}
									</span>	
								 @if ($riparazione->dele=="1") 
									 </del></font>
								 @endif	
								</td>	
								
								
								<td>
									{{ $riparazione->officina_riferimento }}
								</td>
								
								<td>
									
									{{ date('d-m-Y', strtotime($riparazione->data_consegna_prevista)) }}
								</td>
								
								<td>
									{{ date('d-m-Y', strtotime($riparazione->data_consegna_riparazione)) }}
								</td>
								<td>
									{{ number_format($riparazione->importo_preventivo,2) }}
								</td>
								<td>
									{{ number_format($riparazione->importo_fattura,2) }}
								</td>																
								
								<td>
									@if ($riparazione->dele=="3") 

										
										<a href="{{ route('riparazione',['id_mezzo'=>$riparazione->id_mezzo]) }}" >
											<button type="button" class="btn btn-info" alt='Edit'><i class="fas fa-edit"></i></button>
										</a>
										<a href="{{ route('scheda_mezzo',['id'=>$riparazione->id_mezzo]) }}" >
											<button type="button" class="btn btn-primary" alt='Riparazione' title='Scheda mezzo'><i class="fas fa-car"></i></button>
										</a>										
										<a href='#' onclick="dele_element({{$riparazione->id}})">
											<button type="submit" name='dele_ele' class="btn btn-danger"><i class="fas fa-trash"></i></button>	
										</a>
									@endif
									@if ($riparazione->dele=="1") 
										<a href='#'onclick="restore_element({{$riparazione->id}})" >
											<button type="submit" class="btn btn-warning" alt='Restore'><i class="fas fa-trash-restore"></i></button>
										</a>
									@endif
									
									
								</td>	
							</tr>
						@endforeach
						
					</tbody>
					<tfoot>
						<tr>
							<th>Mezzo</th>
							<th>Officina di riferimento</th>
							<th>Data consegna prevista</th>
							<th>Data consegna riparazione</th>
							<th>Importo preventivo</th>
							<th>Importo fattura</th>

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
					<a href="">
						<button type="button" class="btn btn-primary">
							<i class="fa fa-plus-circle"></i> Nuova Riparazione
						</button>
					</a>
	
					<div class="form-check form-switch mt-3 ml-3">
					  <input class="form-check-input" type="checkbox" id="view_dele" name="view_dele" onchange="$('#frm_riparazioni').submit()" {{ $check }}>
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
	
	

	<script src="{{ URL::asset('/') }}dist/js/riparazioni.js?ver=1.49"></script>

@endsection