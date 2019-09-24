<div class="full-box cover login-bg">
	<form method="POST" autocomplete="off" class="full-box logInForm">
		<p class="text-center text-muted"><i class="zmdi zmdi-account-circle zmdi-hc-5x"></i></p>
		<p class="text-center text-muted text-uppercase">Inicia sesión con tu cuenta</p>
		<div class="form-group label-floating">
		  <label class="control-label" for="loginUserName">Nombre de usuario</label>
		  <input class="form-control" id="loginUserName" type="text" name="loginUserName" pattern="[a-zA-Z0-9]{1,15}" maxlength="15" required="">
		  <p class="help-block">Escribe tú nombre de usuario</p>
		</div>
		<div class="form-group label-floating">
		  <label class="control-label" for="loginUserPass">Contraseña</label>
		  <input class="form-control" id="loginUserPass" type="password" name="loginUserPass" required="">
		  <p class="help-block">Escribe tú contraseña</p>
		</div>
		<div class="form-group text-center">
			<input type="submit" value="Iniciar sesión" class="btn btn-info" style="color: #FFF;">
		</div>
	</form>
</div>
<?php 
	if(isset($_POST['loginUserName']) && isset($_POST['loginUserPass'])){
		require_once "./controllers/loginController.php";
		$reg = new loginController();
		echo $reg->session_start_controller();
	}
?>