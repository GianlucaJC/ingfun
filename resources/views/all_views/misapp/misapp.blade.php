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
.circle {
  width: 30px;
  height: 30px;
  border-radius: 70%;
  display: flex;
  align-items: center;
  text-align: center;
}


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
				
                <div class="circle" style="background: yellow;">
                    <p>3</p>
                </div>
				
			  </a>
			  <a href="#" class="list-group-item list-group-item-action">
				<div class="d-flex w-100 justify-content-between">
				  <h5 class="mb-1">List group item heading</h5>
				  <small class="text-body-secondary"><i class="fas fa-list-alt"></i></small>
				</div>
				<p class="mb-1">Some placeholder content in a paragraph.</p>
				<small class="text-body-secondary">And some muted small print.</small>
			  </a>
			  <a href="#" class="list-group-item list-group-item-action">
				<div class="d-flex w-100 justify-content-between">
				  <h5 class="mb-1">List group item heading</h5>
				  <small class="text-body-secondary">3 days ago</small>
				</div>
				<p class="mb-1">Some placeholder content in a paragraph.</p>
				<small class="text-body-secondary">And some muted small print.</small>
			  </a>
			</div>

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