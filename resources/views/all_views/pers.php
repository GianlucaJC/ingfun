
			  
			  

			</div>
			<!-- /.row -->

			<a href="{{ route('newcand',['id'=>0,'from'=>1]) }}" class="nav-link active">
				<button type="button" class="btn btn-primary btn-lg btn-block">Inserisci Nuova Anagrafica</button>
			<a/>
			
			<a href="{{ route('export-users') }}" class="nav-link active">
				<button type="button" class="btn btn-primary btn-lg btn-block">Esporta Tutti i dati</button>
			<a/>

			<?php
				$check="";
				if ($view_dele=="1") $check="checked";
			?>

			<input type='hidden' id='dele_cand' name='dele_cand'>
			<input type='hidden' id='restore_cand' name='restore_cand'>
		</form>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  

 
 @section('content_plugin')
	<!-- jQuery -->
	<script src="plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="dist/js/adminlte.min.js"></script>

	<!-- inclusione standard
		per personalizzare le dipendenze DataTables in funzione delle opzioni da aggiungere: https://datatables.net/download/
	!-->
	
	<!-- dipendenze DataTables !-->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.css"/>
		 
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.js"></script>
	<!-- fine DataTables !-->


	<script src="{{ URL::asset('/') }}dist/js/listpers.js?ver=2.00"></script>

@endsection