<?php
	if($_SESSION['user_type_srce']!="Administrador"){
		echo $sc->force_close_session_controller();
	}
?>		
<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-account zmdi-hc-fw"></i> Administradores <small>(Lista)</small></h1>
	</div>
	<p class="lead text-titles">
		Bienvenido a la sección de listado de administradores, aquí podrá encontrar todos los administradores que han sido registrados en el sistema. Además puede eliminar y actualizar datos de los administradores.
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
	include_once "./controllers/adminController.php";
	$insAdmin = new adminController();
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12">
	  		<div class="panel panel-success">
			  	<div class="panel-heading">
			    	<h3 class="panel-title"><i class="zmdi zmdi-format-list-bulleted"></i> &nbsp; Lista de Administradores</h3>
			  	</div>
			  	<div class="panel-body">
					<?php
						$page=explode("/", $_GET['views']);
						echo $insAdmin->pagination_admin_controller($page[1],10,$_SESSION['user_privilege_srce'],$_SESSION['account_code_srce'],"");
					?>
			  	</div>
			</div>
		</div>
	</div>
</div>