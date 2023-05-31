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
						INVITO A FATTURARE
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
	
		<!------------ Stepper !-->	
		<div class="card">
		  
		  <div class="card-header">
			<div id='div-step' class="btn-group mb-3" role="group" aria-label="Step" >
			  <button type="button" id="btn_step_ditte" class="btn btn-primary steps">Definizione Ditta/Cliente</button>
			  <button type="button" id="btn_step_datidoc" class="btn btn-secondary steps">Dati Documento</button>
			  <button type="button" id="btn_step_articoli" class="btn btn-secondary steps">Lista Articoli</button>
			  <button type="button" id="btn_step_pagamenti" class="btn btn-secondary steps">Pagamenti</button>
			</div>	
			
		  </div>
		 
			<div id='div_alert' class='container-fluid mt-2'></div>
			
			<form class="needs-validation2" id='needs-validation2' novalidate autocomplete="off" method="post" enctype="multipart/form-data">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
				<input type='hidden' name='step_active' id='step_active' value='{{$step_active}}'>
				
				<input type='hidden' name='session_cart' id='session_cart' value='{{$session_cart}}'>
				
				@include('all_views.invitofatt.stepditte')
				
			</form>
			


			<form class="needs-validation3" id='needs-validation2' novalidate autocomplete="off" method="post" enctype="multipart/form-data">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
				<input type='hidden' name='step_active' id='step_active' value='{{$step_active}}'>
				<input type='hidden' name='session_cart' id='session_cart' value='{{$session_cart}}'>
			
				<input type='hidden' name='ditta' id='ditta' value='{{$ditta}}'>
			
					@include('all_views.invitofatt.steparticoli')
			</form>		
			
		</div>	
		<!-- fine card stepper !-->		
	

        <hr>
		<div class="row mb-3 mt-5">
			<a href="">
				<button type="button"  id='back_appalti' class="btn btn-secondary btn-lg btn-block mt-3">TORNA AD ELENCO INVITI DA FATTURARE</button> 
			</a>


			<input type="hidden" value="{{url('/')}}" id="url" name="url">
			
			<input type="hidden" name="id_fatt" id="id_fatt" value="">
		</div>
		
			
		
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>

<!-- Modal -->
<div class="modal fade bd-example-modal-xl" id="modal_story" tabindex="-1" role="dialog" aria-labelledby="info" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title_modal">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id='body_modal'>
			<div class="row mb-3">
				<div class="col-md-6">
					<div class="row mb-3">
						<div class="col-md-3">
							<div class="form-floating">
								<input class="form-control" id="codice" name='codice' type="text" placeholder="Codice"   />
								<label for="codice">Codice</label>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-floating">
								<input class="form-control" id="prodotto" name='prodotto' type="text" placeholder="Prodotto"  />
								<label for="prodotto">Prodotto</label>
							</div>		
						</div>
						<div class="col-md-3">
							<div class="form-floating">
								<input class="form-control" id="quantita" name='quantita' type="text" placeholder="Q.tà" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" />
								<label for="quantita">Quantità</label>
							</div>		
						</div>
					</div>
					
				</div>
				

				<div class="col-md-6">
					<div class="row mb-3">
						<div class="col-md-2">
							<div class="form-floating">
								<input class="form-control" id="um" name='um' type="text" placeholder="UM"  />
								<label for="um">U.M.</label>
							</div>		
						</div>							
						<div class="col-md-4">
							<div class="form-floating">
								<input class="form-control" id="prezzo_unitario" name='prezzo_unitario' type="text" placeholder="Prezzo unitario" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" />
								<label for="prezzo_unitario">Prezzo Unitario</label>
							</div>		
						</div>	
						<div class="col-md-3">
							<div class="form-floating">
								<input class="form-control" id="subtotale" name='subtotale' type="text" placeholder="Subtotale" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" />
								<label for="subtotale" >Subtotale</label>
							</div>		
						</div>
						<div class="col-md-3">
						  <div class="form-floating mb-3 mb-md-0">
							
							<select class="form-select" id="aliquota" aria-label="Stato Occupazione" name='aliquota' required>
								<option value=''>Select...</option>
								@foreach ($aliquote_iva as $aliquota) 
									<option value='{{$aliquota->id}}'>
										{{$aliquota->aliquota}}% - {{$aliquota->descrizione}}
									</option>	
								@endforeach
							</select>
							
							<label for="aliquota">Aliquota Iva</label>
							</div>
						</div>						
					</div>	
				</div>
			</div>			
      </div>
      <div class="modal-footer">
		<div id='altri_btn'></div>
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
	<script src="{{ URL::asset('/') }}dist/js/invito.js?ver=1.065"></script>
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