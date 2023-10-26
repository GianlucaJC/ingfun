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
            <h1 class="m-0">EVASIONE ORDINI FORNITORI</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			  <li class="breadcrumb-item">Amministrazione</li>
			  <li class="breadcrumb-item">Ufficio Acquisti</li>
			  <li class="breadcrumb-item">Gestioni Fornitori</li>
              <li class="breadcrumb-item active">Ordini Fornitori</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">

		<form method='post' action="{{ route('elenco_ordini_fornitori') }}" id='frm_ordine' name='frm_ordine' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>


        <div class="row">
          <div class="col-md-12">

				<table id='tbl_ordine' class="display">
					<thead>
						<tr>
							<th>ID</th>
							<th>Azienda di proprietà</th>
							<th>Data ordine</th>
							<th>Magazzino</th>
							<th>Fornitori</th>
							<th>Stato</th>
							<th>Operazioni</th>
						</tr>
					</thead>
					<tbody>
						@foreach($elenco_ordini as $ordine)
							<tr>
								<td>
								 @if ($ordine->dele=="1") 
									<font color='red'><del> 
								 @endif
									<span id='id_descr{{$ordine->id}}' data-id_ordine=''>
										{{ $ordine->id }}
									</span>	
								 @if ($ordine->dele=="1") 
									 </del></font>
								 @endif	
								</td>	

								<td>
									{{ $ordine->azienda_proprieta }}
								</td>								
								<td>
									{{ $ordine->data_ordine_it }}
								</td>
								
								<td>
									<?php
										if (isset($magazzini[$ordine->id_sede_consegna]))
											echo $magazzini[$ordine->id_sede_consegna];
									?>
								</td>
								<td>
								<?php
									if (isset($info_fornitori[$ordine->id])) {

										for ($ss=0;$ss<=count($info_fornitori[$ordine->id])-1;$ss++) {
											if ($ss>0) echo ", ";
											if (isset($arr_forn[$info_fornitori[$ordine->id][$ss]]))
												echo $arr_forn[$info_fornitori[$ordine->id][$ss]];
										}
									}
								?>
								</td>

								<td>
								
									<?php
									$stato_ordine=$ordine->stato_ordine;
									$stato="Bozza";
									if ($stato_ordine==1) $stato="Ordinato";
									if ($stato_ordine==2) $stato="Concluso";
									?>
									{{ $stato }}

								</td>
								
								<td>
									@if ($ordine->dele=="0") 
										
										
										<a href="{{ route('ordini_fornitore',['id'=>$ordine->id]) }}" >
											<button title="Modifica ordine fornitore" type="button" class="btn btn-info" alt='Edit'><i class="fas fa-edit"></i></button>
										</a>
										
										
										<a href="{{ route('evasione_ordini',['id'=>$ordine->id]) }}" >
											<button title="Procedura evasione ordine" type="button" class="btn btn-success" alt='Evasione'><i class="fas fa-tasks"></i></button>
										</a>
										

										
										<a href='#' onclick="dele_element({{$ordine->id}})">
											<button title="Elimina ordine fornitore" type="submit" name='dele_ele' class="btn btn-danger"><i class="fas fa-trash"></i></button>	
										</a>
										
									@endif
									@if ($ordine->dele=="1") 
										<a href='#'onclick="restore_element({{$ordine->id}})" >
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
							<th>Azienda di proprietà</th>
							<th>Data ordine</th>
							<th>Magazzino</th>
							<th>Fornitori</th>
							<th>Stato</th>
							<th></th>
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
					<a href="{{ route('ordini_fornitore') }}">
						<button type="button" class="btn btn-primary">
							<i class="fa fa-plus-circle"></i> Nuovo ordine fornitore
						</button>
					</a>

					<div class="form-check form-switch mt-3 ml-3">
					  <input class="form-check-input" type="checkbox" id="view_dele" name="view_dele" onchange="$('#frm_ordine').submit()" {{ $check }}>
					  <label class="form-check-label" for="view_dele">Mostra anche elementi eliminati</label>
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
	
	

	<script src="{{ URL::asset('/') }}dist/js/elenco_ordini_fornitori.js?ver=1.01"></script>

@endsection