<?php
	if($AjaxRequest){
		require_once "../core/mainModel.php";
	}else{
		require_once "./core/mainModel.php";
	}
	class loginModel extends mainModel{

    protected function session_start_model($dataLogin){
        $query=mainModel::connect()->prepare("SELECT * FROM usuario WHERE usuario=:AccountUserName AND password=:AccountPass ");
        $query->bindParam(":AccountUserName",$dataLogin['AccountUserName']);
        $query->bindParam(":AccountPass",$dataLogin['AccountPass']);
        $query->execute();
        return $query;
    }

	protected function session_destroy_model($dataLogin){
			if($dataLogin['userName']!="" && $dataLogin['token']==$dataLogin['token']){
					session_unset();
					session_destroy();
					$response="true";
			}else{
				$response="false";
			}
			return $response;
		}
	}