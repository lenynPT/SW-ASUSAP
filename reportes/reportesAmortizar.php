<?php
//Permite incluir los archivos necesarios para las funciones de consulta.
$AjaxRequest=true;

require_once "../controllers/adminController.php";
require "fpdf/fpdf.php";


//Permite incluir los archivos necesarios para las funciones de consulta.

$_POST['urlimg'] = 'img/boletaAGua.jpg';
//anRSI=${anIRS}&codSI=${codsuI}&desNSI=${co},
//$codSRSI=$_POST['CodRS']  tto;
$IRAS=$_GET['RAS'];


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
        // Logo
        //$this->Image('logo.jpg',10,8,33);
        //$this->Image('img/sinResultado.jpg',0,0,148,210);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 20);
        // Movernos a la derecha
        $this->SetXY(5, 7);
        // Título
        //$this->Cell(30, 12, 'ASUSAP', 0, 0, 'C');
        // Salto de línea
        $this->Ln(20);
    }

    // Pie de página
    function Footer()
    {

        // Posición: a 1,5 cm del final
        $this->SetY(-12);
        // Arial italic 8
        $this->SetFont('Arial', 'B', 12);
        $this->SetX(110);
        $this->Cell(10, 10, "S/ ", 0, 0, 'L');
        // Número de página
        //  $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF('P', 'mm', 'A5');

//cUANDO NO HAY REGISTRO
$pdf->AddPage();
//$pdf->Image($_POST['urlimg'] ,1,.5,146.5,240);
//$pdf->Image($_POST['urlimg'] ,0,0,148,210);

$pdf->SetFont('Arial', 'B', 20);
$pdf->SetXY(5, 7);
// $pdf->Cell(30, 12, 'ASUSAP', 0, 0, 'C');

$pdf->SetFont('Arial', 'B', 15);
$pdf->SetXY(75, 6);
/*$pdf->Cell(100, 10, $codsI, 0, 0, 'C');
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetXY(125, 22);
$pdf->Cell(20, 10, $idfsI, 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetXY(0, 38);
$pdf->Cell(90, 10, $tcsI, 0, 0, 'C');
$pdf->SetXY(110, 44);
$pdf->Cell(90, 10, $created_date, 0, 0, 'L');
$pdf->SetXY(0, 50);
$pdf->Cell(90, 10, $anorsI, 0, 0, 'C');
$pdf->SetXY(0, 56);
$pdf->Cell(90, 10, utf8_decode($servSI), 0, 0, 'C');
$pdf->SetXY(0, 44);
$pdf->Cell(90, 10, $disI, 0, 0, 'C');*/
//PARA LOS SERVICIOS
$textypos+=25;
//$pdf->setX(2);
//$pdf->Cell(5,$textypos,'Nombre del Servicio / Descripcion          PRECIO ');

$off = $textypos+25;

$pdf->SetFont('Arial','',12);
for($i = 0; $i <= $ac; ++$i){
    $pdf->setX(2);
    $pdf->Cell(5,$off,utf8_decode($array1[$i]));
   $off+=20;
}

$pdf->Output();

