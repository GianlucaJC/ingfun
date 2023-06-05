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
			  <button type="button" id="btn_step_0" class="btn btn-primary steps">Definizione Ditta/Cliente</button>
			  <button type="button" id="btn_step_1" class="btn btn-secondary steps">Dati Documento</button>
			  <button type="button" id="btn_step_2" class="btn btn-secondary steps">Lista Articoli</button>
			</div>	
			
		  </div>
		 
			<div id='div_alert' class='container-fluid mt-2'></div>
			
			<form class="needs-validation2" id='needs-validation2' novalidate autocomplete="off" method="post" enctype="multipart/form-data">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
				<input type='hidden' name='step_active' id='step_active' value='{{$step_active}}' class='step'>
				
				<input type='hidden' name='session_cart' id='session_cart' value='{{$session_cart}}'>
				
				@include('all_views.invitofatt.stepditte')
				
			</form>


			<form class="needs-validation2a" id='needs-validation2a' novalidate autocomplete="off" method="post" enctype="multipart/form-data">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
				<input type='hidden' name='step_active' id='step_active' value='{{$step_active}}' class='step'>
				<input type='hidden' name='session_cart' id='session_cart' value='{{$session_cart}}'>
				<input type='hidden' name='ditta' id='ditta' value='{{$ditta}}'>
			
					@include('all_views.invitofatt.stepdoc')
			</form>	
			


			<form class="needs-validation3" id='needs-validation3' novalidate autocomplete="off" method="post" enctype="multipart/form-data">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
				<input type='hidden' name='step_active' id='step_active' value='{{$step_active}}' class='step'>
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
		<form id='frm_modal' class="needs-validation4" id='needs-validation4' novalidate autocomplete="off" method="post" enctype="multipart/form-data">
					<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>

			<input type='hidden' name='step_active' id='step_active' value='{{$step_active}}' class='step'>
			<input type='hidden' name='session_cart' id='session_cart' value='{{$session_cart}}'>
			<input type='hidden' name='ditta' id='ditta' value='{{$ditta}}'>
					
			<input type='hidden' id='edit_riga' name='edit_riga'>
					
			@include('all_views.invitofatt.editmodal')
		</form>	

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
	<script src="{{ URL::asset('/') }}dist/js/invito.js?ver=1.122"></script>
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