<?php
use App\Models\User;
	$id = Auth::user()->id;
	$user = User::find($id);
?>
@extends('all_views.viewmaster.index')

@section('title', 'IngFUN')

@section('content_main')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Divisione FUNEBRE | Servizi in primo piano</h1>
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

	    @if ($user->hasRole('admin'))
			<div class="row">
				<div class="col-md-6">
				<a href="{{ route('newcand') }}">
					<div class="d-grid gap-2 mt-2">
					  <button class="btn btn-primary" type="button">
						<i class="fas fa-cube" style='font-size:36px'></i><br>
						NUOVA CANDIDATURA
					  </button>
					</div>
				</a>
				</div>
				<div class="col-md-6">
				<a href="{{ route('listcand') }}">
					<div class="d-grid gap-2 mt-2">
					  <button class="btn btn-primary" type="button">
					  <i class="fas fa-cubes" style='font-size:36px'></i><br>
						LISTA CANDIDATURE
					  </button>
					</div>
				</a>
				</div>
			</div>
		
			<div class="row">
				<div class="col-md-6">
					<a href="#">
						<div class="d-grid gap-2 mt-2">
						  <button class="btn btn-primary disabled" type="button">
						  <i class="fas fa-users" style='font-size:36px'></i><br>
							GESTIONE PERSONALE
						  </button>
						</div>
					</a>
				</div>
				<div class="col-md-6">
					<a href="#">
						<div class="d-grid gap-2 mt-2">
						  <button class="btn btn-primary disabled" type="button">
						  <i class="far fa-address-card" style='font-size:36px'></i><br>
							GESTIONE LIBERI PROFESSIONISTI
						  </button>
						</div>
					</a>
				</div>
			</div>


			<div class="row">
				<div class="col-md-6">
					<a href="#">
						<div class="d-grid gap-2 mt-2">
						  <button class="btn btn-primary disabled" type="button">
						  <i class="fas fa-file-signature" style='font-size:36px'></i><br>
							GESTIONE SCADENZA CONTRATTI
						  </button>
						</div>
					</a>
				</div>	

				<div class="col-md-6">
					
					<a href="#">
						<div class="d-grid gap-2 mt-2">
						  <button class="btn btn-primary disabled" type="button">
						  <i class="fas fa-list" style='font-size:36px'></i><br>
							REGISTRO PRESENZE
						  </button>
						</div>
					</a>
				</div>	
			</div>


			<div class="row">
				<div class="col-md-6">
					<a href="#">
						<div class="d-grid gap-2 mt-2">
						  <button class="btn btn-primary disabled" type="button">
						  <i class="fas fa-clipboard-list" style='font-size:36px'></i><br>
							DOCUMENTI SOSPESI
						  </button>
						</div>
					</a>
				</div>
				<div class="col-md-6">
					<a href="#">
						<div class="d-grid gap-2 mt-2">
						  <button class="btn btn-primary disabled" type="button">
						  <i class="fas fa-clipboard-check" style='font-size:36px'></i><br>
							CEDOLINI
						  </button>
						</div>
					</a>
				</div>	
			</div>		

			<a href="#">
				<div class="d-grid gap-2 mt-2">
				  <button class="btn btn-primary disabled" type="button">
				  <i class="fas fa-upload" style='font-size:36px'></i><br>
					UPLOAD CEDOLINI
				  </button>
				</div>
			</a>

			<a href="#">
				<div class="d-grid gap-2 mt-2">
				  <button class="btn btn-primary disabled" type="button">
				  <i class="fas fa-users-cog" style='font-size:36px'></i><br>
					GESTIONE ASSENZE PERSONALI
				  </button>
				</div>
			</a>

			<a href="#">
				<div class="d-grid gap-2 mt-2">
				  <button class="btn btn-primary disabled" type="button">
				  
				  <i class="fas fa-cogs" style='font-size:36px'></i><br>
					ARCHIVI
				  </button>
				</div>
			</a>			


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
@endsection
