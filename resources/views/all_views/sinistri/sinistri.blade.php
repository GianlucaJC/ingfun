<?php
$nolog=1;
if (!Auth::user() && $from==0) $nolog=0;

?>

@extends($nolog==1 ? 'all_views.viewmaster.index' : 'all_views.viewmaster.index_sinistri')

@section('title', 'Sinistro')


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
   <div class="content-wrapper" style="background-color:white">
    <!-- Content Header (Page header) -->
    <div class="content-header d-none d-sm-block">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12">  
			<h1 class="m-0">
				<center>
					<font color='red'>
						DEFINIZIONE DEL SINISTRO
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
	@if ($id_appalto==0 && $from==0) 
		@php ($disp="display:none")
		<div class="container-fluid">
			<div class="alert alert-warning" role="alert">
				<b>Attenzione!</b><hr>
				Manca definizione appalto
			</div>
		</div>	
	@endif
	
	@if ($id_appalto==0 && $from==1) 
		@php ($disp="display:none")
		<div class="container" style="padding-left:20px;padding-right:20px">
			<div class="row mb-3">
				<div class="col-md-12">
					<div class="form-floating mb-3 mb-md-0">
						<select class="form-select" name="ref_appalto" id="ref_appalto" onchange="def_sinistro(this.value)" required>
							<option value=''>Select...</option>
							@foreach($last_appalti as $last)
								<option value='{{$last->id}}'>
									{{$last->id}})  
									{{$last->data_ref}} - 			{{$last->orario_ref}} - 
									{{$last->chiesa}}
								</option>
							@endforeach
					
						</select>
						<label for="ref_appalto">Appalto di riferimento*</label>
					</div>
				</div>					
			</div>
		</div>			
	
	@endif
      <div class="container-fluid" style="{{$disp}};padding-left:20px;padding-right:20px;height:1000px">
	  
		<form method='post' action="{{ route('sinistri',[$id_appalto,$from,$id_sinistro]) }}" id='frm_sinistro' name='frm_sinistro' autocomplete="off" class="needs-validation" novalidate>
		<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
		<input type="hidden" value="{{url('/')}}" id="url" name="url">
		<input type="hidden" value="{{$from}}" id="from" name="from">
		<input type='hidden' name='delefoto' id='delefoto'>
		<input type='hidden' name='dele_cid' id='dele_cid'>
		<input type='hidden' name='save_sin' id='save_sin'>
		
		<?php
			$view_main="";
			//if ($id_sinistro!=0) $view_main="display:none";
		?>
		
		<div id='div_main' style="{{$view_main}}">
			<div class="row mb-2">
				<div class="col-6">
					<div class="form-floating">
						<input class="form-control"  id="id_appalto" name="id_appalto" type="text" placeholder="ID appalto"  value="{{$id_appalto ?? ''}}" disabled  />
						<label for="id_appalto">ID Appalto</label>
					</div>
				</div>
				<div class="col-6">
					<div class="form-floating">
						<input class="form-control"  id="responsabile" name="responsabile" type="text" value="{{$responsabile_mezzo ?? ''}}" disabled  />
						<label for="responsabile">Responsabile</label>
					</div>
				</div>

			</div>
			
			<div class="row mb-2">

				<div class="col-6">
					<div class="form-floating">
						<select class="form-control" name="mezzo_coinvolto" id="mezzo_coinvolto" aria-label="da mezzo_coinvolto" required>
							<option value=''>Select...</option>
								@foreach($mezzi as $mezzo)
									<option value='{{$mezzo->id}}'
									
									@if (isset($info_sinistro[0])) 
										@if ($info_sinistro[0]->id_mezzo==$mezzo->id) 
											selected
										@endif
									@else
										@if(isset($allinfo[0]->targa))
											@if ($allinfo[0]->targa==$mezzo->targa) 	selected
											@endif
										@endif
									@endif
									
									>{{$mezzo->targa}}</option>
								@endforeach
						</select>
						<label for="mezzo_coinvolto">Mezzo*</label>
					</div>
				</div>

				

				<div class="col-6">
					<div class="form-floating">
						<input class="form-control" id="dataora" name='dataora' type="datetime-local" required value="{{$info_sinistro[0]->dataora ?? ''}}" />
						<label for="dataora">Data e Ora*</label>
					</div>
				</div>	
			</div>
			

			<div class="row mb-2">

				<div class="col-6">
					<div class="form-floating">
						<select class="form-control" name="mezzo_marciante" id="mezzo_marciante" aria-label="Mezzo marciante" required >
							<option value=''>Select...</option>
								<option value=1
								@if (isset($info_sinistro[0]) && $info_sinistro[0]->mezzo_marciante==1) 
									selected
								@endif
								>SI</option>
								
								<option value=0	
								@if (isset($info_sinistro[0]) && $info_sinistro[0]->mezzo_marciante==0) 
									selected
								@endif
								>NO</option>	
								
						</select>
						<label for="mezzo_marciante">Marciante*</label>
					</div>
				</div>
				<div class="col-6">
					<div class="form-floating">
						<input class="form-control"  id="citta" name="citta" type="text" placeholder="Città"  required value="{{$info_sinistro[0]->citta ?? ''}}" />
						<label for="citta">Città*</label>
					</div>
				</div>
			</div>
			
			<div class="row mb-2">
				<div class="col-3">
					<div class="form-floating">
						<input class="form-control"  id="provincia" name="provincia" type="text" placeholder="Prov" required  value="{{$info_sinistro[0]->provincia ?? ''}}" />
						<label for="provincia">Prov*</label>
					</div>
				</div>

				<div class="col-9">
					<div class="form-floating">
						<input class="form-control"  id="indirizzo" name="indirizzo" type="text" placeholder="Indirizzo"  required value="{{$info_sinistro[0]->indirizzo ?? ''}}" />
						<label for="indirizzo">Indirizzo*</label>
					</div>
				</div>
			</div>

			<div class="row mb-2">
				<div class="col-md-12">
					<div class="form-floating">
						<textarea class="form-control" id="descrizione" name="descrizione" rows="4" required style='height:100px'>{{$info_sinistro[0]->descrizione ?? ''}}</textarea>
						<label for="descrizione">Descrizione del sinistro*</label>
					</div>
				</div>
			</div>
			
		</div>
		<?php
			$lbl_save="Crea Sinistro";
			
			if ($id_sinistro!=0) {
				/*
				echo "<button type='button' id='vision_main' class='btn btn-success btn-lg btn-block' onclick=\"$('#div_main').toggle(150)\">Visiona sinistro</button>";
				*/
				if ($nolog==0)
					$lbl_save="Aggiorna sinistro/Torna elenco foto";
				else
					$lbl_save="Aggiorna sinistro";
			} 
		?>
		@if (($id_sinistro!=0 && $from==1))
			<a href='javascript:void(0)' class='link' onclick="init_allegati({{$id_sinistro}})" >Aggiungi allegati</a>
			<a  href='#' class='link ml-3' onclick='url=window.location.href;location.href=url;'>Refresh allegati</a>
			
			<div class="row mt-3 mb-3" id='div_allegati' style="display:none">
				<div class="col-md-6">
					<div class="form-floating mb-3 mb-md-0">
						<select class="form-select" name="tipo_allegato" id="tipo_allegato" onchange="set_class_allegati.tipo_allegato=this.value">
							<option value='1'>Allegato sinistro
							</option>
							<option value='2'>File CID
							</option>
						</select>
						<label for="tipo_allegato">Tipo di file*</label>
					</div>
				</div>
				<div class="col-md-12">
					<!-- l'upload viene fatto dal plugin  dist/js/upload_sinistri/demo-config.js !-->
					
					<?php include("class_allegati.php"); ?>
				</div>
			</div>

			<div id="div_save" class='mt-2'></div>
			
			<div class="row mt-3 mb-2">
				<div class="col-md-6">
					<div class="row mb-2">
						@foreach($support_sinistri as $support)
							<div class="col-md-2" id='fx{{$support->id}}'>
								<a href="{{ URL::asset('/') }}dist/upload/sinistri/{{$support->filename}}" target='_blank' >
								<?php if (strpos($support->filename,"pdf"))
									echo "<i class='fas fa-file-pdf fa-lg'></i> Apri file ";
								else {?>							
									
									<img src="{{ URL::asset('/') }}dist/upload/sinistri/thumbnail/medium/{{$support->filename}}" class="img-fluid img-thumbnail">
								 <?php } ?>	
								</a>
								
								<label>
								<a href="javascript:void(0)" onclick='dele_fx({{$support->id}})'>
									<i class="fas fa-trash-alt fa-xs"></i>
								</a>
								</label>
							</div>
							
						@endforeach
					</div>
					<hr>
					@if (isset($info_sinistro[0]) && strlen($info_sinistro[0]->file_cid)>0)
						<a href="{{ URL::asset('/') }}dist/upload/sinistri/{{$info_sinistro[0]->file_cid}}" target='_blank' >
						<?php
						if (strpos($info_sinistro[0]->file_cid,"pdf"))
							echo "<i class='fas fa-file-pdf fa-lg'></i> Apri file ";
						else { 
							?>
							<img src="{{ URL::asset('/') }}dist/upload/sinistri/thumbnail/medium/{{$info_sinistro[0]->file_cid}}" class="img-fluid img-thumbnail">
							<?php } ?>
						</a>
						<label>Cid</label>
						<a href="javascript:void(0)" onclick="dele_cid({{$id_sinistro}})">
							<i class="fas fa-trash-alt fa-xs"></i>
						</a>						
					@endif
				</div>
			</div>	
			
			<hr>
			
			
		@endif	

		
		
		
		<!--
			onclick="save()" 
			disabilitato perchè su iOS non viene eseguita JS nella webview
		!-->	
		<button type="submit" id="btn_save" onclick="save()"  name="btn_save" class="btn btn-primary btn-lg btn-block" value="save">{{$lbl_save}}</button>




		</form> 
		
 
  

			
		
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>




  

  
  

  
 @endsection
 
@section('content_plugin')
	<!-- jQuery -->
	<script src="{{ URL::asset('/') }}plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="{{ URL::asset('/') }}plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="{{ URL::asset('/') }}dist/js/adminlte.min.js"></script>
	<script src="{{ URL::asset('/') }}dist/js/sinistri.js?ver=1.029"></script>
	<!--select2 !-->
	<script src="{{ URL::asset('/') }}plugins/select2/js/select2.full.min.js"></script>
	
	<!-- per upload -->
	<script src="{{ URL::asset('/') }}dist/js/upload_sinistri/jquery.dm-uploader.min.js"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload_sinistri/demo-ui.js?ver=1.24"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload_sinistri/demo-config.js?ver=2.363"></script>
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