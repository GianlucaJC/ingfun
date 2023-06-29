<div id='div_definition' style='display:none'>
	<form method='post' action="{{ route('sezionali') }}" id='frm_sezionali1' name='frm_sezionali1' autocomplete="off">	
		<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
		<input type='hidden' name='edit_elem' id='edit_elem'>

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
					
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">Mail per scadenze contratti</span>
						  </div>
						  <input type="email" class="form-control" placeholder="Mail Scadenze" aria-label="Mail scadenze" name="mail_scadenze" id="mail_scadenze">
						</div>			
					</div>
					
					<div class="col-md-6">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">Mail invio fatture</span>
						  </div>
						  <input type="email" class="form-control" placeholder="Mail fatture" aria-label="Mail fatture" name="mail_fatture" id="mail_fatture" >
						</div>			
					</div>
					
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<button type="submit" class="btn btn-success" >Crea/Modifica Azienda</button>
						<button type="button" class="btn btn-secondary" onclick="$('#div_definition').toggle(150)">
						Chiudi
						</button>
						
					</div>
				</div>	
				
				
			<hr>	
		</div>	
	</form>		
</div>