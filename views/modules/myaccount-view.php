<?php
	if($_SESSION['user_type_srce']!="Administrador"){
		echo $sc->force_close_session_controller();
	}
?>