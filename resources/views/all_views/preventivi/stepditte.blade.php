<!-- SEZIONE SELEZIONE DITTE / CLIENTI !-->
<div id='div_sez_0' class="sezioni mb-5">
	<div class="card-body">
	
		<div class="row mb-3">
			<div class="col-md-3">
				<div class="form-floating">
					<input class="form-control" id="data_preventivo" name='data_preventivo' type="date" required  value="{{$data_preventivo}}" />
					<label for="data_preventivo">Data preventivo*</label>
				</div>
			</div>		
			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="sezionale" id="sezionale"  required>
					<option value=''>Select...</option>
						@foreach ($sezionali as $sez) 
							<option value='{{$sez->id}}'
							<?php
								if ($sez->id==$sezionale) echo " selected ";
								
							?>
							>{{$sez->descrizione}}</option>
						@endforeach
					</select>
					<label for="sezionale">Sezionale*</label>
				</div>
			</div>
			
			<div class="col-md-5">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="ditta" id="ditta"  required onchange='popola_servizi(this.value)'>
					<option value=''>Select...</option>
					<?php
						foreach ($ditte as $ditta_ref) {
							$id_ditta=$ditta_ref->id;
							$denominazione=$ditta_ref->denominazione;
							echo "<option value='".$id_ditta."' ";
							if ($id_ditta==$ditta) echo " selected ";
							echo ">".$denominazione."</option>";
						}
					?>						
					</select>
					<label for="ditta">Scelta Cliente per il preventivo*</label>

				<a href="{{ route('ditte') }}" class="link-primary" target='_blank' onclick="
						 $('.up').hide();$('#div_up').show();">
					Definisci nuovo o modifica esistente
				</a>	
				
				<span id='div_up' class='up' style='display:none'>
					<a href='javascript:void(0)' class='ml-2' onclick=" $('#step_active').val('');$('#needs-validation2').submit();">
						<font color='green'>
							<i class="fas fa-sync-alt"></i>
						</font>	
					</a>	
				</span>					
				</div>
			</div>			
		</div>				

		
		<div class="float-sm-left">		
			<?php 
			if ($id_doc!=0) {
				echo "<button class='btn btn-primary' type='button' onclick='set_sezione($id_doc)'>Allega preventivo firmato</button>";
			}
			
			$path = 'allegati/preventivi_firmati/*';
			$num=0;
			foreach (glob($path) as $filename) {
				//echo "$filename size " . filesize($filename) . "\n";
				$num++;
			}
			if ($num>0) {
				echo "<button class='btn btn-secondary ml-2' type='submit' name='btn_dele_prev' value='dele_prev'>Elimina preventivi firmati inviati</button>";				
			}


			?>	
		</div>		
		
		<div class="float-sm-right">		
			<button type="submit" name='btn_ditta' id='btn_ditta' onclick="$('.step').val('1')" value='btn_ditta' class="btn btn-success btn-lg">Salva e vai avanti</button>
		</div>

		<div id='div_allegati'></div>
		
	</div>
</div> 