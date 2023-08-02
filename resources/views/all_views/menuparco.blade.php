<?php
use App\Models\User;
	$id = Auth::user()->id;
	$user = User::find($id);
?>
@extends('all_views.viewmaster.index')

@section('title', 'IngFUN')

@section('notifiche') 


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
            <h1 class="m-0">Divisione FUNEBRE | MENU PARCO AUTO</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
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
			<div class="col-md-12">
			<a href="{{ route('amministrazione') }}">
				<div class="d-grid gap-2 mt-2">
				  <button class="btn btn-secondary" type="button">
				  <i class="fas fa-home" style='font-size:36px'></i><br>
					TORNA MENU PRECEDENTE ==> Amministrazione
				  </button>
				</div>
			</a>
			</div>
		</div>			
		@if ($user->hasRole('admin'))
			<div class="row">
				<div class="col-md-12">
				<a href="{{ route('scheda_mezzo') }}">
					<div class="d-grid gap-2 mt-2">
					  <button class="btn btn-primary" type="button">
						<i class="fas fa-cube" style='font-size:36px'></i><br>
						NUOVO MEZZO
					  </button>
					</div>
				</a>
				</div>
				<div class="col-md-12">
				<a href="{{ route('inventario_flotta') }}">
					<div class="d-grid gap-2 mt-2">
					  <button class="btn btn-primary" type="button">
					  <i class="fas fa-cubes" style='font-size:36px'></i><br>
						INVENTARIO FLOTTA
					  </button>
					</div>
				</a>
				</div>
			</div>


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
	
	<script src="{{ URL::asset('/') }}dist/js/dash.js?ver=1.05"></script>
	
@endsection
