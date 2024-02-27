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
	  
	  <a class="dropdown-item" href="javascript:void(0)"  onclick="metodo_ins(3)" >c) Importazione da Servizi</a>
	  
	  <a class="dropdown-item" href="#" onclick="metodo_ins(4)">d) Importazione da preventivi</a>
	  
	  <!--
	  <a class="dropdown-item" href="#" onclick="metodo_ins(3)">c) Servizi associati alla ditta selezionata</a>
	  !-->
	  <div class="dropdown-divider"></div>
	  <a class="dropdown-item" href="#" onclick="metodo_ins(5)"><i>Torna alla visualizzazione degli articoli</i></a>

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

<?php
	$style="display:none";
	if ($filtroa==true) $style="";
?>	
<div id='div_from_appalti' style='{{$style}}' class='metodi mt-3'>



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
				echo "<b>Attenzione</b>: Nel periodo impostato non risultano appalti associati alla ditta selezionata. <i>Selezionare un periodo diverso</i>";
			echo "</div>";
		}
		 
	  ?>

			<div class="row mb-3">
				<div class="col-md-4">
					<div class="form-floating">
						<input class="form-control" id="range_da" name='range_da' type="date"  value="{{$range_da}}" />
						<label for="range_da">Da data</label>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-floating">
						<input class="form-control" id="range_a" name='range_a' type="date"  value="{{$range_a}}" />
						<label for="range_a">A data</label>
					</div>
				</div>
			</div>
			
			<button type="submit" name='btn_filtro' id='btn_filtro' onclick='' class="btn btn-success btn-sm mb-3" value='filtro_appalti'>Filtro data</button>
		
			
			<table id='tbl_list_appalti' class="display">
				<thead>
					<tr>
						<th style='text-align:center'>ID Appalto</th>
						<th>Data</th>
						<th>Squadra</th>
						<th>Servizi</th>
						<th>Selezione</th>
					</tr>
				</thead>
				<tbody>
					@php ($num_art=0)
					@php ($old_ida=0)
					@foreach($ditteinapp as $ditta)
						<?php 
						if ($old_ida==$ditta->id_appalto) continue;
						$old_ida=$ditta->id_appalto;
						?>
						
						<tr>
							@php ($num_art++)
							<td style='text-align:center'>
								{{$ditta->id_appalto}}
							</td>	

							<td>
								{{$ditta->data_ref}}
							</td>	

							<td>
								<?php

								if (isset($ids_lav[$ditta->id_appalto])) {
									for ($sca=0;$sca<count($ids_lav[$ditta->id_appalto]);$sca++) {
										$value=$ids_lav[$ditta->id_appalto][$sca];
										if (isset($all_lav[$value])) 
											if ($sca>0) echo ", ";
											echo $all_lav[$value];
									}
								}

								?>
							</td>
							<td>
							<?php							
								if (isset($id_servizi[$ditta->id_appalto])) {
									for ($sca=0;$sca<count($id_servizi[$ditta->id_appalto]);$sca++) {
										$value=$id_servizi[$ditta->id_appalto][$sca];
										if (isset($all_servizi[$value])) 
											if ($sca>0) echo ", ";
											$descr_servizio=$all_servizi[$value]['descrizione'];
											echo $descr_servizio.":";
											
											if(strpos($descr_servizio,'RIMBORSO KM') !== false) {
												$km=$ditta->km_percorrenza;
												$importo=$all_servizi[$value]['importo_ditta'];
												
												$new_imp=floatval($km)*floatval($importo);
												$all_servizi[$value]['importo_ditta']=$new_imp;
												echo "$km*$importo=";
											}
												
											echo $all_servizi[$value]['importo_ditta']."€";

									}
								}		
							?>								
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



