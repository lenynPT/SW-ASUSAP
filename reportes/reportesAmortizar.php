<?php
//Permite incluir los archivos necesarios para las funciones de consulta.
$AjaxRequest=true;

require_once "../core/configSite.php";

require_once "../controllers/adminController.php";

$inst = new adminController();

require "fpdf/fpdf.php";

$idate=$_GET['inicioDate'];
$fdate=$_GET['finalDate'];

$query = "SELECT  f.idfactura_servicio,f.suministro_cod_suministro,f.a_nombre,f.total_pago,a.fechaA, a.monto_amorti FROM amorti_servicio a INNER  JOIN factura_servicio f ON f.idfactura_servicio = a.factura_servicio_idfactura_servicio WHERE ";


$query .= 'fechaA BETWEEN "'.$idate.'" AND "'.$fdate.'" ';
$query .= 'ORDER BY fechaA DESC ';
$result = $inst->consultaAsociado( $query );
//Permite incluir los archivos necesarios para las funciones de consulta.

$_POST['urlimg'] = 'img/boletaAGua.jpg';

$IRAS=$_GET['RAS'];


date_default_timezone_set('America/Lima');
$created_date = date("Y-m-d H:i");
session_start(['name'=>'ASUSAP']);
$_SESSION['fecha']=$created_date;
$_SESSION['fechaInicio']=$_GET['inicioDate'];
$_SESSION['fechaFinal']=$_GET['finalDate'];
$td=$_GET['finalDate']-$_GET['inicioDate'];
$_SESSION['diatotal']=$td;

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
        $this->Cell(135,10, 'Reporte De Ingreso (Ricibos de Servicio)',0,0,'C');
        $this->SetFont('Arial','B',10);
        $this->Cell(10);
        $this->Cell(15,20,'Fecha: '.$_SESSION['fecha'],0,0,'R');
        $this->SetFont('Arial','B',15);
        $this->SetX(50);
        $this->SetY(15);
        $this->Cell(185,20,''.$_SESSION['fechaInicio'].'     A      '.$_SESSION['fechaFinal'],0,0,'C');
        $this->Ln(20);// Logo
        $this->Image('img/agua.jpg',10,6,45);

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
$pdf->AddPage();

$pdf->SetY(35);
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(6,6,'#',1,0,'C',1);
$pdf->Cell(25,6,'N. RECIBO',1,0,'C',1);
$pdf->Cell(25,6,'SUMINISTRO',1,0,'C',1);
$pdf->Cell(85,6,'A NOMBRE COMPLETO',1,0,'C',1);
$pdf->Cell(30,6,'FECHA',1,0,'C',1);
$pdf->Cell(20,6,'MONTO',1,1,'C',1);


$pdf->SetFont('Arial','',8);
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
    $pdf->Cell(6,6,$o++,1,0,'C');
    $pdf->Cell(25,6,utf8_decode($row['idfactura_servicio']),1,0,'C');
    $pdf->Cell(25,6,utf8_decode($row['suministro_cod_suministro']),1,0,'C');
   // $pdf->Cell(25,6,utf8_decode($row['monto_amorti']),1,0,'C');
    $pdf->Cell(85,6,utf8_decode($row['a_nombre']),1,0,'L');
    $pdf->Cell(30,6,$row['fechaA'],1,0,'C');
    $pdf->Cell(20,6,utf8_decode("S/ ".$row['monto_amorti']),1,1,'L');
    $y=$y+$row['monto_amorti'];
    //$pdf->Cell(20,6,utf8_decode($y=($y+$row['total_pago'])),1,1,'C');

   /* $pdf->Cell(11,$off,2,".","," ,0,0,"R");
    $off+=12;*/


}
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetX(158);
$pdf->Cell(10, 10,'TOTAL:  '. 'S/ '.$y, 0, 0, 'L');

$pdf->Output();

