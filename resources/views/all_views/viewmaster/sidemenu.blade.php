
<?php
use App\Models\User;
	$id = Auth::user()->id;
	$user = User::find($id);
?>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
      <img src="{{ URL::asset('/') }}dist/img/if.png" alt="IngFUN Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">IngFUN</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
			@if ($user->hasRole('admin'))
				<img src="{{ URL::asset('/') }}dist/img/AdminLTELogo.png" class="img-circle elevation-2" alt="User Image">
			@else
				<img src="{{ URL::asset('/') }}dist/img/avatar1.png" class="img-circle elevation-2" alt="User Image">
			@endif
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Cerca" aria-label="Cerca">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
			@if ($user->hasRole('admin'))
			 <li class="nav-item menu">
				<a href="#" class="nav-link">
				  <i class="nav-icon fas fa-cube"></i>
				  <p>Candidature
					<i class="right fas fa-angle-left"></i>
				  </p>
				</a>
				<ul class="nav nav-treeview">
				  <li class="nav-item">
					<a href="{{ route('newcand') }}" class="nav-link active">
					  <i class="far fa-circle nav-icon"></i>
					  <p>Nuova candidatura</p>
					</a>
				  </li>
				  <li class="nav-item">
					<a href="{{ route('listcand') }}" class="nav-link">
					  <i class="far fa-circle nav-icon"></i>
					  <p>Lista candidature</p>
					</a>
				  </li>

				</ul>
			  </li>


			
			
			  <li class="nav-item menu">
				<a href="#" class="nav-link">
				  <i class="fas fa-cogs"></i> 
				  <p>
					Archivi
					<i class="right fas fa-angle-left"></i>
				  </p>
				</a>
				<ul class="nav nav-treeview">
				  <li class="nav-item">
					<a href="{{ route('tipo_contratto') }}" class="nav-link active">
					  <i class="far fa-circle nav-icon"></i>
					  <p>Tipologie di contratto</p>
					</a>
				  </li>
				  <li class="nav-item">
					<a href="{{ route('mansione') }}" class="nav-link">
					  <i class="far fa-circle nav-icon"></i>
					  <p>Mansioni</p>
					</a>
				  </li>
				  <li class="nav-item">
					<a href="{{ route('frm_attestati') }}" class="nav-link">
					  <i class="far fa-circle nav-icon"></i>
					  <p>Form Attestati sicurezza</p>
					</a>
				  </li>
				  
				  <li class="nav-item">
					<a href="{{ route('societa_assunzione') }}" class="nav-link">
					  <i class="far fa-circle nav-icon"></i>
					  <p>Società di assunzione</p>
					</a>
				  </li>
				  <li class="nav-item">
					<a href="{{ route('costo') }}" class="nav-link">
					  <i class="far fa-circle nav-icon"></i>
					  <p>Centri di Costo</p>
					</a>
				  </li>
				  <li class="nav-item">
					<a href="{{ route('area_impiego') }}" class="nav-link">
					  <i class="far fa-circle nav-icon"></i>
					  <p>Aree di impiego</p>
					</a>
				  </li>
				  <li class="nav-item">
					<a href="{{ route('ccnl') }}" class="nav-link">
					  <i class="far fa-circle nav-icon"></i>
					  <p>Contratti CCNL</p>
					</a>
				  </li>
				</ul>
			  </li>		  
			 @endif 
			
          

	  
		  <li class="nav-item">
				<form method="POST" action="{{ route('logout') }}">
					@csrf
					  <li class="nav-item">
						<a href="#" class="nav-link" onclick="event.preventDefault();this.closest('form').submit();">
						  <i class="far fa-circle nav-icon"></i>
						  <p>Logout</p>
						</a>
					  </li>

				</form>	
          </li>
		  
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
