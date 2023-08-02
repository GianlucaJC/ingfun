@extends('all_views.viewmaster.index')

@section('title', 'IngFUN')




@section('extra_style')  
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ URL::asset('/') }}plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="{{ URL::asset('/') }}plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
 <!-- per upload -->
  <link href="{{ URL::asset('/') }}dist/css/upload/jquery.dm-uploader.min.css" rel="stylesheet">
  <!-- per upload -->  
  <link href="{{ URL::asset('/') }}dist/css/upload/styles.css?ver=1.1" rel="stylesheet">  
  <link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet">

@endsection


@section('content_main')
<form method='post' action="{{ route('scheda_mezzo') }}" id='frm_mezzo' name='frm_mezzo' autocomplete="off" class="needs-validation" novalidate>

<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>


  <!-- Content Wrapper. Contains page content -->
   <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12">  
			<h1 class="m-0">
				<center>
					<font color='red'>
						SCHEDA MEZZO
					</font>
				</center>
				
			</h1>
			</div>	
		</div>	

      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
	<input type='hidden' name='id_mezzo' id='id_mezzo' value='{{$id_mezzo}}'>
      <div class="container-fluid">
		<div class="row mb-3">
			<?php
				$dis="";
				if ($id_mezzo!="0") $dis="disabled";
			?>
			<div class="col-md-6">
				<div class="form-floating">
					<input class="form-control"  id="targa" name="targa" type="text" placeholder="ID"  value="{{$info_mezzo[0]->targa ?? ''}}" required maxlength=30 {{$dis}}  />
					<label for="targa">TARGA*</label>
				</div>
			</div>			
			<div class="col-md-6">
				<div class="form-floating">
					<input class="form-control" id="numero_interno" name='numero_interno' type="text" required value="{{$info_mezzo[0]->numero_interno ?? ''}}" maxlength=50 />
					<label for="numero_interno">Numero interno*</label>
				</div>
			</div>			
		</div>
		
		<div class='row mb-3'>
			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="tipologia" id="tipologia"  required >
					<option value=''>Select...</option>
					<?php
						
						for ($sca=0;$sca<=count($tipomezzo)-1;$sca++) {
							$id_tipo=$tipomezzo[$sca]['id'];
							$descr_mezzo=$tipomezzo[$sca]['descrizione'];
							
							echo "<option value='".$id_tipo."' ";
							if (isset($info_mezzo[0])) {
								if ($id_tipo==$info_mezzo[0]->tipologia) echo " selected ";
							}
							echo ">".$descr_mezzo."</option>";
						}
						
					?>						
					</select>
					<label for="tipologia">Tipologia*</label>
				</div>
			</div>	

			
			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="marca" id="marca"  required onchange='popola_modelli(this.value)' >
					<option value=''>Select...</option>
					<?php
						
						foreach ($marche as $marca_m) {
							$id_marca=$marca_m->id;
							$descrizione=$marca_m->marca;
							echo "<option value='".$id_marca."' ";
							
							if (isset($info_mezzo[0])) {
								if ($id_marca==$info_mezzo[0]->marca) echo " selected ";
							}	
							echo ">".$descrizione."</option>";
						}
						
					?>						
					</select>
					<label for="marca">Marca*</label>
					<a href="{{ route('marca') }}" class="link-primary" target='_blank' onclick="
							 $('.up').hide();$('#div_up_marca').show()">
						Definisci/modifica
					</a>					
					<span id='div_up_marca' class='up' style='display:none'>
						<a href='javascript:void(0)' class='ml-2' onclick='refresh_marca()'>
							<font color='green'>
								<i class="fas fa-sync-alt"></i>
							</font>	
						</a>	
					</span>	
										
				</div>
			</div>	

			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="modello" id="modello"  required >
						<option value=''>Select...</option>
						<?php
							if ($id_mezzo!=0 && strlen($id_mezzo)!=0) {
								
								foreach($modello as $mod) {
									$id_modello=$mod->id;
									$modello_view=$mod->modello;
									echo "<option value=$id_modello";
									if ($id_modello==$info_mezzo[0]->modello) echo " selected ";
									echo ">$modello_view</option>";
								}
								
							}
						?>
					</select>
					<label for="ditta">Modello*</label>
				</div>
				
				<a href="{{ route('modello') }}" class="link-primary" target='_blank' onclick="
						 $('.up').hide();$('#div_up_modello').show()">
					Definisci/modifica
				</a>					
				<span id='div_up_modello' class='up' style='display:none'>
					<a href='javascript:void(0)' class='ml-2' onclick='refresh_modello()'>
						<font color='green'>
							<i class="fas fa-sync-alt"></i>
						</font>	
					</a>	
				</span>					
			</div>	
			
		</div>		
	


		<div class='row mb-3'>
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="telaio" name='telaio' type="text"  maxlength=100 value="{{$info_mezzo[0]->telaio ?? ''}}" />
					<label for="telaio">Telaio</label>
				</div>
			</div>		

			
			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="alimentazione" id="alimentazione"  required >
					<option value=''>Select...</option>
						<option value='1'
						<?php if (isset($info_mezzo[0]->alimentazione) 
							&& $info_mezzo[0]->alimentazione==1) echo " selected ";
						?>
						>Benzina</option>
						<option value='2'
						<?php if (isset($info_mezzo[0]->alimentazione) 
							&& $info_mezzo[0]->alimentazione==2) echo " selected ";
						
						?>
						>Diesel</option>
					</select>
					<label for="alimentazione">Alimentazione*</label>
				</div>
			</div>	

			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="proprieta" id="proprieta"  required  onchange="check_noleggio(this.value)">
					<option value=''>Select...</option>
						<option value='1'
						<?php if (isset($info_mezzo[0]->proprieta) 
							&& $info_mezzo[0]->proprieta==1) echo " selected ";
						
						?>						
						>Noleggio</option>
						<option value='2'
						<?php if (isset($info_mezzo[0]->proprieta) 
							&& $info_mezzo[0]->proprieta==2) echo " selected ";
						
						?>						
						>Proprietà</option>
						<option value='3'
						<?php if (isset($info_mezzo[0]->proprieta) 
							&& $info_mezzo[0]->proprieta==3) echo " selected ";
						

						?>						
						>Leasing</option>
					</select>
					<label for="proprieta">Proprietà*</label>
				</div>
			</div>
			
		</div>
		
		<?php 
			$disp="display:none";
			if (isset($info_mezzo[0]->proprieta) && $info_mezzo[0]->proprieta==1) $disp="";
		?>				

		<div class='container-fluid border border-primary p-2 mb-3' id='div_noleggio' style='{{$disp}}' >

		 <div id='div_sub_noleggio' >

			<div class='row'>
				<div class="col-md-3">
					<div class="form-floating">
						<input class="form-control" id="da_data_n" name='da_data_n' type="date" value="{{$info_mezzo[0]->da_data_n ?? ''}}"/>
						<label for="da_data_n">Da data noleggio*</label>
					</div>
				</div>

				<div class="col-md-3" style='display:none'>
					<div class="form-floating">
						<input class="form-control" id="a_data_n" name='a_data_n' type="date" value="{{$info_mezzo[0]->a_data_n ?? ''}}"/>
						<label for="da_data_n">A data noleggio*</label>
					</div>
				</div>				
				
				<div class="col-md-3">
					<div class="form-floating">
						<select class="form-select" name="tipo_durata_noleggio" id="tipo_durata_noleggio">
						<option value=''>Select...</option>
						
							<option value='g'
							<?php if (isset($info_mezzo[0]->tipo_durata_noleggio) && $info_mezzo[0]->tipo_durata_noleggio=="g") echo " selected ";?>
							>Giorni</option>
							<option value='m'
							<?php if (isset($info_mezzo[0]->tipo_durata_noleggio) && $info_mezzo[0]->tipo_durata_noleggio=="m") echo " selected ";?>
							>Mesi</option>
							<option value='a'
							<?php if (isset($info_mezzo[0]->tipo_durata_noleggio) && $info_mezzo[0]->tipo_durata_noleggio=="a") echo " selected ";?>
							>Anni</option>
							
						</select>						
						<label for="tipo_durata_noleggio">Tipo durata noleggio*</label>
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-floating">
						<input class="form-control" id="durata_noleggio" name='durata_noleggio' type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" value="{{$info_mezzo[0]->durata_noleggio ?? ''}}"/>
						<label for="durata_noleggio">Durata noleggio*</label>
					</div>
				</div>

				
				<div class="col-md-3">
					<div class="form-floating">
						<input class="form-control" id="km_noleggio" name='km_noleggio' type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" maxlength=11 value="{{$info_mezzo[0]->km_noleggio ?? ''}}" />
						<label for="km_noleggio">Km in dotazione noleggio</label>
					</div>
				</div>					

	
				
			</div>
			<div class='row mt-2'>
				<div class="col-md-2">
					<div class="form-floating">
						<input class="form-control" id="km_noleggio_remote" name='km_noleggio_remote' type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" maxlength=11 value="{{$info_mezzo[0]->km_noleggio_remote ?? ''}}" />
						<label for="km_noleggio_remote">Km attuali noleggio (via APP)*</label>
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-floating">
						<input class="form-control" id="importo_noleggio" name='importo_noleggio' type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" maxlength=11 value="{{$info_mezzo[0]->importo_noleggio ?? ''}}" />
						<label for="importo_noleggio">Importo noleggio*</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-floating">
						<input class="form-control" id="km_alert_mail" name='km_alert_mail' type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" maxlength=11 value="{{$info_mezzo[0]->km_alert_mail ?? ''}}" />
						<label for="km_alert_mail">Soglia Km alert mail</label>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-floating">
						<select class="form-select" name="tipo_alert_noleggio" id="tipo_alert_noleggio">
						<option value=''>Select...</option>
						
							<option value='g'
							<?php if (isset($info_mezzo[0]->tipo_alert_noleggio) && $info_mezzo[0]->tipo_alert_noleggio=="g") echo " selected ";?>
							>Giorni</option>
							<option value='m'
							<?php if (isset($info_mezzo[0]->tipo_alert_noleggio) && $info_mezzo[0]->tipo_alert_noleggio=="m") echo " selected ";?>
							>Mesi</option>
							<option value='a'
							<?php if (isset($info_mezzo[0]->tipo_alert_noleggio) && $info_mezzo[0]->tipo_alert_noleggio=="a") echo " selected ";?>
							>Anni</option>
							
						</select>						
						<label for="tipo_alert_noleggio">Tipo alert mail durata noleggio*</label>
					</div>
				</div>				
				<div class="col-md-3">
					<div class="form-floating">
						<input class="form-control" id="alert_mail" name='alert_mail' type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"  value="{{$info_mezzo[0]->alert_mail ?? ''}}" />
						<label for="alert_mail">Alert mail (da inizio noleggio)</label>
					</div>
				</div>

				
			</div>	
			<div class='row mt-2'>
				<div class="col-md-12">
					<div class="mb-3 mb-md-0">
						<label for="servizi_noleggio">Servizi offerti nel noleggio</label>
						<a href="{{ route('servizi_noleggio') }}" class="ml-3 link-primary" target='_blank' onclick="		 $('.up').hide();$('#div_up_servizi').show()">
							Definisci/modifica
						</a>					
						<span id='div_up_servizi' class='up' style='display:none'>
							<a href='javascript:void(0)' class='ml-2' onclick='refresh_servizi_noleggio()'>
								<font color='green'>
									<i class="fas fa-sync-alt"></i>
								</font>	
							</a>	
						</span>							
						<select class="form-select select2" name="servizi_noleggio[]" id="servizi_noleggio"  multiple >
						
							@foreach($servizi_noleggio as $servizi)
								<option value='{{$servizi->id}}'
								<?php
								if (isset($info_mezzo[0]->servizi_noleggio)) {
									$arr=explode(";",$info_mezzo[0]->servizi_noleggio);
									if (in_array($servizi->id,$arr))
										echo " selected ";
								}
								?>									
								>{{$servizi->descrizione}}</option>
							@endforeach
						</select>
					</div>
				</div>	
			</div>
		  </div>
		  <input type='hidden' name='notifica_alert_noleggio' id='notifica_alert_noleggio'>
			@if (strlen($id_mezzo)!=0 && $id_mezzo!=0)
				<div id='div_new_edit_noleggio'>
				<hr>
					<button type="button" class="btn btn-primary" onclick='new_noleggio()'>Rinnovo Noleggio</button>
					<button type="button" class="btn btn-success" onclick='edit_noleggio()'>Modifica noleggio</button>
				</div>
			@endif	
		</div>
			

		<div class='row mb-3'>
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="posti" name='posti' type="text" maxlength=50  value="{{$info_mezzo[0]->posti ?? ''}}" />
					<label for="posti">Posti</label>
				</div>
			</div>		
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="chilometraggio" name='chilometraggio' type="text" maxlength=50 value="{{$info_mezzo[0]->chilometraggio ?? ''}}" />
					<label for="chilometraggio">Chilometraggio</label>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="catene" id="catene"  required >
					<option value=''>Select...</option>
						<option value=1
						<?php
						if (isset($info_mezzo[0])) {
								if ($info_mezzo[0]->catene==1) echo " selected ";
								
						}?>
						>SI</option>
						<option value=2
						<?php
						if (isset($info_mezzo[0])) {
								if ($info_mezzo[0]->catene==2) echo " selected ";
								
						}?>
						>NO</option>
					</select>
					<label for="catene">Catene*</label>
				</div>
			</div>				
		</div>
		

		<div class='row mb-3'>
			<div class="col-md-6">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="carta_carburante" id="carta_carburante" >
					<option value="">Select...</option>
					<?php
						
						foreach ($carte_c as $carta_c) {
							$id_ref=$carta_c->id;
							$id_carta=$carta_c->id_carta;
							echo "<option value='".$id_ref."' ";
							if (isset($info_mezzo[0])) {
								if ($info_mezzo[0]->carta_carburante==$id_ref) echo " selected ";
							}
							echo ">".$id_carta."</option>";
						}
						
					?>	
					</select>
					<label for="carta_carburante">Carta carburante</label>
					<a href="{{ route('cartac') }}" class="link-primary" target='_blank' onclick="
							 $('.up').hide();$('#div_up_carta').show()">
						Definisci/modifica
					</a>					
					<span id='div_up_carta' class='up' style='display:none'>
						<a href='javascript:void(0)' class='ml-2' onclick='refresh_carta()'>
							<font color='green'>
								<i class="fas fa-sync-alt"></i>
							</font>	
						</a>	
					</span>						
				</div>
			</div>	

			<div class="col-md-6">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="badge_cisterna" id="badge_cisterna" >
					<option value="">Select...</option>
					<?php
						
						foreach ($badges as $badge) {
							$id_ref=$badge->id;
							$id_badge=$badge->id_badge;
							echo "<option value='".$id_ref."' ";
							if (isset($info_mezzo[0])) {
								if ($info_mezzo[0]->badge_cisterna==$id_ref) echo " selected ";
							}
							echo ">".$id_badge."</option>";
						}
						
					?>	
					</select>
					<label for="badge_cisterna">Badge cisterna</label>
					<a href="{{ route('badge') }}" class="link-primary" target='_blank' onclick="
							 $('.up').hide();$('#div_up_badge').show()">
						Definisci/modifica
					</a>					
					<span id='div_up_badge' class='up' style='display:none'>
						<a href='javascript:void(0)' class='ml-2' onclick='refresh_badge()'>
							<font color='green'>
								<i class="fas fa-sync-alt"></i>
							</font>	
						</a>	
					</span>						
				</div>
			</div>	
			
		</div>		
		
		
		<div class='row mb-3'>
			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="telepass" id="telepass" >
					<option value="">Select...</option>
					<?php
						
						foreach ($teles as $telep) {
							$id_ref=$telep->id;
							$id_telepass=$telep->id_telepass;
							echo "<option value='".$id_ref."' ";
							if (isset($info_mezzo[0])) {
								if ($info_mezzo[0]->telepass==$id_ref) echo " selected ";
							}
							echo ">".$id_telepass."</option>";
						}
						
					?>	
					</select>
					<label for="telepass">Telepass</label>
					<a href="{{ route('telepass') }}" class="link-primary" target='_blank' onclick="$('.up').hide();$('#div_up_telepass').show()">
						Definisci/modifica
					</a>					
					<span id='div_up_telepass' class='up' style='display:none'>
						<a href='javascript:void(0)' class='ml-2' onclick='refresh_telepass()'>
							<font color='green'>
								<i class="fas fa-sync-alt"></i>
							</font>	
						</a>	
					</span>						
				</div>
			</div>	
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="data_immatricolazione" name='data_immatricolazione' type="date" required value="{{$info_mezzo[0]->data_immatricolazione ?? ''}}"/>
					<label for="data_immatricolazione">Data immatricolazione*</label>
				</div>
			</div>	
			
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="ultima_revisione" name='ultima_revisione' type="date" value="{{$info_mezzo[0]->ultima_revisione ?? ''}}"  required />
					<label for="ultima_revisione">Ultima revisione</label>
				</div>
			</div>			
		</div>		


		<div class='row mb-3'>

			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="scadenza_assicurazione" name='scadenza_assicurazione' type="date" value="{{$info_mezzo[0]->scadenza_assicurazione ?? ''}}" />
					<label for="scadenza_assicurazione">Scadenza Assicurazione</label>
				</div>
			</div>	


			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="scadenza_bollo" name='scadenza_bollo' type="date" value="{{$info_mezzo[0]->scadenza_bollo ?? ''}}" />
					<label for="scadenza_bollo">Scadenza Bollo</label>
				</div>
			</div>	
			
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="prossimo_tagliando" name='prossimo_tagliando' type="text" maxlength=50 value="{{$info_mezzo[0]->prossimo_tagliando ?? ''}}"/>
					<label for="prossimo_tagliando">Prossimo tagliando</label>
				</div>
			</div>			
		</div>

		<div class='row mb-3'>

			<div class="col-md-12">
				<div class="form-floating">
					<input class="form-control" id="marca_modello_pneumatico" name='marca_modello_pneumatico' type="text" maxlength=80 value="{{$info_mezzo[0]->marca_modello_pneumatico ?? ''}}" />
					<label for="marca_modello_pneumatico">Marca e modello pneumatico</label>
				</div>
			</div>			
		</div>

		<div class='row mb-3'>

			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="misura_pneumatico" name='misura_pneumatico' type="text" maxlength=50 value="{{$info_mezzo[0]->misura_pneumatico ?? ''}}" />
					<label for="misura_pneumatico">Misura pneumatico</label>
				</div>
			</div>	
			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="primo_equipaggiamento" id="primo_equipaggiamento" >
					<option value=''>Select...</option>
						<option value=1
						<?php
						if (isset($info_mezzo[0])) {
								if ($info_mezzo[0]->primo_equipaggiamento==1) echo " selected ";
						}?>						
						>SI</option>
						<option value=2
						<?php
						if (isset($info_mezzo[0])) {
								if ($info_mezzo[0]->primo_equipaggiamento==2) echo " selected ";
						}?>								
						>NO</option>
					</select>
					<label for="primo_equipaggiamento">Primo equipaggiamento</label>
				</div>
			</div>		
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="km_installazione" name='km_installazione' type="text" maxlength=30 value="{{$info_mezzo[0]->km_installazione ?? ''}}"/>
					<label for="km_installazione">Km installazione</label>
				</div>
			</div>				
		</div>

		<div class='row mb-3'>

			<div class="col-md-12">
				<div class="form-floating">
					<input class="form-control" id="officina_installazione" name='officina_installazione' type="text" maxlength=80 value="{{$info_mezzo[0]->officina_installazione ?? ''}}" />
					<label for="officina_installazione">Officina installazione</label>
				</div>
			</div>			
		</div>
		
		<div class='row mb-3'>

			<div class="col-md-12">
				<div class="form-floating">
					<textarea class="form-control" id="anomalia_note" name="anomalia_note" rows="3">{{$info_mezzo[0]->anomalia_note ?? ''}}</textarea>
					<label for="anomalia_note">Anomalie e note</label>
				</div>
			</div>			
		</div>


		<div class='row mb-3'>
			<div class="col-md-3">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="mezzo_riparazione" id="mezzo_riparazione" >
					<option value=''>Select...</option>
						<option value=1
						<?php
						if (isset($info_mezzo[0])) {
								if ($info_mezzo[0]->mezzo_riparazione==1) echo " selected ";
						
						}?>						
						>SI</option>
						<option value=2
						<?php
						if (isset($info_mezzo[0])) {
								if ($info_mezzo[0]->mezzo_riparazione==2) echo " selected ";
						}?>						
						>NO</option>
					</select>
					<label for="mezzo_riparazione">Mezzo in riparazione</label>
				</div>
			</div>
			<div class="col-md-9">
				<div class="form-floating">
					<input class="form-control" id="officina_riferimento" name='officina_riferimento' type="text" maxlength=80 value="{{$info_mezzo[0]->officina_riferimento ?? ''}}" />
					<label for="officina_installazione">Officina riferimento</label>
				</div>
			</div>
		</div>
		<div class='row mb-3'>
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="data_consegna_riparazione" name='data_consegna_riparazione' type="date" value="{{$info_mezzo[0]->data_consegna_riparazione ?? ''}}"/>
					<label for="da_data_n">Data consegna riparazione</label>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="importo_preventivo" name='importo_preventivo' type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" maxlength=11 value="{{$info_mezzo[0]->importo_preventivo ?? ''}}" />
					<label for="importo_preventivo">Importo preventivo</label>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="importo_fattura" name='importo_fattura' type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" maxlength=11 value="{{$info_mezzo[0]->importo_fattura ?? ''}}" />
					<label for="importo_fattura">Importo fattura</label>
				</div>
			</div>			
		</div>	


		<div class='row mb-3'>
			<div class="col-md-3">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="mezzo_marciante" id="mezzo_marciante" >
					<option value=''>Select...</option>
						<option value=1
						<?php
						if (isset($info_mezzo[0])) {
								if ($info_mezzo[0]->mezzo_marciante==1) echo " selected ";
						}?>						
						>SI</option>
						<option value=2
						<?php
						if (isset($info_mezzo[0])) {
								if ($info_mezzo[0]->mezzo_marciante==2) echo " selected ";
						}?>						
						>NO</option>
					</select>
					<label for="mezzo_marciante">Mezzo marciante</label>
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="mezzo_manutenzione" id="mezzo_manutenzione" >
					<option value=''>Select...</option>
						<option value=1
						<?php
						if (isset($info_mezzo[0])) {
							if ($info_mezzo[0]->mezzo_manutenzione==1) echo " selected ";
						}?>								
						>SI</option>
						<option value=2
						<?php
						if (isset($info_mezzo[0])) {
							if ($info_mezzo[0]->mezzo_manutenzione==2) echo " selected ";
						}?>								
						>NO</option>
					</select>
					<label for="mezzo_manutenzione">Mezzo in manutenzione</label>
				</div>
			</div>
		</div>			

        <div class="row">

			<button type="submit" name='btn_save_mezzo' id='btn_save_mezzo' value="save" class="btn btn-success btn-lg btn-block">SALVA</button>  
			
			<a href="{{ route('inventario_flotta') }}">
				<button type="button"  id='back_appalti' class="btn btn-info btn-lg btn-block mt-3">ELENCO MEZZI</button> 
			</a>


			<input type="hidden" value="{{url('/')}}" id="url" name="url">

		</div>
		
			
		
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="modal_story" tabindex="-1" role="dialog" aria-labelledby="info" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title_modal">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id='body_modal'>
        ...
      </div>
      <div class="modal-footer">
		<div id='altri_btn'></div>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div> 


  <!-- /.content-wrapper -->
</form>  
  
  

  
  

  
 @endsection
 
@section('content_plugin')
	<!-- jQuery -->
	<script src="{{ URL::asset('/') }}plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="{{ URL::asset('/') }}plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="{{ URL::asset('/') }}dist/js/adminlte.min.js"></script>
	<script src="{{ URL::asset('/') }}dist/js/scheda_mezzo.js?ver=1.229"></script>
	<!--select2 !-->
	<script src="{{ URL::asset('/') }}plugins/select2/js/select2.full.min.js"></script>
	
	<!-- per upload -->
	<script src="{{ URL::asset('/') }}dist/js/upload/jquery.dm-uploader.min.js"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload/demo-ui.js?ver=1.24"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload/demo-config.js?ver=2.356"></script>
	<!-- fine upload -->		
	

	<!-- inclusione standard
		per personalizzare le dipendenze DataTables in funzione delle opzioni da aggiungere: https://datatables.net/download/
	!-->
	
	<!-- dipendenze DataTables !-->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.css"/>
		 
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.js"></script>
	<!-- fine DataTables !-->

	
@endsection 