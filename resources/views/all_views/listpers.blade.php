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
            <h1 class="m-0">GESTIONE PERSONALE</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Gestione Personale</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
		<form method='post' action="{{ route('listpers') }}" id='frm_listc' name='frm_listc' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>	  
			<div class="row">
			  <div class="col-lg-12">
					<table id='tbl_list_pers' class="display">
						<thead>
							<tr>
								<th>View</th>
								<th>Dipendente</th>
								<th>Stato</th>
								<th>Inizio</th>
								<th>Fine</th>
								<th>Contratto</th>
								<th>Società</th>
								<th>Area Impiego</th>
								<th>Centro Costo</th>
								<th>Ruolo</th>
								<!--
								<th>Appartenenza</th>
								<th>Contratto</th>
								<th>Livello</th>
								<th>Cat.Legale</th>
									
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>

								!-->
								
							</tr>
						</thead>
						<tbody>
							@foreach($candidati as $candidato)
								<tr>
									<td>
										@if ($candidato->dele=="0") 
											<a href="{{ route('newcand',['id'=>$candidato->id,'from'=>1]) }}" >
												<button type="button" class="btn btn-info" alt='Edit'><i class="fas fa-edit"></i></button>
											</a>
										@endif
									</td>									
									
									<td>
										@if ($candidato->dele=="1") 
											<font color='red'><del> 
										@endif
										
										{{ $candidato->nominativo }}
										
										@if ($candidato->dele=="1") 
											</del></font>
										@endif											
									</td>
									<td>
									@if ($candidato->status_candidatura=="1") GESTIONE @endif
									@if ($candidato->status_candidatura=="2") RESPINTA @endif
									@if ($candidato->status_candidatura=="3") ASSUNZIONE 
									@endif

									</td>
									<td>{{ $candidato->data_inizio }}</td>
									<td>{{ $candidato->data_fine }}</td>
									<td></td>
									<td>
									</td>

									<td></td>	
									<td>
									</td>
									
									
									<td>
									</td>
									
								</tr>
							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<th></th>
								<th>Nominativo</th>
								<th>Mansione</th>
								<th>Zona di lavoro</th>
								<th>Ultimo Aggiornamento</th>
								<th>Sorgente</th>
								<th>Status</th>						
								<th></th>
								<th></th>
								<th></th>
							</tr>
						</tfoot>					
					</table>
			  </div>
			  
			  

			</div>
			<!-- /.row -->

			<?php
				$check="";
				if ($view_dele=="1") $check="checked";
			?>
			<div class="row">
				<div class="col-lg-12">
					<div class="form-check form-switch mt-3 ml-3">
					  <input class="form-check-input" type="checkbox" id="view_dele" name="view_dele" onchange="$('#frm_listc').submit()" {{ $check }}>
					  <label class="form-check-label" for="view_dele">Mostra anche Anagrafiche eliminate</label>
					</div>
				</div>
			</div>	
			<input type='hidden' id='dele_cand' name='dele_cand'>
			<input type='hidden' id='restore_cand' name='restore_cand'>
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

	<!-- inclusione standard
		per personalizzare le dipendenze DataTables in funzione delle opzioni da aggiungere: https://datatables.net/download/
	!-->
	
	<!-- dipendenze DataTables !-->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.css"/>
		 
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.js"></script>
	<!-- fine DataTables !-->


	<script src="{{ URL::asset('/') }}dist/js/listpers.js?ver=2.00"></script>

@endsection