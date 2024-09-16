<div id='div_definition' style='display:none'>
	<form method='post' action="{{ route('registro') }}" id='frm_assenze' name='frm_assenze' autocomplete="off">	
		<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>

		<input type="hidden" value="{{url('/')}}" id="url" name="url">
		<input type='hidden' name='old_cerca' id='old_cerca' value='{{$old_cerca}}'>
		<div class="container-fluid">



		<div class="row mb-3">							
			<div class="col-md-12">

			  <div class="form-floating mb-3 mb-md-0">
					<select class="form-select" id="servizio_custom" aria-label="Servizio Custom" name='servizio_custom' onchange='select_servizi(this.value)' required>
						<option value=''>Select...</option>
						<option value='0'>(Nuova Definizione)</option>
						@foreach ($servizi_custom as $servizio)
							<option value='{{$servizio->id}}' data-tipo_dato='{{$servizio->tipo_dato}}'> 
								{{$servizio->descrizione}}
							</option>
						@endforeach
						
					</select>
					<label for='servizio_custom'>Assenze/Voci extra per i lavoratori</label>
				
				</div>
			</div>
		</div>

		<div class="row mb-3" style='display:none' id='div_newserv'>
			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">
					<input type="text" class="form-control" name="descrizione" id="descrizione" maxlength=100 />
					<label for="descrizione">Descrizione servizio</label>
				</div>		
			</div>

			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">
					<input type="text" class="form-control" name="alias_ref" id="alias_ref" maxlength=20 />
					<label for="alias_ref">Alias</label>
				</div>		
			</div>
				
		</div>

		<div class="row mb-3">							
			<div class="col-md-12">

			  <div class="form-floating mb-3 mb-md-0">
					<b>Scelta Lavoratore/i per assegnazione giustificativo</b>
					<select class="form-select select2" id="lavoratori" aria-label="Lavoratori" name='lavoratori[]' multiple="multiple" required>
						@php ($old_t="?")
						@foreach ($lavoratori as $lavoratore)
						
						<?php
							

						$tipo_contr=$lavoratore->tipo_contr;
						$tipo_contratto=$lavoratore->tipo_contratto;
						$ref_tipo=$tipo_contr.$tipo_contratto;
						$descr_t="";

							
						if ($tipo_contr==2 && $tipo_contratto==1)
							$descr_t="Indeterminati - Full Time";
						elseif ($tipo_contr==2 && $tipo_contratto==2)
							$descr_t="Indeterminati - Part Time";
						elseif ($tipo_contr==2 && ($tipo_contratto>2))
							$descr_t="Indeterminati - Altro";
						if ($tipo_contr==1 && $tipo_contratto==1)
							$descr_t="Determinati - Full Time";
						elseif ($tipo_contr==1 && $tipo_contratto==2)
							$descr_t="Determinati - Part Time";
						elseif ($tipo_contr==1 && ($tipo_contratto>2))
							$descr_t="Determinati - Altro";
						

								
						if ($old_t!=$ref_tipo) {
							if ($old_t!="?") echo "</optgroup>";
							echo "<optgroup label='$descr_t'>"; 
						}
						$old_t=$ref_tipo;
							
						?>
						
							<option value='{{$lavoratore->id}}'
							> 
								{{$lavoratore->nominativo}}
							</option>
						@endforeach
						</optgroup>
					</select>
				
				</div>
			</div>
		</div>
		<div class="row mb-3">
			<div class="col-md-4">		
			
                <div class="form-group">
                  <label>Date range:</label>

                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control float-right" id="range_date" name="range_date" required>
                  </div>
                  <!-- /.input group -->
                </div>	
			</div>		
		</div>
				
		<div class="row mb-3 mt-5">
			<div class="col-md-4">
				
				<button type="submit"  class="btn btn-success" >Crea Assenza</button>
				<button type="button" class="btn btn-secondary" onclick="$('#div_definition').toggle(150)">
				Chiudi
				</button>
				
			</div>	
		</div>
			
			<hr>	
		</div>	
	</form>		
</div>