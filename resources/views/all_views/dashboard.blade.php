<?php
use App\Models\User;
	$id = Auth::user()->id;
	$user = User::find($id);
?>
@extends('all_views.viewmaster.index')

@section('title', 'IngFUN')

@section('notifiche') 

	@if (1==2)
      <li class="nav-item dropdown notif" onclick="azzera_notif()">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">{{count($scadenze)}}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-header">Avvisi di scadenza</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file-signature"></i> {{count($scadenze)}} {{$descr_num}} in scadenza
            <span class="float-right text-muted text-sm"></span>
          </a>
          <div class="dropdown-divider"></div>

          <div class="dropdown-divider"></div>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">Vai al dettaglio</a>
        </div>
      </li>
	@endif  
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
            <h1 class="m-0">Divisione FUNEBRE | DASHBOARD</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
		
		@if ($user->hasRole('user'))

			<div class="row">
				<div class="col-md-12">
					<a href="{{ route('cedolini_view') }}">
						<div class="d-grid gap-2 mt-2">
						  <button class="btn btn-primary" type="button">
						  <i class="fas fa-clipboard-check" style='font-size:36px'></i><br>
							CEDOLINI
						  </button>
						</div>
					</a>
				</div>	
			</div>	
		@endif
		
		
		
		

	    @if ($user->hasRole('admin'))

			<div class="row">
				<div class="col-md-12">
				<a href="{{ route('menuhr') }}">
					<div class="d-grid gap-2 mt-2">
					  <button class="btn btn-primary" type="button">
					  <i class="fas fa-users" style='font-size:36px'></i><br>
						RISORSE UMANE
					  </button>
					</div>
				</a>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<a href="{{ route('amministrazione') }}">
						<div class="d-grid gap-2 mt-2">
						  <button class="btn btn-primary" type="button">
						  <i class="fas fa-cubes" style='font-size:36px'></i><br>
							AMMINISTRAZIONE
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
