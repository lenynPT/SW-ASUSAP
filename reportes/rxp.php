<?php
    //Permite incluir los archivos necesarios para las funciones de consulta.
    $AjaxRequest=true;

    require_once "../controllers/adminController.php";
    require "fpdf/fpdf.php";

    $cod_sum = $_GET['codigoSum'];
    $anio = $_GET['anio'];
    $mes = $_GET['mes'];

    $dataObj = new adminController();
    $arrData = $dataObj->recibosObtenerDataSumXCod($cod_sum,$anio,$mes);
    $fechaL = $dataObj->obtenerNombrefecha($anio,$mes);
    $mesLit = $fechaL['r_mes'];
    //var_dump($arrData['data']->fetch());

    $_POST['urlimg'] = $arrData['res']?'img/reciboAgua.jpg':'img/sinResultado.jpg';

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

    //pdf
    $pdf = new PDF('P','mm','A5');

    if(!$arrData['res']){
        $pdf->AddPage();    
        $pdf->Image($_POST['urlimg'] ,0,0,148,210);

        $pdf->SetFont('Arial','B',20);
        $pdf->SetXY(5,7);
        $pdf->Cell(30,12,'ASUSAP',0,0,'C');

        $pdf->SetFont('Arial','B',8);
        $pdf->SetXY(21,4.5);
        $pdf->Cell(100,10,"SIN REGISTRO PARA {$mesLit} del {$anio}",0,0,'C');
    }else{
        $dataQuery = $arrData['data'];

        while($element = $dataQuery->fetch(PDO::FETCH_ASSOC)){   

            $esta_cancelado = $element['esta_cancelado']?'RECIBO CANCELADO':'FALTA PAGAR RECIBO';                    
            //verifica que sea una institución para solo imprimir el nombre        
            $_POST['urlimg'] = $element['estado_corte']?'img/corte.jpg':'img/reciboAgua.jpg';

            //recuperar los meses de las deudas anteriores
            $deudasMes = $dataObj->consultaDeudasMes($element['cod_suministro']);

            $medidor = $element['tiene_medidor']?'Si':'No';    
            $cortado = $element['estado_corte']?'Si':'No';
            $nombre_completo = $element['categoria_suministro']=='Estatal'?utf8_decode($element['nombre']):utf8_decode($element['apellido']." ".$element['nombre']); 
            $msjCorte = ($element['contador_deuda']>=3)?'EN CORTE':'no está en corte';

            //AGREGAMOS PÁGINA ****************************************************
            $pdf->AddPage();    
            $pdf->Image($_POST['urlimg'] ,0,0,148,210);

        //código del suministro
        $pdf->SetFont('Arial','B',15);     
        $pdf->SetXY(103,5);
        $pdf->Cell(45,8,$element['cod_suministro'],0,0,'C');
        
        //CONFIGURANDO EL TAMAÑO Y TIPO DE LETRA
        $pdf->SetFont('Arial','B',7);  

        //PRIMERA COLUMNA - INFORMACIÓN GENERAL ****************************************************
        $pdf->SetXY(21,35);
        $pdf->Cell(50,5,$nombre_completo ,0,0,'');  //nombre de tituar
        $pdf->SetXY(24,40.5);
        $pdf->Cell(50,5,$element['direccion'],0,0,'');    //direccion del titular 
        $pdf->SetXY(8,43);
        $pdf->Cell(100,10,utf8_decode("SAN JERÓNIMO"),0,0,''); //distrito
        $pdf->SetXY(11,48.3);
        $pdf->Cell(100,10,$element['categoria_suministro'],0,0,''); //categoria del suministro

        //SEGUNDA COLUMNA - INFORMACIÓN DE PAGO ****************************************************
        $pdf->SetXY(118,35);
        $pdf->Cell(50,5,"{$mesLit} del {$anio}",0,0,''); //mes facturado 
        $pdf->SetXY(129,40.3);
        $pdf->Cell(50,5,"mes",0,0,''); //frecuencia de facturación
        $pdf->SetXY(121,45.5);
        $pdf->Cell(50,5,$element['fecha_emision'],0,0,''); //fecha emision
        $pdf->SetXY(125,50.7);
        $pdf->Cell(50,5,$element['fecha_vencimiento'],0,0,''); //fecha vencimiento


        //REGISTROS DEL MEDIDOR ****************************************************
        $pdf->SetXY(7,62);
        $pdf->Cell(100,10,$medidor,0,0,''); // Tiene medidor??


        //INFORMACIÓN COMPLEMENTARIA ****************************************************              
        $pdf->SetXY(10,75);
        $pdf->Cell(100,10,$esta_cancelado,0,0,''); // Está cancelado el recibo ??
        
        $pdf->SetXY(10,82);
        $pdf->Cell(100,5,"Cantidad deudas: {$element['contador_deuda']}",0,0,'');   
        
        //Imprime los meses endeudados
        $pdf->SetXY(10,87);        
        foreach ($deudasMes as $value) {
            # code...
            $nombreMes =  $dataObj->obtenerNombrefecha($value['anio'],$value['mes']);
            $pdf->SetX(10);
            $pdf->Cell(30,3,$nombreMes['r_mes']." del ".$nombreMes['r_anio'],1,0,'');
            $pdf->ln();
        }  
        
        //DETALLE DE LA FACTURACIÓN ****************************************************
        $pdf->SetXY(118,139);
        $pdf->Cell(100,10,"S/ ".$element['monto_pagar'],0,0,'');
        
        //mensaje de corte
        $pdf->SetXY(10,150);
        $pdf->Cell(0,10,utf8_decode($msjCorte),0,0,'');

        }        
    }
    
    $pdf->Output();
