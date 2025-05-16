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
	<script>
	</script>	
	<link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet">
	<link rel="stylesheet" href="{{ URL::asset('/') }}plugins/fullcalendar/main.css">
@endsection


@section('content_main')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style='background-color:white'>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid mt-4">
	  <!--<div class='onesignal-customlink-container'></div>!-->

		<form method='post' action="{{ route('makeappalti') }}" id='frm_appalti' name='frm_appalti' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>	
			<input type="hidden" value="{{url('/')}}" id="url" name="url">
				<section class="content">
				<div class="container-fluid">
					<div class="row">
					<div class="col-md-3">
						<div class="sticky-top mb-3">
						<div class="card">
							<div class="card-header">
							<h4 class="card-title">Lavoratori disponibili</h4>
							</div>
							<div class="card-body" style='max-height:700px;overflow-y:scroll'>
								<!-- the events -->
								<div id="external-events">
									@foreach ($lavoratori as $lavoratore)
										<div class="external-event bg-success" title='l1'>
											{{$lavoratore->nominativo}}
										</div>
									@endforeach	
								</div>
							</div>
							<div class="checkbox mt-2 ml-3">
							<label for="drop-remove">
								<input type="checkbox" id="drop-remove">
								 Elimina dopo inserimento
							</label>
							</div>

							<!-- /.card-body -->
						</div>
						<!-- /.card -->
						<div class="card" style='display:none'>
							<div class="card-header">
							<h3 class="card-title">Create Event</h3>
							</div>
							<div class="card-body">
							<div class="btn-group" style="width: 100%; margin-bottom: 10px;">
								<ul class="fc-color-picker" id="color-chooser">
								<li><a class="text-primary" href="#"><i class="fas fa-square"></i></a></li>
								<li><a class="text-warning" href="#"><i class="fas fa-square"></i></a></li>
								<li><a class="text-success" href="#"><i class="fas fa-square"></i></a></li>
								<li><a class="text-danger" href="#"><i class="fas fa-square"></i></a></li>
								<li><a class="text-muted" href="#"><i class="fas fa-square"></i></a></li>
								</ul>
							</div>
							<!-- /btn-group -->
							<div class="input-group">
								<input id="new-event" type="text" class="form-control" placeholder="Event Title">

								<div class="input-group-append">
								<button id="add-new-event" type="button" class="btn btn-primary">Add</button>
								</div>
								<!-- /btn-group -->
							</div>
							<!-- /input-group -->
							</div>
						</div>
						</div>
					</div>
					<!-- /.col -->
					<div class="col-md-9">
						
							<!-- THE CALENDAR -->
						<table>	
							<tr>
								<td colspan='7' style='text-align:center;background-color:rgb(30, 139, 255);color:rgb(255, 255, 30)'>
									<div style='padding:10px'>APPALTI DELLA MATTINA</div>
								</td>
							</tr>
							<tr>
								<td><div id="calendar1"></div></td>
								<td><div id="calendar2"></div></td>
								<td><div id="calendar3"></div></td>
								<td><div id="calendar4"></div></td>
								<td><div id="calendar5"></div></td>
								<td><div id="calendar6"></div></td>
								<td><div id="calendar7"></div></td>
							</tr>
							<tr><td colspan='7'> </td></tr>
							<tr><td colspan='7'> </td></tr>
							<tr>
								<td colspan='7' style='text-align:center;background-color:rgb(30, 139, 255);color:rgb(255, 255, 30)'>
									<div style='padding:10px'>APPALTI DEL POMERIGGIO</div>
								</td>
							</tr>							
							<tr>
								<td><div id="calendars1"></div></td>
								<td><div id="calendars2"></div></td>
								<td><div id="calendars3"></div></td>
								<td><div id="calendars4"></div></td>
								<td><div id="calendars5"></div></td>
								<td><div id="calendars6"></div></td>
								<td><div id="calendars7"></div></td>
							</tr>							
						</table>	
					</div>
					<!-- /.col -->
					</div>
					<!-- /.row -->
				</div><!-- /.container-fluid -->
				</section>            

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
	<!-- jQuery UI -->
	<script src="{{ URL::asset('/') }}plugins/jquery-ui/jquery-ui.min.js"></script>
	<!-- AdminLTE App -->
	<script src="{{ URL::asset('/') }}dist/js/adminlte.min.js"></script>


	<!-- fullCalendar 2.2.5 -->
	<script src="{{ URL::asset('/') }}plugins/moment/moment.min.js"></script>
	<script src="{{ URL::asset('/') }}plugins/fullcalendar/main.js"></script>



	<script src="{{ URL::asset('/') }}dist/js/makeappalti.js?ver=2.054"></script>


	

@endsection