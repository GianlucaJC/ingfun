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
            <h1 class="m-0">STORICO SINISTRI</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>

            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">

		<form method='post' action="{{ route('elenco_sinistri') }}" id='frm_sinistri' name='frm_sinistri' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>

		<input type="hidden" value="{{url('/')}}" id="url" name="url">
        <div class="row">
          <div class="col-md-12">
		  
				<table id='tbl_list_sinistri' class="display">
					<thead>
						<tr>
							<th style='width:20px'>ID</th>
							<th>Data e ora</th>
							<th>Responsabile</th>
							<th>Mezzo coinvolto</th>
							<th>Operazioni</th>
						</tr>
					</thead>
					<tbody>
						
						@foreach($sinistri as $sinistro)
							
							<tr>
								<td style='text-align:center'>
									{{$sinistro->id}}
								</td>
								<td>
								 @if ($sinistro->dele=="1") 
									<font color='red'><del> 
								 @endif
									{{ $sinistro->dataora }}
								 @if ($sinistro->dele=="1") 
									 </del></font>
								 @endif	
								</td>

								<td>
								<?php
									if (isset($all_lav[$sinistro->responsabile_mezzo]))
										echo $all_lav[$sinistro->responsabile_mezzo]['nominativo'];
								?>
								</td>

								<td style='text-align:center'>
									{{$sinistro->targa}}
								</td>
								
								<td>
								
								<?php
								$fx="";
								if (isset($support_ref[$sinistro->id])) {
									for ($sca=0;$sca<=count($support_ref[$sinistro->id])-1;$sca++) {
										if ($sca>0) $fx.=";";
										$fx.=$support_ref[$sinistro->id][$sca];
									}
								}
								?>

								<span id='id_foto{{$sinistro->id}}' data-foto='{{$fx}}'></span>
								<span id='id_cid{{$sinistro->id}}' data-foto='{{$sinistro->file_cid}}'></span>
								
								@if (strlen($fx)>0)
								<a href='javascript:void(0)'  onclick='zoom({{$sinistro->id}},1)'>
									<button type="button" class="btn btn-primary" title='foto allegate'><i class="fas fa-camera"></i></button>
								</a>
								@endif								
								@if (strlen($sinistro->file_cid)>0)
								<a href='javascript:void(0)'  onclick='zoom({{$sinistro->id}},2)'>
									<button type="button" class="btn btn-info" title='file cid'><i class="fas fa-file"></i></button>
								</a>
								@endif								
								
								<a href='#' onclick="dele_element({{$sinistro->id}})">
									<button type="submit" name='dele_ele' class="btn btn-danger"><i class="fas fa-trash"></i></button>	
								</a>
								@if ($sinistro->dele=="1") 
									<a href='#'onclick="restore_element({{$sinistro->id}})" >									<button type="submit" class="btn btn-warning" alt='Restore'><i class="fas fa-trash-restore"></i></button>
									</a>
								@endif								

									
									
								</td>	
							</tr>
						@endforeach
						
					</tbody>
					<tfoot>
						<tr>
							<th style='width:20px'>ID</th>
							<th>Data e ora</th>
							<th>Responsabile</th>
							<th>Mezzo coinvolto</th>
							<th>Operazioni</th>
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

				<div class="form-check form-switch mt-3 ml-3">
				  <input class="form-check-input" type="checkbox" id="view_dele" name="view_dele" onchange="$('#frm_sinistri').submit()" {{ $check }}>
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
  

	<!-- Modal -->
	<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modal_img">
	  <div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="title_modal">Documentazione allegata</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body" id='body_modal'>
			...
		  </div>
		  <div class="modal-footer">
			<div id='altri_btn'></div>
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		  </div>
		</div>
	  </div>
	</div> 
  
  
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
	
	

	<script src="{{ URL::asset('/') }}dist/js/elenco_sinistri.js?ver=1.180"></script>

@endsection
