<?php
//Permite incluir los archivos necesarios para las funciones de consulta.
$AjaxRequest=true;

require_once "../controllers/adminController.php";
require "fpdf/fpdf.php";


//Permite incluir los archivos necesarios para las funciones de consulta.



$MP=$_POST['mopagr'];
$MPa=$_GET['montP'];
$MP=$_GET['PM'];




$_POST['urlimg'] = 'img/boletaAGua.jpg';
//anRSI=${anIRS}&codSI=${codsuI}&desNSI=${co},
//$codSRSI=$_POST['CodRS']  tto;
$anorsI=$_GET['anRSI'];
$codsI=$_GET['codSI']; //cod suministro
$disI=$_GET['DRSI']; //cod suministro
$tcsI=$_GET['TCSI']; //cod suministro
$idfsI=$_GET['IDFSI']; //cod suministro
$cosTSI=$_GET['cosTSI']; //cod suministro
$servSI=$_GET['servSI']; //tipo de servicio

session_start(['name'=>'ASUSAP']);
$_SESSION['cost']=$_GET['cosTSI'];

/*$asociado = new adminController();
$v=$asociado->mostImpItem($idfsI);*/

date_default_timezone_set('America/Lima');
$created_date = date("Y-m-d H:i");
//desNSI=${co}&descSI=${a}&desCcSI=${to}
$desNSI=$_GET['desNSI'];
$dessI=$_GET['descSI'];
$descCSI=$_GET['desCcSI'];

$ac = count(explode(",", $dessI));

$array1 = explode(",", $desNSI, $ac );
$array2 = explode(",", $dessI, $ac );
$array3 = explode(",", $descCSI, $ac );
/*
$IdFRSI=$_POST['idfSI'];
$descCSI=$_POST['desCcSI'];
$costAllSI=$_POST['costALLSI'];
$desNSI=$_POST['desNSI'];*/


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
            $this->Cell(10, 10, "Total: S/ ".$_SESSION['cost'], 0, 0, 'L');
            // Número de página
          //  $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        }
    }

    $pdf = new PDF('P', 'mm', 'A5');

//cUANDO NO HAY REGISTRO
    $pdf->AddPage();
    $pdf->Image($_POST['urlimg'] ,1,.5,146.5,240);
//$pdf->Image($_POST['urlimg'] ,0,0,148,210);

    $pdf->SetFont('Arial', 'B', 20);
    $pdf->SetXY(5, 7);
   // $pdf->Cell(30, 12, 'ASUSAP', 0, 0, 'C');

    $pdf->SetFont('Arial', 'B', 15);
    $pdf->SetXY(75, 6);
    $pdf->Cell(100, 10, $codsI, 0, 0, 'C');
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetXY(125, 22);
    $pdf->Cell(20, 10, "", 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->SetXY(22, 45);
    $pdf->Cell(90, 10, utf8_decode($tcsI), 0, 0, 'L');
    $pdf->SetXY(110, 53);
    $pdf->Cell(90, 10, $created_date, 0, 0, 'L');
    $pdf->SetXY(20, 59);
    $pdf->Cell(90, 10, utf8_decode($anorsI), 0, 0, 'L');
    $pdf->SetXY(0, 52);
    $pdf->Cell(90, 10, $disI, 0, 1, 'C');
    $pdf->SetXY(0, 66);
    $pdf->Cell(90, 10, utf8_decode($servSI), 0, 0, 'C');

    //PARA LOS SERVICIOS
    $textypos+=35;
    //$pdf->setX(2);
    //$pdf->Cell(5,$textypos,'Nombre del Servicio / Descripcion          PRECIO ');

    $off = $textypos+60;
    //$pdf->SetXY(0, 83);
    $pdf->setY(83);
    $pdf->SetFont('Arial','',12);
    for($i = 0; $i <= $ac; ++$i){
       // $pdf->setX(2);
      // $pdf->setY(90);

        $pdf->Cell(1,5,utf8_decode($array1[$i])."   ".utf8_decode($array2[$i]),0,0,"L");
       // $pdf->setX(6);
        //$pdf->setX(50);
        $pdf->Cell(0,5,  '                                                                                             '.$array3[$i],0,1,"L");
        //$pdf->Cell(20,6,utf8_decode("S/ ".$row['monto_amorti']),1,1,'L');
        $off+=5;
    }

$pdf->Output();

