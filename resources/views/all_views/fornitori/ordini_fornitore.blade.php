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
<form method='post' action="{{ route('scheda_fornitore') }}" id='frm_fornitore' name='frm_fornitore' autocomplete="off" class="needs-validation" novalidate>
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
						ORDINE FORNITORE
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
	<input type='hidden' name='id_fornitore' id='id_fornitore' value='{{$id_fornitore}}'>
	<input type='hidden' name='id_ordine' id='id_ordine' value='{{$id_ordine}}'>

      <div class="container-fluid">
		<div class="row mb-3">
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control"  id="ragione_sociale" name="ragione_sociale" type="text" placeholder="Ragione sociale"  value="{{$info_ordine[0]->ragione_sociale ?? ''}}"  maxlength=60 disabled />
					<label for="ragione_sociale">FORNITORE</label>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control dp" 
					name="data_ordine" id="data_ordine" type="date" required  value="{{$info_ordine[0]->data_ordine ?? ''}}" />
					<label for="data_ordine">Data ordine*</label>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control dp" 
					name="data_presunta_arrivo_merce" id="data_presunta_arrivo_merce" type="date"   value="{{$info_ordine[0]->data_presunta_arrivo_merce ?? ''}}" />
					<label for="data_presunta_arrivo_merce">Data presunta arrivo merce</label>
				</div>
				
			</div>
		</div>
			

		<div class="row mb-3">
			<div class="col-md-6">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-control" name="stato_ordine" id="stato_ordine" aria-label="Stato ordine" >
						<option value=''>Select...</option>
					</select>
					<label for="stato_ordine">Stato ordine</label>
				</div>	
			</div>

			<div class="col-md-6">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-control" name="id_sede_consegna" id="id_sede_consegna" aria-label="Sede di consegna" >
						<option value=''>Select...</option>
					</select>
					<label for="id_sede_consegna">Sede di consegna</label>
				</div>	
			</div>

		</div>	


	

			<div class="row mb-3 mt-5">
				<div class="col-md-4">
					<button type="submit" name="btn_save_fornitore" value="save" class="btn btn-success">Crea/Modifica Ordine</button>
					
					<button type="button" name="btn_save_fornitore" value="save" onclick="edit_product()"  class="btn btn-primary">Aggiungi articolo</button>
					<a href="#">
						<button type="button" class="btn btn-secondary" >
						Elenco Ordini
						</button>
					</a>
					
				</div>	
			</div>	
		
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>

  @include('all_views.fornitori.editmodal')


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
	<script src="{{ URL::asset('/') }}dist/js/ordine_fornitore.js?ver=1.236"></script>
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