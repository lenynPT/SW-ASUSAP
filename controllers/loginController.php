<?php
	if($AjaxRequest){
		require_once "../models/loginModel.php";
	}else{
		require_once "./models/loginModel.php";
	}
	class loginController extends loginModel{

		/* Funcion para iniciar sesion - Function to log in */
		public function session_start_controller(){

			$userName=mainModel::clean_string($_POST['loginUserName']);
			$userPass=mainModel::clean_string($_POST['loginUserPass']);

			//$userPass=mainModel::encryption($userPass);

			$dataLogin=[
				"AccountUserName"=>$userName,
				"AccountPass"=>$userPass
			];

			if($dataAccount=loginmodel::session_start_model($dataLogin)){
				if($dataAccount->rowCount()==1){

					$row=$dataAccount->fetch();

                    session_start(['name'=>'ASUSAP']);
                    $_SESSION['user_name_srce']=$row['usuario'];
                    $_SESSION['user_token_srce']=$row['password'];
                   // $_SESSION['user_token_srce']=md5(uniqid(mt_rand(), true));

                    $dateNow=date("Y-m-d");
					$yearNow=date("Y");
					$timeNow=date("h:i:s a");

                    $url=SERVERURL."dashboard/";

					/*$query1=mainModel::execute_single_query("SELECT BinnacleId FROM binnacle");
					$correlative=($query1->rowCount())+1;

					$bCode=mainModel::generate_code("BC",7,$correlative);

					$dataBit=[
						"BinnacleCode"=>$bCode,
						"BinnacleDate"=>$dateNow,
						"BinnacleStartTime"=>$timeNow,
						"BinnacleEndTime"=>"Sin registro",
						"BinnacleType"=>$row['AccountType'],
						"YearCode"=>$yearNow,
						"AccountCode"=>$row['AccountCode']
					];

					if(mainModel::save_binnacle($dataBit)){
                    session_start(['name'=>'SRCE']);
                    $_SESSION['user_name_srce']=$row['AccountUserName'];
                    $_SESSION['user_type_srce']=$row['AccountType'];
                    $_SESSION['user_privilege_srce']=$row['AccountPrivilege'];
                    $_SESSION['user_photo_srce']=$row['AccountPhoto'];
                    $_SESSION['user_token_srce']=md5(uniqid(mt_rand(), true));
                    $_SESSION['account_code_srce']=$row['AccountCode'];
                    $_SESSION['binnacle_code_srce']=$bCode;

                    if($row['AccountType']=="Administrador"){
                        $url=SERVERURL."dashboard/";
                    }
                    return $urlLocation='<script type="text/javascript"> window.location="'.$url.'"; </script>';
                }else{
                        $dataAlert=[
                            "Title"=>"Ocurrió un error inesperado",
                            "Text"=>"No hemos podido iniciar la sesión por problemas técnicos, por favor intente nuevamente",
                            "Type"=>"error",
                            "Alert"=>"single"
                        ];
                        return mainModel::sweet_alert($dataAlert);
                    }*/
				}else{
					$dataAlert=[
						"Title"=>"Ocurrió un error inesperado",
						"Text"=>"El nombre de usuario y contraseña no son correctos o su cuenta puede estar deshabilitada",
						"Type"=>"error",
						"Alert"=>"single"
					];
					return mainModel::sweet_alert($dataAlert);
				}
                return $urlLocation='<script type="text/javascript"> window.location="'.$url.'"; </script>';
			}else{
				$dataAlert=[
					"Title"=>"Ocurrió un error inesperado",
					"Text"=>"No se pudo realizar la petición",
					"Type"=>"error",
					"Alert"=>"single"
				];
				return mainModel::sweet_alert($dataAlert);
			}
		}


		/* Funcion para destruir sesion - Function to destroy session */
       public function session_destroy_controller(){
			session_start(['name'=>'ASUSAP']);
			$token=$_GET['token'];
			$dataLogin=[
				"userName"=>$_SESSION['user_name_srce'],
				"token"=>$token
			];
			return loginModel::session_destroy_model($dataLogin);
		}


		/*=== Force Close Session Controller ====*/
		public function force_close_session_controller(){
			session_start(['name'=>'ASUSAP']);
			session_unset();
			session_destroy();
			return header("Location: ".SERVERURL."login/");
		}

	}