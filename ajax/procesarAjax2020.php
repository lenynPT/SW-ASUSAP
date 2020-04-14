<?php

    $AjaxRequest=true;
    require_once "../controllers/adminController.php";

    //Lo transforma en un objeto :: crea una 'new stdClass'
    $data = json_decode($_POST['data']); 

    // isset() -> Comprobar si una variable está definida
    if(isset($data)){
        //SI No está vacio. empty() -> para saber si una variable está vacía
        if(!empty($data->OPTION)){            

            //Opcion para generar consumo SIN medidor
            if($data->OPTION == 'habilitaConsumo'){
                
                $habilitarConsumo = new adminController();
                $resData = $habilitarConsumo->update_habilitarConsumoController() ;
                echo json_encode($resData);            

            }
            else {
                # code...
                echo json_encode(["eval"=>false,"data"=>[]]);
            }

        }
    }
