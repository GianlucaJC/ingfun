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
<form method='post' action="{{ route('riparazione') }}" id='frm_mezzo' name='frm_mezzo' autocomplete="off" class="needs-validation" novalidate>

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
						SCHEDA RIPARAZIONE MEZZO
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
	<input type='hidden' name='id_mezzo' id='id_mezzo' value='{{$id_mezzo}}'>
      <div class="container-fluid">
		<div class="row mb-3">
			@if ($id_mezzo!="0")
				<div class="col-md-6">
					<div class="form-floating">
						<input class="form-control"  id="targa" name="targa" type="text" placeholder="ID"  value="{{$info_mezzo[0]->targa ?? ''}}" disabled  />
						<label for="targa">TARGA</label>
					</div>
				</div>
			@else	
				<div class="col-md-6">
					<div class="form-floating mb-3 mb-md-0">
						<select class="form-select" name="targa" id="targa" required >
							<option value=''>Select...</option>
							@foreach($mezzi as $mezzo)
								<option value="{{$mezzo->id}}"
								>{{$mezzo->targa}}</option>
							@endforeach
						</select>
						<label for="targa">Identificativo mezzo*</label>
					</div>
				</div>
			@endif
		</div>

	




		<div class='row mb-3'>

			<div class="col-md-12">
				<div class="form-floating">
					<input class="form-control" id="officina_riferimento" name='officina_riferimento' type="text" maxlength=80 value="{{$info_mezzo[0]->officina_riferimento ?? ''}}" />
					<label for="officina_installazione">Officina riferimento</label>
				</div>
			</div>
		</div>
		<div class='row mb-3'>
			<div class="col-md-3">
				<div class="form-floating">
					<input class="form-control" id="data_consegna_prevista" name='data_consegna_prevista' type="date" value="{{$info_mezzo[0]->data_consegna_prevista ?? ''}}"/>
					<label for="data_consegna_prevista">Data consegna prevista</label>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-floating">
					<input class="form-control" id="data_consegna_riparazione" name='data_consegna_riparazione' type="date" value="{{$info_mezzo[0]->data_consegna_riparazione ?? ''}}"/>
					<label for="data_consegna_riparazione">Data consegna riparazione</label>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-floating">
					<input class="form-control" id="importo_preventivo" name='importo_preventivo' type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" maxlength=11 value="{{$info_mezzo[0]->importo_preventivo ?? ''}}" />
					<label for="importo_preventivo">Importo preventivo</label>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-floating">
					<input class="form-control" id="importo_fattura" name='importo_fattura' type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" maxlength=11 value="{{$info_mezzo[0]->importo_fattura ?? ''}}" />
					<label for="importo_fattura">Importo fattura</label>
				</div>
			</div>			
		</div>	


		<div class='row mb-3'>
			<div class="col-md-3">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="mezzo_marciante" id="mezzo_marciante" >
					<option value=''>Select...</option>
						<option value=1
						<?php
						if (isset($info_mezzo[0])) {
								if ($info_mezzo[0]->mezzo_marciante==1) echo " selected ";
						}?>						
						>SI</option>
						<option value=2
						<?php
						if (isset($info_mezzo[0])) {
								if ($info_mezzo[0]->mezzo_marciante==2) echo " selected ";
						}?>						
						>NO</option>
					</select>
					<label for="mezzo_marciante">Mezzo marciante</label>
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="mezzo_manutenzione" id="mezzo_manutenzione" >
					<option value=''>Select...</option>
						<option value=1
						<?php
						if (isset($info_mezzo[0])) {
							if ($info_mezzo[0]->mezzo_manutenzione==1) echo " selected ";
						}?>								
						>SI</option>
						<option value=2
						<?php
						if (isset($info_mezzo[0])) {
							if ($info_mezzo[0]->mezzo_manutenzione==2) echo " selected ";
						}?>								
						>NO</option>
					</select>
					<label for="mezzo_manutenzione">Mezzo in manutenzione</label>
				</div>
			</div>
		</div>			

        <div class="row">

			<button type="submit" name='btn_save_mezzo' id='btn_save_mezzo' value="save" class="btn btn-success btn-lg btn-block">SALVA</button>  
			
			<a href="{{ route('riparazioni') }}">
				<button type="button"  id='back_appalti' class="btn btn-info btn-lg btn-block mt-3">ELENCO RIPARAZIONI</button> 
			</a>


			<input type="hidden" value="{{url('/')}}" id="url" name="url">

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
	<script src="{{ URL::asset('/') }}dist/js/riparazione.js?ver=1.237"></script>
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