<?php 

//Permite incluir los archivos necesarios para las funciones de consulta.
$AjaxRequest=true;

require_once "../controllers/adminController.php";
require "fpdf/fpdf.php";


//Recepcionando datos por GET
$selectDireccion = $_GET['direccion'];//$_GET['direccion'] || 'Jr. los chancas'
$selectAnio = $_GET['anio'];
$selectMes = $_GET['mes'];

//Inicializando objeto de consultas
$objDirec = new adminController();

$resConsult = $objDirec->recibosObtenerDataSumXdirec($selectDireccion,$selectAnio,$selectMes);

$fechaL = $objDirec->obtenerNombrefecha($selectAnio,$selectMes);
$mesLit = $fechaL['r_mes'];

$_POST['urlimg'] = $resConsult['res']?'img/reciboAgua.jpg':'img/sinResultado.jpg';

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        // Logo
        //$this->Image('logo.jpg',10,8,33);
        //$this->Image('img/sinResultado.jpg',0,0,148,210);
        // Arial bold 15
        $this->SetFont('Arial','B',20);
        // Movernos a la derecha
        $this->SetXY(5,7);
        // Título
        $this->Cell(30,12,'ASUSAP',0,0,'C');
        // Salto de línea
        $this->Ln(20);
    }

    // Pie de página
    function Footer()
    {

        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Número de página
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

//function mainClass(){
//}
//mainClass();

//pdf
$pdf = new PDF('P','mm','A5');

if(!$resConsult['res']){
    //Sin registros para emitir recibo
    $pdf->AddPage();    
    $pdf->Image($_POST['urlimg'] ,0,0,148,210);

    $pdf->SetFont('Arial','B',20);
    $pdf->SetXY(5,7);
    $pdf->Cell(30,12,'ASUSAP',0,0,'C');

    $pdf->SetFont('Arial','B',8);
    $pdf->SetXY(21,4.5);
    $pdf->Cell(100,10,"REGISTRO VACIO PARA {$mesLit} del {$selectAnio}",0,0,'C');

}else{
    $dataQuery = $resConsult['data'];

    while($element = $dataQuery->fetch(PDO::FETCH_ASSOC)){

        //verifica que sea una institución para solo imprimir el nombre
        $nombre_completo = ($element['categoria_suministro']=='Estatal')?utf8_decode($element['nombre']):utf8_decode($element['apellido']." ".$element['nombre']); 

        $pdf->AddPage();    
        $pdf->Image($_POST['urlimg'] ,0,0,148,210);

        $pdf->SetFont('Arial','B',20);
        $pdf->SetXY(5,7);
        $pdf->Cell(30,12,'ASUSAP',0,0,'C');

        $pdf->SetFont('Arial','B',7);
        //$result = $sql->reporte_prueba("SELECT * FROM asociado");
        $pdf->SetXY(57,7);
        $pdf->Cell(50,5,$nombre_completo ,0,0,'');
        $pdf->SetXY(57,10.4);
        $pdf->Cell(50,5,$selectDireccion,0,0,'');
    
        $pdf->SetXY(27,100);
        $pdf->Cell(100,10,$element['direccion'],0,0,'C');
    }
    
}

$pdf->Output();
