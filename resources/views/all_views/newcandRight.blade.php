<!-- Right Window !-->
<div class="col-md-6">

	<center><h4>DATI SPECIFICI</h4></center>
		<div class="row mb-3">							
			<div class="col-md-4">
			  <div class="form-floating mb-3 mb-md-0">
				
				<select class="form-select" id="stato_occ" aria-label="Stato Occupazione" name='stato_occ' required>
					<option value=''>Select...</option>
					<option value='1' 
					<?php
						if ($candidati[0]['stato_occ']=="1") echo "selected";
					?>	
					>Disoccupato</option>
					<option value='2' 
					<?php
						if ($candidati[0]['stato_occ']=="2") echo "selected";
					?>	
					>Occupato</option>

				</select>
				<label for="stato_occ">Stato Occupazione*</label>
				</div>
			</div>

			<div class="col-md-4">
			  <div class="form-floating mb-3 mb-md-0">
				
				<select class="form-select" id="rdc" aria-label="Reddido Cittadinanza" name='rdc'>
					<option value=''>Select...</option>
					<option value='0'>No</option>
					<option value='1'>Sì</option>
				</select>
				<label for="rdc">Reddito di cittadinanza</label>
				</div>
			</div>
		<div class="col-md-4">
			<div class="form-floating">
				<input class="form-control" id="cat_pro" name='cat_pro' type="text" placeholder="categoria protetta" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"  maxlength=5 value=""  />
				<label for="telefono">Categoria protetta (%)</label>
			</div>
		</div>
			
		</div>
		

		<div class="row mb-3">							
			<div class="col-md-3">
			  <div class="form-floating mb-3 mb-md-0">
				
				<select class="form-select" id="titolo_studio" aria-label="Titolo di studio" name='titolo_studio' >
					<option value=''>Select...</option>
					<option value='1'>Licenza Media</option>
					<option value='2'>Diploma Istituto Superiore</option>
					<option value='3'>Laurea</option>
				</select>
				<label for="titolo_studio">Titolo di studio</label>
				</div>
			</div>

			<div class="col-md-6">
			  <div class="form-floating mb-3 mb-md-0">
				
				<input class="form-control" id="istituto_conseguimento" name='istituto_conseguimento' type="text" placeholder="Istituto"  maxlength=150 value=""  />
				<label for="istituto_conseguimento">Istituto di conseguimento</label>
				</div>
			</div>
		<div class="col-md-3">
			<div class="form-floating">
				<input class="form-control"  id="anno_mese" name='anno_mese' type="month" placeholder="YYYY-MM" maxlength=7/>
				<label for="anno_mese">Anno e mese</label>
			</div>
		</div>
			
		</div>


		<div class="row mb-3">							
			<div class="col-md-4">
			  
			  <div class="form-floating mb-3 mb-md-0">
				
				<select class="form-select select2" id="patenti" aria-label="Patenti" name='patenti[]' multiple="multiple" >
					<option value='AM'>AM</option>
					<option value='A1'>A1</option>
					<option value='A2'>A2</option>
					<option value='A'>A</option>
					<option value='B'>B</option>
					<option value='BE'>BE</option>
					<option value='C1'>C1</option>
					<option value='C1E'>C1E</option>
					<option value='C'>C</option>
					<option value='C3'>C3</option>
					<option value='D1'>D1</option>
					<option value='D1E'>D1E</option>
					<option value='D'>D</option>
					<option value='DE'>DE</option>

				</select>
				<b>Patenti</b>
				</div>
			</div>

			<div class="col-md-8">

				  <div class="form-group">
					
					<input type="range" class="custom-range" id="capacita" name="capacita" value="0" oninput="$('#out').html(this.value)">
					<label for="capacita">Livello Capacità</label> <span id='out'></span>
					
					
				  </div>

			</div>

		</div>	


		<div class="row mb-3">							
			<div class="col-md-4">
				<div class="form-check ml-1">
				  <input class="form-check-input" type="checkbox" value="" id="libero_p" name="libero_p">
				  <label class="form-check-label" for="libero_p">
					Libero professionista
				  </label>
				</div>

			</div>

			<div class="col-md-4">

			  <div class="form-floating mb-3 mb-md-0">
				<select class="form-select" id="tipo_contratto" aria-label="Tipologia contratto" name='tipo_contratto' >
					<option value=''>Select...</option>
					@foreach($tipoc as $tipo)
							<option value='{{ $tipo->id }}'>{{ $tipo->descrizione }}</option>
					@endforeach								
				</select>
				<a href="{{ route('tipo_contratto') }}" class="link-primary" target='_blank' onclick="$('.up').hide();$('#div_up1').show()">
					Definisci nuovo
				</a>
				
				<span id='div_up1' class='up' style='display:none'>
					<a href='#' class='ml-2' onclick='refresh_tipoc()'>
						<font color='green'>
							<i class="fas fa-sync-alt"></i>
						</font>	
					</a>	
				</span>

				
				<label for="tipo_contratto">Tipologia contratto</label>
				</div>
			</div>
			
			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">
					<input type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" class="form-control" name="ore_sett" id="ore_sett"/>		  
					<label for="ore_sett">Ore settimanali</label>
				</div>		
			</div>	
		</div>		

		<div class="row mb-3">							
			<div class="col-md-12">
			  <div class="form-floating mb-3 mb-md-0">
				
				<select class="form-select" id="soc_ass" aria-label="Società assunzione" name='soc_ass' >
					<option value=''>Select...</option>
					@foreach($societa as $soc)
							<option value='{{ $soc->id }}'
							>{{ $soc->descrizione }}</option>

					@endforeach							
				</select>
				<label for="soc_ass">Società di assunzione</label>
				<a href="{{ route('societa_assunzione') }}" class="link-primary" target='_blank' onclick="$('.up').hide();$('#div_up3').show()">
					Definisci nuova
				</a>
				<span id='div_up3' class='up' style='display:none'>
					<a href='#' class='ml-2' onclick='refresh_soc()'>
						<font color='green'>
							<i class="fas fa-sync-alt"></i>
						</font>	
					</a>	
				</span>
				
				</div>
			</div>

			<!--
			<div class="col-md-6">
			  <div class="form-floating mb-3 mb-md-0">
				
				<select class="form-select" id="divisione" aria-label="Divisione" name='divisione' >
					<option value=''>Select...</option>
				</select>
				<label for="divisione">Divisione</label>
				<a href="#" target='_blank'>
					Definisci nuova
				</a>
				</div>
			</div>
			!-->

			
		</div>		

		<div class="row mb-3">							
			<div class="col-md-6">
			  <div class="form-floating mb-3 mb-md-0">
				
				<select class="form-select" id="area_impiego" aria-label="Area impiego" name='area_impiego' >
					<option value=''>Select...</option>
					@foreach($area_impiego as $area)
							<option value='{{ $area->id }}'>{{ $area->descrizione }}</option>
					@endforeach						
				</select>
				<label for="area_impiego">Area di impiego</label>
				<a href="{{ route('area_impiego') }}" class="link-primary" target='_blank' onclick="$('.up').hide();$('#div_up5').show()">
					Definisci nuova
				</a>
				<span id='div_up5' class='up' style='display:none'>
					<a href='#' class='ml-2' onclick='refresh_area()'>
						<font color='green'>
							<i class="fas fa-sync-alt"></i>
						</font>	
					</a>	
				</span>				
				
				</div>
			</div>

			<div class="col-md-6">
			  <div class="form-floating mb-3 mb-md-0">
				
				<select class="form-select" id="mansione" aria-label="Mansione" name='mansione' >
					<option value=''>Select...</option>
				</select>
				<label for="mansione">Mansione</label>
				<a href="#" target='_blank'>
					Definisci nuova
				</a>
				</div>
			</div>
		</div>		

		<div class="row mb-3">							
			<div class="col-md-12">
			  <div class="form-floating mb-3 mb-md-0">
				
				<select class="form-select" id="centro_costo" aria-label="Centro di Costo" name='centro_costo' >
					<option value=''>Select...</option>
					@foreach($centri_costo as $costo)
							<option value='{{ $costo->id }}'
							>{{ $costo->descrizione }}</option>

					@endforeach						
				</select>
				<label for="centro_costo">Centro di Costo</label>
				<a href="{{ route('costo') }}" class="link-primary" target='_blank' onclick="$('.up').hide();$('#div_up4').show()">
					Definisci nuovo
				</a>
				<span id='div_up4' class='up' style='display:none'>
					<a href='#' class='ml-2' onclick='refresh_costo()'>
						<font color='green'>
							<i class="fas fa-sync-alt"></i>
						</font>	
					</a>	
				</span>				
				</div>
			</div>
		</div>		

		<div class="row mb-3">							
			<div class="col-md-12">
			  <div class="form-floating mb-3 mb-md-0">
				
				<select class="form-select" id="contratto" aria-label="Contratto" name='contratto' >
					<option value=''>Select...</option>
				</select>
				<label for="contratto">Contratto</label>
				<a href="#" class="link-primary" target='_blank'>
					Definisci nuovo
				</a>	
				</div>
			</div>
		</div>
		
		<div class="row mb-3">							
			<div class="col-md-6">
				<div class="form-floating">
					<input class="form-control" id="livello" name='livello' type="text" value="" maxlength=40 />
					<label for="livello">Livello</label>
				</div>
			</div>
			<div class="col-md-6">
			  <div class="form-floating mb-3 mb-md-0">
				
				<select class="form-select" id="tipo_contr" aria-label="Tipo contratto" name='tipo_contr' >
					<option value=''>Select...</option>
				</select>
				<label for="contratto">Tipo Contratto</label>
				<a href="#" class="link-primary" target='_blank'>
					Definisci nuovo
				</a>	
				</div>
			</div>						
		</div>					

	<div class="row mb-3">
		<div class="col-md-4">
			<div class="form-floating">
				<input class="form-control" id="netto_concordato" name='netto_concordato' type="text" value="" maxlength=30 />
				<label for="netto_concordato">Netto concordato</label>
			</div>
		</div>

		<div class="col-md-4">
			<div class="form-floating">
				<input class="form-control" id="costo_azienda" name='costo_azienda' type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" value=""  />

				<label for="costo_azienda">Costo azienda</label>
			</div>
		</div>

		<div class="col-md-4">
			<div class="form-floating">
				<input class="form-control" id="zona_lavoro" name='zona_lavoro' type="text"  maxlength=100 value=""  />
				<label for="zona_lavoro">Zona di lavoro</label>
			</div>
		</div>
	</div>	

		<div class="row mb-3">
			<div class="col-md-6">
				<div class="form-floating">
					<input class="form-control" id="n_scarpe" name='n_scarpe' type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" value=""  />
					<label for="n_scarpe">N° Scarpe</label>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-floating">
					<input class="form-control" id="taglia" name='taglia' type="text" value="" maxlength=20/>

					<label for="taglia">Taglia</label>
				</div>
			</div>


		</div>
		
		<div class="row mb-3">
			<div class="col-md-12">
				  <div class="form-floating mb-3 mb-md-0">
					
					<select class="form-select" id="status_candidatura" aria-label="status_candidatura" name='status_candidatura' >
						<option value=''>Select...</option>
						<option value='1'>ASSUNZIONE</option>
					</select>
					<label for="status_candidatura">Status Candidatura</label>
					</div>

			</div>

		</div>	

		<div class="row mb-3">
			<div class="col-md-12">
				<div class="form-floating">
					<textarea class="form-control" id="note" name="note" rows="4"></textarea>
					<label for="note">Note e considerazioni</label>
				</div>
			</div>



		</div>					

</div>
<!-- End Right Window !-->