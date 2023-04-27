<div id='div_definition' style='display:none'>
	<form method='post' action="{{ route('giustificativi') }}" id='frm_giust1' name='frm_giust1' autocomplete="off">	
		<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
		<input type='hidden' name='edit_elem' id='edit_elem'>
		<input type="hidden" value="{{url('/')}}" id="url" name="url">
		<div class="container-fluid">


		<div class="row mb-3">							
			<div class="col-md-12">
			  <?php
				//$arr_patenti=explode(";",$candidati[0]['patenti']);
			  ?>
			  <div class="form-floating mb-3 mb-md-0">
				
				<select class="form-select select2" id="lavoratori" aria-label="Elenco lavoratori" name='lavoratori[]' multiple="multiple" >
					<option value='L1'
					<?php 
						//if (in_array("AM",$arr_patenti)) echo " selected "; 
					?>
					>Lavoratore 1</option>
					<option value='L2'
					<?php 
						//if (in_array("AM",$arr_patenti)) echo " selected "; 
					?>
					>Lavoratore 2</option>
					<option value='L3'
					<?php 
						//if (in_array("AM",$arr_patenti)) echo " selected "; 
					?>
					>Lavoratore 3</option>


				</select>
				<b>Scelta Lavoratore/i per assegnazione giustificativi</b>
				</div>
			</div>



		</div>

				
			<div class="row mb-3 mt-5">
				<div class="col-md-4">
					<button type="submit" class="btn btn-success" >Crea Giustificativo</button>
					<button type="button" class="btn btn-secondary" onclick="$('#div_definition').toggle(150)">
					Chiudi
					</button>
					
				</div>	
			</div>
				
			<hr>	
		</div>	
	</form>		
</div>