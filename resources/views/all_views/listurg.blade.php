@extends('all_views.viewmaster.index')

@section('title', 'IngFUN')

@section('extra_style') 
<!-- x button export -->

<link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet">
<!-- -->
@endsection



<style>
<!-- crea problemi con il footer di fine pagina !-->
<!-- 
	foot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }
!-->	
</style>
@section('content_main')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style='background-color:white'>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">LISTA URGENZE</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			  <li class="breadcrumb-item active">Amministrazione</li>
			  <li class="breadcrumb-item active">Appalti</li>
			  
              <li class="breadcrumb-item active">Lista Urgenze</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">

		<div class="row">
			<?php
			
				if (!empty($num_send) && $num_send>0) {
					$txt="Solleciti inviati!";
					if ($num_send==1) $txt="Sollecito inviato!";
					echo "<div class='alert alert-success' role='alert'>";
					  echo "<b>$txt</b>";
					echo "</div>";
				}
			?>
		</div>
	  
		<form method='post' action="{{ route('listurg') }}" id='frm_urg' name='frm_urg' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>	  
			<div class="row">
			  <div class="col-lg-12">
				
								
				<table id='tbl_list_rep' class="display">
					<thead>
						<tr>
							<th>Operazioni</th>
							<th>Nominativo</th>
							<th>DataOra</th>
							<th>Ditta</th>
						</tr>
					</thead>
					<tbody>
						
						@foreach($urgenze as $urgenza)
							<tr>

								<td>
									@if ($urgenza->dele=="0") 
										<a href="{{ route('newurg',['id'=>$urgenza->id])}}" >
											<button type="button" class="btn btn-info" alt='Edit' title="Modifica Urgenza"><i class="fas fa-edit"></i></button>
										</a>
									@endif								
									@if ($urgenza->dele=="0") 
									<a href='#' onclick="dele_element({{$urgenza->id}})">
										<button type="submit" name='dele_ele' class="btn btn-danger" title="Cancella Urgenza"><i class="fas fa-trash"></i></button>
									</a>
									@endif
								
									@if ($urgenza->dele=="1")
									<a href='#'onclick="restore_element({{$urgenza->id}})" >
										<button type="submit" class="btn btn-warning" alt='Restore'><i class="fas fa-trash-restore" title="Ripristina"></i></button>
									</a>
									@endif

									
									
									<a href='#' onclick="push_urg({{$urgenza->id_user}})" >
										<button type="submit" class="btn btn-warning" alt='Sollecito'><i class="fas fa-share-square" title="Invia Sollecito"></i></button>
									</a>


									
								</td>									
									
								<td>
									<?php 
									$colo="yellow";
									if ($urgenza->status==0) {
										$back="yellow";$colo="black";
									}
									if ($urgenza->status==1) {
										$back="green";$colo="white";
									}
									if ($urgenza->status==2) {
										$back="red";$colo="white";
									}
									?>
									
									<div style='inline;background-color:{{$back}};color:{{$colo}}'>
										{{$urgenza->nominativo}}
									</div>								
									
								</td>
									
								<td>
									{{$urgenza->data_it}}
								</td>
								
									
								<td>
                                    <?php
                                        if (isset($info_d[$urgenza->id_ditta]))
                                            echo $info_d[$urgenza->id_ditta];                                  ?>    
								</td>
		
								

							</tr>
						@endforeach

					</tbody>
					<tfoot>
						<tr>
							<th></th>
							<th>Nominativo</th>
							<th>Data</th>
							<th>Ditta</th>
						</tr>
					</tfoot>					
				</table>
					
			  </div>
			  
			  

			</div>
			<!-- /.row -->

			<div class="row">
				<div class="col-lg-12">
					<a href="{{ route('newurg',['id'=>0]) }}" class="nav-link active">
						<button type="button" class="btn btn-primary btn-lg btn-block">Definisci Nuova Urgenza</button>
					</a>
				</div>
			</div>
			<?php
				$check="";
				if ($view_dele=="1") $check="checked";
			?>
			<div class="row">
				<div class="col-lg-12">
					<div class="form-check form-switch mt-3 ml-3">
					  <input class="form-check-input" type="checkbox" id="view_dele" name="view_dele" onchange="$('#frm_urg').submit()" {{ $check }}>
					  <label class="form-check-label" for="view_dele">Mostra anche Urgenze eliminate</label>
					</div>
				</div>
			</div>	
			<input type='hidden' id='dele_cand' name='dele_cand'>
			<input type='hidden' id='restore_cand' name='restore_cand'>
			<input type='hidden' id='push_urgenza' name='push_urgenza'>
		</form>
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

	<!-- inclusione standard
		per personalizzare le dipendenze DataTables in funzione delle opzioni da aggiungere: https://datatables.net/download/
	!-->
	
	<!-- dipendenze DataTables !-->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.css"/>
		 
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.js"></script>
	<!-- fine DataTables !-->


	<script src="{{ URL::asset('/') }}dist/js/listurg.js?ver=1.05"></script>

@endsection