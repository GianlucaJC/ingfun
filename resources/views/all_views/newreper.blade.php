@extends('all_views.viewmaster.index')

@section('title', 'IngFUN')




@section('extra_style')  
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ URL::asset('/') }}plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="{{ URL::asset('/') }}plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
 <!-- per upload -->
  <link href="{{ URL::asset('/') }}dist/css/upload/jquery.dm-uploader.min.css" rel="stylesheet">
  <!-- per upload -->  
  <link href="{{ URL::asset('/') }}dist/css/upload/styles.css?ver=1.1" rel="stylesheet">  
  <link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet">

@endsection


@section('content_main')
<form method='post' action="{{ route('save_reper') }}" id='save_reper' name='save_reper' autocomplete="off" class="needs-validation" novalidate>

<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
  <!-- Content Wrapper. Contains page content -->
   <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12">  
			<h1 class="m-0">
				<center>
					<font color='red'>
						DEFINIZIONE REPERIBILITA'
					</font>
				</center>
				
			</h1>
			</div>	
		</div>	

      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">

		<div class="row">
			<?php
			
				if (!empty($num_send) && $num_send>0) {
					$txt="Notifiche push inviate!";
					if ($num_send==1) $txt="Notifica push inviata!";
					echo "<div class='alert alert-success' role='alert'>";
					  echo "<b>$num_send</b> $txt";
					echo "</div>";
				}
				


			?>
		</div>


		<div class="row mb-3">
			@if($id_reper==0)
			<div class="col-md-6">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select select2" id="lavoratori" aria-label="Lavoratori" name='lavoratori[]' multiple="multiple" required  >
						@php ($old_t="?")
						@foreach ($lavoratori as $lavoratore)
						
						<?php
							

						$tipo_contr=$lavoratore->tipo_contr;
						$tipo_contratto=$lavoratore->tipo_contratto;
						$ref_tipo=$tipo_contr.$tipo_contratto;
						$descr_t="";

							
						if ($tipo_contr==2 && $tipo_contratto==1)
							$descr_t="Indeterminati - Full Time";
						elseif ($tipo_contr==2 && $tipo_contratto==2)
							$descr_t="Indeterminati - Part Time";
						elseif ($tipo_contr==2 && ($tipo_contratto>2))
							$descr_t="Indeterminati - Altro";
						if ($tipo_contr==1 && $tipo_contratto==1)
							$descr_t="Determinati - Full Time";
						elseif ($tipo_contr==1 && $tipo_contratto==2)
							$descr_t="Determinati - Part Time";
						elseif ($tipo_contr==1 && ($tipo_contratto>2))
							$descr_t="Determinati - Altro";
						

								
						if ($old_t!=$ref_tipo) {
							if ($old_t!="?") echo "</optgroup>";
							echo "<optgroup label='$descr_t'>"; 
						}
						$old_t=$ref_tipo;
							
						?>
						
							<option value='{{$lavoratore->id}}'> 
							{{$lavoratore->nominativo}}
							</option>
						@endforeach
						</optgroup>
					</select>
					<b>Lavoratore/i</b>
				</div>
			</div>
			@else
				<div class="col-md-6">
				  <div class="form-floating mb-3 mb-md-0">
					
					<select class="form-select" id="lavoratore" aria-label="Lavoratore" name='lavoratore' disabled>
						<option value=''>Select...</option>
							@foreach ($lavoratori as $lavoratore)
								<option value='{{$lavoratore->id}}'
									@if ($lavoratore->id==$edit_rep[0]->id_user)
										selected
									@endif
								>		
									{{$lavoratore->nominativo}}
								</option>
							@endforeach
						
					</select>
					<label for="lavoratore">Lavoratore</label>
					
					</div>
				</div>
			

			@endif

			<div class="col-md-3">
				<div class="form-floating">
					<input class="form-control" id="data_reper" name='data_reper' type="date" required value="{{$edit_rep[0]->data ?? ''}}"/>
					<label for="data_app">Data* </label>
				</div>
			</div>		

				
			<div class="col-md-3">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="fascia" id="fascia"  required>
					<option value=''>Select...</option>
					<?php
						echo "<option value='1' ";
						if (!empty($edit_rep) && $edit_rep[0]->fascia=="1") echo "selected";
						echo ">Mattutina</option>";
						echo "<option value='2' ";
						if (!empty($edit_rep) && $edit_rep[0]->fascia=="2") echo "selected";
						echo ">Pomeridiana</option>";
						echo "<option value='3' ";
						if (!empty($edit_rep) && $edit_rep[0]->fascia=="3") echo "selected";
						echo ">Prima Fascia Notturna</option>";						
						echo "<option value='4' ";
						if (!empty($edit_rep) && $edit_rep[0]->fascia=="4") echo "selected";
						echo ">Seconda Fascia Notturna</option>";						
					?>						
					</select>
					<label for="ditta">Fascia*</label>
				</div>
			</div>	
		



		</div>
		

				<hr>

        <div class="row">

			<button type="submit" name='btn_save_rep' id='btn_save_rep' class="btn btn-success btn-lg btn-block">SALVA</button>  
			
			<a href="{{ route('listrep') }}">
				<button type="button"  id='back_appalti' class="btn btn-info btn-lg btn-block mt-3">TORNA AD ELENCO REPERIBILITA'</button> 
			</a>


			<input type="hidden" value="{{url('/')}}" id="url" name="url">
			<input type="hidden" name="id_reper" id="id_reper" value="{{$id_reper}}">

		</div>
		
			
		
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="modal_story" tabindex="-1" role="dialog" aria-labelledby="info" aria-hidden="true">
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
		<div id='altri_btn'></div>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div> 


  <!-- /.content-wrapper -->
</form>  
  
  

  
  

  
 @endsection
 
@section('content_plugin')
	<!-- jQuery -->
	<script src="{{ URL::asset('/') }}plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="{{ URL::asset('/') }}plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="{{ URL::asset('/') }}dist/js/adminlte.min.js"></script>
	<script src="{{ URL::asset('/') }}dist/js/newreper.js?ver=1.11"></script>
	<!--select2 !-->
	<script src="{{ URL::asset('/') }}plugins/select2/js/select2.full.min.js"></script>
	
	<!-- per upload -->
	<script src="{{ URL::asset('/') }}dist/js/upload/jquery.dm-uploader.min.js"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload/demo-ui.js?ver=1.24"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload/demo-config.js?ver=2.356"></script>
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

	
@endsection 