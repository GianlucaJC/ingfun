@extends('all_views.viewmaster.index')

@section('title', 'IngFUN')

@section('operazioni')
<!-- Pannello gestione utenza !-->
	<div class="p-3">
		<h5>Accesso Utente</h5>
		<p>
			@if ($user_active==false) 				
				<h6>
					L'utente non possiede credenziali per accedere!
				</h6>
			@endif
			<form method='post' action="{{ route('save_newuser') }}" id='save_newcand' name='save_newcand' autocomplete="off" class="needs-validation" autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
			
			<input type='hidden' name='from' value="{{$from}}">
			<input type="hidden" name="id_cand" id="id_cand" value="{{ $id_cand}}">
			<input type="hidden" name="nome" id="nome" value="{{ $candidati[0]['nome']}}">
			
			  <div class="form-group">
				<label for="email_accesso">Email accesso</label>
				<input type="email" class="form-control" id="email_accesso" name="email_accesso" aria-describedby="email_accesso" placeholder="Email Accesso" required maxlength=150 onkeyup="this.value = this.value.toLowerCase();" value="{{ $email_accesso }}" >
				<small id="email_accesso" class="form-text text-muted">L'email di accesso è correlata al sistema di amministrazione interna</small>
			  </div>
			  
			<div class="form-check">
			  <input class="form-check-input" type="checkbox" value="" id="shwp" onclick="showp()">
			  <label class="form-check-label" for="shwp">
				Mostra Password
			  </label>
			</div>				  
			  
			  <div class="form-group">
				<label for="pw_first">Password Iniziale</label>
				<input type="password" class="form-control" id="pw_first" name="pw_first" placeholder="Password" required>
			  </div>
			  <div class="form-group">
				<label for="pw_ripeti">Ripeti Password</label>
				<input type="password" class="form-control" id="pw_ripeti" name="pw_ripeti" placeholder="Password" required>
			  </div>

				@if ($user_active==false) 
					@php($lbl_crea="Crea utenza")
				@else 
					@php($lbl_crea="Reset password")
				@endif					
					<button type="submit" id="btn_crea" class="btn btn-primary">{{$lbl_crea}}</button>
				
			</form>	
			@if ($user_active==true) 
				<hr>
			
				
				<form method='post' action="{{ route('set_ruolo') }}" id='save_newcand' name='save_newcand' autocomplete="off" class="needs-validation" autocomplete="off">					

					<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>				
					<input type='hidden' name='from' value="{{$from}}">
					<input type="hidden" name="id_cand" id="id_cand" value="{{ $id_cand}}">

					<div class="input-group">
						<select class="form-select" name="ruolo" id="ruolo" aria-label="Ruolo" required>
							<option value=''>Select...</option>
							@foreach ($roles as $role) 
								<option value="{{$role->name}}" 
								<?php if($ruolo==$role->name) echo "selected";?>
								
								>{{$role->name}}</option>		
								
							@endforeach
						</select>
					  <button type='submit' class="btn btn-outline-secondary" type="button">Imposta</button>
					</div>					
					<label for="ruolo">Ruolo</label>
				</form>
				
					
				
				
				<form method='post' action="{{ route('disable_user') }}" id='save_newcand' name='save_newcand' autocomplete="off" class="needs-validation" autocomplete="off">
					<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
					<input type='hidden' name='from' value="{{$from}}">
					<input type="hidden" name="id_cand" id="id_cand" value="{{ $id_cand}}">
					<input type="hidden" name="nome" id="nome" value="{{ $candidati[0]['nome']}}">
					<button type="submit" id='btn_disable' class="btn btn-info" >Disabilita Accesso</button>
				</form>
			@endif

		</p>
    </div>
	
	
@endsection



@section('extra_style')  
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ URL::asset('/') }}plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="{{ URL::asset('/') }}plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
 <!-- per upload -->
  <link href="{{ URL::asset('/') }}dist/css/upload/jquery.dm-uploader.min.css" rel="stylesheet">
  <!-- per upload -->  
  <link href="{{ URL::asset('/') }}dist/css/upload/styles.css?ver=1.1" rel="stylesheet">  
  <link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet">

@endsection


@section('content_main')
<form method='post' action="{{ route('save_newcand') }}" id='save_newcand' name='save_newcand' autocomplete="off" class="needs-validation" novalidate>

<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
  <!-- Content Wrapper. Contains page content -->
   <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12">  
			<h1 class="m-0">
				<center>
					<font color='red'>
						@if ($from=="0") 
							SCHEDA CANDIDATO
						@else
							SCHEDA DIPENDENTE
						@endif
					</font>
				</center>
				
			</h1>
			</div>	
		</div>	

      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">

		<input type='hidden' name='setuser'  id='setuser' value="{{$setuser}}">
        <div class="row">
		 
			@include('all_views.newcandLeft')
			
			@include('all_views.newcandRight')
        </div>
		

		<div class='row'>
		
			<div class="col-md-3">
				@if ($id_cand!=0 && $candidati[0]['status_candidatura']=="3")
					<button type="button" onclick="prepara_mail(2)" class="btn btn-secondary btn-lg btn-block mb-3" id='btn_comunica'>INVIA COMUNICAZIONE </button><hr>
				@endif	  
			</div>	

			<div class="col-md-3">
				@if ($id_cand!=0 && $candidati[0]['status_candidatura']=="3")
					<button type="button" onclick="prepara_mail(4)" class="btn btn-warning btn-lg btn-block mb-3" id='btn_dim'>DIMISSIONI</button><hr>
				@endif	  
			</div>	
			<div class="col-md-3">
				@if ($id_cand!=0 && $candidati[0]['status_candidatura']=="3")
					<button type="button" onclick="prepara_mail(5)" class="btn btn-danger btn-lg btn-block mb-3" id='btn_lic'>LICENZIAMENTO</button><hr>
				@endif	  
			</div>	
			<div class="col-md-3">
				@if ($id_cand!=0 && $candidati[0]['status_candidatura']=="3")
					<button type="button" onclick="prepara_mail(6)" class="btn btn-primary btn-lg btn-block mb-3" id='btn_scad'>SCADENZA NATURALE</button><hr>
				@endif	  
			</div>	
		</div>		
		
        <div class="row">
		
			@if ($id_cand!=0 && $candidati[0]['status_candidatura']=="1")
				<button type="button" onclick="prepara_mail(3)" class="btn btn-primary btn-lg btn-block mb-3" id='btn_inoltra'>INOLTRA CANDIDATURA</button><hr>
			@endif



			<button type="submit" name='sub_newcand_onlysave' id='sub_newcand_onlysave' class="btn btn-info btn-lg btn-block">SALVA</button>  

			@php ($desc_btn="")
			@if ($from=="0") 
				@php ($desc_btn="SALVA DATI E TORNA ALLA LISTA CANDIDATURE") 
			@elseif ($from=="1") 
				@php ($desc_btn="SALVA DATI E TORNA ALLA GESTIONE PERSONALE") 
			@elseif ($from=="2") 
				@php ($desc_btn="SALVA DATI E TORNA ALLE SCADENZE CONTRATTUALI") 
			@else
				@php ($desc_btn="SALVA DATI E TORNA ALLA LISTA CANDIDATURE") 
			@endif

			<button type="submit" name='sub_newcand' id='sub_newcand' class="btn btn-success btn-lg btn-block">{{$desc_btn}}</button>         
		
			<input type='hidden' name='from' value="{{$from}}">
			<input type="hidden" value="{{url('/')}}" id="url" name="url">
			<input type="hidden" name="fx_curr" id="fx_curr" value="{{ $candidati[0]['file_curr']}}">
			<input type="hidden" name="id_cand" id="id_cand" value="{{ $id_cand}}">
		</div>
		
			
		
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="modal_story" tabindex="-1" role="dialog" aria-labelledby="info" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title_modal">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id='body_modal'>
        ...
      </div>
      <div class="modal-footer">
		<div id='altri_btn'></div>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
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
	<script src="{{ URL::asset('/') }}dist/js/newcand.js?ver=3.103"></script>
	<script src="{{ URL::asset('/') }}dist/js/azione.js?ver=1.18"></script>
	<!--select2 !-->
	<script src="{{ URL::asset('/') }}plugins/select2/js/select2.full.min.js"></script>
	
	<!-- per upload -->
	<script src="{{ URL::asset('/') }}dist/js/upload/jquery.dm-uploader.min.js"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload/demo-ui.js?ver=1.24"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload/demo-config.js?ver=2.356"></script>
	<!-- fine upload -->		
	

	<!-- inclusione standard
		per personalizzare le dipendenze DataTables in funzione delle opzioni da aggiungere: https://datatables.net/download/
	!-->
	
	<!-- dipendenze DataTables !-->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.css"/>
		 
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.js"></script>
	<!-- fine DataTables !-->

	
@endsection 