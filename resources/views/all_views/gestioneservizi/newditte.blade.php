<div id='div_definition' style='display:none'>
	<form method='post' action="{{ route('ditte') }}" id='frm_ditte1' name='frm_ditte1' autocomplete="off">	
		<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
		<input type='hidden' name='refr' id='refr' value='{{$refr}}'>
		<input type='hidden' name='edit_elem' id='edit_elem'>
		<input type="hidden" value="{{url('/')}}" id="url" name="url">
		<div class="container-fluid">

				<div class="row mb-3">
				
					<div class="col-md-12">
						<div class="form-floating mb-3 mb-md-0">
							<select class="form-select" name="azienda_prop" id="azienda_prop"  required >
							<option value=''>Select...</option>
							<?php
								foreach ($aziende_prop as $azienda) {
									$id_azienda=$azienda->id;
									$denominazione=$azienda->azienda_prop;
									echo "<option value='".$id_azienda."' ";
									echo ">".$denominazione."</option>";
								}
							?>						
							</select>
							<label for="azienda_prop">Azienda di proprietà di riferimento*</label>
						</div>
					</div>
				</div>	
				<div class="row mb-3">
					<div class="col-md-12">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">Denominazione</span>
						  </div>
						  <input type="text" class="form-control" placeholder="Denominazione" aria-label="Denominazione" name="descr_contr" id="descr_contr" required>
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
					<div class="col-md-4">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">Partita Iva</span>
						  </div>
						  <input type="text" class="form-control" placeholder="Partita Iva" id="piva" name='piva' maxlength=11>
						</div>			
					</div>
					
					<div class="col-md-4">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon2">Codice Fiscale</span>
						  </div>
						  <input type="text" class="form-control" placeholder="C.Fiscale" id="cf" name='cf' maxlength=16>
						</div>			
					</div>

					<div class="col-md-4">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon3">@</span>
						  </div>
						  <input type="email" class="form-control" placeholder="E-mail" id="email" name='email' maxlength=150>
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
							<span class="input-group-text" id="basic-addon6">Fax</span>
						  </div>
						  <input type="text" class="form-control" placeholder="Fax" id="fax" name='fax' maxlength=50>
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
				<div class="row mb-3" id='div_allega'>
					<div class="col-md-12">
					<div id='div_allegati'></div>
						<a href='javascript:void(0)' onclick="$('#div_fx').show(150)">
							Allega file relativo alla ditta
						</a>
					</div>
				</div>
				
				<div class="row mb-3" style='display:none' id='div_fx'>
					<div class="col-md-6">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="span_file">Descrizione associata</span>
						  </div>
						  <input type="text" class="form-control" placeholder="Descrivi il file da inviare" id="descr_file" name='descr_file' maxlength=50>
						</div>			
					</div>
					<div class="col-md-6">
						<button type="button" class="btn btn-success" onclick="set_sezione()">Vai Allo step successivo</button>

						<button type="button" class="btn btn-secondary" onclick="$('#div_fx').hide(150)">Annulla operazione</button>
					</div>

				</div>
				

				<div id='div_doc'></div>



				<div class="row mb-3 mt-5">
					<div class="col-md-4">
						<button type="submit" class="btn btn-success" >Crea/Modifica Ditta</button>
						<button type="button" class="btn btn-secondary" onclick="$('#div_definition').toggle(150)">
						Chiudi
						</button>
						
					</div>	
				</div>

							
				
			<hr>	
		</div>	
	</form>	

	
</div>