@extends('all_views.viewmaster.index_sinistri')

@section('title', 'IngFUN')




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
						GESTIONE SINISTRI
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


	@php ($disp="display:block")
	@if ($id_appalto==0) 
		@php ($disp="display:none")
		<div class="container-fluid">
			<div class="alert alert-warning" role="alert">
				<b>Attenzione!</b><hr>
				Manca definizione appalto e/o targa
			</div>
		</div>	
	@endif
      <div class="container-fluid" style="{{$disp}}">
	  
		<form method='post' action="{{ route('sinistri') }}" id='frm_sinistro' name='frm_sinistro' autocomplete="off" class="needs-validation" novalidate>
		<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
		<input type="hidden" value="{{url('/')}}" id="url" name="url">
		<div class="row mb-3">
			<div class="col-md-6">
				<div class="form-floating">
					<input class="form-control"  id="id_appalto" name="id_appalto" type="text" placeholder="ID appalto"  value="{{$id_appalto ?? ''}}" disabled  />
					<label for="id_appalto">ID Appalto</label>
				</div>
			</div>


		</div>
		
		<div class="row mb-3">

			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-control" name="mezzo_coinvolto" id="mezzo_coinvolto" aria-label="da mezzo_coinvolto" >
						<option value=''>Select...</option>
							<option value='S'
							>SI</option>
					</select>
					<label for="mezzo_coinvolto">Mezzo coinvolto*</label>
				</div>
			</div>

			<div class="col-md-2">
				<div class="form-floating mb-3 mb-md-0">
				<?php
					$d_def=date("Y-m-d");
					$d_app=$d_def;
				?>
					<input class="form-control" id="data_sinistro" name='data_sinistro' type="date" required value="{{$d_app}}" />
					<label for="data_sinistro">Data*</label>
				</div>
			</div>			

			<div class="col-md-2">
				<div class="form-floating mb-3 mb-md-0">
					<input class="form-control" id="ora_app" name='ora_sinistro' type="datetime-local" required maxlength=5 />
					<label for="ora_sinistro">Ora*</label>
				</div>
			</div>	

			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-control" name="mezzo_marciante" id="mezzo_marciante" aria-label="Mezzo marciante" >
						<option value=''>Select...</option>
							<option value='S'
							>SI</option>
							
							
							<option value='N'	
							>NO</option>	
							
					</select>
					<label for="mezzo_marciante">Mezzo Marciante?*</label>
				</div>
			</div>
			



		</div>
		

		<div class="row mb-3">

			<!--
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control"  id="in_arrivo" name="in_arrivo" type="text" placeholder="In arrivo"  disabled />
					<label for="in_arrivo">In arrivo</label>
				</div>
			</div>
			!-->


			
			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-control" name="da_riordinare" id="da_riordinare" aria-label="da riordinare" >
						<option value=''>Select...</option>
							<option value='S'
							<?php
								if (isset($info_articolo[0]->da_riordinare) && $info_articolo[0]->da_riordinare=="S") echo " selected ";
							?>
							>SI</option>
							
							<option value='N'
							<?php
								if (isset($info_articolo[0]->da_riordinare) && $info_articolo[0]->da_riordinare=="N") echo " selected ";
							?>
							>NO</option>	
							


							
					</select>
					<label for="da_riordinare">Da riordinare</label>
				</div>
			</div>
			
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control"  id="scorta_minima" name="scorta_minima" type="text" placeholder="Scorta"  value="{{$info_articolo[0]->scorta_minima ?? ''}}" />
					<label for="scorta_minima">Scorta minima</label>
				</div>

			</div>			


		</div>
		

		<div class="row mb-3">
			<div class="col-md-6">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-control" name="id_categoria" id="id_categoria" aria-label="Categoria" required onchange='load_sc(this.value)'>
						<option value=''>Select...</option>
					</select>
					<label for="categoria">Categoria*</label>
					
					<a href="{{ route('categorie_prodotti') }}" class="link-primary" target='_blank' onclick="
							 $('.up').hide();$('#div_up_cat').show()">
						Definisci/modifica
					</a>					
					<span id='div_up_cat' class='up' style='display:none'>
						<a href='javascript:void(0)' class='ml-2' onclick='refresh_cat()'>
							<font color='green'>
								<i class="fas fa-sync-alt"></i>
							</font>	
						</a>	
					</span>						
				</div>	
			</div>

			<div class="col-md-6">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-control" name="id_sotto_categoria" id="id_sotto_categoria" aria-label="Categoria" required >
						<option value=''>Select...</option>
					
					</select>
					<label for="categoria">Sotto Categoria*</label>
					
					<a href="{{ route('sottocategorie_prodotti') }}" class="link-primary" target='_blank' onclick='$("#id_sotto_categoria").find("option").remove().end();$(".up").hide();$("#div_up_scat").show()'>
						Definisci/modifica
					</a>
					<span id='div_up_scat' class='up' style='display:none'>
						<a href='javascript:void(0)' class='ml-2' onclick="$('#id_categoria').attr('selected', 'selected').trigger('change')">
							<font color='green'>
								<i class="fas fa-sync-alt"></i>
							</font>	
						</a>	
					</span>						
				</div>	
			</div>

		</div>






		</form> 
		
 
  

			
		
        <!-- /.row -->
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
	<script src="{{ URL::asset('/') }}dist/js/definizione_articolo.js?ver=1.241"></script>
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