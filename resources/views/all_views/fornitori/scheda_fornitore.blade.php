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
						SCHEDA FORNITORE
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
      <div class="container-fluid">
		<div class="row mb-3">

			<div class="col-md-12">
				<div class="form-floating">
					<input class="form-control"  id="ragione_sociale" name="ragione_sociale" type="text" placeholder="Ragione sociale"  value="{{$ragione_sociale[0]->ragione_sociale ?? ''}}" required maxlength=60  />
					<label for="targa">RAGIONE SOCIALE*</label>
				</div>
			</div>
		</div>	
		<div class="row mb-3">	
			<div class="col-md-6">
				<div class="form-floating">
					<input class="form-control" id="partita_iva" name='partita_iva' type="text" required value="{{$info_fornitore[0]->partita_iva ?? ''}}" maxlength=16 oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" />
					<label for="partita_iva">Partita iva*</label>
				</div>
			</div>			
			<div class="col-md-6">
				<div class="form-floating">
					<input class="form-control" id="codice_fiscale" name='codice_fiscale' type="text" required value="{{$info_fornitore[0]->codice_fiscale ?? ''}}" maxlength=16  />
					<label for="codice_fiscale">Codice fiscale*</label>
				</div>
			</div>			

		</div>

		<div class="row mb-3">
			<div class="col-md-12">
				<div class="form-floating">
					<input class="form-control"  id="indirizzo" name="indirizzo" type="text" placeholder="Indirizzo"  value="{{$info_fornitore[0]->indirizzo ?? ''}}" maxlength=60  />
					<label for="indirizzo">Indirizzo</label>
				</div>
			</div>
		</div>			


		<div class="row mb-3">
		
			<div class="col-md-6">
				<div class="form-floating mb-3 mb-md-0">

					<select class="form-control" name="comune" id="comune" aria-label="Comune"  onchange='popola_cap_pro(this.value)'>
						<option value=''>Select...</option>
						<option value="--|--"
						>Altro</option>

						<?php
						
						foreach ($all_comuni as $comuni) {
							$prov=$comuni->provincia;		
							$cap=$comuni->cap;
							$comune=$comuni->comune;
							$value=$cap."|".$prov;
							echo "<option value='$value' ";
							//if ($candidati[0]['comune']==$value) echo " selected ";
							echo ">".$comune."</option>";
						}
						?>
					</select>

					
					<label for="comune">Comune</label>
				</div>	
			</div>

			<div class="col-md-3">
				<div class="form-floating">
					<input class="form-control" id="cap" name='cap' type="text" placeholder="C.A.P."   maxlength=5 value=""  />
					<label for="cap">Cap</label>
				</div>
			</div>
			
			<div class="col-md-3">
				<div class="form-floating">
					<input class="form-control" id="provincia" name='provincia' type="text" placeholder="Provincia"   maxlength=10 value=""  />
					<label for="provincia">Provincia</label>
				</div>
			</div>

		</div>
	
		<div class="row mb-3">
			<div class="col-md-3">
				<div class="input-group mb-3">
				  <div class="input-group-prepend">
					<span class="input-group-text" id="basic-addon4">@ PEC</span>
				  </div>
				  <input type="email" class="form-control" placeholder="Pec" id="pec" name='pec' maxlength=150>
				</div>			
			</div>
			
			<div class="col-md-3">
				<div class="input-group mb-3">
				  <div class="input-group-prepend">
					<span class="input-group-text" id="basic-addon5">Telefono</span>
				  </div>
				  <input type="text" class="form-control" placeholder="Telefono" id="telefono" name='telefono' maxlength=50>
				</div>			
			</div>

			<div class="col-md-3">
				<div class="input-group mb-3">
				  <div class="input-group-prepend">
					<span class="input-group-text" id="basic-addon6">SDI</span>
				  </div>
				  <input type="text" class="form-control" placeholder="sdi" id="sdi" name='sdi' maxlength=10>
				</div>			
			</div>
			<div class="col-md-3">
				<div class="input-group mb-3">
				  <div class="input-group-prepend">
					<span class="input-group-text" id="basic-addon6">IBAN</span>
				  </div>
				  <input type="text" class="form-control" placeholder="iban" id="iban" name='iban' maxlength=10>
				</div>			
			</div>
		</div>
		
		<div class="row mb-3">
			<div class="col-md-12">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select select2" style='height:auto' name="tipo_pagamento[]" id="tipo_pagamento" multiple>
						<?php
						for ($sca=0;$sca<=count($lista_pagamenti)-1;$sca++) {?>
							<option value="{{$lista_pagamenti[$sca]['id']}}">{{$lista_pagamenti[$sca]['descrizione']}}
							</option>
						<?php } ?>
					</select>
				</div>
				<label for="tipo_pagamento">Tipo Pagamento</label>
			</div>	
		</div>			
		
		<h3>
		  
		  <small class="text-muted">Dati referente</small>
		</h3>
		<div class="row mb-3">	
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="cognome" name='cognome' type="text" value="{{$info_fornitore[0]->cognome ?? ''}}" maxlength=40 />
					<label for="cognome">Cognome</label>
				</div>
			</div>			
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="nome" name='nome' type="text" value="{{$info_fornitore[0]->nome ?? ''}}" maxlength=40  />
					<label for="nome">Nome</label>
				</div>
			</div>			
			
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="telefono_referente" name='telefono_referente' type="text" value="{{$info_fornitore[0]->telefono_referente ?? ''}}" maxlength=40  />
					<label for="telefono_referente">Telefono referente</label>
				</div>
			</div>	
		</div>	
	

			<div class="row mb-3 mt-5">
				<div class="col-md-4">
					<button type="submit" class="btn btn-success">Crea/Modifica Ditta</button>
					<a href="{{ route('scheda_fornitore') }}">
						<button type="button" class="btn btn-secondary" >
						Elenco fornitori
						</button>
					</a>
					
				</div>	
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
	<script src="{{ URL::asset('/') }}dist/js/scheda_fornitore.js?ver=1.232"></script>
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