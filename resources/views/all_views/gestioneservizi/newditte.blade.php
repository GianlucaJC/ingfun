<div id='div_definition' style='display:none'>
	<form method='post' action="{{ route('ditte') }}" id='frm_ditte1' name='frm_ditte1' autocomplete="off">	
		<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
		<input type='hidden' name='edit_elem' id='edit_elem'>
		<input type="hidden" value="{{url('/')}}" id="url" name="url">
		<div class="container-fluid">

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