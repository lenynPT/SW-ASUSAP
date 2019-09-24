<?php
	$AjaxRequest=true;
	require_once "../core/configSite.php";
	if(isset($_POST['username-reg']) || isset($_POST['code-del'])){

		require_once "../controllers/adminController.php";
		$insAdmin = new adminController();

		if(isset($_POST['name-reg']) && isset($_POST['lastname-reg']) && isset($_POST['username-reg']) && isset($_POST['password1-reg']) && isset($_POST['password2-reg'])){
			echo $insAdmin->add_admin_controller();
		}

		if(isset($_POST['code-del'])){
			echo $insAdmin->delete_admin_controller();
		}
		
	}else{
		session_start(['name'=>'SRCE']);
		session_unset();
		session_destroy();
		echo '<script> window.location.href="'.SERVERURL.'login/"; </script>';
	}