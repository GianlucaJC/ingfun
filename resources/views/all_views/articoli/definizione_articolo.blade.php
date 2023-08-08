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
<form method='post' action="{{ route('definizione_articolo') }}" id='frm_fornitore' name='frm_fornitore' autocomplete="off" class="needs-validation" novalidate>
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
					<input class="form-control"  id="codice" name="codice" type="text" placeholder="Codice prodotto"  value="{{$info_articolo[0]->codice ?? ''}}" required maxlength=60 />
					<label for="codice">CODICE PRODOTTO*</label>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-floating">
					<input class="form-control"  id="descrizione" name="descrizione" type="text" placeholder="Descrizione prodotto"  value="{{$info_articolo[0]->descrizione ?? ''}}"  maxlength=60 required />
					<label for="codice">Descrizione prodotto*</label>
				</div>
			</div>

		</div>

		<div class="row mb-3">
			<div class="col-md-6">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-control" name="id_categoria" id="id_categoria" aria-label="Categoria" required >
						<option value=''>Select...</option>
					</select>
					<label for="categoria">Categoria*</label>
				</div>	
			</div>

			<div class="col-md-6">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-control" name="id_sotto_categoria" id="id_sotto_categoria" aria-label="Categoria" required >
						<option value=''>Select...</option>
					</select>
					<label for="categoria">Sotto Categoria*</label>
				</div>	
			</div>

		</div>
		

		<div class="row mb-3">
			<div class="col-md-3">
				<div class="form-floating">
					<input class="form-control"  id="prezzo_acquisto" name="prezzo_acquisto" type="text" placeholder="Prezzo acquisto"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" value="{{$info_articolo[0]->prezzo_acquisto ?? ''}}" required maxlength=11 />
					<label for="prezzo_acquisto">Prezzo acquisto*</label>
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-control" name="aliquota_iva" id="aliquota_iva" aria-label="Aliquota iva" required >
						<option value=''>Select...</option>
						@foreach ($aliquote_iva as $aliquota) 
							<option value='{{$aliquota->id}}|{{$aliquota->aliquota}}'>
								{{$aliquota->aliquota}}% - {{$aliquota->descrizione}}
							</option>	
						@endforeach						
					</select>
					<label for="categoria">Aliquota iva*</label>
				</div>	
			</div>
			
			<div class="col-md-3">
				<div class="form-floating">
					<input class="form-control"  id="giacenza" name="giacenza" type="text" placeholder="Giacenza"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" value="{{$info_articolo[0]->giacenza ?? ''}}" required maxlength=11 />
					<label for="giacenza">Giacenza*</label>
				</div>
			</div>			
			
			<div class="col-md-3">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-control" name="id_magazzino" id="id_magazzino" aria-label="Magazzino di riferimento" required >
						<option value=''>Select...</option>
					</select>
					<label for="id_magazzino">Magazzino di riferimento*</label>
				</div>	
			</div>
			
			


		</div>		
			
		<div class="row mb-3">
			<div class="col-md-4">
				<button type="submit" name="btn_save_articolo" value="save" class="btn btn-success">Crea/Modifica Articolo</button>

				<a href="#">
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
	<script src="{{ URL::asset('/') }}dist/js/definizione_articolo.js?ver=1.238"></script>
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