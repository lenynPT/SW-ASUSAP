<?php
	if($_SESSION['user_type_srce']!="Administrador"){
		echo $sc->force_close_session_controller();
	}
?>				
<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-account zmdi-hc-fw"></i> Administradores <small>(Búsqueda)</small></h1>
	</div>
	<p class="lead text-titles">
		Bienvenido a la sección para buscar administradores, puede buscar un administrador por su nombre, apellido o teléfono.
	</p>
</div>
<div class="container-fluid">
	<ul class="breadcrumb breadcrumb-tabs">
	  	<li>
	  		<a href="<?php echo SERVERURL; ?>admin/" class="btn btn-info">
	  			<i class="zmdi zmdi-plus"></i> &nbsp; NUEVO ADMINISTRADOR
	  		</a>
	  	</li>
	  	<li>
	  		<a href="<?php echo SERVERURL; ?>adminlist/" class="btn btn-success">
	  			<i class="zmdi zmdi-format-list-bulleted"></i> &nbsp; LISTA DE ADMINISTRADORES
	  		</a>
	  	</li>
	  	<li>
	  		<a href="<?php echo SERVERURL; ?>adminsearch/" class="btn btn-primary">
	  			<i class="zmdi zmdi-search"></i> &nbsp; BUSCAR ADMINISTRADORES
	  		</a>
	  	</li>
	</ul>
</div>
<?php
	require_once "./controllers/adminController.php";
	$insAdmin = new adminController();

	if(isset($_POST['init_search_admin'])){
		$_SESSION['admin_search']=$_POST['init_search_admin'];
	}

	if(isset($_POST['delete_admin_search'])){
		unset($_SESSION['admin_search']);
	}

	if(!isset($_SESSION['admin_search']) && empty($_SESSION['admin_search'])):
?>
<div class="container-fluid">
	<form action="" method="POST" enctype="multipart/form-data" autocomplete="off" class="well">
		<div class="row">
			<div class="col-xs-12 col-md-8 col-md-offset-2">
				<div class="form-group label-floating">
					<span class="control-label">¿A quién estas buscando?</span>
					<input class="form-control" type="text" name="init_search_admin" required="">
				</div>
			</div>
			<div class="col-xs-12">
				<p class="text-center">
					<button type="submit" class="btn btn-primary btn-raised btn-sm"><i class="zmdi zmdi-search"></i> &nbsp; Buscar</button>
				</p>
			</div>
		</div>
	</form>
</div>
<?php 
	else:
?>
<div class="container-fluid">
	<form action="" method="POST" enctype="multipart/form-data" autocomplete="off" class="well">
		<p class="lead text-center">Su última búsqueda  fue <strong>“<?php echo $_SESSION['admin_search']; ?>”</strong></p>
		<div class="row">
			<input class="form-control" type="hidden" name="delete_admin_search" value="1">
			<div class="col-xs-12">
				<p class="text-center">
					<button type="submit" class="btn btn-danger btn-raised btn-sm"><i class="zmdi zmdi-delete"></i> &nbsp; Eliminar búsqueda</button>
				</p>
			</div>
		</div>
	</form>
</div>

<!-- Panel listado de busqueda de administradores -->
<div class="container-fluid">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="zmdi zmdi-search"></i> &nbsp; BUSCAR ADMINISTRADOR</h3>
		</div>
		<div class="panel-body">
			<?php
				$page=explode("/", $_GET['views']);
				echo $insAdmin->pagination_admin_controller($page[1],10,$_SESSION['user_privilege_srce'],$_SESSION['account_code_srce'],$_SESSION['admin_search']);
			?>
		</div>
	</div>
</div>
<?php endif; ?>