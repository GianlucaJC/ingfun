@extends('all_views.viewmaster.index')

@section('title', 'IngFUN')

@section('extra_style') 
<!-- x button export -->

<link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet">
<!-- -->
@endsection



<style>

</style>
@section('content_main')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style='background-color:white'>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">LISTA APPALTI</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			  <li class="breadcrumb-item active">Amministrazione</li>
			  <li class="breadcrumb-item active">Appalti</li>

              <li class="breadcrumb-item active">Lista Appalti</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
	  
		<form method='post' action="{{ route('listnewapp') }}" id='frm_appalti' name='frm_appalti' autocomplete="off">
		<input type="hidden" value="{{url('/')}}" id="url" name="url">
		<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>	  
			<?php if (1==2) {?>
			<div class="container">
            <div class="row">
                <h1>Laravel: Whatsapp Twilio Notifications Example</h1>

                <div class="col-md-12 mt-5">
                    
                        @csrf

                        <div class="mb-3">
                            <label for="message" class="form-label">Your Message</label>
                            <textarea name="message" class="form-control" id="message" rows="3" required="required"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">To Phone Number</label>
                            <input type="text" name="to" class="form-control" id="phone" placeholder="+919876543210" required="required">
                        </div>

                        <div>
                            <button type="submit" name='send_wa' value='send' class="btn btn-primary">Send</button>
                        </div>
                    
                </div>
            </div>
        </div>
		<?php } ?>


					

				
			<div class="row">
			  <div class="col-lg-12">
				<h5>TOTALE  APPALTI</h5>
				<table id='tbl_list_pers' class="display">
					<thead>
						<tr>
							<th style='min-width:220px'>Operazioni</th>
							<th style='max-width:40px'>ID</th>
							<th>Data</th>
						</tr>
					</thead>
					<tbody>
			
					@foreach($all_appalti as $appalti)
						<tr>

							<td style='min-width:220px'>
								
						
								@if ($appalti->dele=="0") 
									<a href="{{ route('makeapp',['id_giorno_appalto'=>$appalti->id])}}" >
										<button type="button" class="btn btn-info" alt='Edit' title="Modifica Appalti del giorno"><i class="fas fa-edit"></i></button>
									</a>
								@endif

								@if ($appalti->dele=="0") 
								<a href='#' onclick="dele_element({{$appalti->id}})">
									<button type="submit" name='dele_ele' class="btn btn-danger" title="Cancella Appalti del giorno"><i class="fas fa-trash"></i></button>
								</a>
								@endif
								@if ($appalti->dele=="1") 
									<a href='#'onclick="restore_element({{$appalti->id}})" >
										<button type="submit" class="btn btn-warning" alt='Restore'><i class="fas fa-trash-restore" title="Ripristina appalti del giorno"></i></button>
									</a>
								@endif	
							</td>	




							<td style='max-width:40px'>
								{{$appalti->id}}
							</td>

							<td>
								{{$appalti->data_appalto}}
							</td>

						</tr>
					@endforeach
					</tbody>
					<tfoot>
						<tr>
							<th style='min-width:220px'>Operazioni</th>
							<th style='max-width:40px'>ID</th>
							<th>Data</th>
						</tr>
					</tfoot>					
				</table>
					
			  </div>
			  
			  

			</div>
			<!-- /.row -->
<?php

	//$value="734|2047";
	//$request->session()->push('onlysel', $value);
?>	

			<div class="row mt-2">
				<div class="col-lg-12">
					
					<button type="button" onclick="new_app()" class="btn btn-primary btn-lg btn-block">Definisci Nuovo Appalto</button>
				</div>
			</div>
	
			<!-- DISATTIVATO!
				<div class="row">
					<div class="col-lg-12">
						<a href="#" class="nav-link active" >
							<button type="sumbit" name='send_notif_today' id='send_notif_today' class="btn btn-success btn-lg btn-block">Invio notifica per tutti gli appalti (oggi+1)</button>
						</a>
					</div>
				</div>	
			!-->
			<hr>
			<?php
				$check="";
				if ($view_dele=="1") $check="checked";
			?>
			<div class="row">
				<div class="col-lg-12">
					<div class="form-check form-switch mt-3 ml-3">
					  <input class="form-check-input" type="checkbox" id="view_dele" name="view_dele" onchange="$('#frm_appalti').submit()" {{ $check }}>
					  <label class="form-check-label" for="view_dele">Mostra anche Appalti eliminati</label>
					</div>
				</div>
			</div>	
			<input type='hidden' id='dele_cand' name='dele_cand'>
			<input type='hidden' id='restore_cand' name='restore_cand'>
			<input type='hidden' id='push_appalti' name='push_appalti'>
			<input type='hidden' id='data_app' value='{{$data_app}}'>
			<!-- /.content -->
			<!-- MODAL !-->


			<div class="modal fade bd-example-modal-lg" id="modalinfo" tabindex="-1" role="dialog" aria-labelledby="servizio" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="modalinfotitle">Operazione di servizio</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"  onclick="$('#body_content').html('');">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<center><div id='div_wait' class='mt-2'></div></center>
					<div class="modal-body" id='body_content'>

					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="$('#body_content').html('')">Chiudi</button>
					</div>
					</div>
				</div>
			</div>    
			<!-- Fine MODAL !-->			
		</form>
      </div><!-- /.container-fluid -->
    </div>
	
  </div>
  <!-- /.content-wrapper -->
  
 @endsection
 
 @section('content_plugin')
	<!-- jQuery -->
	<script src="{{ URL::asset('/') }}plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 5 -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
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


	<script src="{{ URL::asset('/') }}dist/js/listnewapp.js?ver=1.003"></script>

@endsection