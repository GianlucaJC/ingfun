<div id='div_definition' style='display:none'>
	<form method='post' action="{{ route('sezionali') }}" id='frm_sezionali1' name='frm_sezionali1' autocomplete="off">	
		<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
		<input type='hidden' name='edit_elem' id='edit_elem'>
		<input type="hidden" value="{{url('/')}}" id="url" name="url">

		<div class="container-fluid">
			<hr>
			<h4>
				<font color='red'>Definizione Azienda di proprietà</font>
			</h4>
				<div class="row">
					<div class="col-md-6">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">Denominazione Azienda</span>
						  </div>
						  <input type="text" class="form-control" placeholder="Denominazione" aria-label="Denominazione" name="descr_contr" id="descr_contr" required>
						</div>			
					</div>
					<div class="col-md-6">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">Telefono</span>
						  </div>
						  <input type="text" class="form-control" placeholder="Telefono" aria-label="Telefono" name="telefono" id="telefono">
						</div>			
					</div>					
				</div>

				<div class="row">
					<div class="col-md-3">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">Mail per scadenze contratti</span>
						  </div>
						  <input type="email" class="form-control" placeholder="Mail Scadenze" aria-label="Mail scadenze" name="mail_scadenze" id="mail_scadenze">
						</div>			
					</div>
					
					<div class="col-md-3">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">Mail invio fatture</span>
						  </div>
						  <input type="email" class="form-control" placeholder="Mail fatture" aria-label="Mail fatture" name="mail_fatture" id="mail_fatture" >
						</div>			
					</div>
					

					<div class="col-md-3">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">Pec</span>
						  </div>
						  <input type="email" class="form-control" placeholder="Mail Pec" aria-label="Mail Pec" name="mail_pec" id="mail_pec">
						</div>			
					</div>

					<div class="col-md-3">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">Mail azienda</span>
						  </div>
						  <input type="email" class="form-control" placeholder="Mail Azienda" aria-label="Mail Azienda" name="mail_azienda" id="mail_azienda">
						</div>			
					</div>
				</div>	
				
				
				
				
				<input type='hidden' id='id_sezionale'>

				<div class="row mb-2" id='div_logo'>
					
				</div>				
				<div class="row mb-3" id='div_allega'>
					<div class="col-md-12">
					<div id='div_allegati'></div>
						<a href='javascript:void(0)' onclick="set_sezione($('#id_sezionale').val());">
							Definizione file logo
						</a> <small>(sono ammessi solo file .jpg)</small>
					</div>
				</div>				
				
				<div class="row">
					<div class="col-md-6">
						<button type="submit" class="btn btn-success" >Crea/Modifica Azienda</button>
						<button type="button" class="btn btn-secondary" onclick="$('#div_definition').toggle(150);$('#div_allegati').empty()">
						Chiudi
						</button>
						
					</div>
				</div>	
				
				
			<hr>	
		</div>	
	</form>		
</div>