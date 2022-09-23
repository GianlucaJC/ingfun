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
            <h1 class="m-0">Gruppo INGENIOUS | Divisione FUNEBRE | Servizi in primo piano</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
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
          <div class="col-lg-6">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">
					<b>
					Nuova Candidatura
					</b>
				</h5>

                <p class="card-text">
                  Procedura dedicata all'inserimento di una nuova candidatura.
                </p>

                <a href="#" class="card-link">Accedi al servizio</a>
                
              </div>
            </div>

            <div class="card card-primary card-outline">
              <div class="card-body">
                <h5 class="card-title">Card title</h5>

                <p class="card-text">
                  Servizio da implementare...
                </p>
                <a href="#" class="card-link">Accedi al serivizo</a>
              </div>
            </div><!-- /.card -->
          </div>
          <!-- /.col-md-6 -->
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header">
                <h5 class="m-0">LISTA Candidature</h5>
              </div>
              <div class="card-body">
                <h6 class="card-title">Elenco candidature</h6>

                <p class="card-text">Procedura contenente l'elenco delle candidature con possibilità di modifica/cancellazione e passaggio in assunzione</p>
                <a href="#" class="btn btn-primary">Accedi al servizio</a>
              </div>
            </div>

            <div class="card card-primary card-outline">
              <div class="card-header">
                <h5 class="m-0">Servizio...</h5>
              </div>
              <div class="card-body">
                <h6 class="card-title">Da implementare...</h6>

                <p class="card-text">Questa procedura...</p>
                <a href="#" class="btn btn-primary">Accedi al servizio</a>
              </div>
            </div>
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
 @endsection