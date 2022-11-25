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
            <h1 class="m-0">AREA CEDOLINI</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			  <li class="breadcrumb-item active">Cedolini</li>
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
		
		
		

		<form method='POST' action="{{ route('cedolini_view') }}" id='frm_documenti' name='frm_documenti' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
			<input type='hidden' name='id_cand' name='id_cand' value=''>
			<input type="hidden" value="{{url('/')}}" id="url" name="url">
			<input type='hidden' name='allegato' id='allegato'>
				@if (session('status')) 
					@if (session('esito')=="OK")
						<div class="alert alert-success">
					@else
						<div class="alert alert-danger">
					@endif	
						{{ session('status') }}
					</div>
				@endif

			
			<div class="row mb-3">
				<div class="col-md-6">
					<div class="form-floating mb-3 mb-md-0">
						<select class="form-select" name="periodo" id="periodo" onchange="$('#frm_documenti').submit()" >
							<option value=''>Select...</option>
								
								@foreach ($periodi as $k=>$v)
									<option value='{{$k}}'
									@if ($periodo==$k) 
										selected	
									@endif
									>{{strtoupper($v)}}</option>	
								@endforeach
										
						</select>
						<label for="periodo">Periodi disponibili</label>					
					</div>	
				</div>

				<div class="col-md-6">
					<div class="form-floating mb-3 mb-md-0">
						<select class="form-select" name="id_cand" id="id_cand" onchange="$('#frm_documenti').submit()" >
							<option value=''>Tutti</option>
							@foreach($candidati as $cand)
								<option value='{{ $cand->id }}-{{ $cand->codfisc }}'
								<?php  
									$ref=$cand->id."-".$cand->codfisc;
								?>								
									@if ($ref==$id_cand) selected @endif
								>{{ $cand->nominativo}}</option>	
							@endforeach			
						</select>
						<label for="id_cand">Lavoratore</label>					
					</div>	
				</div>
			</div>	
		
		<hr>

        <div class="row">
          <div class="col-md-12">
		  
				<table id='tbl_list_documenti' class="display">
					<thead>
						<tr>
							<th>CF</th>
							<th>Nominativo</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($tb_risp as $k=>$v)
							@php ($k_c=md5($k).".pdf")
							@if (isset($cand_cf[$k]))
								<tr>
									<td>
										<a href='{{$dir_ref}}/{{$k_c}}' target='_blank'>
											{{$k}}
										</a>
									</td>
									<td>
										@if (count($cand_cf[$k])==1)
											{{$cand_cf[$k][0]}}
										@else
											<?php
												echo "Multianagrafica associata al CF<hr>";
												$anagr="";
												for ($sca=0;$sca<count($cand_cf[$k]);$sca++) {
													if (strlen($anagr)!=0) $anagr.=", ";
													$anagr.=$cand_cf[$k][$sca];
												}
												echo $anagr;
											?>
										@endif
									</td>
								</tr>
							@endif	
						@endforeach	
					</tbody>
					<tfoot>
						<tr>
							<th>CF</th>
							<th>Nominativo</th>
						</tr>
					</tfoot>					
				</table>
          </div>

        </div>
					
		</form>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="modal_win" tabindex="-1" role="dialog" aria-labelledby="info" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title_modal">Modal title</h5>


        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id='body_modal'>
        ...
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-primary" onclick="send_email()">Invia a selezionati</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
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

	<script src="{{ URL::asset('/') }}dist/js/documenti.js?ver=1.95"></script>

@endsection