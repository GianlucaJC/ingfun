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
	<link rel="manifest" href="{{ asset('/manifest.json') }}">

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

	<link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet">
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
			<input type="hidden" value="{{url('/')}}" id="url" name="url">
			<div id="app">
				<App></App>
			</div>

			<div id="rep">
				<Rep></Rep>
			</div>

			<div id="rif">
				<Rif></Rif>
			</div>

			<div id="rimb">
				<Rimb></Rimb>
			</div>
			
			<div id="urg">
				<Urg></Urg>
			</div>		

			<input type='hidden' id='id_edit_rimborso' value='{{$id_edit_rimborso}}'>
			<!-- altre view non gestite completamente da vue !-->
			@include('all_views.misapp.storico_rimborsi')
			@include('all_views.misapp.misapp_menu')

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

	
	<!-- dipendenze DataTables !-->
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.css"/>
		 
		 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
		 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
		 <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.js"></script>
	 <!-- fine DataTables !-->

	<script src="{{ URL::asset('/') }}dist/js/misapp.js?ver=<?php echo time(); ?>"></script>
	<script src="{{ URL::asset('/') }}dist/js/misrep.js?ver=<?php echo time(); ?>"></script>
	<script src="{{ URL::asset('/') }}dist/js/misref.js?ver=<?php echo time(); ?>"></script>
	<script src="{{ URL::asset('/') }}dist/js/misrimb.js?ver=<?php echo time(); ?>"></script>
	<script src="{{ URL::asset('/') }}dist/js/misurg.js?ver=<?php echo time(); ?>"></script>
	
	<script src="{{ URL::asset('/') }}dist/js/rimborsi.js?ver=<?php echo time(); ?>"></script>
	

@endsection