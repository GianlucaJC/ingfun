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
	
	<form method='post' action="{{ route('ordini_fornitore') }}" id='frm_ordini' name='frm_ordini' autocomplete="off" class="needs-validation" novalidate>
	<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>	
	<input type="hidden" value="{{url('/')}}" id="url" name="url">
	<input type='hidden' name='id_ordine' id='id_ordine' value='{{$id_ordine}}'>
	<input type='hidden' name='dele_riga' id='dele_riga'>

      <div class="container-fluid">
		<div class="row mb-3">
			<div class="col-md-4">
				<div class="form-floating">
					<select class="form-control" name="id_fornitore" id="id_fornitore" aria-label="Fornitore" required>
					<option value=''>Select...</option>
						@foreach ($fornitori as $fornitore)
							<option value='{{$fornitore->id}}'
							<?php
							if (isset($info_ordine[0]->id_fornitore) && $info_ordine[0]->id_fornitore==$fornitore->id) echo " selected ";
						    ?>
							>{{$fornitore->ragione_sociale}}</option>
						@endforeach
					</select>
					<label for="id_fornitore">FORNITORE*</label>
					
					<a href="{{ route('elenco_fornitori') }}" class="link-primary" target='_blank' onclick="
							 $('.up').hide();$('#div_up_forn').show()">
						Definisci/modifica
					</a>					
					<span id='div_up_forn' class='up' style='display:none'>
						<a href='javascript:void(0)' class='ml-2' onclick='refresh_forn()'>
							<font color='green'>
								<i class="fas fa-sync-alt"></i>
							</font>	
						</a>	
					</span>						
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
					<select class="form-control" name="id_sede_consegna" id="id_sede_consegna" aria-label="Sede di consegna" required >
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
					<label for="id_sede_consegna">Sede di consegna*</label>
				</div>	
			</div>

		</div>	

<hr>
		<div class="row">
		  <div class="col-lg-12">
			<table id='tbl_prodotti_ordine' class="display">
				<thead>
					<tr>
						<th>Codice</th>
						<th style='text-align:right'>Qta</th>
						<th style='text-align:right'>Prezzo Unitario</th>
						<th style='text-align:right'>Imponibile</th>
						<th style='text-align:right'>Iva</th>
						<th style='text-align:right'>Subtotale</th>
						<th style='width:200px'>Operazioni</th>
					</tr>
				</thead>
				<body>
					@php($imponibile=0)
					@php($s_iva=0)
					@php($s_totale=0)
					@foreach($prodotti_ordini as $prodotto)
					@php($imponibile+=$prodotto->prezzo_unitario*$prodotto->quantita)
					@php($s_totale+=$prodotto->subtotale)
					@php($s_iva+=$prodotto->subtotale-($prodotto->prezzo_unitario*$prodotto->quantita))
					<tr>
						<td>{{$prodotto->codice_articolo}}</td>
						<td style='text-align:right'>{{$prodotto->quantita}}</td>
						<td style='text-align:right'>{{number_format($prodotto->prezzo_unitario,2)}}€</td>
						<td style='text-align:right'>{{number_format($prodotto->prezzo_unitario*$prodotto->quantita,2)}}€</td>

						<td style='text-align:right'>{{number_format($prodotto->subtotale-($prodotto->prezzo_unitario*$prodotto->quantita),2)}}€</td>
						<td style='text-align:right'>{{number_format($prodotto->subtotale,2)}}€</td>
						
						<td style='width:200px'>

							<!-- riga info per js !-->
							<span id='inforow{{$prodotto->id}}'  data-codice='{{ $prodotto->codice_articolo}}' data-prezzo_unitario='{{$prodotto->prezzo_unitario}}' data-quantita='{{$prodotto->quantita}}' 
							data-subtotale='{{$prodotto->subtotale}}' data-aliquota='{{ $prodotto->aliquota}}|{{$arr_aliquota[$prodotto->aliquota]}}' >
							</span>							
							<a href="javascript:void(0)" onclick="edit_product({{$prodotto->id}})" >
								<button type="button" class="btn btn-info" alt='Edit' title='Modifica riga'><i class="fas fa-edit"></i></button>
							</a>

							<a href='#' onclick="dele_element({{$prodotto->id}})">
								<button type="submit" name='dele_ele' class="btn btn-danger" title='Elimina riga ordine'><i class="fas fa-trash"></i></button>	
							</a>
							
						</td>						
					</tr>
					@endforeach
				</body>
				
				<tfoot>
					<tr>
						<th></th>
						<th></th>
						<th></th>
						<th style='text-align:right'>{{number_format($imponibile,2)}}€</th>
						<th style='text-align:right'>{{number_format($s_iva,2)}}€</th>
						<th style='text-align:right'>{{number_format($s_totale,2)}}€</th>
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
						


	

			<div class="row mb-3 mt-5">
				<div class="col-md-4">
					<button type="submit" name="btn_save_ordine" value="save" class="btn btn-success">Crea/Modifica Ordine</button>
					
					@if ($id_ordine!=0)
						<button type="button" name="btn_add_articolo" value="save" onclick="edit_product(0)"  class="btn btn-primary">Aggiungi articolo</button>
					@endif
					
					
					<a href="{{route('elenco_ordini_fornitori')}}">
						<button type="button" class="btn btn-secondary" >
						Elenco Ordini
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

  
	<form method='post' action="{{ route('ordini_fornitore') }}" id='frm_ordini1' name='frm_ordini1' autocomplete="off" class="needs-validation1" novalidate>
	<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>	
	<input type="hidden" value="{{url('/')}}" id="url" name="url">
	<input type='hidden' name='id_ordine_modal' id='id_ordine_modal' value='{{$id_ordine}}'>
		@include('all_views.fornitori.editmodal')
	</form>


  <!-- /.content-wrapper -->
  
  
  

  
  

  
 @endsection
 
@section('content_plugin')
	<!-- jQuery -->
	<script src="{{ URL::asset('/') }}plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="{{ URL::asset('/') }}plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="{{ URL::asset('/') }}dist/js/adminlte.min.js"></script>
	<script src="{{ URL::asset('/') }}dist/js/ordine_fornitore.js?ver=1.282"></script>
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