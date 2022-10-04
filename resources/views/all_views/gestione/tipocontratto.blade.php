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
            <h1 class="m-0">TIPOLOGIE DI CONTRATTO</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			  <li class="breadcrumb-item">Archivi</li>
              <li class="breadcrumb-item active">Tipo contratto</li>
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
				<table id='tbl_list_contr'>
					<thead>
						<tr>
							<th>ID</th>
							<th>Descrizione</th>
							<th>Operazioni</th>
						</tr>
					</thead>
					<tbody>
						@foreach($tipoc as $tipo)
							<tr>
								<th>{{ $tipo->id }}</th>	
								<th>{{ $tipo->descrizione }}</th>	
								<th>--</th>	
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

	<script src="{{ URL::asset('/') }}dist/js/tipo_contr.js?ver=1.2"></script>

@endsection