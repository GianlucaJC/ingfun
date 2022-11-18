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
					<option value='0'
					<?php if ($candidati[0]['rdc']=="0") echo "selected";?>
					>No</option>
					<option value='1'
					<?php if ($candidati[0]['rdc']=="1") echo "selected";?>
					>Sì</option>
				</select>
				<label for="rdc">Reddito di cittadinanza</label>
				</div>
			</div>
		<div class="col-md-4">
			<div class="form-floating">
				<input class="form-control" id="cat_pro" name='cat_pro' type="text" placeholder="categoria protetta" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"  maxlength=5 value="{{ $candidati[0]['cat_pro'] }}"  />
				<label for="telefono">Categoria protetta (%)</label>
			</div>
		</div>
			
		</div>
		

		<div class="row mb-3">							
			<div class="col-md-3">
			  <div class="form-floating mb-3 mb-md-0">
				
				<select class="form-select" id="titolo_studio" aria-label="Titolo di studio" name='titolo_studio' >
					<option value=''>Select...</option>
					<option value='1' 
					<?php if ($candidati[0]['titolo_studio']=="1") echo "selected";?>
					>Licenza Media
					</option>
					<option value='2'
					<?php if ($candidati[0]['titolo_studio']=="2") echo "selected";?>
					>Diploma Istituto Superiore</option>
					<option value='3'
					<?php if ($candidati[0]['titolo_studio']=="3") echo "selected";?>
					>Laurea</option>
				</select>
				<label for="titolo_studio">Titolo di studio</label>
				</div>
			</div>

			<div class="col-md-5">
			  <div class="form-floating mb-3 mb-md-0">
				
				<input class="form-control" id="istituto_conseguimento" name='istituto_conseguimento' type="text" placeholder="Istituto"  maxlength=150 value="{{$candidati[0]['istituto_conseguimento']}}"  />
				<label for="istituto_conseguimento">Istituto di conseguimento</label>
				</div>
			</div>
		<?php
			$anno_mese=$candidati[0]['anno_mese'];
			$arr=explode("-",$anno_mese);
			$anno="";$mese="";
			if (count($arr)>1) {
				$anno=$arr[0];$mese=$arr[1];
			}
		?>

			<div class="col-md-2">
			  <div class="form-floating mb-3 mb-md-0">
				
				<select class="form-select" id="mese" aria-label="mese" name='mese' >
					<option value=''>Select...</option>
					<option value='01'
					<?php if ($mese=="01") echo "selected";?>
					>Gennaio</option>
					<option value='02'
					<?php if ($mese=="02") echo "selected";?>
					>Febbraio</option>
					<option value='03'
					<?php if ($mese=="03") echo "selected";?>
					>Marzo</option>
					<option value='04'
					<?php if ($mese=="04") echo "selected";?>
					>Aprile</option>
					<option value='05'
					<?php if ($mese=="05") echo "selected";?>
					>Maggio</option>
					<option value='06'
					<?php if ($mese=="06") echo "selected";?>
					>Giugno</option>
					<option value='07'
					<?php if ($mese=="07") echo "selected";?>
					>Luglio</option>
					<option value='08'
					<?php if ($mese=="08") echo "selected";?>
					>Agosto</option>
					<option value='09'
					<?php if ($mese=="09") echo "selected";?>
					>Settembre</option>
					<option value='11'
					<?php if ($mese=="10") echo "selected";?>
					>Ottobre</option>
					<option value='11'
					<?php if ($mese=="11") echo "selected";?>
					>Novembre</option>
					<option value='12'
					<?php if ($mese=="12") echo "selected";?>
					>Dicembre</option>


				</select>
				<label for="titolo_studio">Mese</label>
			  </div>
			 </div> 
			<div class="col-md-2">
				<div class="form-floating">
					<select class="form-select" id="anno" aria-label="anno" name='anno' >
					<option value=''>Select...</option>
					<?php
						$inizio=date("Y");$fine=$inizio-60;
						for ($sca=$inizio;$sca>=$fine;$sca--) {
							echo "<option value='$sca'";
							if ($anno==$sca) echo " selected ";
							echo ">$sca</option>";
						}
					?>	
					</select>
					<label for="anno">Anno</label>

				</div>
			</div>
			
		</div>


		<div class="row mb-3">							
			<div class="col-md-4">
			  <?php
				$arr_patenti=explode(";",$candidati[0]['patenti']);
			  ?>
			  <div class="form-floating mb-3 mb-md-0">
				
				<select class="form-select select2" id="patenti" aria-label="Patenti" name='patenti[]' multiple="multiple" >
					<option value='AM'
					<?php if (in_array("AM",$arr_patenti)) echo " selected "; ?>
					>AM</option>
					<option value='A1'
					<?php if (in_array("A1",$arr_patenti)) echo " selected "; ?>
					>A1</option>
					<option value='A2'
					<?php if (in_array("A2",$arr_patenti)) echo " selected "; ?>
					>A2</option>
					<option value='A'
					<?php if (in_array("A",$arr_patenti)) echo " selected "; ?>
					>A</option>
					<option value='B'
					<?php if (in_array("B",$arr_patenti)) echo " selected "; ?>
					>B</option>
					<option value='BE'
					<?php if (in_array("BE",$arr_patenti)) echo " selected "; ?>
					>BE</option>
					<option value='C1'
					<?php if (in_array("C1",$arr_patenti)) echo " selected "; ?>
					>C1</option>
					<option value='C1E'
					<?php if (in_array("C1E",$arr_patenti)) echo " selected "; ?>
					>C1E</option>
					<option value='C'
					<?php if (in_array("C",$arr_patenti)) echo " selected "; ?>
					>C</option>
					<option value='C3'
					<?php if (in_array("C3",$arr_patenti)) echo " selected "; ?>
					>C3</option>
					<option value='D1'
					<?php if (in_array("D1",$arr_patenti)) echo " selected "; ?>
					>D1</option>
					<option value='D1E'
					<?php if (in_array("D1E",$arr_patenti)) echo " selected "; ?>
					>D1E</option>
					<option value='D'
					<?php if (in_array("D",$arr_patenti)) echo " selected "; ?>
					>D</option>
					<option value='DE'
					<?php if (in_array("DE",$arr_patenti)) echo " selected "; ?>
					>DE</option>

				</select>
				<b>Patenti</b>
				</div>
			</div>

			<div class="col-md-8">

				  <div class="form-group">
					
					<input type="range" class="custom-range" id="capacita" name="capacita" value="{{$candidati[0]['capacita']}}" oninput="$('#out').html(this.value)">
					<label for="capacita">Livello Capacità</label> 
					<span id='out'>{{$candidati[0]['capacita']}}</span>
					
					
				  </div>

			</div>

		</div>	


		<div class="row mb-3">							
			<div class="col-md-4">
				<div class="form-check ml-1">
				<?php
					$lib=$candidati[0]['libero_p'];
					$libp="";
					if ($lib=="1") $libp=" checked ";
				?>
				  <input class="form-check-input" type="checkbox" value="1" id="libero_p" name="libero_p" {{$libp}}>
				  <label class="form-check-label" for="libero_p" >
					Libero professionista
				  </label>
				</div>

			</div>

			<div class="col-md-4">

			  <div class="form-floating mb-3 mb-md-0">
				<select class="form-select" id="tipologia_contr" aria-label="Tipologia contratto" name='tipologia_contr' >
					<option value=''>Select...</option>
					@foreach($tipologia_contr as $tipologia_c)
							<option value='{{ $tipologia_c->id }}'
							<?php
							
							if ($tipologia_c->id==$candidati[0]['tipo_contratto'])  echo " selected "; 
							
							?>
							>{{ $tipologia_c->descrizione }}</option>

					@endforeach							
				</select>
				<label for="tipologia_contr">Tipologia contratto</label>
				<a href="{{ route('tipologia_contr') }}" class="link-primary" target='_blank' onclick="window.open(this.href, 
                         'newwindow', 
                         'width=1024,height=600,left=200'); 
						 $('.up').hide();$('#div_up8').show();
						 return false;">
					Definisci nuova
				</a>
				<span id='div_up8' class='up' style='display:none'>
					<a href='javascript:void(0)' class='ml-2' onclick='refresh_tipologia_contr()'>
						<font color='green'>
							<i class="fas fa-sync-alt"></i>
						</font>	
					</a>	
				</span>	
				<a href='javascript:void(0)' class='link-danger' onclick="storia('tipologia_contr',{{$id_cand}})">
				<i class="fa fa-history ml-2" title='storicizzazione' ></i>
				</a>				
				</div>
			</div>
			
			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">
					<input type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" class="form-control" name="ore_sett" id="ore_sett" value="{{$candidati[0]['ore_sett']}}" />		  
					<label for="ore_sett">Ore settimanali</label>
				</div>		
				<a href='javascript:void(0)' class='link-danger' onclick="storia('ore_sett',{{$id_cand}})">
				<i class="fa fa-history ml-2" title='storicizzazione' ></i>
				</a>
				
			</div>	
		</div>		


		
			<div class="row mb-3">							
				<div class="col-md-6">
				  <div class="form-floating mb-3 mb-md-0">
					
					<select class="form-select" id="appartenenza" aria-label="Appartenenza" name='appartenenza' >
						<option value=''>Select...</option>
						<option value='1'
						@if ($candidati[0]['appartenenza']=="1") 
							selected 
						@endif
						>SOCIALE</option>
						<option value='2'
						@if ($candidati[0]['appartenenza']=="2") 
							selected 
						@endif
						>SUB APPALTO</option>
						
					</select>
					<label for="appartenenza">Appartenenza</label>
					
					</div>
				</div>

				
				<div class="col-md-6">
					<div class="form-floating mb-3 mb-md-0">
						<input class="form-control" id="subappalto" name='subappalto' type="text"  maxlength=150 value="{{$candidati[0]['subappalto']}}"  />
						
						<label for="subappalto">Subappalto (Se previsto)</label>
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
							<?php
							
							if ($soc->id==$candidati[0]['soc_ass'])  echo " selected "; 
							
							?>							
							>{{ $soc->descrizione }}</option>

					@endforeach							
				</select>
				<label for="soc_ass">Società di assunzione</label>
				<a href="{{ route('societa_assunzione') }}" class="link-primary" target='_blank' onclick="$('.up').hide();$('#div_up3').show()">
					Definisci nuova
				</a>
				<span id='div_up3' class='up' style='display:none'>
					<a href='javascript:void(0)' class='ml-2' onclick='refresh_soc()'>
						<font color='green'>
							<i class="fas fa-sync-alt" title='refresh'></i>
						</font>	
					</a>	
				</span>
				
				
				<a href='javascript:void(0)' class='link-danger' onclick="storia('soc_ass',{{$id_cand}})">
				<i class="fa fa-history ml-2" title='storicizzazione' ></i>
				</a>
				
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
							<option value='{{ $area->id }}'
							<?php
							
							if ($area->id==$candidati[0]['area_impiego'])  echo " selected "; 
							
							?>							
							>{{ $area->descrizione }}</option>
					@endforeach						
				</select>
				<label for="area_impiego">Area di impiego</label>
				<a href="{{ route('area_impiego') }}" class="link-primary" target='_blank' onclick="$('.up').hide();$('#div_up5').show()">
					Definisci nuova
				</a>
				<span id='div_up5' class='up' style='display:none'>
					<a href='javascript:void(0)' class='ml-2' onclick='refresh_area()'>
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
					@foreach($mansione as $mans)
							<option value='{{ $mans->id }}'
							<?php
							
							if ($mans->id==$candidati[0]['mansione'])  echo " selected "; 
							
							?>							

							>{{ $mans->descrizione }}</option>
					@endforeach						
				</select>
				<label for="mansione">Mansione</label>
				<a href="{{ route('mansione') }}" class="link-primary" target='_blank' onclick="$('.up').hide();$('#div_up6').show()">
					Definisci nuova
				</a>
				<span id='div_up6' class='up' style='display:none'>
					<a href='javascript:void(0)' class='ml-2' onclick='refresh_mansione()'>
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
				
				<select class="form-select" id="centro_costo" aria-label="Centro di Costo" name='centro_costo' >
					<option value=''>Select...</option>
					@foreach($centri_costo as $costo)
							<option value='{{ $costo->id }}'
							
							<?php
							if ($costo->id==$candidati[0]['centro_costo'])  echo " selected "; 
							
							?>							
							
							>{{ $costo->descrizione }}</option>

					@endforeach						
				</select>
				<label for="centro_costo">Centro di Costo</label>
				<a href="{{ route('costo') }}" class="link-primary" target='_blank' onclick="$('.up').hide();$('#div_up4').show()">
					Definisci nuovo
				</a>
				<span id='div_up4' class='up' style='display:none'>
					<a href='javascript:void(0)' class='ml-2' onclick='refresh_costo()'>
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
					@foreach($ccnl as $cc)
							<option value='{{ $cc->id }}'
							<?php
							if ($cc->id==$candidati[0]['contratto'])  echo " selected "; 
							
							?>							
							
							
							>{{ $cc->descrizione }}</option>

					@endforeach							
				</select>
				<label for="contratto">Contratto</label>
				
				<a href="{{ route('ccnl') }}" class="link-primary" target='_blank' onclick="$('.up').hide();$('#div_up7').show()">
					Definisci nuovo
				</a>
				<span id='div_up7' class='up' style='display:none'>
					<a href='javascript:void(0)' class='ml-2' onclick='refresh_ccnl()'>
						<font color='green'>
							<i class="fas fa-sync-alt"></i>
						</font>	
					</a>	
				</span>	
				<a href='javascript:void(0)' class='link-danger' onclick="storia('contratto',{{$id_cand}})">
				<i class="fa fa-history ml-2" title='storicizzazione' ></i>
				</a>				
				</div>
			</div>
		</div>
		@if ($id_cand!=0 || $from!="0" )
			<div class="row mb-3">							
				<div class="col-md-6">
					<div class="form-floating">
						<input class="form-control" id="livello" name='livello' type="text" value="{{ $candidati[0]['livello']}}" maxlength=40 />
						<label for="livello">Livello</label>
						
						<a href='javascript:void(0)' class='link-danger' onclick="storia('livello',{{$id_cand}})">
						<i class="fa fa-history ml-2" title='storicizzazione' ></i>
						</a>				
					</div>
				</div>
				<div class="col-md-6">
				  <div class="form-floating mb-3 mb-md-0">
					
					<select class="form-select" id="tipo_contr" aria-label="Tipo contratto" name='tipo_contr' >
						<option value=''>Select...</option>
						@foreach($tipoc as $tipo)
								<option value='{{ $tipo->id }}'
								
								<?php
								if ($tipo->id==$candidati[0]['tipo_contr'])  echo " selected "; 
								
								?>										
								>{{ $tipo->descrizione }}</option>
						@endforeach		
					</select>
					<label for="contratto">Tipo Contratto</label>
					<a href="{{ route('tipo_contratto') }}" class="link-primary" target='_blank' onclick="$('.up').hide();$('#div_up1').show()">
						Definisci nuovo
					</a>
					
					<span id='div_up1' class='up' style='display:none'>
						<a href='javascript:void(0)' class='ml-2' onclick='refresh_tipoc()'>
							<font color='green'>
								<i class="fas fa-sync-alt"></i>
							</font>	
						</a>	
					</span>
						<a href='javascript:void(0)' class='link-danger' onclick="storia('tipo_contr',{{$id_cand}})">
						<i class="fa fa-history ml-2" title='storicizzazione' ></i>
						</a>						
					
					</div>
				</div>						
			</div>	
			<div class="row mb-3">							
				<div class="col-md-4">
				  <div class="form-floating mb-3 mb-md-0">
						<select class="form-select" id="categoria_legale" aria-label="Categoria legale" name='categoria_legale' >
							<option value=''>Select...</option>						
									<option value='0'
									<?php
									if ($candidati[0]['categoria_legale']=="0")  echo " selected "; 
									?>
									>Operaio</option>

									<option value='1'
									<?php
									if ($candidati[0]['categoria_legale']=="1")  echo " selected "; 
									?>
									>Impiegato</option>
							
						</select>
						<label for="categoria_legale">Categoria Legale</label>

					</div>
				</div>

				<div class="col-md-4">
				  <div class="form-floating mb-3 mb-md-0">
						<select class="form-select" id="qualificato" aria-label="qualificato" name='qualificato' >
							<option value=''>Select...</option>						
									<option value='1'
									<?php
									if ($candidati[0]['qualificato']=="1")  echo " selected "; 
									?>
									>SI</option>

									<option value='0'
									<?php
									if ($candidati[0]['qualificato']=="0")  echo " selected "; 
									?>
									>NO</option>
							
						</select>
						<label for="qualificato">Qualificato</label>

					</div>
				</div>
				
				<div class="col-md-4">
					<div class="form-floating">
						<input class="form-control" id="codice_qualifica" name='codice_qualifica' type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" value="{{ $candidati[0]['codice_qualifica']}}"  maxlength=50 />

						<label for="codice_qualifica">Codice Qualifica</label>
					</div>
				</div>				
			</div>	
				
		@endif

	<div class="row mb-3">
		<div class="col-md-4">
			<div class="form-floating">
				<input class="form-control" id="netto_concordato" name='netto_concordato' type="text" value="{{ $candidati[0]['netto_concordato'] }}" maxlength=30 />
				<label for="netto_concordato">Netto concordato</label>
			</div>
		</div>

		<div class="col-md-4">
			<div class="form-floating">
				<input class="form-control" id="costo_azienda" name='costo_azienda' type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" value="{{ $candidati[0]['costo_azienda']}}"  />

				<label for="costo_azienda">Costo azienda</label>
			</div>
		</div>

		<div class="col-md-4">
			<div class="form-floating">
				<input class="form-control" id="zona_lavoro" name='zona_lavoro' type="text"  maxlength=100 value="{{ $candidati[0]['zona_lavoro']}}"  />
				<label for="zona_lavoro">Zona di lavoro</label>
				<a href='javascript:void(0)' class='link-danger' onclick="storia('zona_lavoro',{{$id_cand}})">
				<i class="fa fa-history ml-2" title='storicizzazione' ></i>
				</a>				
			</div>
		</div>
	</div>	

		<div class="row mb-3">
			<div class="col-md-6">
				<div class="form-floating">
					<input class="form-control" id="n_scarpe" name='n_scarpe' type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" value="{{ $candidati[0]['n_scarpe']}}"  />
					<label for="n_scarpe">N° Scarpe</label>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-floating">
					<input class="form-control" id="taglia" name='taglia' type="text" value="{{$candidati[0]['taglia']}}" maxlength=20/>

					<label for="taglia">Taglia</label>
				</div>
			</div>


		</div>

		@if ($id_cand!=0 || $from!="0")
			<div class="row mb-3">
				<div class="col-md-12">
					<div class="form-floating mb-3 mb-md-0">
						<input class="form-control" id="affiancamento" name='affiancamento' type="text"  maxlength=150 value="{{$candidati[0]['affiancamento']}}"  />
						<label for="affiancamento">Affiancamento</label>
					</div>

				</div>		
			</div>
		@endif
		
		
		@php ($req="")
		@if (($id_cand=="0" && $from=="1")) 
			@php  ($req="required")
		@endif
		<?php $w_col=12; ?>
		<div class="row mb-3">
			@if ($id_cand!=0 || ($id_cand=="0" && $from=="1"))
				<?php $w_col=3; ?>
				<div class="col-md-3">
					<div class="form-floating">
						<input class="form-control" id="data_inizio" name='data_inizio' type="date"   value="{{ $candidati[0]['data_inizio']}}"  {{$req}} />
						<label for="data_inizio">Data inizio</label>
					</div>
				<a href='javascript:void(0)' class='link-danger' onclick="storia('data_inizio',{{$id_cand}})">
				<i class="fa fa-history ml-2" title='storicizzazione' ></i>
				</a>

				</div>
				
				<div class="col-md-3">
					<div class="form-floating">
						<input class="form-control" id="data_fine" name='data_fine' type="date"   value="{{ $candidati[0]['data_fine']}}"  />
						<label for="data_fine">Data fine</label>
					</div>
				<a href='javascript:void(0)' class='link-danger' onclick="storia('data_fine',{{$id_cand}})">
				<i class="fa fa-history ml-2" title='storicizzazione' ></i>
				</a>
				</div>				
			@endif
			

			@if ($w_col=="3") 
				<div class="col-md-3">
					<div class="form-floating">
						<input class="form-control" id="proroghe" name='proroghe' type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" value="{{ $candidati[0]['proroghe']}}"  />

						<label for="proroghe">N° Proroghe</label>
					</div>
				</div>							
			@endif			
			
			@if (!($id_cand=="0" && $from=="1")) 

				<div class="col-md-{{$w_col}}">
				  <div class="form-floating mb-3 mb-md-0">
					@php ($dis="")
					@if ($candidati[0]['status_candidatura']>=3)
						@php ($dis="disabled")
					@endif


					<select class="form-select" id="status_candidatura" aria-label="status_candidatura" name='status_candidatura' {{$dis}} >
						<option value=''>Select...</option>
						<option value='1'
							<?php
							
							if ($candidati[0]['status_candidatura']=="1" || $candidati[0]['status_candidatura']==null)  echo " selected "; 
							
							?>							
						
						>GESTIONE</option>
						<option value='2'
							<?php
							
							if ($candidati[0]['status_candidatura']=="2")  echo " selected "; 
							
							?>							

						>RESPINTA</option>
						@if ($candidati[0]['status_candidatura']>=3)
							<option value='3'
								<?php
								if ($candidati[0]['status_candidatura']=="3")  echo " selected "; 
								?>							
							
							>ASSUNZIONE</option>

							<option value='4'
								<?php
								if ($candidati[0]['status_candidatura']=="4")  echo " selected "; 
								?>							
							
							>DIMISSIONI</option>

							<option value='5'
								<?php
								if ($candidati[0]['status_candidatura']=="5")  echo " selected "; 
								?>							
							
							>LICENZIAMENTO</option>							

							<option value='6'
								<?php
								if ($candidati[0]['status_candidatura']=="6")  echo " selected "; 
								?>							
							
							>SCADENZA NATURALE</option>	
						@endif
					</select>
					<label for="status_candidatura">Status Candidatura</label>
					</div>

			</div>
			@else
				<div class="col-md-{{$w_col}}">
					  <div class="form-floating mb-3 mb-md-0">
						<select class="form-select" id="status_c" aria-label="status_c"  disabled >
							<option value='3'>ASSUNZIONE</option>
						</select>
						<label for="status_c">Status Candidatura</label>
						
						
						<input type='hidden' id='status_candidatura' name='status_candidatura' value='3'>
					</div>
				</div>				
			@endif
			

		</div>	

		<div class="row mb-3">
			<div class="col-md-12">
				<div class="form-floating">
					<textarea class="form-control" id="note" name="note" rows="4">{{$candidati[0]['note']}}</textarea>
					<label for="note">Note e considerazioni</label>
				</div>
			</div>



		</div>					

</div>
<!-- End Right Window !-->