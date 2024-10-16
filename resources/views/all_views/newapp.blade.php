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

@section('extra_button_home')
	<li class="nav-item  d-sm-inline-block">	
        <a href="{{ route('listapp') }}" class="nav-link">
			<button type="button" class="btn btn-success btn-sm">Elenco appalti</button>	
		</a>
     </li>
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

		<div class="row">
			<?php
			
				if (!empty($num_send) && $num_send>0) {
					$txt="Notifiche push inviate!";
					if ($num_send==1) $txt="Notifica push inviata!";
					echo "<div class='alert alert-success' role='alert'>";
					  echo "<b>$num_send</b> $txt";
					echo "</div>";
				}
			?>
		</div>
		<?php
			$id_ditta_db=0;
			if (isset($appalti[0]->id_ditta))  $id_ditta_db=$appalti[0]->id_ditta;
		?>	


		<div class="row mb-3">
					<!-- div disattivato !-->		
					<div class="col-md-6" style='display:none'>
						<div class="form-floating">
							<input class="form-control" id="descrizione_appalto" name='descrizione_appalto' type="text" placeholder="Definizione"  maxlength=150 value="{{$appalti[0]->descrizione_appalto ?? ''}}" />
							<label for="descrizione_appalto">Descrizione Appalto</label>
						</div>
					</div>			

			
			<div class="col-md-2">
				<div class="form-floating">
					<input class="form-control" disabled id="ref_appalto" type="text" placeholder="ID"  value="{{$appalti[0]->id ?? ''}}" />
					<label for="ref_appalto">ID Appalto</label>
				</div>
			</div>


			
			<div class="col-md-8">
				<div class="form-floating mb-3 mb-md-0">
					<input class="form-control" id="luogo_incontro" name='luogo_incontro' type="text" placeholder="Luogo e ora dell'incontro" required value="{{$appalti[0]->luogo_incontro ?? ''}}"  />
					<label for="nome">Luogo incontro*</label>
				</div>
			</div>


			<div class="col-md-2">
				<div class="form-floating mb-3 mb-md-0">
					<input class="form-control" id="ora_incontro" name='ora_incontro'  type="time" placeholder="Ora dell'incontro" required value="{{$appalti[0]->ora_incontro ?? ''}}" maxlength=5  />
					<label for="nome">Ora incontro*</label>
				</div>
			</div>
		</div>	
		
		<div class="row mb-3">
			<div class="col-md-2">
				<div class="form-floating">
					<input class="form-control" id="km_percorrenza" name='km_percorrenza' type="text"  value="{{$appalti[0]->km_percorrenza ?? ''}}" maxlength=10 />
					<label for="km_percorrenza">Km percorrenza</label>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-floating">
					<input class="form-control" id="orario_incontro" name='orario_incontro' type="time" required value="{{$appalti[0]->orario_incontro ?? ''}}" maxlength=5 />
					<label for="orario_incontro">Orario Destinazione*</label>
				</div>
			</div>	
			<div class="col-md-8">
				<div class="form-floating mb-3 mb-md-0">
					<input class="form-control" id="luogo_destinazione" name='luogo_destinazione' type="text" placeholder="Luogo Destinazione" required value="{{$appalti[0]->luogo_destinazione ?? ''}}"  />
					<label for="nome">Luogo Destinazione*</label>
				</div>
			</div>			
		</div>			
		
		
		<div class="row mb-3">	
			
			<div class="col-md-6">
				<div class="form-floating mb-3 mb-md-0">
					<input class="form-control" id="chiesa" name='chiesa' type="text" placeholder="Chiesa" required value="{{$appalti[0]->chiesa ?? ''}}"  />
					<label for="nome">Chiesa*</label>
				</div>
			</div>
			
			<div class="col-md-2">
				<div class="form-floating">
				<?php
					$d_def=date("Y-m-d");
					$d_def=date('Y-m-d', strtotime($d_def. ' + 1 days'));
					if (!isset($appalti[0]->data_ref)) $d_app=$d_def;
					else $d_app=$appalti[0]->data_ref;
				?>
					<input class="form-control" id="data_app" name='data_app' type="date" required value="{{$d_app}}" />
					<label for="data_app">Data del servizio*</label>
				</div>
			</div>			

			<div class="col-md-2">
				<div class="form-floating">
					<input class="form-control" id="ora_app" name='ora_app' type="time" required value="{{$appalti[0]->orario_ref ?? ''}}" maxlength=5 />
					<label for="data_app">Orario del servizio*</label>
				</div>
			</div>	

			<div class="col-md-2">
				<div class="form-floating">
					<input class="form-control" id="orario_fine_servizio" name='orario_fine_servizio' type="time"  value="{{$appalti[0]->orario_fine_servizio ?? ''}}" maxlength=5 />
					<label for="orario_fine_servizio">Orario fine servizio</label>
				</div>
			</div>			
		</div>
		
		<div class='row mb-3'>
			<div class="col-md-6">
				<div class="form-floating mb-3 mb-md-0">
					<input class="form-control" id="testo_libero" name='testo_libero' type="text"  value="{{$appalti[0]->testo_libero ?? ''}}"  />
					<label for="testo_libero">Testo libero che sarà riportato in fattura (es. nominativo salma)</label>
				</div>
			</div>			
			<div class="col-md-6">
				
					<select class="form-select select2" name="ditta" id="ditta"  required onchange='popola_servizi(this.value)'>
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
		
		<div class="row mb-3">
			<div class="col-md-12">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select select2" style='height:auto' name="servizi[]" id="servizi" required multiple>
					

					<?php
						if (strlen($id_app)!=0 && $id_app!=0) {
							
							foreach ($servizi as $servizio) {
								$id_servizio=$servizio->id_servizio;
								$descr_servizio=$servizio->descrizione;
								echo "<option value='".$id_servizio."' ";
								if (in_array($id_servizio,$id_servizi)) echo " selected ";
								echo ">".$descr_servizio."</option>";
							}
						}
					?>						
					</select>
					
				</div>
				<label for="servizi">Servizi*</label>
				<a id='a_serv' href="{{ route('servizi',['id_ref'=>$id_ditta_db]) }}" class="link-primary ml-3" target='_blank' onclick="
						 $('.up').hide();$('#div_up_servizi').show()">
					Definisci/modifica
				</a>					
				<span id='div_up_servizi' class='up' style='display:none'>
					<a href='javascript:void(0)' class='ml-2' onclick='refresh_servizi()'>
						<font color='green'>
							<i class="fas fa-sync-alt"></i>
						</font>	
					</a>	
				</span>	
				
			</div>	
		</div>		

		<div class="row mb-3">
			<div class="col-md-12">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" style='height:auto' name="mezzo" id="mezzo" required>
					<option value=''>Select...</option>
					<?php
						foreach ($mezzi as $mezzo) {
							$marca=$mezzo->marca;
							$modello=$mezzo->modello;
							$targa=$mezzo->targa;
							$ref_mezzo="$marca - $modello - $targa";
							echo "<option value='".$targa."' ";
							if (isset($appalti[0]->targa)) {
								if ($appalti[0]->targa==$targa) echo " selected ";
							}
							echo ">".$ref_mezzo."</option>";
						}
					?>						
					</select>
					<label for="mezzi">Mezzo*</label>
				</div>
			</div>	
		</div>

		<center><h4>FORMAZIONE SQUADRA</h4></center>


		
		<div id='div_ditta'>
			<div class="row mb-3">
				<div class="col-md-12">
					<div class="form-floating mb-3 mb-md-0">
						<select class="form-select" name="azienda_proprieta" id="azienda_proprieta"   onchange='lista_lavoratori(this.value)'>
						<option value=''>Select...</option>
						<option value='all'>[[Tutte]]</option>
						<?php
							$azienda_db="";
							if (isset($appalti[0]->id_azienda_proprieta))  $azienda_db=$appalti[0]->id_azienda_proprieta;
							foreach ($sezionali as $sezionale) {
								echo "<option value='".$sezionale->id."' ";
								if ($azienda_db==$sezionale->id) echo " selected ";
								echo ">".$sezionale->descrizione."</option>";
							}
						?>						
						</select>
						<label for="azienda_proprieta">Azienda di Proprietà*</label>
					</div>
				</div>					
			</div>
			
			<?php
				$id_lav="";
				foreach ($ids_lav as $id_l=>$v) {
					if (strlen($id_lav)!=0) $id_lav.=";";
					$id_lav.=$id_l;
				}
			?>
			<input type='hidden' name='lavoratori' id='lavoratori' value='{{$id_lav}}'>
			

			<div class='mb-3' id='div_lavoratori'>
				
			</div>
		
		
			<div class="row mb-3" id='div_lav_sel'>
				<div class="col-md-12">
					<div class="form-floating mb-3 mb-md-0">
						<select class="form-select select2" id="lavoratoria" disabled aria-label="Lavoratori"  multiple="multiple" required>
							@foreach ($lavoratori as $lavoratore)
								<option value='{{$lavoratore->id}}'
								<?php
									$status=0;
									if (array_key_exists($lavoratore->id,$ids_lav)) {
										echo " selected ";
										$status=$ids_lav[$lavoratore->id];
									}
									
								?>
								> 
								{{$lavoratore->nominativo}}
								@if ($status==1) (Accettato) @endif
								@if ($status==2) (Rifiutato) @endif
								</option>
							@endforeach
						</select>
						<b>Squadra</b>
					</div>
				</div>				
			</div>	
			

		
			<div class="row mb-3">

				<div class="col-md-4">
					<div class="form-floating mb-3 mb-md-0">
						<select class="form-select" name="responsabile_mezzo" id="responsabile_mezzo"  required >
						<option value=''>Select...</option>
						<?php
							foreach ($lavoratori as $lavoratore) {
								if (array_key_exists($lavoratore->id,$ids_lav)) {
									echo "<option value=".$lavoratore->id;
									if (isset($appalti[0]->responsabile_mezzo) && $appalti[0]->responsabile_mezzo==$lavoratore->id ) echo " selected ";
									echo ">".$lavoratore->nominativo."</option>";
								}								
							}
						?>						
						</select>
						<label for="responsabile_mezzo">Responsabile del mezzo*</label>
					</div>
				</div>					
				<div class="col-md-8">
					<div class="form-floating">					  
						
						<textarea class="form-control" name='note' id="note" rows="4">{{$appalti[0]->note ?? ''}}</textarea>
						<label for="note">Note</label>
					</div>
				</div>	
			</div>
			
			
			<div class="row mb-3">
				<span id='bell' onclick="set_bell()">
				<i class="far fa-bell-slash"></i>
				</span>
				<div class="col-md-12">
					<div class="form-floating">
						<textarea class="form-control" name='variazione' id="variazione" rows="4">{{$appalti[0]->variazione ?? ''}}</textarea>
						<label for="variazione">Variazione</label>
					</div>
				</div>	
			</div>				

			<input type='hidden' name='flag_variazione' id='flag_variazione'>
			<hr>
		</div>
			
		


        <div class="row mb-3">

			<button type="submit" name='sub_newcand_onlysave' id='sub_newcand_onlysave' onclick='check_save()' class="btn btn-success btn-lg btn-block">SALVA</button> 
			
			<input type="hidden" value="{{url('/')}}" id="url" name="url">
			
			<input type="hidden" name="id_app" id="id_app" value="{{$id_app}}">
		</div>
		<hr>
		
			
		
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
	<script src="{{ URL::asset('/') }}dist/js/newapp.js?ver=1.376"></script>
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