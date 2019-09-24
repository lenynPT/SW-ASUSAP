<?php
	require_once "./models/viewsModel.php";
	class viewsController extends viewsModel{
		public function getTemplate(){
			require_once "./views/template.php";
		}
		public function getViewsController(){
			if(isset($_GET['views'])){
				$route=explode("/", $_GET['views']);
				$view=$route[0];
				$response=viewsModel::getViewsModel($view);
			}else{
				$response="login";
			}
			return $response;
		}
	}