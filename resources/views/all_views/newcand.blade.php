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
@endsection


@section('content_main')
<form method='post' action="{{ route('save_newcand') }}" id='save_newcand' name='save_newcand' autocomplete="off" class="needs-validation" novalidate>

<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
  <!-- Content Wrapper. Contains page content -->
   <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">
				@if ($id_cand==0)
					NUOVA Candidatura
				@else
					MODIFICA Candidatura
				@endif
			</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Candidatura</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
	  
        <div class="row">
         
			@include('all_views.newcandLeft')
			
			@include('all_views.newcandRight')
			@if ($id_cand!=0 && $candidati[0]['status_candidatura']!="3")
				<button type="submit" onclick="" name='sub_assunzione' id='sub_assunzione' class="btn btn-primary btn-lg btn-block" value="1">INOLTRA CANDIDATURA</button>         
			@endif


			<button type="submit" name='sub_newcand' id='sub_newcand' class="btn btn-success btn-lg btn-block">SALVA DATI E TORNA ALLA LISTA CANDIDATURE</button>         
		

			<input type="hidden" value="{{url('/')}}" id="url" name="url">
			<input type="hidden" name="fx_curr" id="fx_curr" value="{{ $candidati[0]['file_curr']}}">
			<input type="hidden" name="id_cand" id="id_cand" value="{{ $id_cand}}">
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
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
	<script src="{{ URL::asset('/') }}dist/js/newcand.js?ver=3.6"></script>
	<!--select2 !-->
	<script src="{{ URL::asset('/') }}plugins/select2/js/select2.full.min.js"></script>
	
	<!-- per upload -->
	<script src="{{ URL::asset('/') }}dist/js/upload/jquery.dm-uploader.min.js"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload/demo-ui.js?ver=1.24"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload/demo-config.js?ver=2.351"></script>
	<!-- fine upload -->		
@endsection 