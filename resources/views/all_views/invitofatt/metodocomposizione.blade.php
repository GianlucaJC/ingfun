<div>
	<h5 >Modalità di composizione fattura:</h5>
</div>
<div class="margin">

  <div class="btn-group">
	<button type="button" class="btn btn-info">Aggiungi righe fattura tramite:</button>
	<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
	  <span class="sr-only">Toggle Dropdown</span>
	</button>
	<div class="dropdown-menu" role="menu">
	  <a class="dropdown-item" href="#" onclick="metodo_ins(1)">a) Modalità di inserimento manuale</a>
	  <a class="dropdown-item" href="#" onclick="metodo_ins(2)">b) Importazione da Appalti relativi alla ditta selezionata</a>
	  <!--
	  <a class="dropdown-item" href="#" onclick="metodo_ins(3)">c) Servizi associati alla ditta selezionata</a>
	  !-->
	  <div class="dropdown-divider"></div>
	  <a class="dropdown-item" href="#" onclick="metodo_ins(4)"><i>Torna alla visualizzazione degli articoli</i></a>

	</div>
  </div>		
</div>	



<div id='div_from_servizi' class='metodi mt-3' style='display:none'>
	<div class="row mb-3">
		<div class="col-md-12">
			<div class="form-floating mb-3 mb-md-0">
				<select class="form-select select2" style='height:auto' name="servizi[]" id="servizi" required multiple>
				<?php
					/*
					if (strlen($id_app)!=0 && $id_app!=0) {
						
						foreach ($servizi as $servizio) {
							$id_servizio=$servizio->id_servizio;
							$descr_servizio=$servizio->descrizione;
							echo "<option value='".$id_servizio."' ";
							if (in_array($id_servizio,$id_servizi)) echo " selected ";
							echo ">".$descr_servizio."</option>";
						}
					}
					*/
				?>						

				</select>
				
			</div>
			<label for="servizi">Servizi*</label>
		</div>	
	</div>	
</div>	


<div id='div_from_appalti' style='display:none' class='metodi mt-3'>



	<div class="row">
	  <div class="col-md-12">
	  <?php
		$ditta_ref="?";

		foreach($ditteinapp as $ditta_def) {
			$ditta_ref=$ditta_def->denominazione;
		}
		if ($ditta_ref!="?") {
			echo "<div class='alert alert-info' role='alert'>";
				echo "Ditta selezionata: <b>$ditta_ref</b>";
			echo "</div>";
		} else {
			echo "<div class='alert alert-warning' role='alert'>";
				echo "<b>Attenzione</b>: Non risultano appalti associati alla ditta selezionata";
			echo "</div>";
		}
		 
	  ?>
			<button type="submit" name='btn_filtro' id='btn_filtro' onclick='' class="btn btn-success btn-sm mb-3">Filtro data</button>
		
			<table id='tbl_list_appalti' class="display">
				<thead>
					<tr>
						<th>Data</th>
						<th>Operazioni</th>
					</tr>
				</thead>
				<tbody>
					@php ($num_art=0)
					@foreach($ditteinapp as $ditta)
						<tr>
							@php ($num_art++)
							<td>
								{{$ditta->data_ref}}
							</td>	

							<td style='text-align:center'>
								<div class="form-check">
								  <input class="form-check-input" type="checkbox" value="{{$ditta->id_appalto}}" id="app_sel" name="app_sel[]" checked>
								  <label class="form-check-label" for="app_sel">
								   
								  </label>
								</div>								
							</td>
						</tr>
					@endforeach
					@if ($num_art>100) 
						<div class="alert alert-warning" role="alert">
						  <b>Attenzione!</b> Limite massimo di righe visualizzabili superato (100). Impostare un filtro per ridurle
						</div>						
					@endif
					
				</tbody>
					
			</table>
			<button type="submit" name='btn_import_app' id='btn_import_app' onclick='' class="btn btn-primary btn-sm mb-3 mt-2" value='import_a'>Importa appalti selezionati</button>
	  </div>
	</div>	
</div>


