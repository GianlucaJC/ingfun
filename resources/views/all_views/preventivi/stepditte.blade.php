<!-- SEZIONE SELEZIONE DITTE / CLIENTI !-->
<div id='div_sez_0' class="sezioni mb-5">
	<div class="card-body">
	
		<div class="row mb-3">
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="data_preventivo" name='data_preventivo' type="date" required  value="{{$data_preventivo}}" />
					<label for="data_preventivo">Data preventivo*</label>
				</div>
			</div>		
			<div class="col-md-8">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="ditta" id="ditta"  required onchange='popola_servizi(this.value)'>
					<option value=''>Select...</option>
					<?php
						$old_az="?";
						foreach ($ditte as $ditta_ref) {
							$id_azienda=$ditta_ref->id_azienda;
							$azienda=$ditta_ref->azienda;
							if ($old_az!=$id_azienda) {
								$old_az=$id_azienda;
								if ($old_az!="?")echo "</optgroup>";
								echo "<optgroup label='$azienda'>";
							}	
							$azienda=$ditta_ref->azienda;
							$id_ditta=$ditta_ref->id;
							$denominazione=$ditta_ref->denominazione;
							echo "<option value='".$id_ditta."' ";
							if ($id_ditta==$ditta) echo " selected ";
							echo ">".$denominazione."</option>";
						}
					?>						
					</select>
					<label for="ditta">Scelta Cliente/Ditta per il preventivo*</label>

				<a href="{{ route('ditte') }}" class="link-primary" target='_blank' onclick="window.open(this.href, 
                         'newwindow', 
                         'width=1024,height=800,left=200'); 
						 $('.up').hide();$('#div_up').show();
						 return false;">
					Definisci nuova o modifica esistente
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
		<div class="float-sm-right">		
			<button type="submit" name='btn_ditta' id='btn_ditta' onclick="$('.step').val('1')" value='btn_ditta' class="btn btn-success btn-lg">Salva e vai avanti</button>
		</div>
	</div>
</div> 