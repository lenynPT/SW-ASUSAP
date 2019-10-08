<?php
	if($AjaxRequest){
		require_once "../core/mainModel.php";
	}else{
		require_once "./core/mainModel.php";
	}
	class adminModel extends mainModel{


        public function guardarUsuario($datosModel){

        $stmt=mainModel::connect()->prepare("INSERT INTO asociado (dni, nombre, apellido, telefono, estado, cant_suministro)
                                            VALUES (:dnis, NULL, NULL, NULL, NULL, NULL ");
        $stmt->bindParam(":dnis",$datosModel["dni"]);
        /*$stmt->bindParam(":nombre",$datosModel["nombre"]);
        $stmt->bindParam(":apellido",$datosModel["apellido"]);
        $stmt->bindParam(":telefono",$datosModel["telefono"]);
        $stmt->bindParam(":estado",$datosModel["estado"]);
        $stmt->bindParam(":cantSumi",$datosModel["direccion"]);*/
        $stmt->execute();
        return $stmt;

        }

	}