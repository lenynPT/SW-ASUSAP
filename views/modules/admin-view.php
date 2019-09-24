<?php
	if($_SESSION['user_type_srce']!="Administrador"){
		echo $sc->force_close_session_controller();
	}
?>			
<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-account zmdi-hc-fw"></i> Administradores <small>(Registro)</small></h1>
	</div>
	<p class="lead text-titles">
		Bienvenido a la sección para agregar administradores, aquí podrá registrar nuevos administradores (Los campos marcados con * son obligatorios para registrar un administrador), además puede ver los administradores registrados en el sistema haciendo clic en el enlace "LISTA DE ADMINISTRADORES" que se muestra a continuación.
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
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-info">
				<div class="panel-heading">
				    <h3 class="panel-title"><i class="zmdi zmdi-plus"></i> &nbsp; Nuevo Administrador</h3>
				</div>
			  	<div class="panel-body">
				    <form action="<?php echo SERVERURL; ?>ajax/adminAjax.php" method="POST" class="DataAjaxForm" data-form="save" enctype="multipart/form-data" autocomplete="off">
				    	<fieldset>
				    		<legend><i class="zmdi zmdi-account-box"></i> &nbsp; Datos personales</legend>
				    		<div class="container-fluid">
				    			<div class="row">
				    				<div class="col-xs-12 col-sm-6">
								    	<div class="form-group label-floating">
										  	<label class="control-label">Nombres *</label>
										  	<input pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control" type="text" name="name-reg" required="" maxlength="30">
										</div>
				    				</div>
				    				<div class="col-xs-12 col-sm-6">
										<div class="form-group label-floating">
										  	<label class="control-label">Apellidos *</label>
										  	<input pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control" type="text" name="lastname-reg" required="" maxlength="30">
										</div>
				    				</div>
				    				<div class="col-xs-12 col-sm-6">
										<div class="form-group label-floating">
										  	<label class="control-label">Teléfono</label>
										  	<input pattern="[0-9+]{1,15}" class="form-control" type="text" name="phone-reg" maxlength="15">
										</div>
				    				</div>
				    				<div class="col-xs-12">
										<div class="form-group label-floating">
										  	<label class="control-label">Dirección</label>
										  	<input class="form-control" type="text" name="address-reg" maxlength="100">
										</div>
				    				</div>
				    			</div>
				    		</div>
				    	</fieldset>
				    	<br>
				    	<fieldset>
				    		<legend><i class="zmdi zmdi-key"></i> &nbsp; Datos de la cuenta</legend>
				    		<div class="container-fluid">
				    			<div class="row">
				    				<div class="col-xs-12">
							    		<div class="form-group label-floating">
										  	<label class="control-label">Nombre de usuario *</label>
										  	<input pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ]{1,15}" class="form-control" type="text" name="username-reg" required="" maxlength="15">
										</div>
				    				</div>
				    				<div class="col-xs-12 col-sm-6">
										<div class="form-group label-floating">
										  	<label class="control-label">Contraseña *</label>
										  	<input class="form-control" type="password" name="password1-reg" required="" maxlength="70">
										</div>
				    				</div>
				    				<div class="col-xs-12 col-sm-6">
										<div class="form-group label-floating">
										  	<label class="control-label">Repita la contraseña *</label>
										  	<input class="form-control" type="password" name="password2-reg" required="" maxlength="70">
										</div>
				    				</div>
				    				<div class="col-xs-12 col-sm-6">
										<div class="form-group label-floating">
										  	<label class="control-label">E-mail</label>
										  	<input class="form-control" type="email" name="email-reg" maxlength="50">
										</div>
				    				</div>
				    				<div class="col-xs-12">
										<div class="form-group">
											<label class="control-label">Genero</label>
											<div class="radio radio-primary">
												<label>
													<input type="radio" name="gender-reg" id="optionsRadios1" value="Masculino" checked="">
													<i class="zmdi zmdi-male-alt"></i> &nbsp; Masculino
												</label>
											</div>
											<div class="radio radio-primary">
												<label>
													<input type="radio" name="gender-reg" id="optionsRadios2" value="Femenino">
													<i class="zmdi zmdi-female"></i> &nbsp; Femenino
												</label>
											</div>
										</div>
				    				</div>
				    			</div>
				    		</div>
				    	</fieldset>
				    	<br>
				    	<fieldset>
				    		<legend><i class="zmdi zmdi-star"></i> &nbsp; Nivel de privilegios</legend>
		                    <div class="container-fluid">
		                    	<div class="row">
		                    		<div class="col-xs-12 col-sm-6">
							    		<p class="text-left">
					                        <div class="label label-success">Nivel 1</div> Control total del sistema
					                    </p>
					                    <p class="text-left">
					                        <div class="label label-primary">Nivel 2</div> Permiso para registro y actualización
					                    </p>
					                    <p class="text-left">
					                        <div class="label label-info">Nivel 3</div> Permiso para registro
					                    </p>
		                    		</div>
		                    		<div class="col-xs-12 col-sm-6">
										<?php if($_SESSION['user_privilege_srce']==1): ?>
										<div class="radio radio-primary">
											<label>
												<input type="radio" name="privelege-reg" value="<?php echo $sc->encryption(1); ?>">
												<i class="zmdi zmdi-star"></i> &nbsp; Nivel 1
											</label>
										</div>
										<?php 
								          	endif;
								          	if($_SESSION['user_privilege_srce']<=2): 
								        ?>
										<div class="radio radio-primary">
											<label>
												<input type="radio" name="privelege-reg" value="<?php echo $sc->encryption(2); ?>">
												<i class="zmdi zmdi-star"></i> &nbsp; Nivel 2
											</label>
										</div>
										<?php endif; ?>
										<div class="radio radio-primary">
											<label>
												<input type="radio" name="privelege-reg" value="<?php echo $sc->encryption(3); ?>" checked="">
												<i class="zmdi zmdi-star"></i> &nbsp; Nivel 3
											</label>
										</div>
				    				</div>
		                    	</div>
		                    </div>
				    	</fieldset>
					    <p class="text-center" style="margin-top: 20px;">
					    	<button type="submit" class="btn btn-info btn-raised btn-sm"><i class="zmdi zmdi-floppy"></i> Guardar</button>
					    </p>
					    <p class="AjaxReply"></p>
				    </form>
			  	</div>
			</div>
		</div>
	</div>
</div>