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
<form method='post' action="{{ route('save_newapp') }}" id='save_newapp' name='save_newapp' autocomplete="off" class="needs-validation" novalidate>

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
							DEFINIZIONE DELL'APPALTO
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
					<input class="form-control" id="descrizione_appalto" name='descrizione_appalto' type="text" placeholder="Definizione" required maxlength=150 value="{{$appalti[0]->descrizione_appalto ?? ''}}" />
					<label for="descrizione_appalto">Descrizione Appalto*</label>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-floating">
					<input class="form-control" id="data_app" name='data_app' type="date" required value="{{$appalti[0]->data_ref ?? ''}}" />
					<label for="data_app">Data*</label>
				</div>
			</div>			
		</div>
		
		<div class="row mb-3">
			<div class="col-md-12">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" style='height:auto' name="servizi[]" id="servizi" required multiple>
					
					<?php
						foreach ($servizi as $servizio) {
							$id_servizio=$servizio->id;
							$descr_servizio=$servizio->descrizione;
							echo "<option value='".$id_servizio."' ";
							if (in_array($id_servizio,$id_servizi)) echo " selected ";
							echo ">".$descr_servizio."</option>";
						}
					?>						
					</select>
					<label for="servizi">Servizi*</label>
				</div>
			</div>	
		</div>		

		<center><h4>Ditte in Appalto</h4></center>
			<?php
				$id_ditta_db=0;
				if (isset($appalti[0]->id_ditta))  $id_ditta_db=$appalti[0]->id_ditta;
			?>	

		
			<div id='div_ditta'>
				<div class="row mb-3">
					<div class="col-md-6">
						<div class="form-floating mb-3 mb-md-0">
							<select class="form-select" name="ditta" id="ditta" onchange='popola_lav(this.value)' required>
							<option value=''>Select...</option>
							<?php
								foreach ($ditte as $ditta_ref) {
									$id_ditta=$ditta_ref->id;
									$denominazione=$ditta_ref->denominazione;
									echo "<option value='".$id_ditta."' ";
									if ($id_ditta==$id_ditta_db) echo " selected ";
									echo ">".$denominazione."</option>";
								}
							?>						
							</select>
							<label for="ditta">Ditta*</label>
						</div>
					</div>	
				

				</div>	
				<div class="row mb-3">
					
					<div class="col-md-6">
						<div class="form-floating mb-3 mb-md-0">
							<select class="form-select" name="lavoratori[]" id="lavoratori" required style='height:auto' multiple >
								@foreach($ids_lav as $id_lav=>$nominativo)
									<option value="{{$id_lav}}" selected
									>{{$nominativo}}</option> 
									
								@endforeach
							</select>
							<label for="lavoratori">Lavoratori*</label>
						</div>
					</div>
				</div>
				
			
				<div class="row mb-3">
					<div class="col-md-6">
						<div class="form-floating">					  
							
							<textarea class="form-control" name='note' id="note" rows="4">{{$appalti[0]->note ?? ''}}</textarea>
							<label for="note">Note</label>
						</div>
					</div>	
				</div>
				<hr>
			</div>
			
		
		

        <!-- 
		<div class="row mb-3">
			<div class="col-md-2">
				<button class="btn btn-success" type="button" onclick='add_ditta()'>
					<i class="fas fa-plus-circle"></i> Aggiungi Ditta
				</button>
			</div>			
		</div>
		!-->

		

        <div class="row">

			<button type="submit" name='sub_newcand_onlysave' id='sub_newcand_onlysave' class="btn btn-success btn-lg btn-block">SALVA</button>  
			
			<a href="{{ route('listapp') }}">
				<button type="button"  id='back_appalti' class="btn btn-info btn-lg btn-block mt-3">TORNA AD ELENCO APPALTI</button> 
			</a>


			<input type="hidden" value="{{url('/')}}" id="url" name="url">
			
			<input type="hidden" name="id_app" id="id_app" value="{{$id_app}}">
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
	<script src="{{ URL::asset('/') }}dist/js/newapp.js?ver=1.09"></script>
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