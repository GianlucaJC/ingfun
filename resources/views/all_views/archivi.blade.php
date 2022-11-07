<?php
use App\Models\User;
	$id = Auth::user()->id;
	$user = User::find($id);
?>
@extends('all_views.viewmaster.index')

@section('title', 'IngFUN')

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
            <h1 class="m-0">Divisione FUNEBRE | Archivi</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Archivi</li>
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
				<a href="{{ route('tipologia_contr') }}">
					<div class="d-grid gap-2 mt-2">
					  <button class="btn btn-info" type="button">
						<i class="fa fa-book" style='font-size:36px'></i><br>
						Tipologie di Contratto
					  </button>
					</div>
				</a>
				</div>
				<div class="col-md-6">
				<a href="{{ route('tipo_contratto') }}">
					<div class="d-grid gap-2 mt-2">
					  <button class="btn btn-info" type="button">
					  <i class="fas fa-cubes" style='font-size:36px'></i><br>
						Tipo Contratto
					  </button>
					</div>
				</a>
				</div>
			</div>
		
			<div class="row">
				<div class="col-md-6">
					<a href="{{ route('mansione') }}">
						<div class="d-grid gap-2 mt-2">
						  <button class="btn btn-info" type="button">
						  <i class="fas fa-users" style='font-size:36px'></i><br>
							Mansioni
						  </button>
						</div>
					</a>
				</div>
				<div class="col-md-6">
					<a href="{{ route('frm_attestati') }}">
						<div class="d-grid gap-2 mt-2">
						  <button class="btn btn-info" type="button">
						  <i class="far fa-address-card" style='font-size:36px'></i><br>
							Corsi Formazione
						  </button>
						</div>
					</a>
				</div>
			</div>


			<div class="row">
				<div class="col-md-6">
					<a href="{{ route('societa_assunzione') }}">
						<div class="d-grid gap-2 mt-2">
						  <button class="btn btn-info" type="button">
						  <i class="fa fa-cubes" style='font-size:36px'></i><br>
							Società di assunzione
						  </button>
						</div>
					</a>
				</div>	

				<div class="col-md-6">
					
					<a href="{{ route('costo') }}">
						<div class="d-grid gap-2 mt-2">
						  <button class="btn btn-info" type="button">
						  <i class="fas fa-list" style='font-size:36px'></i><br>
							Centri di Costo
						  </button>
						</div>
					</a>
				</div>	
			</div>


			<div class="row">
				<div class="col-md-6">
					<a href="{{ route('area_impiego') }}">
						<div class="d-grid gap-2 mt-2">
						  <button class="btn btn-info" type="button">
						  <i class="fa fa-sitemap" style='font-size:36px'></i><br>
							Aree di impiego
						  </button>
						</div>
					</a>
				</div>
				<div class="col-md-6">
					<a href="{{ route('ccnl') }}">
						<div class="d-grid gap-2 mt-2">
						  <button class="btn btn-info" type="button">
						  <i class="fas fa-file-signature" style='font-size:36px'></i><br>
							Contratti CCNL
						  </button>
						</div>
					</a>
				</div>	
			</div>		

			<div class="row">
				<div class="col-md-6">
					<a href="{{ route('tipo_documento') }}">
						<div class="d-grid gap-2 mt-2">
						  <button class="btn btn-info" type="button">
						  <i class="fas fa-clipboard-list" style='font-size:36px'></i><br>
							Tipologie di Documento
						  </button>
						</div>
					</a>
				</div>
				<div class="col-md-6">
					<a href="{{ route('sotto_tipo_documento') }}">
						<div class="d-grid gap-2 mt-2">
						  <button class="btn btn-info" type="button">
						  <i class="fas fa-clipboard-check" style='font-size:36px'></i><br>
							Sottotipo di Documento
						  </button>
						</div>
					</a>
				</div>	
			</div>	
			

			<div class="row">
				<div class="col-md-6">
					<a href="{{ route('documenti') }}">
						<div class="d-grid gap-2 mt-2">
						  <button class="btn btn-info" type="button">
						  <i class="fa fa-clone" style='font-size:36px'></i><br>
							Area Documenti
						  </button>
						</div>
					</a>
				</div>
				<div class="col-md-6">
					<a href="{{ route('contatti') }}">
						<div class="d-grid gap-2 mt-2">
						  <button class="btn btn-info" type="button">
						  <i class="fa fa-address-book" style='font-size:36px'></i><br>
							Contatti Interni
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
