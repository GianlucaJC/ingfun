@extends('all_views.viewmaster.index')

@section('title', 'IngFUN')

@section('content_main')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">LISTA CANDIDATURE</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Lista Candidature</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
	  
        <div class="row">
          <div class="col-lg-12">
				<table id='tbl_list_cand'>
					<thead>
						<tr>
							<th>ID</th>
							<th>Nominativo</th>
							<th>Mansione</th>
							<th>Zona di lavoro</th>
							<th>Ultimo Aggiornamento</th>
							<th>Sorgente</th>
							<th>Status</th>
							<th>Operazioni</th>
							<th>View</th>
							<th>Delete</th>
							
							
						</tr>
					</thead>
					<tbody>
						@foreach($candidati as $candidato)
							<tr>
								<td>{{ $candidato->id }}</td>
								<td>
									{{ $candidato->nominativo }}
								</td>
								<td>{{ $candidato->mansione }}</td>
								<td>{{ $candidato->zona_lavoro }}</td>
								<td>{{ $candidato->updated_at }}</td>
								<td>Ufficio</td>
								<td>-----</td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						@endforeach
					</tbody>
				</table>
          </div>

        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
 @endsection
 
 @section('content_plugin')
	<!-- jQuery -->
	<script src="plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="dist/js/adminlte.min.js"></script>

	
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>

	<script src="{{ URL::asset('/') }}dist/js/home.js?ver=1.2"></script>

@endsection