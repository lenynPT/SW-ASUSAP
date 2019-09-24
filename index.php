<?php
	require_once "./core/configSite.php";
	require_once "./controllers/viewsController.php";

	$ViewTemplate=new viewsController();
	$ViewTemplate->getTemplate();