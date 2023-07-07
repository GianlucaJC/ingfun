@extends('all_views.viewmaster.index')

@section('title', 'IngFUN')
@section('extra_style') 
<!-- x button export -->

<link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet">
<!-- -->
@endsection



<style>
	tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }
</style>
@section('content_main')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">ELENCO PREVENTIVI</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			  <li class="breadcrumb-item">Amministrazione</li>
			  <li class="breadcrumb-item active">Elenco preventivi</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">

		<form method='post' action="{{ route('lista_preventivi') }}" id='frm_servizi' name='frm_servizi' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
        <div class="row">
          <div class="col-md-12">
		  
				<table id='tbl_list_preventivi' class="display">
					<thead>
						<tr>
							<th style='text-align:center'>ID</th>
							<th>Data</th>
							<th>Sezionale</th>
							<th>Cliente</th>
							<th>Stato</th>
							<th>Totale</th>
							<th style='width:200px'>Operazioni</th>
						</tr>
					</thead>
					<tbody>
						@foreach($preventivi as $preventivo)
							<tr>
								<td style='text-align:center'>{{$preventivo->id}}</td>
								<td>
									{{$preventivo->data_preventivo}}
								</td>
								<td>
									{{$preventivo->sezionale}}
								</td>
								
								
								<td>
									 @if ($preventivo->dele=="1") 
										<font color='red'><del> 
									 @endif
										{{$preventivo->denominazione}}
										
									 @if ($preventivo->dele=="1") 
										 </del></font>
									 @endif									
								</td>
								<td>
									@if ($preventivo->status==2)
										<i>Bozza</i>
									@elseif($preventivo->status==3)
										<i>Elaborato</i>
									@elseif($preventivo->status==4)
										<i>Accettato</i>
									@endif	
								</td>
								<td>
									<?php
										echo number_format($preventivo->totale,2)." €";
									?>
								</td>
								<td style='width:200px'>
									
									@if ($preventivo->dele=="0") 
										<a href="{{ route('preventivo',['id'=>$preventivo->id]) }}">
											<button type="button" class="btn btn-info" alt='Edit' title='Modifica preventivo'><i class="fas fa-edit"></i></button>
										</a>
										@if ($preventivo->status>0)
										<a href="allegati/preventivi/{{$preventivo->id}}.pdf?ver=<?php echo time();?>" target='_blank'>
											<button type="button" class="btn btn-secondary" alt='Pdf' title='apri file pdf'><i class="fas fa-file-pdf" ></i></button>
										</a>
											@if ($preventivo->status>=2)
												<a href='javascript:void(0)'  onclick='change_state({{$preventivo->id}})'>
												<button type="button" class="btn btn-warning" alt='Status' title='Cambio stato'><i class="fas fa-cog"></i></button>
												</a>
											@endif

										
										@endif
										<a href='#' onclick="dele_element({{$preventivo->id}})">
											<button type="submit" name='dele_ele' class="btn btn-danger" title='Elimina preventivo'><i class="fas fa-trash"></i></button>	
										</a>
									@endif
									@if ($preventivo->dele=="1") 
										<a href='#'onclick="restore_element({{$preventivo->id}})" >
											<button type="submit" class="btn btn-warning" alt='Restore'><i class="fas fa-trash-restore"></i></button>
										</a>
									@endif
									
									
								</td>	
							</tr>
						@endforeach
						
					</tbody>
					<tfoot>
						<tr>
							
							<th>ID</th>
							<th>Data</th>
							<th>Sezionale</th>
							<th>Cliente</th>
							<th>Stato</th>
							<th>Totale</th>
							<th style='width:200px'></th>
						</tr>
					</tfoot>					
				</table>
				<input type='hidden' id='dele_contr' name='dele_contr'>
				<input type='hidden' id='restore_contr' name='restore_contr'>
			
          </div>

        </div>
		<?php
		
			$check="";
			if ($view_dele=="1") $check="checked";
		?>
			<div class="row">
			    <div class="col-lg-12">
					<a href="{{ route('preventivo') }}">
						<button type="button" class="btn btn-primary" >
							<i class="fa fa-plus-circle"></i> Nuovo preventivo
						</button>
					</a>
					<div class="form-check form-switch mt-3 ml-3">
					  <input class="form-check-input" type="checkbox" id="view_dele" name="view_dele" onchange="$('#frm_servizi').submit()" {{ $check }}>
					  <label class="form-check-label" for="view_dele">Mostra anche elementi eliminati</label>
					</div>
				</div>
			</div>	
			
			<!-- Modal in form-->
			<div class="modal fade bd-example-modal-lg" id="modal_body" tabindex="-1" role="dialog" aria-labelledby="info" aria-hidden="true">
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
	<script src="plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="dist/js/adminlte.min.js"></script>


	
	<!-- inclusione standard
		per personalizzare le dipendenze DataTables in funzione delle opzioni da aggiungere: https://datatables.net/download/
	!-->
	
	<!-- dipendenze DataTables !-->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.css"/>
		 
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.js"></script>
	<!-- fine DataTables !-->
	
	

	<script src="{{ URL::asset('/') }}dist/js/lista_preventivi.js?ver=1.161"></script>

@endsection