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
        $pdf->Cell(100,10,"REGISTRO VACIO PARA {$mesLit} del {$anio}",0,0,'C');
    }else{
        $dataQuery = $arrData['data'];
        header('Content-Type: text/html; charset=UTF-8');
        while($element = $dataQuery->fetch(PDO::FETCH_ASSOC)){   
            $medidor = $element['tiene_medidor']?'Si':'No';    
            $cortado = $element['estado_corte']?'Si':'No';
            $nombre_completo = $element['categoria_suministro']=='Estatal'?utf8_decode($element['nombre']):utf8_decode($element['apellido']." ".$element['nombre']); 
            $msjCorte = ($element['contador_deuda']>=2)?'EN CORTE':'No eaksd akdka ndaks dnka sna';

            $pdf->AddPage();    
            $pdf->Image($_POST['urlimg'] ,0,0,148,210);
            
            $pdf->SetFont('Arial','B',20);
            $pdf->SetXY(5,7);
            $pdf->Cell(30,12,'ASUSAP',0,0,'C');

            $pdf->SetFont('Arial','B',7);
            $pdf->SetXY(55,7);
            //$info = utf8_decode($element['apellido']);
            $pdf->Cell(60,5,"$nombre_completo ",1,1,'');//
            $pdf->SetXY(22,8);
            $pdf->Cell(100,10,$element['direccion'],0,0,'C');
        
            $pdf->SetXY(27,100);
            $pdf->Cell(100,10,$element['monto_pagar'],0,0,'C');
            $pdf->SetXY(27,105);
            $pdf->Cell(100,10,$element['cod_suministro'],0,0,'C');
            $pdf->SetXY(27,110);
            $pdf->Cell(100,10,$element['categoria_suministro'],0,0,'C');
            $pdf->SetXY(27,115);
            $pdf->Cell(100,10,"Tiene medidor:".$medidor,0,0,'C');
            $pdf->SetXY(27,120);
            $pdf->Cell(100,10,"Cantidad deudas: {$element['contador_deuda']}",0,0,'C');
            $pdf->SetXY(10,150);
            $pdf->Cell(0,10,$msjCorte,0,0,'');

        }        
    }
    
    $pdf->Output();
