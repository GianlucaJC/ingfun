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
					<?php 
						$nome="";
						for ($sca=1;$sca<=20;$sca++) {
							if ($sca==1) $nome="ROSSI AAA";
							if ($sca==2) $nome="ROSSI BBB";
							if ($sca==3) $nome="ROSSI CCC";
							if ($sca==4) $nome="ROSSI DDD";
							if ($sca==5) $nome="ROSSI EEE";
							if ($sca==6) $nome="ROSSI FFF";
							if ($sca==7) $nome="ROSSI GGG";
							if ($sca==8) $nome="ROSSI HHH";
							if ($sca==9) $nome="ROSSI III";
							if ($sca==10) $nome="ROSSI JJJ";
							if ($sca==11) $nome="ROSSI KKK";
							if ($sca==12) $nome="ROSSI LLL";
							if ($sca==13) $nome="ROSSI MMM";
							if ($sca==14) $nome="ROSSI NNN";
							if ($sca==15) $nome="ROSSI OOO";
							if ($sca==16) $nome="ROSSI PPP";
							if ($sca==17) $nome="ROSSI QQQ";
							if ($sca==18) $nome="ROSSI RRR";
							if ($sca==19) $nome="ROSSI SSS";
							if ($sca==20) $nome="ROSSI TTT";
							?>
						
							<tr>
								<td>{{$sca}}</td>
								<td>{{ $nome }}</td>
								<td>Mansione {{$sca}}</td>
								<td>Zona {{$sca}}</td>
								<td>01/01/2022 00:00</td>
								<td>Ufficio</td>
								<td>-----</td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
					<?php } ?>	
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