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
            $this->SetY(-10);
            $this->SetFont('Arial','I',8);
            $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
        }
    }

    $cod_sum = $_GET['codigoSum'];
    
    $dataObj = new adminController();
    $arrData = $dataObj->dataReciboXanio($cod_sum);
    
    $del_mes = $arrData['data']['del_mes'];
    $anio = $arrData['data']['anio'];

    $fechaL = $dataObj->obtenerNombrefecha($anio,$del_mes);
    $mesLit = $fechaL['r_mes'];

    $fecha_e = "{$mesLit}-{$anio}";

    $_POST['urlimg'] = $arrData['res']?'img/reciboAgua.jpg':'img/sinResultado.jpg';

    //pdf
    $pdf = new PDF('P','mm','A5');

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
        $pdf->SetXY(21,35);
        $pdf->Cell(50,5,$nombre_completo ,0,0,'');  //nombre de tituar
        $pdf->SetXY(24,40.5);
        $pdf->Cell(50,5,utf8_decode($registro['direccion']),0,0,'');    //direccion del titular 
        $pdf->SetXY(8,43);
        $pdf->Cell(100,10,utf8_decode("SAN JERÓNIMO"),0,0,''); //distrito
        $pdf->SetXY(11,48.3);
        $pdf->Cell(100,10,$registro['categoria_suministro'],0,0,''); //categoria del suministro

        //SEGUNDA COLUMNA - INFORMACIÓN DE PAGO ****************************************************
        $pdf->SetXY(118,35);
        $pdf->Cell(50,5,"{$mesLit} - Dic.",0,0,''); //mes facturado 
        $pdf->SetXY(129,40.3);
        $pdf->Cell(50,5,utf8_decode("Año"),0,0,''); //frecuencia de facturación
        $pdf->SetXY(121,45.5);
        $pdf->Cell(50,5,$fecha_e,0,0,''); //fecha emision
        $pdf->SetXY(125,50.7);
        $pdf->Cell(50,5,utf8_decode('Mismo día'),0,0,''); //fecha vencimiento

        //REGISTROS DEL MEDIDOR ****************************************************
        $pdf->SetXY(7,62);
        $pdf->Cell(100,10,'No',0,0,''); // Tiene medidor??
        $pdf->SetXY(18,63);
        $pdf->Cell(19,8,"# m3",0,0,'C'); // lectura anterior
        $pdf->SetXY(37,63);
        $pdf->Cell(19,8,"# m3",0,0,'C'); // lectura Actual
        $pdf->SetXY(56,63);
        $pdf->Cell(19,8,"# m3",0,0,'C'); // consumo

        //INFORMACIÓN COMPLEMENTARIA ****************************************************              
        $pdf->SetXY(10,75);
        $pdf->Cell(100,10,'RECIBO CANCELADO',0,0,''); // Está cancelado el recibo ??
        
        $pdf->SetXY(10,82);
        $pdf->Cell(100,5,"",0,0,'');  

        //DETALLE DE LA FACTURACIÓN ****************************************************
            //primera fila de 
            $pdf->SetXY(85,64);
            $pdf->Cell(100,10,"Por consumo de agua x mes",0,0,'');
            $pdf->SetXY(130,64);
            $pdf->Cell(100,10,"$/ 3.56",0,0,'');
            //segunda fila de 
            $pdf->SetXY(85,67);
            $pdf->Cell(100,10,"Por IGV (18%)",0,0,'');
            $pdf->SetXY(130,67);
            $pdf->Cell(100,10,"$/ 0.64",0,0,'');
            
            imprimirXmes($pdf,$registro['del_mes'],$dataObj);

        $pdf->SetXY(118,139);
        $pdf->Cell(100,10,"S/ ".$registro['monto'],0,0,'');
    
    }


    $pdf->Output();


    function imprimirXmes($pdf,$del_mes,$dataObj){

        $y = 6;

        for ($mes=$del_mes; $mes <= 12; $mes++) { 
            # code...
            //Imprimiendo los meses
            $mesName = $dataObj->obtenerNombrefecha(2019,$mes);

            $pdf->SetXY(85,67+$y);
            $pdf->Cell(100,10,"Para el mes de {$mesName['r_mes']}",0,0,'');
            $pdf->SetXY(130,67+$y);
            $pdf->Cell(100,10,"$/ 4.20",0,0,'');

            $y+=3;
        }
    }
