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
            <h1 class="m-0">Divisione FUNEBRE | DASHBOARD</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('menu') }}">Home</a></li>
              
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

			<div class="row mt-5">
				<form method='post' action="{{ route('newpassuser') }}" id='save_newcand' name='save_newcand' autocomplete="off" class="needs-validation" autocomplete="off">
					<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
					
					<input type="hidden" name="id_cand" id="id_cand" value="">
					<input type="hidden" name="nome" id="nome" value="">

					  
					<div class="form-check mb-4">
					  <input class="form-check-input" type="checkbox" value="" id="shwp" onclick="showp()">
					  <label class="form-check-label" for="shwp">
						Mostra Password
					  </label>
					</div>				  

					<!--
					<div class="form-group">
						<label for="pw_first">Vecchia Password</label>
						<input type="password" class="form-control" id="old_pw" name="old_pw" placeholder="Password" required>
					</div>
					!-->
					  
					<div class="form-group">
						<label for="pw_first">Nuova Password</label>
						<input type="password" class="form-control" id="pw_first" name="pw_first" placeholder="Password"  maxlength=12 required>
					</div>
					<div class="form-group">
						<label for="pw_ripeti">Ripeti Password</label>
						<input type="password" class="form-control" id="pw_ripeti" name="pw_ripeti" placeholder="Password" 
						maxlength=12 required>
					</div>

					
					<button type="submit" onclick='change_p()' id="btn_crea" class="btn btn-primary">Procedi</button>
					
				</form>
			</div>
			
			
			@if ($esito=="OK")
			<div class="alert alert-success mt-3" role="alert">
				<b>Opererazione effettualta!</b><hr> Password cambiata con successo
			</div>
			@endif
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
	
	<script src="{{ URL::asset('/') }}dist/js/newpass.js?ver=1.10"></script>
	
@endsection
