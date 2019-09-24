<?php
	$AjaxRequest=true;
	require_once "../core/configSite.php";
	if(isset($_GET['token'])){


		/* Destruyendo la sesion - Destroy session */
		if(isset($_GET['token'])){
			require_once "../controllers/loginController.php";
			$logout = new loginController();
			echo $logout->session_destroy_controller();
		}
		
	}else{
		session_start(['name'=>'ASUSAP']);
		session_unset();
		session_destroy();
		echo '<script> window.location.href="'.SERVERURL.'login/"; </script>';
	}