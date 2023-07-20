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
<form method='post' action="{{ route('scheda_mezzo') }}" id='frm_mezzo' name='frm_mezzo' autocomplete="off" class="needs-validation" novalidate>

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
						SCHEDA MEZZO
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
					<input class="form-control" disabled id="targa" type="text" placeholder="ID"  value="" />
					<label for="targa">TARGA</label>
				</div>
			</div>			
			<div class="col-md-6">
				<div class="form-floating">
					<input class="form-control" id="numero_interno" name='numero_interno' type="text" required value="" />
					<label for="data_app">Numero interno*</label>
				</div>
			</div>			
		</div>
		
		<div class='row mb-3'>
			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="tipo_mezzo" id="tipo_mezzo"  required >
					<option value=''>Select...</option>
					<?php
						
						for ($sca=0;$sca<=count($tipomezzo)-1;$sca++) {
							$id_tipo=$tipomezzo[$sca]['id'];
							$descr_mezzo=$tipomezzo[$sca]['descrizione'];
							
							echo "<option value='".$id_tipo."' ";
							//if ($id_ditta==$id_ditta_db) echo " selected ";
							echo ">".$descr_mezzo."</option>";
						}
						
					?>						
					</select>
					<label for="tipo_mezzo">Tipologia*</label>
				</div>
			</div>	

			
			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="marca" id="marca"  required onchange='popola_modelli(this.value)' >
					<option value=''>Select...</option>
					<?php
						
						foreach ($marche as $marca_m) {
							$id_marca=$marca_m->id;
							$descrizione=$marca_m->marca;
							echo "<option value='".$id_marca."' ";
							//if ($id_ditta==$id_ditta_db) echo " selected ";
							echo ">".$descrizione."</option>";
						}
						
					?>						
					</select>
					<label for="marca">Marca*</label>
					<a href="{{ route('marca') }}" class="link-primary" target='_blank' onclick="
							 $('.up').hide();$('#div_up_marca').show()">
						Definisci/modifica
					</a>					
					<span id='div_up_marca' class='up' style='display:none'>
						<a href='javascript:void(0)' class='ml-2' onclick='refresh_marca()'>
							<font color='green'>
								<i class="fas fa-sync-alt"></i>
							</font>	
						</a>	
					</span>	
										
				</div>
			</div>	

			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="modello" id="modello"  required >
						<option value=''>Select...</option>
					</select>
					<label for="ditta">Modello*</label>
				</div>
				
				<a href="{{ route('modello') }}" class="link-primary" target='_blank' onclick="
						 $('.up').hide();$('#div_up_modello').show()">
					Definisci/modifica
				</a>					
				<span id='div_up_modello' class='up' style='display:none'>
					<a href='javascript:void(0)' class='ml-2' onclick='refresh_modello()'>
						<font color='green'>
							<i class="fas fa-sync-alt"></i>
						</font>	
					</a>	
				</span>					
			</div>	
			
		</div>		
	


		<div class='row mb-3'>
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="telaio" name='telaio' type="text"  />
					<label for="data_app">Telaio</label>
				</div>
			</div>		

			
			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="alimentazione" id="alimentazione"  required >
					<option value=''>Select...</option>
						<option value='1'
						>Benzina</option>
						<option value='2'
						>Diesel</option>
					</select>
					<label for="alimentazione">Alimentazione*</label>
				</div>
			</div>	

			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="proprieta" id="proprieta"  required >
					<option value=''>Select...</option>
						<option value='1'
						>Noleggio</option>
						<option value='2'
						>Proprietà</option>
						<option value='3'
						>Leasing</option>
					</select>
					<label for="proprieta">Proprietà*</label>
				</div>
			</div>
			
		</div>
			

		<div class='row mb-3'>
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="posti" name='posti' type="text"  />
					<label for="posti">Posti</label>
				</div>
			</div>		
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="chilometraggio" name='chilometraggio' type="text"  />
					<label for="chilometraggio">Chilometraggio</label>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="catene" id="catene"  required >
					<option value=''>Select...</option>
						<option value='S'
						>SI</option>
						<option value='N'
						>NO</option>
					</select>
					<label for="alimentazione">Catene*</label>
				</div>
			</div>				
		</div>
		

		<div class='row mb-3'>
			<div class="col-md-6">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="carta_carburante" id="carta_carburante" >
					<option value="">Select...</option>
					<?php
						
						foreach ($carte_c as $carta_c) {
							$id_ref=$carta_c->id;
							$id_carta=$carta_c->id_carta;
							echo "<option value='".$id_ref."' ";
							//if ($id_ditta==$id_ditta_db) echo " selected ";
							echo ">".$id_carta."</option>";
						}
						
					?>	
					</select>
					<label for="carta_carburante">Carta carburante</label>
				</div>
			</div>	

			<div class="col-md-6">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="badge_cisterna" id="badge_cisterna" >

					</select>
					<label for="carta_carburante">Badge cisterna</label>
				</div>
			</div>	
			
		</div>		
		
		
		<div class='row mb-3'>
			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="Telepass" id="telepass" >
					<option value="">Select...</option>

					</select>
					<label for="telepass">Telepass</label>
				</div>
			</div>	
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="immatricolato" name='immatricolato' type="date" required />
					<label for="immatricolato">Immatricolato*</label>
				</div>
			</div>	
			
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="ultima_revisione" name='ultima_revisione' type="date" required />
					<label for="ultima_revisione">Ultima revisione</label>
				</div>
			</div>			
		</div>		


		<div class='row mb-3'>

			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="scadenza_assicurazione" name='scadenza_assicurazione' type="date" />
					<label for="scadenza_assicurazione">Scadenza Assicurazione</label>
				</div>
			</div>	


			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="scadenza_bollo" name='scadenza_bollo' type="date" />
					<label for="immatricolato">Scadenza Bollo</label>
				</div>
			</div>	
			
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="prossimo_tagliando" name='prossimo_tagliando' type="text" />
					<label for="prossimo_tagliando">Prossimo tagliando</label>
				</div>
			</div>			
		</div>

		<div class='row mb-3'>

			<div class="col-md-12">
				<div class="form-floating">
					<input class="form-control" id="marca_modello_penumatico" name='marca_modello_penumatico' type="text" />
					<label for="prossimo_tagliando">Marca e modello pneumatico</label>
				</div>
			</div>			
		</div>

		<div class='row mb-3'>

			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="misura_pneumatico" name='misura_pneumatico' type="text" />
					<label for="misura_pneumatico">Misura pneumatico</label>
				</div>
			</div>	
			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="primo_equipaggiamento" id="primo_equipaggiamento" >
					<option value=''>Select...</option>
						<option value='S'
						>SI</option>
						<option value='N'
						>NO</option>
					</select>
					<label for="primo_equipaggiamento">Primo equipaggiamento</label>
				</div>
			</div>		
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="km_installazione" name='km_installazione' type="text" />
					<label for="km_installazione">Km installazione</label>
				</div>
			</div>				
		</div>

		<div class='row mb-3'>

			<div class="col-md-12">
				<div class="form-floating">
					<input class="form-control" id="officina_installazione" name='officina_installazione' type="text" />
					<label for="officina_installazione">Officina installazione</label>
				</div>
			</div>			
		</div>
		
		<div class='row mb-3'>

			<div class="col-md-12">
				<div class="form-floating">
					<textarea class="form-control" id="anomalia_note" rows="3"></textarea>
					<label for="anomalia_note">Anomalie e note</label>
				</div>
			</div>			
		</div>


		<div class='row mb-3'>
			<div class="col-md-3">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="mezzo_marciante" id="mezzo_marciante" >
					<option value=''>Select...</option>
						<option value='S'
						>SI</option>
						<option value='N'
						>NO</option>
					</select>
					<label for="mezzo_marciante">Mezzo marciante</label>
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="mezzo_manutenzione" id="mezzo_manutenzione" >
					<option value=''>Select...</option>
						<option value='S'
						>SI</option>
						<option value='N'
						>NO</option>
					</select>
					<label for="mezzo_manutenzione">Mezzo in manutenzione</label>
				</div>
			</div>
		</div>			

        <div class="row">

			<button type="submit" name='btn_save_mezzo' id='btn_save_mezzo'  class="btn btn-success btn-lg btn-block">SALVA</button>  
			
			<a href="">
				<button type="button"  id='back_appalti' class="btn btn-info btn-lg btn-block mt-3">ELENCO MEZZI</button> 
			</a>


			<input type="hidden" value="{{url('/')}}" id="url" name="url">

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
	<script src="{{ URL::asset('/') }}dist/js/scheda_mezzo.js?ver=1.191"></script>
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