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
            <h1 class="m-0">LAVORATORI</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			  <li class="breadcrumb-item">Archivi Servizi</li>
              <li class="breadcrumb-item active">Lavoratori</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
		<!-- form new contratti !-->	
		<!-- form new ditte !-->	
		@include('all_views.gestioneservizi.newlav')
		

		<form method='post' action="{{ route('lavoratori') }}" id='frm_lav' name='frm_lav' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
			

			
			<div class="row mb-3">
			
				<div class="col-md-6">
					<div class="form-floating mb-3 mb-md-0">

						<select class="form-control" name="ditta" id="ditta" aria-label="Comune"  onchange="$('#frm_lav').submit()">
							<option value=''>Select...</option>
							@foreach($ditte as $ditta_ref)
								@if ($ditta_ref->dele==0)
									<option value="{{ $ditta_ref->id }}"
									<?php
									if ($ditta==$ditta_ref->id)
									 echo " selected ";
									?>
									>{{ $ditta_ref->denominazione }}
									</option>
								@endif
							@endforeach	
						</select>
						<label for="ditta">Scegli Ditta di riferimento </label>
					</div>	
				</div>
			</div>	

	
        <div class="row">
          <div class="col-md-12">
		  
				<table id='tbl_list_lav' class="display">
					<thead>
						<tr>
							<th>Lavoratori associati alla ditta</th>
							<th>Operazioni</th>
						</tr>
						
					</thead>
					<tbody>
						@foreach($lavoratori as $lavoratore)
							<tr>
								<td>
								 @if ($lavoratore->dele=="1") 
									<font color='red'><del> 
								 @endif
								 <span id='ref_cognome{{$lavoratore->id}}' data-cognome='{{ $lavoratore->cognome }}'>
								 </span>
								 <span id='ref_nome{{$lavoratore->id}}' data-nome='{{ $lavoratore->nome }}'>
								 </span>								 
									{{ $lavoratore->nominativo }}
									
								 @if ($lavoratore->dele=="1") 
									 </del></font>
								 @endif	
								</td>	

								<td>
									@if ($lavoratore->dele=="0") 
										<a href='#' onclick="edit_elem({{$lavoratore->id}})">
											<button type="button" class="btn btn-info" alt='Edit'><i class="fas fa-edit"></i></button>
										</a>
										<a href='#' onclick="dele_element({{$lavoratore->id}})">
											<button type="submit" name='dele_ele' class="btn btn-danger"><i class="fas fa-trash"></i></button>	
										</a>
									@endif
									@if ($lavoratore->dele=="1") 
										<a href='#'onclick="restore_element({{$lavoratore->id}})" >
											<button type="submit" class="btn btn-warning" alt='Restore'><i class="fas fa-trash-restore"></i></button>
										</a>
									@endif
									
									
								</td>	
							</tr>
						@endforeach
						
					</tbody>
					<tfoot>
						<tr>
							<th>Nominativo</th>
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
					<button type="button" class="btn btn-primary" onclick="new_ditta()">
						<i class="fa fa-plus-circle"></i> Nuovo Lavoratore
					</button>
					<div class="form-check form-switch mt-3 ml-3">
					  <input class="form-check-input" type="checkbox" id="view_dele" name="view_dele" onchange="$('#frm_lav').submit()" {{ $check }}>
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


	
	<!-- inclusione standard
		per personalizzare le dipendenze DataTables in funzione delle opzioni da aggiungere: https://datatables.net/download/
	!-->
	
	<!-- dipendenze DataTables !-->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.css"/>
		 
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.js"></script>
	<!-- fine DataTables !-->
	
	

	<script src="{{ URL::asset('/') }}dist/js/lavoratori.js?ver=1.05"></script>

@endsection