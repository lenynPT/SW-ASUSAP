<?php
//Permite incluir los archivos necesarios para las funciones de consulta.
$AjaxRequest=true;

require_once "../core/configSite.php";

require_once "../controllers/adminController.php";

$inst = new adminController();

require "fpdf/fpdf.php";

$idate=$_GET['inicioDate'];
$fdate=$_GET['finalDate'];

$query = "SELECT * FROM factura_servicio WHERE ";


$query .= 'fecha BETWEEN "'.$idate.'" AND "'.$fdate.'" ';
$query .= 'ORDER BY fecha DESC ';
$result = $inst->consultaAsociado( $query );
//Permite incluir los archivos necesarios para las funciones de consulta.

$_POST['urlimg'] = 'img/boletaAGua.jpg';
//anRSI=${anIRS}&codSI=${codsuI}&desNSI=${co},
//$codSRSI=$_POST['CodRS']  tto;
//window.open(`../reportes/reportesAmortizar.php?inicioDate=${start_date}&finalDate=${end_date}`,'_blank')

$IRAS=$_GET['RAS'];
//$idate=$_GET['inicioDate'];
//$fdate=$_GET['finalDate'];


//session_start(['name'=>'ASUSAP']);
//$_SESSION['cost']=$_GET['cosTSI'];


date_default_timezone_set('America/Lima');
$created_date = date("Y-m-d H:i");
//desNSI=${co}&descSI=${a}&desCcSI=${to}

$ac = count(explode(",", $IRAS));

$array1 = explode(",", $IRAS, $ac );



class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        //$this->Image('images/logo.png', 5, 5, 30 );
        $this->SetFont('Arial','B',15);
        $this->Cell(30);
        $this->Cell(120,10, 'Reporte De Estados',0,0,'C');
        $this->Ln(20);// Logo
        //$this->Image('logo.jpg',10,8,33);
        //$this->Image('img/sinResultado.jpg',0,0,148,210);
        // Arial bold 15
        //$this->SetFont('Arial', 'B', 20);
        // Movernos a la derecha
        //$this->SetXY(5, 7);
        // Título
        //$this->Cell(30, 12, 'ASUSAP', 0, 0, 'C');
        // Salto de línea
       // $this->Ln(20);
    }

    // Pie de página
    function Footer()
    {

        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        $this->SetFont('Arial','I', 8);
        $this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',0,0,'C' );
        // Número de página
        //  $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF('P', 'mm', 'A4');

//cUANDO NO HAY REGISTRO
$pdf->AddPage();/*
$pdf->SetFont('Arial', 'B', 20);
$pdf->SetXY(5, 7);
$pdf->SetFont('Arial', 'B', 15);
$pdf->SetXY(75, 6);*/

$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(10,6,'#',1,0,'C',1);
$pdf->Cell(30,6,'N. RECIBO',1,0,'C',1);
$pdf->Cell(30,6,'SUMINISTRO',1,0,'C',1);
$pdf->Cell(50,6,'A NOMBRE COMPLETO',1,0,'C',1);
$pdf->Cell(50,6,'FECHA',1,0,'C',1);
$pdf->Cell(20,6,'MONTO',1,1,'C',1);


$pdf->SetFont('Arial','',10);
//PARA LOS SERVICIOS
/*$textypos+=25;
$off = $textypos+25;

$pdf->SetFont('Arial','',12);*/
$textypos+=35;
//$pdf->setX(2);
//$pdf->Cell(5,$textypos,'Nombre del Servicio / Descripcion          PRECIO ');

$off = $textypos+25;
$o=1;
$y=0;
while($row = $result->fetch()) {

    $pdf->SetFillColor(232,232,232);
    $pdf->Cell(10,6,$o++,1,0,'C');
    $pdf->Cell(30,6,utf8_decode($row['idfactura_servicio']),1,0,'C');
    $pdf->Cell(30,6,utf8_decode($row['suministro_cod_suministro']),1,0,'C');
    $pdf->Cell(50,6,$row['a_nombre'],1,0,'C');
    $pdf->Cell(50,6,$row['fecha'],1,0,'C');
    $pdf->Cell(20,6,utf8_decode($row['total_pago']),1,1,'C');
    $y=$y+$row['total_pago'];
    //$pdf->Cell(20,6,utf8_decode($y=($y+$row['total_pago'])),1,1,'C');

   /* $pdf->Cell(11,$off,2,".","," ,0,0,"R");
    $off+=12;*/


}
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetX(158);
$pdf->Cell(10, 10,'TOTAL:  '. 'S/ '.$y, 0, 0, 'L');

$pdf->Output();

