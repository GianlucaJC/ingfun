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
            <h1 class="m-0">Divisione FUNEBRE | Amministrazione</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Amministrazione</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
	    @if ($user->hasRole('admin') || ($user->hasRole('coord')) )
	
			<div class="row">
				<div class="col-md-12">
				<a href="{{ route('dashboard') }}">
					<div class="d-grid gap-2 mt-2">
					  <button class="btn btn-secondary" type="button">
					  <i class="fas fa-home" style='font-size:36px'></i><br>
						TORNA MENU' PRECEDENTE ==> Dashboard
					  </button>
					</div>
				</a>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
				<a href="{{ route('appalti') }}">
					<div class="d-grid gap-2 mt-2">
					  <button class="btn btn-primary" type="button">
					  <i class="fas fa-database" style='font-size:36px'></i><br>
						GESTIONE APPALTI
					  </button>
					</div>
				</a>
				</div>
			</div>	

			
			<div class="row">
				<div class="col-md-12">
					<a href="{{ route('cliditte') }}">
						<div class="d-grid gap-2 mt-2">
						  <button class="btn btn-primary" type="button">
						  
						  <i class="fas fa-user-tie" style='font-size:36px'></i><br>
							CLIENTI/DITTE
						  </button>
						</div>
					</a>
				</div>

			</div>


			<div class="row">
				<div class="col-md-12">
					<a href="{{ route('menuaziende') }}">
						<div class="d-grid gap-2 mt-2">
						  <button class="btn btn-primary" type="button">
						  <i class="fas fa-industry" style='font-size:36px'></i><br>
							AZIENDE DI PROPRIETA'
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
