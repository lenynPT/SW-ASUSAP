<!DOCTYPE html>
<html lang="es">
<head>
	<title><?php echo COMPANY; ?></title>
	<?php require_once "./views/sections/links.php"; ?>
</head>
<body>
	<?php
		$AjaxRequest=false;

		$tp = new viewsController(); 
		$response = $tp->getViewsController();

		if($response=="login" || $response=="404"){
			require_once "./views/modules/".$response."-view.php";
		}else{
			session_start(['name'=>'ASUSAP']);

			/*----------  Check Access  ----------*/
            require_once "./controllers/loginController.php";
            $sc = new loginController();
            if(!isset($_SESSION['user_token_srce']) || !isset($_SESSION['user_name_srce'])){
            	echo $sc->force_close_session_controller();	
            }
	?>
	<!-- SideBar -->
	<?php require_once "./views/sections/sideBar.php"; ?>

	<!-- Content page-->
	<section class="full-box dashboard-contentPage">
		<!-- NavBar -->
		<?php require_once "./views/sections/navBar.php"; ?>

		<!-- Content -->
		<?php 
			require_once $response;
		?>
	</section>
	<?php 
			//Notifications area
			//require_once "./views/sections/notificationArea.php";

			// Modal help
			require_once "./views/sections/modalHelp.php";

			//Scripts JS 
			require_once "./views/sections/scriptLogOut.php"; 
		}
	?>
	<script>
		$.material.init();
	</script>
</body>
</html>