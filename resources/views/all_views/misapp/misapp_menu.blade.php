<?php
	$disp="";
	if (!isset($result['count'])) {
		//$disp="display:none";
		//echo "<h3><center>Utente non riconosciuto!</center></h3>";
	}	
?>
<div class="list-group" style='{{$disp}}' id='div_servizi'>


<span class="list-group-item list-group-item-action">
	<div class="d-flex w-100 justify-content-between">
		<h4 class="mb-1">Storico, Rifornimenti, Sinistri</h4>
		<i class="fas fa-map-marker-alt fa-2x"></i>
	</div>
	<p class="mb-1"><i>Visiona lo storico dei lavori e la gestione dei rifornimenti</i></p>
	<?php
		$trigger="onclick=\"clickit('New')\"";
		if (!isset($result['count']) ||  $result['count']==0) {
			$trigger="disabled onclick=\"clickit('New')\"";
		}
	?>
	<button type="button" <?php echo $trigger; ?>  class="btn btn-warning position-relative">
		Nuovi
		<span id='new_job1' class="notif position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
			<?php
				if (isset($result['count'])) echo $result['count'];
			?>
		</span>
	</button>                
	<?php
		$trigger="onclick=\"clickit('Rif')\"";
		if (!isset($result['storici_no']) ||  $result['storici_no']==0) {
			$trigger="disabled onclick=\"clickit('Rif')\"";
		}
	?>
	<button type="button" <?php echo $trigger; ?> class="ml-3 btn btn-danger position-relative">
		Rifiutati
		<span id='job_no' class="notif position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
			<?php
				if (isset($result['storici_no'])) echo $result['storici_no'];
			?>
		</span>
	</button>                

	<?php
		$trigger="onclick=\"clickit('Acc')\"";
		if (!isset($result['storici_si']) ||  $result['storici_si']==0) {
			$trigger="disabled onclick=\"clickit('Acc')\"";
		}
	?>
	<button type="button" <?php echo $trigger; ?> class="ml-3 btn btn-success position-relative">
		Accettati
		<span id='job_yes' class="notif position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
			<?php
				if (isset($result['storici_si'])) echo $result['storici_si'];
			?>
	</span>
	</button> 

</span>



<a href="#" class="list-group-item list-group-item-action">
	<div class="d-flex w-100 justify-content-between">
		<h4 class="mb-1">Storico Reperibilità</h4>
		<i class="fas fa-list-alt fa-2x"></i>
	</div>
	<p class="mb-1"><i>Visiona storico delle reperibilità</i></p>
	<?php
		$trigger="onclick=\"clickitr('New')\"";
		if (!isset($result['count_newrep']) ||  $result['count_newrep']==0) {
			$trigger="disabled onclick=\"clickitr('New')\"";
		}
	?>
	<button type="button" <?php echo $trigger; ?>  class="btn btn-warning position-relative">
		Nuove
		<span id='new_job1' class="notif position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
			<?php
				if (isset($result['count_newrep'])) echo $result['count_newrep'];
			?>
		</span>
	</button>  

	<?php
		$trigger="onclick=\"clickitr('Rif')\"";
		if (!isset($result['numrepno']) ||  $result['numrepno']==0) {
			$trigger="disabled onclick=\"clickitr('Rif')\"";
		}
	?>
	<button type="button" <?php echo $trigger; ?>  class="ml-3 btn btn-danger position-relative">
		Rifiutate
		<span class="notif position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
			<?php
				if (isset($result['numrepno'])) echo $result['numrepno'];
			?>
		</span>
	</button>                
	
	<?php
		$trigger="onclick=\"clickitr('Acc')\"";
		if (!isset($result['numrepsi']) ||  $result['numrepsi']==0) {
			$trigger="disabled onclick=\"clickitr('Acc')\"";
		}
	?>
	<button type="button" <?php echo $trigger; ?>  class="ml-3 btn btn-success position-relative">
		Accettate
		<span class="notif position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
			<?php
				if (isset($result['numrepsi'])) echo $result['numrepsi'];
			?>
		</span>
	</button> 

</a>			  
	
<a href="#" class="list-group-item list-group-item-action">
	<div class="d-flex w-100 justify-content-between">
		<h4 class="mb-1">Storico urgenze</h4>
		<i class="fas fa-list-alt fa-2x"></i>
	</div>
	<p class="mb-1"><i>Visiona storico delle urgenze</i></p>
	<?php
		$trigger="onclick=\"clickitu('New')\"";
		if (!isset($result['count_newurg']) ||  $result['count_newurg']==0) {
			$trigger="disabled onclick=\"clickitu('New')\"";
		}
	?>
	<button type="button" <?php echo $trigger; ?>  class="btn btn-warning position-relative">
		Nuove
		<span id='new_job1' class="notif position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
			<?php
				if (isset($result['count_newurg'])) echo $result['count_newurg'];
			?>
		</span>
	</button>  

	<?php
		$trigger="onclick=\"clickitu('Rif')\"";
		if (!isset($result['numurgno']) ||  $result['numurgno']==0) {
			$trigger="disabled onclick=\"clickitu('Rif')\"";
		}
	?>
	<button type="button" <?php echo $trigger; ?>  class="ml-3 btn btn-danger position-relative">
		Rifiutate
		<span class="notif position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
			<?php
				if (isset($result['numurgno'])) echo $result['numurgno'];
			?>
		</span>
	</button>                
	
	<?php
		$trigger="onclick=\"clickitu('Acc')\"";
		if (!isset($result['numurgsi']) ||  $result['numurgsi']==0) {
			$trigger="disabled onclick=\"clickitu('Acc')\"";
		}
	?>
	<button type="button" <?php echo $trigger; ?>  class="ml-3 btn btn-success position-relative">
		Accettate
		<span class="notif position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
			<?php
				if (isset($result['numurgsi'])) echo $result['numurgsi'];
			?>
		</span>
	</button> 

</a>


<a href="#" class="list-group-item list-group-item-action">
	<div class="d-flex w-100 justify-content-between">
		<h4 class="mb-1">Rimborsi</h4>
		<i class="fas fa-euro-sign fa-2x"></i>
	</div>
	<p class="mb-1"><i>Gestisci i tuoi rimborsi</i></p>
	<?php
		
		
		$trigger="onclick=\"clickitrimb('New')\"";
		/*
		if (!isset($result['count_newrep']) ||  $result['count_newrep']==0) {
			$trigger="disabled onclick=\"clickitr('New')\"";
		}
		*/
		
	?>
	<button type="button" <?php echo $trigger; ?>  class="btn btn-warning position-relative">
		Nuovo
		<span id='new_job1' class="notif position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
			<?php
			
			//	if (isset($result['count_newrep'])) echo $result['count_newrep'];

			?>
		</span>
	</button>  

	<?php
		
		$trigger="";
		$trigger="onclick=\"$('#div_servizi').hide(150);$('#div_lista_rimborsi_attesa').show(150);\"";
		/*
		$trigger="onclick=\"clickitr('Rif')\"";
		if (!isset($result['numrepno']) ||  $result['numrepno']==0) {
			$trigger="disabled onclick=\"clickitr('Rif')\"";
		}
		*/
	?>
	<button type="button" <?php echo $trigger; ?>  class="ml-3 btn btn-danger position-relative">
		In attesa
		<span class="notif position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
			<?php
				//if (isset($result['numrepno'])) echo $result['numrepno'];
			?>
		</span>
	</button>                
	
	<?php
		$trigger="";
		$trigger="onclick=\"$('#div_servizi').hide(150);$('#div_lista_rimborsi').show(150);\"";
		/*
		if (!isset($result['numrepsi']) ||  $result['numrepsi']==0) {
			$trigger="disabled onclick=\"clickitr('Acc')\"";
		}
		*/
		
	?>
	<button type="button" <?php echo $trigger; ?>  class="ml-3 btn btn-success position-relative">
		Storico
		<span class="notif position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
			<?php
				//if (isset($result['numrepsi'])) echo $result['numrepsi'];
			?>
		</span>
	</button> 

</a>				
