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
        
        
        
        
        }
    }
