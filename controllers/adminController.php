<?php
	if($AjaxRequest){
		require_once "../models/adminModel.php";
	}else{
		require_once "./models/adminModel.php";
	}
	class adminController extends adminModel{


        public function guardarUsuarioController(){

            if (isset($_POST["nameUser"])){

                $query2=self::execute_single_query("SELECT dni FROM asociado");
                $correlative=($query2->rowCount())+1;
                $code=self::generate_code("EC",4,$correlative);

                $datosController = array(
                    "dni" => $_POST["dniUser"]
                );
                    $respuesta = adminModel::guardarUsuario($datosController);
                    echo '<script>
                    swal({
                          title: "¡OK!",
                          text: "¡Usuario ha sido creado correctamente!"+'.$correlative.',
                          type: "success",
                          confirmButtonText: "Cerrar",
                          closeOnConfirm: false
                    },
                    function(isConfirm){
                             if (isConfirm) {	   
                                window.location = "newaasociat";
                              } 
                    });
                    </script>';


            }

        }

		/*----------  Función para guardar admin - Function to save admin  ----------*/
		/*public function add_admin_controller(){

			$name=mainModel::clean_string($_POST['name-reg']);
			$lastname=mainModel::clean_string($_POST['lastname-reg']);
			$phone=mainModel::clean_string($_POST['phone-reg']);
			$address=mainModel::clean_string($_POST['address-reg']);

			$username=mainModel::clean_string($_POST['username-reg']);
			$password1=mainModel::clean_string($_POST['password1-reg']);
			$password2=mainModel::clean_string($_POST['password2-reg']);
			$gender=mainModel::clean_string($_POST['gender-reg']);
			$email=mainModel::clean_string($_POST['email-reg']);

			$privelege=mainModel::decryption($_POST['privelege-reg']);
			$privelege=mainModel::clean_string($privelege);

			if($privelege<1 || $privelege>3){
				$dataAlert=[
					"Title"=>"Ocurrió un error inesperado",
					"Text"=>"El nivel de privilegio que intenta asignar no es correcto",
					"Type"=>"error",
					"Alert"=>"single"
				];
			}else{
				if($password1!=$password2){
					$dataAlert=[
						"Title"=>"Ocurrió un error inesperado",
						"Text"=>"Las contraseñas que acabas de ingresar no coinciden, por favor verifique e intente nuevamente",
						"Type"=>"error",
						"Alert"=>"single"
					];
				}else{

					if($email!=""){
						$query1=mainModel::execute_single_query("SELECT * FROM account WHERE AccountEmail='$email'");
						$ne=$query1->rowCount();
					}else{
						$ne=0;
					}

					if($ne>=1){
						$dataAlert=[
							"Title"=>"Ocurrió un error inesperado",
							"Text"=>"El Email que acaba de ingresar ya se encuentra registrado, por favor verifique e intente nuevamente",
							"Type"=>"error",
							"Alert"=>"single"
						];
					}else{
						$query2=mainModel::execute_single_query("SELECT AccountUserName FROM account WHERE AccountUserName='$username'");
						if($query2->rowCount()>=1){
							$dataAlert=[
								"Title"=>"Ocurrió un error inesperado",
								"Text"=>"El nombre de usuario que acaba de ingresar ya se encuentra registrado, por favor verifique e intente nuevamente",
								"Type"=>"error",
								"Alert"=>"single"
							];
						}else{

							$query3=mainModel::execute_single_query("SELECT AdminCode FROM admin");
							$correlative=($query3->rowCount())+1;
							$code=mainModel::generate_code("AC",7,$correlative);

							if($gender=="Masculino"){
								$photo="AdminMaleAvatar.png";
							}else{
								$photo="AdminFemaleAvatar.png";
							}

							$password=mainModel::encryption($password1);

							$dataAc=[
								"AccountCode"=>$code,
								"AccountPrivilege"=>$privelege,
								"AccountUserName"=>$username,
								"AccountEmail"=>$email,
								"AccountPass"=>$password,
								"AccountStatus"=>"Activo",
								"AccountType"=>"Administrador",
								"AccountGender"=>$gender,
								"AccountPhoto"=>$photo
							];

							$AddAccount=mainModel::save_account($dataAc);
							if($AddAccount->rowCount()>=1){

								$dataAd=[
									"AdminName"=>$name,
									"AdminLastName"=>$lastname,
									"AdminAddress"=>$address,
									"AdminPhone"=>$phone,
									"AccountCode"=>$code
								];

								$AddAdmin=adminModel::add_admin_model($dataAd);
								if($AddAdmin->rowCount()>=1){
									$dataAlert=[
										"Title"=>"Administrador registrado",
										"Text"=>"Los datos del administrador se registraron con éxito",
										"Type"=>"success",
										"Alert"=>"clear"
									];
								}else{
									mainModel::delete_account($code);
									$dataAlert=[
										"Title"=>"Ocurrió un error inesperado",
										"Text"=>"No hemos podido registrar el administrador, por favor intente nuevamente",
										"Type"=>"error",
										"Alert"=>"single"
									];
								}
							}else{
								$dataAlert=[
									"Title"=>"Ocurrió un error inesperado",
									"Text"=>"No hemos podido registrar el administrador, por favor intente nuevamente",
									"Type"=>"error",
									"Alert"=>"single"
								];
							}
						}
					}
				}	
			}
			return mainModel::sweet_alert($dataAlert);
		}*/


		/*----------  Función para paginar los administradores - Function for paging administrators  ----------*/
//		public function pagination_admin_controller($page,$result,$level,$codeA,$search){
//
//			$page=mainModel::clean_string($page);
//			$result=mainModel::clean_string($result);
//			$level=mainModel::clean_string($level);
//			$codeA=mainModel::clean_string($codeA);
//			$search=mainModel::clean_string($search);
//			$table="";
//
//
//			$page = (isset($page) && $page>0) ? (int) $page : 1;
//
//			$startR = ($page>0) ? (($page * $result)-$result) : 0;
//
//
//			if(isset($search) && $search!=""){
//				$consult="SELECT SQL_CALC_FOUND_ROWS * FROM admin WHERE ((AccountCode!='$codeA' AND 	AdminCode!='1') AND (AdminName LIKE '%$search%' OR AdminLastName LIKE '%$search%' OR AdminPhone LIKE '%$search%')) ORDER BY AdminName ASC LIMIT $startR,$result";
//				$pageurl="adminsearch";
//			}else{
//				$consult="SELECT SQL_CALC_FOUND_ROWS * FROM admin WHERE AccountCode!='$codeA' AND AdminCode!='1' ORDER BY AdminName ASC LIMIT $startR,$result";
//				$pageurl="adminlist";
//			}
//
//
//			$conection = mainModel::connect();
//
//			$data = $conection->query($consult);
//			$data = $data->fetchAll();
//
//			$total = $conection->query("SELECT FOUND_ROWS()");
//			$total = (int) $total->fetchColumn();
//
//			$numPages=ceil($total/$result);
//
//			$table.='
//				<div class="table-responsive">
//					<table class="table table-hover text-center">
//						<thead>
//							<tr>
//								<th class="text-center">#</th>
//								<th class="text-center">Nombres</th>
//								<th class="text-center">Apellidos</th>
//								<th class="text-center">Télefono</th>';
//								if($level<=2){
//									$table.='
//										<th class="text-center">A. Cuenta</th>
//										<th class="text-center">A. Datos</th>
//									';
//								}
//								if($level==1){
//									$table.='
//										<th class="text-center">Eliminar</th>
//									';
//								}
//			$table.='
//							</tr>
//						</thead>
//						<tbody>
//			';
//
//			if($total>=1 && $page<=$numPages){
//				$nt=$startR+1;
//				foreach($data as $rows){
//					$table.='
//						<tr>
//							<td>'.$nt.'</td>
//							<td>'.$rows['AdminName'].'</td>
//							<td>'.$rows['AdminLastName'].'</td>
//							<td>'.$rows['AdminPhone'].'</td>
//					';
//					if($level<=2){
//						$table.='
//							<td>
//								<a href="'.SERVERURL.'myaccount/admin/'.mainModel::encryption($rows['AccountCode']).'/" class="btn btn-success btn-raised btn-xs">
//									<i class="zmdi zmdi-refresh"></i>
//								</a>
//							</td>
//							<td>
//								<a href="'.SERVERURL.'mydata/admin/'.mainModel::encryption($rows['AccountCode']).'/" class="btn btn-success btn-raised btn-xs">
//									<i class="zmdi zmdi-refresh"></i>
//								</a>
//							</td>
//						';
//					}
//					if($level==1){
//						$table.='
//							<td>
//								<form action="'.SERVERURL.'ajax/adminAjax.php" method="POST" class="DataAjaxForm" data-form="delete" enctype="multipart/form-data" autocomplete="off">
//									<input type="hidden" name="code-del" value="'.mainModel::encryption($rows['AccountCode']).'">
//									<input type="hidden" name="admin-level" value="'.mainModel::encryption($level).'">
//									<button type="submit" class="btn btn-danger btn-raised btn-xs">
//										<i class="zmdi zmdi-delete"></i>
//									</button>
//									<span class="AjaxReply"></span>
//								</form>
//							</td>
//						';
//					}
//					$table.='
//						</tr>
//					';
//					$nt++;
//				}
//			}else{
//				if($total>=1){
//					$table.='
//						<tr>
//							<td colspan="7">
//								<a href="'.SERVERURL.$pageurl.'/" class="btn btn-sm btn-info btn-raised">
//									Haga clic acá para recargar el listado
//								</a>
//							</td>
//						</tr>
//					';
//				}else{
//					$table.='
//						<tr>
//							<td colspan="7">
//								No hay registros en el sistema
//							</td>
//						</tr>
//					';
//				}
//			}
//
//			$table.='</tbody></table></div>
//			';
//
//			if($total>=1 && $page<=$numPages){
//				$table.='<nav class="text-center"><ul class="pagination pagination-sm">';
//
//				if($page==1){
//					$table.='<li class="disabled"><a><i class="zmdi zmdi-arrow-left"></i></a></li>';
//				}else{
//					$table.='<li><a href="'.SERVERURL.$pageurl.'/'.($page-1).'/"><i class="zmdi zmdi-arrow-left"></i></a></li>';
//				}
//
//				for($i=1; $i <= $numPages; $i++){
//					if($page == $i){
//						$table.='<li class="active"><a href="'.SERVERURL.$pageurl.'/'.$i.'/">'.$i.'</a></li>';
//					}else{
//						$table.='<li><a href="'.SERVERURL.$pageurl.'/'.$i.'/">'.$i.'</a></li>';
//					}
//				}
//
//				if($page==$numPages){
//					$table.='<li class="disabled"><a><i class="zmdi zmdi-arrow-right"></i></a></li>';
//				}else{
//					$table.='<li><a href="'.SERVERURL.$pageurl.'/'.($page+1).'/"><i class="zmdi zmdi-arrow-right"></i></a></li>';
//				}
//
//				$table.='</nav></div>';
//			}
//			return $table;
//		}
//
//
//		/*----------  Función para eliminar administrador - Function to delete administrator  ----------*/
//		public function delete_admin_controller(){
//			$code=mainModel::decryption($_POST['code-del']);
//			$adminLevel=mainModel::decryption($_POST['admin-level']);
//
//			$code=mainModel::clean_string($code);
//			$adminLevel=mainModel::clean_string($adminLevel);
//
//			if($adminLevel==1){
//				$query1=mainModel::execute_single_query("SELECT * FROM admin WHERE AccountCode='$code'");
//				$adminData=$query1->fetch();
//				if($adminData['AdminCode']!=1){
//					$DelAdmin=adminModel::delete_admin_model($code);
//					mainModel::delete_binnacle($code);
//					if($DelAdmin->rowCount()>=1){
//						$DelAccount=mainModel::delete_account($code);
//						if($DelAccount->rowCount()>=1){
//							$dataAlert=[
//								"Title"=>"Administrador eliminado",
//								"Text"=>"El administrador fue eliminado del sistema con éxito",
//								"Type"=>"success",
//								"Alert"=>"reload"
//							];
//						}else{
//							$dataAlert=[
//								"Title"=>"Ocurrió un error inesperado",
//								"Text"=>"No podemos eliminar la cuenta en este momento",
//								"Type"=>"error",
//								"Alert"=>"single"
//							];
//						}
//					}else{
//						$dataAlert=[
//							"Title"=>"Ocurrió un error inesperado",
//							"Text"=>"No podemos eliminar este administrador en este momento",
//							"Type"=>"error",
//							"Alert"=>"single"
//						];
//					}
//				}else{
//					$dataAlert=[
//						"Title"=>"Ocurrió un error inesperado",
//						"Text"=>"No podemos eliminar el administrador principal del sistema",
//						"Type"=>"error",
//						"Alert"=>"single"
//					];
//				}
//			}else{
//				$dataAlert=[
//					"Title"=>"Ocurrió un error inesperado",
//					"Text"=>"Tú no tienes los permisos necesarios para eliminar registros",
//					"Type"=>"error",
//					"Alert"=>"single"
//				];
//			}
//			return mainModel::sweet_alert($dataAlert);
//		}


	}