<?php
	use App\Models\appalti;
	use App\Models\lavoratoriapp;
	use App\Models\servizi;
	use App\Models\serviziapp;
?>
@extends('all_views.viewmaster.index')

@section('title', 'IngFUN')

@section('extra_style') 

@endsection



<style>

</style>
@section('content_main')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style='background-color:white'>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid mt-4">

	  
		<form method='post' action="{{ route('misapp') }}" id='frm_appalti' name='frm_appalti' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>	
			
			<div class="list-group">
			  <a href="#" class="list-group-item list-group-item-action" aria-current="true">
				<div class="d-flex w-100 justify-content-between">
				  <h3 class="mb-1">Nuovi lavori</h3>

				  <small><i class="fas fa-list-alt"></i></small>
				</div>
				<p class="mb-1">Visiona i nuovi lavori che ti sono stati assegnati</p>
				
                <button type="button" class="btn btn-warning position-relative">
                    Nuovi
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        3
                        <span class="visually-hidden">New jobs</span>
                    </span>
                </button>
                
			  </a>
			  
              
              <a href="#" class="list-group-item list-group-item-action">
				<div class="d-flex w-100 justify-content-between">
				  <h5 class="mb-1">Storico, Rifornimenti, Sinistri</h5>
				  <small class="text-body-secondary"><i class="fas fa-list-alt"></i></small>
				</div>
				<p class="mb-1">Visiona lo storico dei lavori e la gestione dei rifornimenti</p>

                <button type="button" class="btn btn-warning position-relative">
                    Nuovi
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        3
                        <span class="visually-hidden">New jobs</span>
                    </span>
                </button>                

                <button type="button" class="ml-3 btn btn-danger position-relative">
                    Rifiutati
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        0
                        <span class="visually-hidden">Rifiutati</span>
                    </span>
                </button>                
                
                <button type="button" class="ml-3 btn btn-success position-relative">
                    Accettati
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        1
                        <span class="visually-hidden">Accettati</span>
                    </span>
                </button> 

			  </a>


			  <a href="#" class="list-group-item list-group-item-action" aria-current="true">
				<div class="d-flex w-100 justify-content-between">
				  <h3 class="mb-1">Nuove reperibilità</h3>

				  <small><i class="fas fa-list-alt"></i></small>
				</div>
				<p class="mb-1">Visiona le reperibilità che ti sono state assegnate</p>
				
                <button type="button" class="btn btn-warning position-relative">
                    Nuove
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        1
                        <span class="visually-hidden">New reper</span>
                    </span>
                </button>
			  </a>


              <a href="#" class="list-group-item list-group-item-action">
				<div class="d-flex w-100 justify-content-between">
				  <h5 class="mb-1">Storico Reperibilità</h5>
				  <small class="text-body-secondary"><i class="fas fa-list-alt"></i></small>
				</div>
				<p class="mb-1">Visiona storico delle reperibilità</p>

                <button type="button" class="btn btn-warning position-relative">
                    Nuove
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        1
                        <span class="visually-hidden">New reper</span>
                    </span>
                </button>                

                <button type="button" class="ml-3 btn btn-danger position-relative">
                    Rifiutate
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        0
                        <span class="visually-hidden">Rifiutate</span>
                    </span>
                </button>                
                
                <button type="button" class="ml-3 btn btn-success position-relative">
                    Accettate
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        0
                        <span class="visually-hidden">Accettate</span>
                    </span>
                </button> 

			  </a>			  
              

              


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

	<!-- inclusione standard
		per personalizzare le dipendenze DataTables in funzione delle opzioni da aggiungere: https://datatables.net/download/
	!-->
	
	<!-- dipendenze DataTables !-->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.css"/>
		 
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.js"></script>
	<!-- fine DataTables !-->


	<script src="{{ URL::asset('/') }}dist/js/listapp.js?ver=1.06"></script>

@endsection