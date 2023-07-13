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
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">NUOVO PREVENTIVO</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			  <li class="breadcrumb-item active">Amministrazione</li>
              <li class="breadcrumb-item active">Nuovo preventivo</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
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
			  
			  <button type="button" id="btn_step_1" class="btn btn-secondary steps">Lista Articoli</button>
			</div>	
			
		  </div>
		 
			<div id='div_alert' class='container-fluid mt-2'></div>
			
			<form class="needs-validation2" id='needs-validation2' novalidate autocomplete="off" method="post" enctype="multipart/form-data">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
				<input type='hidden' name='step_active' id='step_active' value='{{$step_active}}' class='step'>
				
				<input type='hidden' name='id_doc' id='id_doc' value='{{$id_doc}}'>
				<!-- nel primo step 
					i due campi [ditta] e [data_preventivo] vengono valorizzati all'interno della view stepditte, per gli altri form (view) vengono passati in POST in modalità hidden
				!-->
				
				@include('all_views.preventivi.stepditte')
				
			</form>



			


			<form class="needs-validation3" id='needs-validation3' novalidate autocomplete="off" method="post" enctype="multipart/form-data">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
				<input type='hidden' name='step_active' id='step_active' value='{{$step_active}}' class='step'>
				<input type='hidden' name='id_doc' id='id_doc' value='{{$id_doc}}'>
				
				<input type='hidden' name='data_preventivo' id='data_preventivo' value='{{$data_preventivo}}'>

				<input type='hidden' name='ditta' id='ditta' value='{{$ditta}}'>
			
					@include('all_views.preventivi.steparticoli')
			</form>		
			
		</div>	
		<!-- fine card stepper !-->		
	

        <hr>
		<div class="row mb-3 mt-5">
			<a href="{{ route('lista_preventivi') }}">
				<button type="button"  id='back_appalti' class="btn btn-secondary btn-lg btn-block mt-3">VAI ALL'ELENCO PREVENTIVI</button> 
			</a>
			<input type="hidden" value="{{url('/')}}" id="url" name="url">
			<input type="hidden" name="id_fatt" id="id_fatt" value="">
		</div>
		
			
		
        <!-- /.row -->
		<form id='frm_modal' class="needs-validation4" id='needs-validation4' novalidate autocomplete="off" method="post" enctype="multipart/form-data">
					<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>

			<input type='hidden' name='step_active' id='step_active' value='{{$step_active}}' class='step'>
			<input type='hidden' name='id_doc' id='id_doc' value='{{$id_doc}}'>
		
			<input type='hidden' name='data_preventivo' id='data_preventivo' value='{{$data_preventivo}}'>
			
			<input type='hidden' name='ditta' id='ditta' value='{{$ditta}}'>
					
			<input type='hidden' id='edit_riga' name='edit_riga'>
					
			@include('all_views.preventivi.editmodal')
		</form>	

      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="modal_fatt" tabindex="-1" role="dialog" aria-labelledby="info" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title_modal_fatt">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id='body_modal_fatt'>
        ...
      </div>
      <div class="modal-footer">
		<div id='altri_btn_fatt'></div>
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
	<script src="{{ URL::asset('/') }}dist/js/preventivo.js?ver=1.201"></script>
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