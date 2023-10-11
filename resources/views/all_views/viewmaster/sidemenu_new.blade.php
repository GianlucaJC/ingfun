<?php
	use App\Models\User;
		$id = Auth::user()->id;
		$user = User::find($id);
		
	use App\Models\main_menu;


	$infx=Auth::user()->roles->pluck('name');
	$role=$infx[0];

	$menu=main_menu::
	where(function ($menu) use($role) {
		$menu->where('roles','like',"%$role%")
		->orWhere('roles','=',"");
	})	
	->where('reserved',"=",0)
	->orderBy('parent_id')
	->orderBy('ordine')
	->get();


	$arr_menu=array();
	foreach($menu as $titoli) {
		$dati['id']=$titoli->id;
		$dati['title']=$titoli->voce;
		$dati['parent_id']=$titoli->parent_id;
		$dati['class_icon']=$titoli->class_icon;
		$dati['route']=$titoli->route;
		$dati['params_route']=$titoli->params_route;
		$dati['visible']=$titoli->visible;
		$dati['disable']=$titoli->disable;
		$arr_menu[]=$dati;
	}

?>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('menu') }}" class="brand-link">
      <img src="{{ URL::asset('/') }}dist/img/4.png" alt="IngFUN Logo" class="brand-image img-circle elevation-5" style="opacity: 5;" >
      
	  
	  
	  <span class="brand-text font-weight-light">DayByDay</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
			@if ($user->hasRole('admin'))
				<img src="{{ URL::asset('/') }}dist/img/AdminLTELogo.png" class="img-circle elevation-2" alt="User Image">
			@elseif ($user->hasRole('coord'))
				<img src="{{ URL::asset('/') }}dist/img/coord.png" class="img-circle elevation-2" alt="User Image">
			@elseif ($user->hasRole('resp'))
				<img src="{{ URL::asset('/') }}dist/img/resp.png" class="img-circle elevation-2" alt="User Image">
			@else	
				<img src="{{ URL::asset('/') }}dist/img/avatar1.png" class="img-circle elevation-2" alt="User Image">
			@endif
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
		
		<ul class="nav nav-pills nav-sidebar" role="menu" data-accordion="false">
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
        </div>


		
      </div>

      <!-- SidebarSearch Form -->
      <!--
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
	  !-->
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
			
			<?php
				$render=build_menu($arr_menu);	
				echo $render;
				
			
			?>
		  
			 

		</ul>  
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>


<?php 
	//ref: https://stackoverflow.com/questions/4413776/php-mysql-build-tree-menu
	
	function has_children($rows,$id) {
	  foreach ($rows as $row) {
		if ($row['parent_id'] == $id)
		  return true;
	  }
	  return false;
	}
	function build_menu($rows,$parent=0){  
	  $result="";	
		foreach ($rows as $row){
			if ($row['parent_id'] == $parent && $row['visible'] == 1){
				$dis=$row['disable'];
				$result.="<li class='nav-item menu'>";
					$url="#";
					if (strlen($row['route'])!=0 && $row['route']!="-") {
						$href=$row['route'];							$params=$row['params_route'];
						$url=route($row['route'],$params);
					}	
					
					
					$result.="<a href='$url' class='nav-link  $dis'>";
						
					  $result.="<i class='nav-icon {$row['class_icon']}'></i>";
					  $result.= "<p>{$row['title']}";
						if (has_children($rows,$row['id'])) 
							$result.="<i class='right fas fa-angle-left'></i>";
					$result.="</p></a>";	
					if (has_children($rows,$row['id'])) {
						
						$result.="<ul class='ml-2 nav nav-treeview'>";
						$result.="<li class='nav-item active'>";
							$result.= build_menu($rows,$row['id']);	
						$result.="</li>";
						$result.="</ul>";
					}
				$result.="</li>";

					 
			}
		}
	  return $result;
	}


	

?>