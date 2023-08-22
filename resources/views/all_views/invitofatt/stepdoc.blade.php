<!-- SEZIONE DOCUMENTI !-->
<div id='div_sez_1' class="sezioni mb-5">
	<div class="card-body">
		<div class="row mb-3">
	
			
			<div class="col-md-4">
			  <div class="btn-group">
				<button type="button" class="btn btn-info" style='padding-top: 16px;padding-bottom:16px'>Aggiungi modalità di pagamento</button>
				<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
				  <span class="sr-only">Toggle Dropdown</span>
				</button>
				<div class="dropdown-menu" role="menu">
					<?php
					for ($sca=0;$sca<=count($lista_pagamenti)-1;$sca++) {?>
						<a class="dropdown-item" href="#" onclick="add_pagamento(<?php echo $lista_pagamenti[$sca]['id'];?>)">{{$lista_pagamenti[$sca]['descrizione']}}
						</a>
					<?php } ?>
				</div>
			  </div>			
			</div>


		</div>	

		<div id='div_pagamenti' class='container-fluid'>
			<!-- caricamento dati da db, in prima istanza ed in caso di aggiunte e modifiche viene gestito da js !-->
			
			<!--
				Inoltre, all'atto della creazione della fattura, vengono precaricate le tipologie di pagamento ereditandole dalla anagrafica della ditta prescelta
			!-->
			<?php
			
				$id_group=0;
				for ($sca=0;$sca<=count($elenco_pagamenti_presenti)-1;$sca++) {
				
					$id_group++;
					$js="if (!confirm('Sicuri di eliminare il tipo di pagamento?')) return false; else $('#div_p$id_group').remove()"; 
					$btn_dele="<a href='#' onclick=\"$js\">";
					$btn_dele.="<i class='fas fa-trash-alt'></i>";
					$btn_dele.="</a>";

					$color="";$descr="";
					$disp1="display:none";$disp2="display:none";
					$req1="";$req2="required";
					
					
					$tipo=$elenco_pagamenti_presenti[$sca]['tipo_pagamento'];
					
					if ($tipo=="1") {
						$disp1="";
						$req1="required";
						$descr="Contanti";
						$color="primary";
					}
					if ($tipo=="2") {
						$descr="Bancomat";
						$color="secondary";
					}
					if ($tipo=="3") {
						$descr="Assegno";
						$color="info";
					}
					if ($tipo=="4") {
						$req2="";
						$disp2="";
						$descr="Bonifico";
						$color="warning";
					}
				?>


				<div class='border border-{{$color}} p-2 mb-1' id='div_p{{$id_group}}'>
					<input type='hidden' name='tipo_pagamento[]' value='{{$tipo}}'>
					<div class='alert alert-{{$color}}' role="alert">
						<?php echo $btn_dele?> {{$descr}}
					</div>

					<div class='row mb-3'>
						<div class="col-md-4">
							
							<div class="form-floating">
								<input class="form-control dp" 
								name="data_scadenza[]" type="date" {{$req2}} value="{{$elenco_pagamenti_presenti[$sca]['data_scadenza']}}" />
								<label for="data_pagamento">Data scadenza</label>
							</div>
							
						</div>

						<div class="col-md-4">
							<div class="form-floating">
								<input class="form-control importi" name="importo[]" type="text" placeholder="Importo" {{$req2}} value="{{$elenco_pagamenti_presenti[$sca]['importo']}}"/>
								<label for="importo" >Importo</label>
							</div>		
						</div>
				
				
					<div class="col-md-4" style='{{$disp1}}'>
						<div class="form-floating">
							<input class="form-control" name="persona[]" type="text" placeholder="" {{$req1}} value="{{$elenco_pagamenti_presenti[$sca]['persona']}}" />
							<label for="persona" >Persona che riscuote</label>
						</div>		
					</div>
				
					<div class="col-md-4" style='{{$disp2}}'>
						<div class="form-floating">
							<input class="form-control" name="coordinate[]" type="text" placeholder="" value="{{$elenco_pagamenti_presenti[$sca]['coordinate']}}" />
							<label for="Coordinate" >Coordinate bancarie</label>
						</div>		
					</div>
				

				
					</div>
				</div>
			<?php } ?>
			<!-- fine caricamento !-->
		</div>

		
		<div class="float-sm-right">		
			<button type="submit" name='btn_pagamenti' id='btn_pagamenti' onclick="$('.step').val('2')" class="btn btn-success btn-lg" value='btn_pagamenti'>Salva e vai avanti</button>

			<button type="button" name='btn_prec' id='btn_prec' onclick="set_step('0')" class="btn btn-secondary btn-lg">Indietro</button>
			
		</div>
	</div>
</div> 