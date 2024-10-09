@extends('all_views.viewmaster.index')
@section('title', 'IngFUN')
<meta name="csrf-token" content="{{{ csrf_token() }}}">


@section('extra_style') 
	<link rel="manifest" href="{{ asset('/manifest.json') }}">
	<link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet">
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

		<form method='post' action="{{ route('rimborsi_coord') }}" id='frm_rimborsi' name='frm_rimborsi' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>	
			<input type="hidden" value="{{url('/')}}" id="url" name="url">
			<div id="div_lista_rimborsi">
				<small><b>R</b> - Richiedi rettifica</small>
				<small><b>A</b> - Approva rimborso</small>
				<small><b>S</b> - Scarta rimborso</small>
				<hr>
				<div class="row">
					<div class="col-md-12">
						<table id='tbl_rimborsi' class="display">
							<thead>
								<tr>
									<th>ID</th>
									<th style='width:80px'>Operazioni</th>									
									<th>Utente</th>
									<th>Descrizione</th>
									<th>Data</th>
									<th>Importo</th>
									<th>Foto</th>
									<th>Stato</th>
								</tr>
							</thead>
							<tbody>
								@foreach($elenco_rimborsi as $rimborso)    
									<tr>
										<td>{{$rimborso->id}}</td>
										<td style='width:80px'>
											@if($rimborso->stato=="0")
												<div id='azioni{{$rimborso->id}}'>
													<button type="button" class="btn btn-warning btn-sm" onclick="azione('R',{{$rimborso->id}},this,0,'')">R</button>
													<button type="button" class="btn btn-success btn-sm" onclick="azione('A',{{$rimborso->id}},this,'{{$rimborso->importo}}','{{$rimborso->dataora}}')">A</button>
													<button type="button" class="btn btn-danger btn-sm" onclick="azione('S',{{$rimborso->id}},this,0,'')">S</button>
												</div>
											@endif

										</td>

										<td>{{$rimborso->name}}</td>
										<td>{{$rimborso->descrizione}}</td>
										<td>{{$rimborso->dataora}}</td>
										<td>{{$rimborso->importo}}</td>
										<?php
											$stato_rimb=$rimborso->stato;
											$stato_view="In Attesa";$back="warning";$colo="black";
											if ($stato_rimb=="0") {$stato_view="In Attesa";$back="warning";}
											if ($stato_rimb=="1") {$stato_view="Approvato";$back="success";}
											if ($stato_rimb=="2") {$stato_view="Scartato";$back="danger";}
											if ($stato_rimb=="3") {$stato_view="In attesa rettifica";$back="secondary";}
										?>
									
										
										<td style='width:100px'>
											@if ($rimborso->filename!=null && strlen($rimborso->filename)!=0)
												<span id='id_foto{{$rimborso->id}}' data-foto='{{$rimborso->filename}}'>
												<a href='javascript:void(0)' onclick='zoom({{$rimborso->id}})'>
													<img class="rounded float-left img-fluid img-thumbnail"  src='{{ URL::asset('/') }}dist/upload/rimborsi/thumbnail/small/{{$rimborso->filename}}'>
												</a>
											@endif
										</td>                                
									
										<td  id='td_status{{$rimborso->id}}'>
											
											<div class="alert alert-{{$back}}" role="alert" style='text-align:center'>
												{{$stato_view}}
											</div>
											
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>						
					</div>
				</div>
				
			</div>
		</form>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  

	<!-- Modal -->
	<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modal_img">
	  <div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="title_modal">Foto inviata</h5>
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

	<script src="{{ URL::asset('/') }}dist/js/rimborsi_coord.js?ver=<?php echo time(); ?>"></script>
	

@endsection