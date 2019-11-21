<?php 

    //Permite incluir los archivos necesarios para las funciones de consulta.
    $AjaxRequest=true;

    require_once "../controllers/adminController.php";
    require "fpdf/fpdf.php";

    class PDF extends FPDF
    {
        function Header()
        {
            $this->SetFont('Arial','B',20);
            $this->SetXY(5,7);
            $this->Cell(30,12,'ASUSAP',0,0,'C');
            $this->Ln(20);
        }
        function Footer()
        {
            $this->SetXY(0,-24);
            $this->SetFont('Arial','B',10);
            $this->Cell(25,8,'Codigo Suministro: '.$_GET['codigoSum'],0,0,'');
            /* 
            */
        }
    }

    $cod_sum = $_GET['codigoSum'];
    
    $dataObj = new adminController();

    //mensaje admin
    $msjAdmin = utf8_decode($dataObj->getmensajeReciboController());

    $arrData = $dataObj->dataReciboXanio($cod_sum);
    
    $del_mes = $arrData['data']['del_mes'];
    $anio = $arrData['data']['anio'];

    $fechaL = $dataObj->obtenerNombrefecha($anio,$del_mes);
    $mesLit = $fechaL['r_mes'];

    $fecha_e = "{$mesLit}-{$anio}";

    $_POST['urlimg'] = $arrData['res']?'img/reciboAgua.jpg':'img/sinResultado.jpg';

    $mensajeAlPublico="Aquí el aviso para los suministros";
    //pdf
    $pdf = new PDF('P','mm',Array(148, 218.01));

    if($arrData['res']==false){
        $pdf->AddPage();    
        $pdf->Image($_POST['urlimg'] ,0,0,148,210);
    
        $pdf->SetFont('Arial','B',20);
        $pdf->SetXY(5,7);
        $pdf->Cell(30,12,'ASUSAP',0,0,'C');
    
        $pdf->SetFont('Arial','B',8);
        $pdf->SetXY(21,4.5);
        $pdf->Cell(100,10,"SIN REGISTRO PARA {$mesLit} del {$anio}",0,0,'C');
    }else {
        $registro = $arrData['data'];
        $nombre_completo = $registro['categoria_suministro']=='Estatal'?utf8_decode($registro['nombre']):utf8_decode($registro['apellido']." ".$registro['nombre']); 

        //AGREGAMOS PÁGINA ****************************************************
        $pdf->AddPage();    
        $pdf->Image($_POST['urlimg'] ,0,0,148,210);
        //código del suministro
        $pdf->SetFont('Arial','B',15);     
        $pdf->SetXY(103,5);
        $pdf->Cell(45,8,$registro['cod_sum_anio'],0,0,'C');
        //CONFIGURANDO EL TAMAÑO Y TIPO DE LETRA
        $pdf->SetFont('Arial','B',7);  
        //PRIMERA COLUMNA - INFORMACIÓN GENERAL ****************************************************
        $pdf->SetXY(24,35.5);
        $pdf->Cell(50,5,$nombre_completo ,0,0,'');  //nombre de tituar
        $pdf->SetXY(27,40.8);
        $pdf->Cell(50,5,utf8_decode($registro['direccion']),0,0,'');    //direccion del titular 
        $pdf->SetXY(9,43.5);
        $pdf->Cell(100,10,utf8_decode("SAN JERÓNIMO"),0,0,''); //distrito
        $pdf->SetXY(11,48.8);
        $pdf->Cell(100,10,$registro['categoria_suministro'],0,0,''); //categoria del suministro

        //SEGUNDA COLUMNA - INFORMACIÓN DE PAGO ****************************************************
        $pdf->SetXY(119,35.5);
        $pdf->Cell(50,5,"{$mesLit} - Dic.",0,0,''); //mes facturado 
        $pdf->SetXY(130.5,40.7);
        $pdf->Cell(50,5,utf8_decode("Año"),0,0,''); //frecuencia de facturación
        $pdf->SetXY(121.8,46);
        $pdf->Cell(50,5,$fecha_e,0,0,''); //fecha emision
        $pdf->SetXY(127,51.2);
        $pdf->Cell(50,5,utf8_decode('Mismo día'),0,0,''); //fecha vencimiento

        //REGISTROS DEL MEDIDOR ****************************************************
        $pdf->SetXY(0,63);
        $pdf->Cell(18,9,'No',0,0,'C'); // Tiene medidor??
        $pdf->SetXY(18,63);
        $pdf->Cell(19,9,"# m3",0,0,'C'); // lectura anterior
        $pdf->SetXY(37,63);
        $pdf->Cell(19,9,"# m3",0,0,'C'); // lectura Actual
        $pdf->SetXY(56,63);
        $pdf->Cell(19,9,"# m3",0,0,'C'); // consumo

        //INFORMACIÓN COMPLEMENTARIA ****************************************************              
        $pdf->SetXY(10,75);
        $pdf->Cell(100,10,'',0,0,''); // Está cancelado el recibo ??

        //MENSAJE DE CORTE        
        /*$pdf->SetXY(10,150);
        $pdf->Cell(0,10,utf8_decode($mensajeAlPublico),0,0,'');
        */
        $pdf->SetXY(2,160);
        $pdf->MultiCell( 70, 5,$msjAdmin, 0,'C');

        //Auxiliar 
        $infPag = imprimirXmes($pdf,$registro['del_mes'],$dataObj);

        //DETALLE DE LA FACTURACIÓN ****************************************************
        //primera fila de 
        $pdf->SetXY(78,64);
        $pdf->Cell(100,10,"Por consumo de agua por mes",0,0,'');
        $pdf->SetXY(122,64);
        $pdf->Cell(100,10,"($/ 3.56) X ".$infPag['cant_mess']." meses",0,0,'');
        
        /* 
        //segunda fila de 
            $pdf->SetXY(85,67);
            $pdf->Cell(100,10,"Por IGV (18%)",0,0,'');
            $pdf->SetXY(130,67);
            $pdf->Cell(100,10,"$/ 0.64",0,0,'');
        */
        
        

        $pdf->SetXY(130,103);
        $pdf->Cell(100,10,"S/. ".number_format($infPag['subTotal'],2),0,0,''); 
        $pdf->SetXY(130,107);
        $pdf->Cell(100,10,"S/. ".number_format($infPag['igvTotal'],2),0,0,''); 

        $pdf->SetFont('Arial','B',10); 
        $pdf->SetXY(118,140);
        $pdf->Cell(100,10,"S/. ".number_format($registro['monto'],2),0,0,'');

         //SECCIÓN RECORTAR -**********************************
         $pdf->SetFont('Arial','B',7); 
         $pdf->SetXY(21.5, 182.5);
         $pdf->Cell(100,10,$nombre_completo ,0,0,'');
         
         $pdf->SetXY(118, 182.5);
         $pdf->Cell(100,10,"{$mesLit} - Dic.",0,0,'');

         $pdf->SetXY(24, 188);
         $pdf->Cell(100,10,$registro['direccion'] ,0,0,'');
         
         $pdf->SetFont('Arial','B',10); 
         $pdf->SetXY(117.2, 187.5);
         $pdf->Cell(100,10,"S/. ".number_format($registro['monto'],2),0,0,'');
    
    }


    $pdf->Output();


    function imprimirXmes($pdf,$del_mes,$dataObj){

        $y = 6;
        $subTotal = 0;
        $cant_mess=0;
        for ($mes=$del_mes; $mes <= 12; $mes++) { 
            # code...
            //Imprimiendo los meses
            /*
            $mesName = $dataObj->obtenerNombrefecha(2019,$mes);

            $pdf->SetXY(85,67+$y);
            $pdf->Cell(100,10,"Para el mes de {$mesName['r_mes']}",0,0,'');
            $pdf->SetXY(130,67+$y);
            $pdf->Cell(100,10,"$/ 4.20",0,0,'');

            $y+=3;
            */
            $subTotal += 3.6;
            $igvTotal += 0.6;
            $cant_mess++;
        }
        
        return ['subTotal'=>$subTotal,'igvTotal'=>$igvTotal,'cant_mess'=>$cant_mess];
    }
