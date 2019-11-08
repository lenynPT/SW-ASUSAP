<?php
//Permite incluir los archivos necesarios para las funciones de consulta.
$AjaxRequest=true;

require_once "../controllers/adminController.php";
require "fpdf/fpdf.php";


//Permite incluir los archivos necesarios para las funciones de consulta.



$MP=$_POST['mopagr'];
$MPa=$_GET['montP'];
$MP=$_GET['PM'];


/*
//Recepcionando datos por GET
$selectDireccion = $_GET['direccion'];//$_GET['direccion'] || 'Jr. los chancas'
$selectAnio = $_GET['anio'];
$selectMes = $_GET['mes'];

//Inicializando objeto de consultas
$objDirec = new adminController();
//devuelve los registros por dirección
$resConsult = $objDirec->recibosObtenerDataSumXdirec($selectDireccion,$selectAnio,$selectMes);
//devuelve el mes en forma literal
$fechaL = $objDirec->obtenerNombrefecha($selectAnio,$selectMes);
$mesLit = $fechaL['r_mes'];
//Asigna el fondo para el recibo
$_POST['urlimg'] = $resConsult['res']?'img/reciboAgua.jpg':'img/sinResultado.jpg';*/
$_POST['urlimg'] = 'img/boletaAGua.jpg';


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
            $this->SetY(-15);
            // Arial italic 8
            $this->SetFont('Arial', 'I', 8);
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
    $pdf->Cell(100, 10, $_GET["cdsi"], 0, 0, 'C');
    $pdf->Output();

