<?php

    $AjaxRequest=true;
    require_once "../controllers/adminController.php";

    //$option = $_POST['OPTION'];
    // isset() -> Comprobar si una variable está definida
    if(isset($_POST['OPTION'])){
        //SI No está vacio. empty() -> para saber si una variable está vacía
        if(!empty($_POST['OPTION'])){
            

            //Opcion para generar consumo SIN medidor
            if($_POST['OPTION'] == 'GCSnMedi'){
                
                $gcsnmed = new adminController();
                $msj = $gcsnmed->insertarConsumoSnMController() ;
                echo json_encode($msj);            

            }    
        
            if($_POST['OPTION'] == 'GCCnMedi'){
                
                $codigoSum = $_POST['codigo_sum'];

                $regSumi = new adminController();
                $response = $regSumi->datosSumiAsocController($codigoSum);

                echo json_encode($response);
            }

            else if($_POST['OPTION'] == 'insertGCCnM'){

                $arrData = [
                    "cod_sum"=>$_POST['cod_sum'],
                    "consumo"=>$_POST['consumo'],
                    "monto"=>$_POST['monto']                    
                ];

                $respInsert = new adminController();
                $response = $respInsert->insertarCSumCnMController($arrData);

                echo json_encode($response);
            }
        
        
        }
    }
