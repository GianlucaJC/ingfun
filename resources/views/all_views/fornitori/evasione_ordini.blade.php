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
	
	<form method='post' action="{{ route('evasione_ordini') }}" id='frm_ordini' name='frm_ordini' autocomplete="off" class="needs-validation" novalidate>
	<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>	
	<input type="hidden" value="{{url('/')}}" id="url" name="url">
	<input type='hidden' name='id_ordine' id='id_ordine' value='{{$id_ordine}}'>
	
	<input type='hidden' name='id_magazzino' id='id_magazzino' value="{{$info_ordine[0]->id_sede_consegna ?? ''}}">
	
	<input type='hidden' name='dele_riga' id='dele_riga'>

      <div class="container-fluid">
		<div class="row mb-3">
			<div class="col-md-3">
				<div class="form-floating">
					<input class="form-control" id='id_azienda_proprieta'
					type="text" disabled  value="{{$info_ordine[0]->azienda_proprieta ?? ''}}"/>
					<label for="id_azienda_proprieta">Azienda di proprietà</label>
				</div>
			</div>	
			<div class="col-md-3">
				<div class="form-floating">
					<input class="form-control" id='id_ord'
					type="text" disabled  value="{{$id_ordine}}" />
					<label for="id_ord">ID Ordine</label>
				</div>
			</div>		

			<div class="col-md-3">
				<div class="form-floating">
					<input class="form-control dp" 
					name="data_ordine" id="data_ordine" type="date" disabled  value="{{$info_ordine[0]->data_ordine ?? ''}}" />
					<label for="data_ordine">Data ordine</label>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-floating">
					<input class="form-control dp" 
					name="data_presunta_arrivo_merce" id="data_presunta_arrivo_merce" type="date"   value="{{$info_ordine[0]->data_presunta_arrivo_merce ?? ''}}" disabled />
					<label for="data_presunta_arrivo_merce">Data presunta arrivo merce</label>
				</div>
				
			</div>
		</div>
			

		<div class="row mb-3">
			<div class="col-md-6">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-control" name="stato_ordine" id="stato_ordine" aria-label="Stato ordine" disabled >
						<option value='0'
						<?php
							if (isset($info_ordine[0]->stato_ordine) && $info_ordine[0]->stato_ordine==0) 
								echo " selected ";
						?>	
						>Bozza</option>
						<option value='1'
						<?php
							if (isset($info_ordine[0]->stato_ordine) && $info_ordine[0]->stato_ordine==1) 
								echo " selected ";
						?>
						>Ordinato</option>
					</select>
					<label for="stato_ordine">Stato ordine</label>
				</div>	
			</div>

			<div class="col-md-6">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-control" name="id_sede_consegna" id="id_sede_consegna" aria-label="Sede di consegna" disabled >
						<option value=''>Select...</option>
						@foreach($magazzini as $magazzino)
							<option value='{{$magazzino->id}}'
						<?php
							if (isset($info_ordine[0]->id_sede_consegna) && $info_ordine[0]->id_sede_consegna==$magazzino->id) 
								echo " selected ";
						?>
							
							>{{$magazzino->descrizione}}</option>
						@endforeach
					</select>
					<label for="id_sede_consegna">Sede di consegna</label>
				</div>	
			</div>

		</div>	

<hr>

		@php($disp_view="display:block")
		@if (\Session::has('evasione_ok'))
			@php($disp_view="display:none")
			<div class="alert alert-success" role="alert">
				<b>Operazione effettuata!</b><br>
				<small>Le quantità sono state correttamente evase</small>
			
				<?php
				$referer = $_SERVER['HTTP_REFERER'] ?? null;
					if (strlen($referer)!=0) {
						echo "<hr><a href='$referer' class='nav-link'>";
							echo "<button type='button' class='btn btn-primary btn-sm'>Clicca quì per una nuova evasione sull'ordine</button>";
						echo "</a>";
						?><hr>
						<a href="{{route('elenco_ordini_fornitori')}}">
							<button type="button" class="btn btn-secondary btn-sm" >
							Elenco evasione ordini
							</button>
						</a>
						<?php
					}	
				?>

				
			</div>
		@endif

		<div class="row" style='{{$disp_view}}'>
		  <div class="col-lg-12">
			<table id='tbl_prodotti_ordine' class="display" style='width:60%'>
				<thead>
					<tr>
						<th>Codice</th>
						<th>Fornitore</th>
						<th>Prodotto</th>
						<th style='text-align:right'>Qta Ordinata</th>
						<th style='text-align:right'>Qta già evasa</th>
						<th style='width:200px'>Qta da evadere</th>
					</tr>
				</thead>
				<body>
					
					
					
					@foreach($prodotti_ordini as $prodotto)

					
					<?php
					$id_ref_articolo=$prodotto->id_fornitore."-".$prodotto->codice_articolo;
					?>
					
					<tr>
						<td>
						<input type='hidden' name='id_prod[]' value="{{$prodotto->codice_articolo}}">
						<input type='hidden' name='id_forn[]' value="{{$prodotto->id_fornitore}}">


							{{$prodotto->codice_articolo}}

						</td>



						<td>
							<?php
								if (isset($arr_forn[$prodotto->id_fornitore])) {
									echo $arr_forn[$prodotto->id_fornitore];
								}
							?>
						</td>						
						<td>
							<?php
								if (isset($arr_prod[$prodotto->codice_articolo])) {
									echo $arr_prod[$prodotto->codice_articolo];
								}
							?>
						</td>




						<td style='text-align:right'>{{$prodotto->quantita}}
						</td>


						<td style='text-align:right'>
							<?php
								$gia_evasa=0;
								if (isset($info_movimenti[$prodotto->codice_articolo][$prodotto->id_fornitore])) {
									$gia_evasa=$info_movimenti[$prodotto->codice_articolo][$prodotto->id_fornitore];
								}	
								if ($gia_evasa>0) echo $gia_evasa;
							?>
							<input type='hidden' class='ctrl_qta' name='ctrl_qta[]' value="{{$prodotto->quantita}}-{{$gia_evasa}}" data-id_ref_articolo='qta_e{{$id_ref_articolo}}'>
						</td>

						<td style='width:200px'>
							@php($dis="disabled")
						
							@if ($prodotto->quantita>$gia_evasa)
								@php ($dis="")
							@endif
							<input type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" class="form-control qta_e" name="qta_evasa[]" placeholder="Quantità" {{$dis}} id='qta_e{{$id_ref_articolo}}' />
							
						</td>
						
					</tr>
					@endforeach
				</body>
				
				<tfoot>
					<tr>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th style='width:200px'></th>

					</tr>
				</tfoot>
				
				
				<!--
				<tr class='tfoot1'>
					<th>Codice</th>
					<th>Qta</th>
					<th>Prezzo Unitario</th>
					<th>Aliquota</th>
					<th>Subtotale</th>
				</tr>
				!-->
				
				
			</table>
		   </div>	
		</div>		
						

			<div class="row mb-3 mt-5" style='{{$disp_view}}'>
				<div class="col-md-4">
					<?php
						$dis_btn="";
						if (isset($info_ordine[0]->stato_ordine) && $info_ordine[0]->stato_ordine==2) $dis_btn="disabled";
					?>
					<button type="submit" onclick="evasione()" name="btn_save_qta" value="save" class="btn btn-success" {{$dis_btn}}>Evadi le quantità indicate</button>
					
					
					<a href="{{route('elenco_ordini_fornitori')}}">
						<button type="button" class="btn btn-secondary" >
						Elenco evasione ordini
						</button>
					</a>
					
				</div>	
			</div>	
		
        <!-- /.row -->
      </div><!-- /.container-fluid -->
			
	</form>
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
	<script src="{{ URL::asset('/') }}dist/js/evasione_ordini.js?ver=1.007"></script>
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