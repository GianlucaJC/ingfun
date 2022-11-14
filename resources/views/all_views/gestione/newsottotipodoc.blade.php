<div id='div_definition' style='display:none'>
	<form method='post' action="{{ route('sotto_tipo_documento') }}" id='frm_sottotipodoc1' name='frm_sotottipodoc1' autocomplete="off">	
		<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
		<input type='hidden' name='edit_elem' id='edit_elem'>
		<input type='hidden' name='tipodoc1' id='tipodoc1' value='{{$tipodoc1}}'>

		<div class="container-fluid">
			<hr>
			<h4>
				<font color='red'>Definizione Sotto Tipologia Documento</font>
			</h4>
				<div class="row">
					<div class="col-md-4">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">Descrizione</span>
						  </div>
						  <input type="text" class="form-control" placeholder="Descrizione Sotto Tipologia Documento" aria-label="Descrizione" name="descr_contr" id="descr_contr" required>
						</div>			
					</div>
					<div class="col-md-4">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon2">Alias</span>
						  </div>
						  <input type="text" class="form-control" placeholder="Alias per esportazione" aria-label="Alias" name="alias" id="alias" maxlength="30">
						</div>			
					</div>
					
					<div class="col-md-4">
						<button type="submit" class="btn btn-success" >Crea/Modifica Sotto Tipologia Documento</button>
						<button type="button" class="btn btn-secondary" onclick="$('#div_definition').toggle(150)">
						Chiudi
						</button>
						
					</div>
					
				</div>
			<hr>	
		</div>	
	</form>		
</div>