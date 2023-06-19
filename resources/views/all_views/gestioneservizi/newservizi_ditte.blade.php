<div class="row mb-3">
	<div class="col-md-12">
		<div class="form-floating mb-3 mb-md-0">
			<select class="form-select" name="ditta_ref" id="ditta_ref" onchange="$('#frm_newservice').submit()">
				<option value=''>Select...</option>
					@foreach($ditte as $ditta) 
						<option value='{{ $ditta->id}}' 	
						<?php 
							if ($ditta_ref==$ditta->id) 
							echo " selected ";
						?>
						>{{ $ditta->denominazione}}</option>
					@endforeach
			</select>
			<label for="ditta">Ditta di riferimento</label>
		</div>
	</div>
</div>

<div id='div_set_service' style='display:none'>
	<div class="row mb-3">
		<div class="col-md-3">
			<div class="form-floating mb-3 mb-md-0">
				<select class="form-select" name="service" id="service" onchange="" required>
					<option value=''>Select...</option>
						@foreach($servizi as $servizio) 
							<option value='{{ $servizio->id}}' 	
							<?php 
								if ($service==$servizio->id) 
								echo " selected ";
							?>
							>{{ $servizio->descrizione}}</option>
						@endforeach
				</select>
				<label for="service">Elenco Servizi*</label>
			</div>
		</div>
		
		<div class="col-md-3">
			<div class="form-floating mb-3 mb-md-0">
				<input class="form-control" id="importo" name='importo' type="text" placeholder="Importo" required oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" required />
				<label for="nome">Importo*</label>
			</div>
		</div>	

		<!--
		<div class="col-md-3">
			<div class="form-floating mb-3 mb-md-0">
				<input class="form-control" id="aliquota" name='aliquota' type="text" placeholder="aliquota" required oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" required />
				<label for="nome">Aliquota*</label>
			</div>

		</div>
		!-->
		
		<div class="col-md-3">
		  <div class="form-floating mb-3 mb-md-0">
			
			<select class="form-select" id="aliquota" aria-label="Aliquota" name='aliquota' required>
				<option value=''>Select...</option>
				@foreach ($aliquote_iva as $aliquota) 
					<option value='{{$aliquota->id}}'>
						{{$aliquota->aliquota}}% - {{$aliquota->descrizione}}
					</option>	
				@endforeach
			</select>
			
			<label for="aliquota">Aliquota Iva</label>
			</div>
		</div>		

		<div class="col-md-3">
			<div class="form-floating mb-3 mb-md-0">
				<input class="form-control" id="importo_lavoratore" name='importo_lavoratore' type="text" placeholder="Importo lavoratore" required oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" required />
				<label for="nome">Importo Lavoratore*</label>
			</div>		
		</div>
		
	</div>	
	
	<div class="d-grid gap-2 d-md-flex justify-content-md-begin">
		<button type="submit" class="btn btn-success" onclick=' $("#ditta_ref").prop("disabled", false);$("#save_ds").val("1")'>
			<i class="far fa-save"></i> Salva
		</button>
		<button type="button" class="btn btn-secondary" onclick="$('#ditta_ref').prop('disabled', false);$('#frm_newservice').submit()">
			<i class="fas fa-sign-out-alt"></i> Chiudi procedura associazione
		</button>
	</div>
</div>

