<?php
	use App\Models\appalti;
	use App\Models\lavoratoriapp;
	use App\Models\servizi;
	use App\Models\serviziapp;
?>
@extends('all_views.viewmaster.index')

@section('title', 'IngFUN')


<meta name="csrf-token" content="{{{ csrf_token() }}}">


@section('extra_style') 
	<script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>

	<script>
		window.OneSignalDeferred = window.OneSignalDeferred || [];
		OneSignalDeferred.push(function (OneSignal) {
			OneSignal.init({
				appId: "f9677f83-05dd-44ed-b301-b5c49d5c8777",
			});
			OneSignal.User.PushSubscription.addEventListener("change", function (event) {
				console.log("event");
				console.log(event);
				if (event.current.id) {
						register_push(event.current.id)
				}


			});
		});		
		
		//register_push("test") //per test in locale
		function register_push(pushid) {
			id_user="<?php echo $id_user; ?>"
			const metaElements = document.querySelectorAll('meta[name="csrf-token"]');
			const csrf = metaElements.length > 0 ? metaElements[0].content : "";			
			fetch("register_push", {
				method: 'post',
				headers: {
				"Content-type": "application/x-www-form-urlencoded; charset=UTF-8",
				"X-CSRF-Token": csrf
				},
				body: "pushid="+pushid+"&id_user="+id_user
			})
			.then(response => {
				if (response.ok) {
					return response.json();
				}
			})
			.then(resp=>{

			})
			.catch(status, err => {
				return console.log(status, err);
			})		
		}			
		
	</script>	

	
	<script src="https://cdn.jsdelivr.net/npm/vue@2.5.17/dist/vue.js"></script>



@endsection



<style>

</style>
@section('content_main')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style='background-color:white'>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid mt-4">
	  <!--<div class='onesignal-customlink-container'></div>!-->

		<form method='post' action="{{ route('misapp') }}" id='frm_appalti' name='frm_appalti' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>	

		

			<div id="app">
				<App></App>
			</div>

			<div id="rep">
				<Rep></Rep>
			</div>

			<div id="rif">
				<Rif></Rif>
			</div>

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
              

              


		</form>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
 @endsection
 
 @section('content_plugin')
	<!-- jQuery -->
	<script src="{{ URL::asset('/') }}plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="{{ URL::asset('/') }}plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="{{ URL::asset('/') }}dist/js/adminlte.min.js"></script>

	<!-- inclusione standard
		per personalizzare le dipendenze DataTables in funzione delle opzioni da aggiungere: https://datatables.net/download/
	!-->
	
	<!-- dipendenze DataTables !-->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.css"/>
		 
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.js"></script>
	<!-- fine DataTables !-->


	<script src="{{ URL::asset('/') }}dist/js/misapp.js?ver=<?php echo time(); ?>"></script>
	<script src="{{ URL::asset('/') }}dist/js/misrep.js?ver=<?php echo time(); ?>"></script>
	<script src="{{ URL::asset('/') }}dist/js/misref.js?ver=<?php echo time(); ?>"></script>
	

@endsection