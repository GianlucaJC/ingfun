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
  <link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet">

@endsection


@section('content_main')
<form method='post' action="{{ route('definizione_articolo') }}" id='frm_articolo' name='frm_articolo' autocomplete="off" class="needs-validation" novalidate>
<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
<input type="hidden" value="{{url('/')}}" id="url" name="url">
<input type='hidden' name='id_articolo' id='id_articolo' value='{{$id_articolo}}'>

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
						DEFINIZIONE ARTICOLO
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
		<div class="row mb-3">
			<div class="col-md-6">
				<div class="form-floating">
					<input class="form-control"  id="id_prodotto" name="codice" type="text" placeholder="ID prodotto"  value="{{$info_articolo[0]->id ?? ''}}" disabled  />
					<label for="codice">ID</label>
				</div>
			</div>


		</div>
		
		<div class="row mb-3">

			<div class="col-md-6">
				<div class="form-floating">
					<input class="form-control"  id="descrizione" name="descrizione" type="text" placeholder="Descrizione prodotto"  value="{{$info_articolo[0]->descrizione ?? ''}}"  maxlength=60 required />
					<label for="codice">Descrizione prodotto*</label>
				</div>
			</div>


			<div class="col-md-3">
				<div class="form-floating">
					<input class="form-control"  id="um_conf" name="um_conf" type="text" placeholder="Confezionamento"  value="{{$info_articolo[0]->um_conf ?? ''}}" />
					<label for="giacenza">U.M./Confez.</label>
				</div>

			</div>
			
			<div class="col-md-3">
				<div class="form-floating">
					<input class="form-control"  id="um" name="um" type="text" placeholder="Unità di misura" value="{{$info_articolo[0]->um ?? ''}}" />
					<label for="um">Unità di misura</label>
				</div>
			</div>


		</div>
		

		<div class="row mb-3">

			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control"  id="in_arrivo" name="in_arrivo" type="text" placeholder="In arrivo"  disabled />
					<label for="in_arrivo">In arrivo</label>
				</div>
			</div>


			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control"  id="data_stimata_arrivo" name="data_stimata_arrivo" type="text" placeholder="Confezionamento" disabled />
					<label for="data_stimata_arrivo">Data stimata arrivo</label>
				</div>

			</div>
			
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


		</div>
		

		<dmoreomreiv class="row mb-3">
			<div class="col-md-6">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-control" name="id_categoria" id="id_categoria" aria-label="Categoria" required onchange='load_sc(this.value)'>
						<option value=''>Select...</option>
						@foreach ($categorie as $categoria) 
							<option value='{{$categoria->id}}'
							 @if(isset($info_articolo[0]->id_categoria) && $categoria->id==$info_articolo[0]->id_categoria)  selected
							 @endif
							>{{$categoria->descrizione}}
							</option>	
						@endforeach
					</select>
					<label for="categoria">Categoria*</label>
				</div>	
			</div>

			<div class="col-md-6">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-control" name="id_sotto_categoria" id="id_sotto_categoria" aria-label="Categoria" required >
						<option value=''>Select...</option>
						@foreach ($sotto_categorie as $sottocategoria) 
							<option value='{{$sottocategoria->id}}'
							 @if(isset($info_articolo[0]->id_sottocategoria) && $sottocategoria->id==$info_articolo[0]->id_sottocategoria)  selected
							 @endif
							>{{$sottocategoria->descrizione}}
							</option>	
						@endforeach						
					</select>
					<label for="categoria">Sotto Categoria*</label>
				</div>	
			</div>

		</div>
		

		<div class="row mb-3">

			<!--
				l'elenco dei magazzini di riferimento sarà caricato dinamicamente
				in funzione dell'evasione dell'ordine e quindi dell'assegnazione ai vari magazzini
			!-->
			<div class="col-md-6">
				<div class="form-floating">
					<input class="form-control"  id="magazzino_ref1" name="magazzino_ref1" type="text" placeholder="Magazzino di riferimento" disabled  />
					<label for="giacenza">Magazzino di riferimento</label>
				</div>

			</div>
			
			<div class="col-md-6">
				<div class="form-floating">
					<input class="form-control"  id="qta_magazzino" name="giacenza" type="text" placeholder="Giacenza in magazzino" disabled  />
					<label for="giacenza">Qta magazzino</label>
				</div>
			</div>


		</div>		
			
		<div class="row mb-3">
			<div class="col-md-4">
				<button type="submit" name="btn_save_articolo" value="save" class="btn btn-success">Crea/Modifica Articolo</button>

				<a href="{{ route('elenco_articoli') }}">
					<button type="button" class="btn btn-secondary" >
					Elenco Articoli
					</button>
				</a>
				
			</div>	
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
	<script src="{{ URL::asset('/') }}dist/js/definizione_articolo.js?ver=1.240"></script>
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