<div id='div_definition' style='display:none'>
	<form method='post' action="{{ route('aliquote') }}" id='frm_aliquote1' name='frm_aliquote1' autocomplete="off">	
		<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
		<input type='hidden' name='edit_elem' id='edit_elem'>

		<div class="container-fluid">
			<hr>
			<h4>
				<font color='red'>Definizione Aliquota</font>
			</h4>


				<div class="row">
					<div class="col-md-3">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">% Aliquota</span>
						  </div>
						  <input type="number" class="form-control" placeholder="Aliquota" aria-label="Aliquota iva" name="aliquota" id="aliquota" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"  maxlength=5 required>
						</div>			
					</div>
					
					<div class="col-md-9">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">Descrizione Aliquota</span>
						  </div>
						  <input type="text" class="form-control" placeholder="Descrizione" aria-label="Descrizione aliquota" name="descr_contr" id="descr_contr" maxlength=100 required >
						</div>			
					</div>
					
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<button type="submit" class="btn btn-success" >Crea/Modifica Aliquota</button>
						<button type="button" class="btn btn-secondary" onclick="$('#div_definition').toggle(150)">
						Chiudi
						</button>
						
					</div>
				</div>	
				
				
			<hr>	
		</div>	
	</form>		
</div>