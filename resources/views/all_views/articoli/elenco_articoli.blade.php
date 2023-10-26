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
            <h1 class="m-0">ELENCO PRODOTTI</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			  <li class="breadcrumb-item">Amministrazione</li>
			  <li class="breadcrumb-item">Ufficio Acquisti</li>
			  <li class="breadcrumb-item">Gestioni Prodotti</li>
              <li class="breadcrumb-item active">Elenco Prodotti</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">

		<form method='post' action="{{ route('elenco_articoli') }}" id='frm_articolo' name='frm_articolo' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>


		<div class="row">
			<div class="col-md-6 mb-2">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-control" name="sede_magazzino" id="sede_magazzino" aria-label="Magazzino" onchange="$('#frm_articolo').submit()" >
						<option value=''>[[Tutti]]</option>
						@foreach($magazzini as $magazzino)
							<option value='{{$magazzino->id}}'
							@if ($magazzino->id==$sede_magazzino)
								selected
							@endif
							>{{$magazzino->descrizione}}</option>
						@endforeach
					</select>
					<label for="sede_magazzino">Filtro magazzino</label>
				</div>	
			</div>
		</div>
				

        <div class="row">
          <div class="col-md-12">
		  
				<table id='tbl_articoli' class="display">
					<thead>
						<tr>
							<th>ID</th>
							<th>Categoria</th>
							<th>Sotto Categoria</th>
							<th>Descrizione prodotto</th>
							<th>Fornitori</th>
							<th>Prezzo medio</th>
							@foreach ($magazzini as $magazzino)
								<th class="sum">
									Qta 
									{{$magazzino->descrizione}}
								</th>
							@endforeach
							<th class="sum">Qta</th>
							
							<th>Operazioni</th>
						</tr>
					</thead>
					<tbody>
						@foreach($elenco_articoli as $articolo)
							<tr>
								<td>
								 @if ($articolo->dele=="1") 
									<font color='red'><del> 
								 @endif
									<span id='id_descr{{$articolo->id}}' data-id_ordine=''>
										{{ $articolo->id }}
									</span>	
								 @if ($articolo->dele=="1") 
									 </del></font>
								 @endif	
								</td>	
								
								<td>
									{{ $articolo->categoria }}
								</td>
								<td>
									{{ $articolo->sottocategoria }}
								</td>


								<td>
									{{ $articolo->descrizione }}
								</td>
								
								<td>
								@if (isset($info_prod[$articolo->id]))
									<?php
										for ($sca=0;$sca<=count($info_prod[$articolo->id])-1;$sca++) {
											if ($sca!=0) echo ", ";
											if (isset($arr_forn[$info_prod[$articolo->id][$sca]]))
												echo $arr_forn[$info_prod[$articolo->id][$sca]];
										}
									?>								
								@endif
								</td>
								
								<td>
									{{number_format($articolo->prezzo_medio,2)}}€
								</td>
								
								@php ($giacenza_globale=0)
								@foreach ($magazzini as $magazzino)
									<td>
										<?php
										
											if (isset($info_giacenze[$articolo->id][$magazzino->id])) {
												echo $info_giacenze[$articolo->id][$magazzino->id];
												$giacenza_globale+=$info_giacenze[$articolo->id][$magazzino->id];
											}
										?>
									</td>
								@endforeach
								<td>
									{{$giacenza_globale}}
								</td>




								
								<td>
									@if ($articolo->dele=="0") 
										<a href="{{ route('definizione_articolo',['id'=>$articolo->id]) }}" >
											<button type="button" class="btn btn-info" alt='Edit'><i class="fas fa-edit"></i></button>
										</a>
										<a href='#' onclick="dele_element({{$articolo->id}})">
											<button type="submit" name='dele_ele' class="btn btn-danger"><i class="fas fa-trash"></i></button>	
										</a>
									@endif
									@if ($articolo->dele=="1") 
										<a href='#'onclick="restore_element({{$articolo->id}})" >
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
							<th>Categoria</th>
							<th>Sotto Categoria</th>
							<th>Descrizione prodotto</th>
							<th>Fornitori</th>
							<th>Prezzo medio</th>
							@foreach ($magazzini as $magazzino)
								<th>
									
								</th>
							@endforeach
							<th>Qta</th>
							<th></th>
						</tr>
						
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							@php ($ind_sum=0)
							@foreach ($magazzini as $magazzino)
								@php ($ind_sum++)
								<td id='sum_res{{$ind_sum}}'></td>
							@endforeach
							@php ($ind_sum++)
							<td id='sum_res{{$ind_sum}}'></td>
							<td></td>
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
					<a href="{{ route('definizione_articolo') }}">
						<button type="button" class="btn btn-primary">
							<i class="fa fa-plus-circle"></i> Nuovo Prodotto
						</button>
					</a>

					<div class="form-check form-switch mt-3 ml-3">
					  <input class="form-check-input" type="checkbox" id="view_dele" name="view_dele" onchange="$('#frm_articolo').submit()" {{ $check }}>
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
	
	

	<script src="{{ URL::asset('/') }}dist/js/elenco_prodotti.js?ver=1.087"></script>

@endsection