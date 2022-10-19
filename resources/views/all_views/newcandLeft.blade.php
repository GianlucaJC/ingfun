<!-- Left Window !-->
<div class="col-md-6">
	<center><h4>DATI ANAGRAFICI</h4></center>
	<div class="row mb-3">
		<div class="col-md-6">
			<div class="form-floating">
				<input class="form-control" id="cognome" name='cognome' type="text" placeholder="Inserisci il tuo cognome" required maxlength=40 onkeyup="this.value = this.value.toUpperCase();"  value="{{ $candidati[0]['cognome']}}"  />
				<label for="cognome">Cognome*</label>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-floating mb-3 mb-md-0">
				<input class="form-control" id="nome" name='nome' type="text" placeholder="Inserisci il tuo nome" maxlength=60 required onkeyup="this.value = this.value.toUpperCase();" value="{{ $candidati[0]['nome']}}"  />
				<label for="nome">Nome*</label>
			</div>
		</div>
	</div>
	<div class="row mb-3">
	
		<div class="col-md-3">
			<div class="form-floating mb-3 mb-md-0">
				<select class="form-select" name="sesso" id="sesso" required>
				<option value=''>Select...</option>
					<?php
					echo "<option value='F' ";
					if ($candidati[0]['sesso']=="F") echo " selected "; 
					echo ">F</option>";
					echo "<option value='M' ";
					if ($candidati[0]['sesso']=="M") echo " selected "; 
					echo ">M</option>";
					?>
				</select>
				<label for="sesso">Sesso*</label>
			</div>
		</div>				
		<div class="col-md-9">
			<div class="form-floating">
				<input class="form-control" id="indirizzo" name='indirizzo' type="text" placeholder="Via/Piazza" required maxlength=150 value="{{ $candidati[0]['indirizzo']}}"  />
				<label for="indirizzo">Indirizzo*</label>
			</div>
		</div>
	</div>
	
	<?php if(1==2) { ?>
		<div class="row mb-3">
			<div class="col-md-6">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" id="regione" aria-label="Regione" name='regione' onchange='popola_province(this.value)' placeholder="Regione" required>
						<option value=''>Select...</option>
						<?php

							foreach ($regioni as $reg) {
								$id_regione=$reg->id_regione;		
								$descr_reg=$reg->regione;
								echo "<option value='".$id_regione."' ";
								//if ($regione==$k) echo " selected ";
								echo ">".$descr_reg."</option>";
							}
						?>
					</select>
					<label for="regione">Regione</label>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-control" name="provincia" id="provincia" onchange="popola_comuni(this.value,'0')" aria-label="Provincia" placeholder="Provincia" required>
					<option value=''>Select...</option>
					<?php
						/*
						if (count($province_from_reg)!=0) {
							foreach($province_from_reg as $sigla=>$prov) {
								echo "<option value='".$sigla."'";
								if ($sigla==$provincia) echo " selected ";
								echo ">".$prov."</option>";
							}													
						}
						*/
					?>
					</select>
					<label for="provincia">Provincia</label>
				</div>
			</div>					
			
		</div>	
	<?php } ?>			
	
	<div class="row mb-3">
		<div class="col-md-6">
			<div class="form-floating mb-3 mb-md-0">
			<!--
				<select class="form-select" name="comune" id="comune"  onchange='popola_cap(this.value)' placeholder="Comune" required aria-label="Comune" required >
				<option value=''>Select...</option>
				</select>
			!-->
			
				<select class="form-control" name="comune" id="comune" aria-label="Comune" required  onchange='popola_cap_pro(this.value)'>
					<option value=''>Select...</option>
					<option value="">Altro</option>

					<?php
					
					foreach ($all_comuni as $comuni) {
						$prov=$comuni->provincia;		
						$cap=$comuni->cap;
						$comune=$comuni->comune;
						$value=$cap."|".$prov;
						echo "<option value='$value' ";
						if ($candidati[0]['comune']==$value) echo " selected ";
						echo ">".$comune."</option>";
					}
					?>
				</select>

				
				<label for="comune">Comune*</label>
			</div>	
		</div>

		<div class="col-md-3">
			<div class="form-floating">
				<input class="form-control" id="cap" name='cap' type="text" placeholder="C.A.P." required  maxlength=5 value="{{ $candidati[0]['cap'] }}"  />
				<label for="cap">Cap*</label>
			</div>
		</div>
		
		<div class="col-md-3">
			<div class="form-floating">
				<input class="form-control" id="provincia" name='provincia' type="text" placeholder="Provincia" required  maxlength=10 value="{{ $candidati[0]['provincia'] }}"  />
				<label for="provincia">Provincia*</label>
			</div>
		</div>

	</div>


	<div class="row mb-3">
		<div class="col-md-6">
			<div class="form-floating">
				<input class="form-control" id="codfisc" name='codfisc' type="text" placeholder="C.F." required  maxlength="16" value="{{ $candidati[0]['codfisc']}}" onkeyup="this.value = this.value.toUpperCase();" pattern=".{16,16}" />
				<label for="codfisc">Codice Fiscale*</label>
				<div class="invalid-tooltip">
				  Codice fiscale formalmente non corretto
				</div>							
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-floating">
				<input class="form-control" id="datanasc" name='datanasc' type="date" placeholder="Nato il" required value="{{ $candidati[0]['datanasc']}}"  />
				<label for="datanasc">Data di nascita*</label>
			</div>
		</div>

	</div>


	<div class="row mb-3">
		<div class="col-md-6">
			<div class="form-floating mb-3 mb-md-0">
				<select class="form-control" name="comunenasc" id="comunenasc" aria-label="Comune nascita" required  onchange="popola_pronasc(this.value)">
					<option value=''>Select...</option>
					<option value="">Altro</option>

					<?php
					
					foreach ($all_comuni as $comuni) {
						$istat=$comuni->istat;
						$prov=$comuni->provincia;
						$comunenasc=$comuni->comune;
						$value=$istat."|".$prov;
						echo "<option value='$value' ";
						if ($candidati[0]['comunenasc']==$value) echo " selected ";
						echo ">".$comunenasc."</option>";
					}
					?>php 
				</select>
				<label for="comunenasc">Comune di Nascita*</label>
				
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-floating">
				<input class="form-control" id="pro_nasc" name='pro_nasc' type="text" placeholder="Provincia" required maxlength=10 value="{{ $candidati[0]['pro_nasc']}}"  />
				<label for="pro_nasc">Provincia di Nascita*</label>
			</div>
		</div>

	</div>

	<div class="row mb-3">
		<div class="col-md-6">
			<div class="form-floating">
				<input class="form-control" id="email" name='email' type="email" placeholder="Email" required maxlength=150 value="{{ $candidati[0]['email']}}" onkeyup="this.value = this.value.toLowerCase();" />
				<label for="email">Email Privata*</label>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-floating">
				<input class="form-control" id="telefono" name='telefono' type="text" placeholder="Telefono" required maxlength=20 value="{{ $candidati[0]['telefono']}}"  />
				<label for="telefono">Telefono privato*</label>
			</div>
		</div>
	</div>

	<div class="row mb-3">
		<div class="col-md-6">
			<div class="form-floating">
				<input class="form-control" id="pec" name='pec' type="email" placeholder="Pec"  maxlength=150 value="{{ $candidati[0]['pec']}}" onkeyup="this.value = this.value.toLowerCase();" />
				<label for="pec">Pec</label>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-floating">
				<input class="form-control" id="iban" name='iban' type="text" placeholder="IBAN" maxlength=27 value="{{ $candidati[0]['iban']}}"  />
				<label for="telefono">IBAN</label>
			</div>
		</div>
	</div>

	<div class="row mb-3" id='div_allega'>
		<div class="col-md-12">
		  

			@if ($id_cand!="0") 
				<a href="javascript:void(0)" onclick="set_sezione(1,{{$id_cand}})">
					<span>Allega/Modifica Curriculum Vitae (solo pdf,doc,jpg)</span>
				</a><hr>
			@else
				<a href="javascript:void(0)" onclick="set_sezione(2,{{$id_cand}})">
					<span>Allega Curriculum Vitae (solo pdf,doc,jpg)</span>
				</a>
			@endif	
			@if ($id_cand!="0" && strlen($candidati[0]['file_curr'])!=0) 
				<div id='div_view_curr'>
					<hr>
					<a href="{{url('/')}}/allegati/curr/{{$candidati[0]['file_curr']}}" target='_blank'>
						<button style='font-size:20px' type="button" class="btn btn-primary"><i class="far fa-file-alt"></i> Vedi Curriculum</button>
					</a>

					<a href="javascript:void(0)" onclick="dele_curr('{{$candidati[0]['file_curr']}}',{{$id_cand}})">
						<button style='font-size:20px' type="button" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Elimina Curriculum allegato</button>
					</a>
				</div>
				
				
			@endif	
		  
		<div class="mb-3" id='body_dialog' style='display:none'>
	
		</div>					


		</div>
	</div>
	
	

	@if ($id_cand!="0") 
		<div id='div_attestati' class='mb-3'>
			<h3>Percorso Formativo</h3>
			<!--
				<a href="{{ route('frm_attestati') }}" target="_blank" class="link-primary" onclick="$('.up').hide();$('#div_up2').show()">
					Definisci nuovo
				</a>
			!-->
			
			<span id='div_up2' class='up' style='display:none'>
				<a href='#' class='ml-2' onclick=''>
					<font color='green'>
						<i class="fas fa-sync-alt"></i>
					</font>	
				</a>	
			</span>			
			<div class="form-check form-switch mt-1 ml-4" style='float:right'>
			  <input class="form-check-input" type="checkbox" id="view_choice" name="view_choice" onchange="$('.voci_no').toggle(150)" >
			  <label class="form-check-label" for="view_choice">Mostra solo voci selezionate</label>
			</div>			
			<ul class="list-group" style='clear:right' >
				@php ($num=0)
				@foreach($formazione as $attestati)
				   @php($num++)
				   <?php
					$check="";$class="voci_no";$disp="display:inline";
					$arr_att=explode(";",$candidati[0]['attestati']);
					if (is_array($arr_att)) {
						if (in_array($attestati->id,$arr_att)) {
							$check=" checked "; 
							$class="";$disp="";
						}	
					}
				   ?>
				   
				  <li class="list-group-item {{ $class }} " style="{{$disp}}">
					<span class='ml-2'></span>
					<input class="form-check-input me-1" type="checkbox" value="{{$attestati->id}}" id="attestato{{$num}}" name='attestato[]' 
					 {{ $check }}>
					<label class="form-check-label" for="attestato{{$num}}">
						@if ($attestati->dele!="0") 
						 <font color='red'><del> 
						@endif
							{{ $attestati->descrizione }}
						@if ($attestati->dele!="0") 
						 </del></font>
						@endif
					</label>
				  </li>
				@endforeach		
			</ul>
		</div>
	@endif	


	@if ($id_cand!="0") 
		<center><h3>Area Documenti</h3></center>
		<div class="row mb-3">
			<div class="col-md-12">
				<div class="form-floating">
					<button type="button" onclick="$('#div_doc').toggle(150)" name='add_doc' id='add_doc' class="btn btn-primary btn-lg btn-block">AGGIUNGI DOCUMENTO</button>
				</div>
			</div>
		</div>
		
		
		<div id='div_doc' style='display:none'>
			<div class="row mb-3">
				<div class="col-md-6">
							
					<div class="form-floating mb-3 mb-md-0">
						<select class="form-select" name="tipo_doc" id="tipo_doc">
						<option value=''>Select...</option>
							<option value="1"
							>DOCUMENTO DI RICONOSCIMENTO</option>
							<option value="2"
							>MODELLO 25</option>
							<option value="3"
							>CONTRATTO</option>
						</select>
						<label for="tipo_doc">Tipo documento*</label>
					</div>
				</div>				

				<div class="col-md-6">
					<div class="form-floating">
						<input class="form-control" id="scadenza" name='scadenza' type="date" />
						<label for="scadenza">Scadenza</label>
					</div>
					
				</div>
			</div>	
			<div class="row mb-3">
				<div class="col-md-12">
					<a href="javascript:void(0)" onclick="set_sezione(2,{{$id_cand}})">
						<button style='font-size:12px' type="button" class="btn btn-info"><i class="far fa-file-alt"></i> Allega documento</button>
					</a>
				</div>
			</div>	
		</div>
		
		<div class="row mb-3" style='overflow-y:scroll;max-height:350px'>
			<div class="col-md-12">
				<table class='table' id='tb_doc'>
					<thead>
						<tr>
							<th>Tipo Documento</th>
							<th>Scadenza</th>
							<th>Azioni</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$doc="";
							
							$all_doc=explode(";",$doc);
							if (strlen($doc)>0) {
								for ($sca=0;$sca<=count($all_doc)-1;$sca++) {
									$doc_id=$all_doc[$sca];
									echo "<tr>";
										echo "<td>";
										echo "</td>";
										echo "<td>";
										echo "</td>";
										echo "<td>";
										?>
											<a href='{{url('/')}}/allegati/doc/{{$id_cand}}/{{$doc_id}}' target='_blank'>
										<?php	
												echo "<button type='button' class='btn btn-info'><i class='far fa-file'></i></button>";
											echo "</a> ";
											
											echo "<a href='javascript:void(0)' onclick=\"remove_doc('$doc_id',$id_cand)\">";
												echo "<button type='button' class='btn btn-danger' alt='Remove'><i class='fas fa-trash'></i></button>";
											echo "</a>";
											
										echo "</td>";
									echo "</tr>";
								}
							}
						?>	
					</tbody>
							
				</table>
			</div>
		</div>

	@endif	

	
	


	
</div>
<!-- end Left Window !-->