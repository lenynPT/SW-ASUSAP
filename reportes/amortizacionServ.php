<?php
//Permite incluir los archivos necesarios para las funciones de consulta.
$AjaxRequest=true;

require_once "../core/configSite.php";

require_once "../controllers/adminController.php";

$inst = new adminController();

require "fpdf/fpdf.php";

//$idate=$_GET['inicioDate'];
date_default_timezone_set('America/Lima');
$created_date = date("Y-m-d H:i");
$idate=$_GET['cdsi'];

$query = "SELECT f.a_nombre,f.mont_restante,f.fecha,s.direccion,a.nombre , a.apellido FROM factura_servicio f INNER JOIN suministro s ON  s.cod_suministro=f.suministro_cod_suministro INNER JOIN asociado a ON a.dni=s.asociado_dni WHERE ";
$query .= 'suministro_cod_suministro = "'.$idate.'"';

//$query .= 'direccion = "'.$idate.'"';
//$query .= 'ORDER BY apellido ASC ';
$filesA = $inst->consultaAsociado( $query );


//Permite incluir los archivos necesarios para las funciones de consulta.



//$MP=$_POST['mopagr'];
//$MPa=$_GET['montP'];
$MP=$_GET['PM'];
$_SESSION['cost']=$MP;
$anm=$_GET['anom'];
$fechae=$_GET['fechae'];
$servAs=$_GET['servAs'];

if($filesA->rowCount()) {
    $campos = $filesA->fetch();
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
            $this->SetFont('Arial', 'B', 12);
            $this->SetX(110);
            $this->Cell(10, 17, "Total: S/ ".$_SESSION['cost'], 0, 0, 'L');
            // Número de página
            //  $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        }
    }

    $pdf = new PDF('P', 'mm', 'A5');

//cUANDO NO HAY REGISTRO
    $pdf->AddPage();
    $pdf->Image($_POST['urlimg'], 1, .5, 146.5, 240);
    //$pdf->Image($_POST['urlimg'] ,0,0,148,210);
    $pdf->SetFont('Arial', 'B', 20);
    $pdf->SetXY(5, 7);
    // $pdf->Cell(30, 12, 'ASUSAP', 0, 0, 'C');

    $pdf->SetFont('Arial', 'B', 15);
    $pdf->SetXY(75, 6);
    $pdf->Cell(100, 10, $_GET["cdsi"], 0, 0, 'C');
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetXY(125, 22);
    // $pdf->Cell(20, 10, $idfsI, 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->SetXY(22, 45);
    $pdf->Cell(90, 10, utf8_decode($campos['apellido']." ".$campos['nombre']), 0, 0, 'L');
    $pdf->SetXY(0, 52);
    $pdf->Cell(90, 10, utf8_decode($campos['direccion']), 0, 0, 'C');
    $pdf->SetXY(20, 59);
    $pdf->Cell(90, 10, utf8_decode($anm), 0, 0, 'L');
    $pdf->SetXY(20, 66);
    $pdf->Cell(90, 10, utf8_decode($servAs), 0, 0, 'L');

    $pdf->SetXY(110, 52);
    $pdf->Cell(90, 10, $fechae, 0, 0, 'L');
    $pdf->SetXY(110, 60);
    $pdf->Cell(90, 10, $created_date, 0, 0, 'L');

    $pdf->SetXY(0, 56);
   // $pdf->Cell(90, 10, utf8_decode($campos['direccion']), 0, 0, 'C');

    //PARA LOS SERVICIOS
    $textypos += 35;
    $off = $textypos+35;

    $pdf->SetFont('Arial','',12);

        $pdf->setX(2);
        $pdf->Cell(5,$off,utf8_decode("Monto Amortizado "));
        $pdf->setX(6);
        $pdf->setX(50);
        $pdf->Cell(11,$off,  '                                                            S/ '.$MP,2,".","," ,0,0,"R");
        $off+=12;
        //$pdf->Cell(100, 10, $_GET["cdsi"], 0, 0, 'C');
}
    $pdf->Output();

