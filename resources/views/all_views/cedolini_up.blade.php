<?php

use setasign\Fpdi\Fpdi;
use App\Models\User;
	$id = Auth::user()->id;
	$user = User::find($id);

?>

@extends('all_views.viewmaster.index')

@section('title', 'IngFUN')

@section('extra_style')  
 <!-- per upload -->
  <link href="{{ URL::asset('/') }}dist/css/upload/jquery.dm-uploader.min.css" rel="stylesheet">
  <!-- per upload -->  
  <link href="{{ URL::asset('/') }}dist/css/upload/styles.css?ver=1.1" rel="stylesheet">  

@endsection

@section('content_main')
  <input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
  <input type="hidden" value="{{url('/')}}" id="url" name="url">
  <!-- Content Wrapper. Contains page content -->


  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
	
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">UPLOAD Cedolini. Scegliere il file da inviare al server</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Upload Cedolini</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">

	    @if ($user->hasRole('admin'))
			<div class="row mb-3">
				<div class="col-md-12">
					<!-- l'upload viene fatto dal plugin  dist/js/upload/demo-config.js !-->
					<?php include("class_allegati.php"); ?>
					<input type='hidden' name='allegato' id='allegato'>
					<input type='hidden' name='pagecount' id='pagecount'>
					
					
				</div>
			</div>
			<button type="button" class="btn btn-success  btn-lg btn-block" disabled id='btn_split' onclick='split_pdf(1)'>Avvia suddivisione</button>

		@endif	
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
	
	<script src="{{ URL::asset('/') }}dist/js/cedolini_up.js?ver=1.12"></script>
	
	<!-- per upload -->
	<script src="{{ URL::asset('/') }}dist/js/upload/jquery.dm-uploader.min.js"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload/demo-ui.js?ver=1.24"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload/demo-config.js?ver=2.356"></script>
	<!-- fine upload -->		

	<!-- esempio di utilizzo da js...quì implementato in cedolini_up.js	
	$("#drop-area").dmUploader({
	  url: '/path/to/backend/upload.asp',
	  //... More settings here...
	  
	  onInit: function(){
		console.log('Callback: Plugin initialized');
	  }
	  
	  // ... More callbacks
	});	
	<!-- -->

	
@endsection
