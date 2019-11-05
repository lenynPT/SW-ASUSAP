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
//devuelve los registros por dirección
$resConsult = $objDirec->recibosObtenerDataSumXdirec($selectDireccion,$selectAnio,$selectMes);
//devuelve el mes en forma literal
$fechaL = $objDirec->obtenerNombrefecha($selectAnio,$selectMes);
$mesLit = $fechaL['r_mes'];
//Asigna el fondo para el recibo 
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
    //cUANDO NO HAY REGISTRO
    $pdf->AddPage();    
    $pdf->Image($_POST['urlimg'] ,0,0,148,210);

    $pdf->SetFont('Arial','B',20);
    $pdf->SetXY(5,7);
    $pdf->Cell(30,12,'ASUSAP',0,0,'C');

    $pdf->SetFont('Arial','B',8);
    $pdf->SetXY(21,4.5);
    $pdf->Cell(100,10,"SIN REGISTROS PARA {$mesLit} del {$selectAnio}",0,0,'C');

}else{
    //CUANDO HAY REGISTROS

    $dataQuery = $resConsult['data'];

    while($element = $dataQuery->fetch(PDO::FETCH_ASSOC)){

        $medidor = $element['tiene_medidor']?'Si':'No';    
        $cortado = $element['estado_corte']?'Si':'No';
        $esta_cancelado = $element['esta_cancelado']?'RECIBO CANCELADO':'FALTA PAGAR RECIBO';

        //verifica que sea una institución para solo imprimir el nombre
        $nombre_completo = ($element['categoria_suministro']=='Estatal')?utf8_decode($element['nombre']):utf8_decode($element['apellido']." ".$element['nombre']); 
        $_POST['urlimg'] = $element['estado_corte']?'img/corte.jpg':'img/reciboAgua.jpg';
        $msjCorte = ($element['contador_deuda']>=3)?'EN CORTE':'no está en corte';

        //Función que recupera los meses de las deudas anteriores
        $deudasMes = $objDirec->consultaDeudasMes($element['cod_suministro']);

        //Operacion de importe total
        $lectura_ant="#";
        $lectura_act = "#";
        $consumo_dif = "#";            
        if($medidor == "Si"){
            $lectura_act = $element['consumo'];
            $lectura_ant = $objDirec->obtenerConsumoAnterior($element['cod_suministro'],$selectAnio,$selectMes);
            $consumo_dif = $lectura_act-$lectura_ant;
        }

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
        $pdf->Cell(50,5,$selectDireccion,0,0,'');    //direccion del titular 
        $pdf->SetXY(8,43);
        $pdf->Cell(100,10,utf8_decode("SAN JERÓNIMO"),0,0,''); //distrito
        $pdf->SetXY(11,48.3);
        $pdf->Cell(100,10,$element['categoria_suministro'],0,0,''); //categoria del suministro

        //SEGUNDA COLUMNA - INFORMACIÓN DE PAGO ****************************************************
        $pdf->SetXY(118,35);
        $pdf->Cell(50,5,"{$mesLit} del {$selectAnio}",0,0,''); //mes facturado 
        $pdf->SetXY(129,40.3);
        $pdf->Cell(50,5,"mes",0,0,''); //frecuencia de facturación
        $pdf->SetXY(121,45.5);
        $pdf->Cell(50,5,$element['fecha_emision'],0,0,''); //fecha emision
        $pdf->SetXY(125,50.7);
        $pdf->Cell(50,5,$element['fecha_vencimiento'],0,0,''); //fecha vencimiento

        //REGISTROS DEL MEDIDOR ****************************************************
        $pdf->SetXY(7,62);
        $pdf->Cell(100,10,$medidor,0,0,''); // Tiene medidor??
        $pdf->SetXY(18,63);
        $pdf->Cell(19,8,$lectura_ant." m3",1,0,'C'); // lectura anterior
        $pdf->SetXY(37,63);
        $pdf->Cell(19,8,$lectura_act." m3",1,0,'C'); // lectura Actual
        $pdf->SetXY(56,63);
        $pdf->Cell(19,8,$consumo_dif." m3",1,0,'C'); // consumo


        //INFORMACIÓN COMPLEMENTARIA ****************************************************              
        $pdf->SetXY(10,75);
        $pdf->Cell(100,10,$esta_cancelado,0,0,''); // Está cancelado el recibo ??
        
        $pdf->SetXY(10,82);
        $pdf->Cell(100,5,"Cantidad deudas: {$element['contador_deuda']}",0,0,'');        

        //Imprime los meses endeudados
        $pdf->SetXY(10,87);        
        for ($i=0; $i < count($deudasMes); $i++) { 
            # code...
            $nombreMes =  $objDirec->obtenerNombrefecha($deudasMes[$i]['anio'],$deudasMes[$i]['mes']);
            $pdf->SetX(10);
            $pdf->Cell(30,3,$nombreMes['r_mes']." del ".$nombreMes['r_anio'],1,0,'');
            $pdf->ln();
        }
        
        //DETALLE DE LA FACTURACIÓN ****************************************************
        if($medidor=="Si"){
            modoDePago($pdf,$element,$consumo_dif);
        }else{
            //primera fila de 
            $pdf->SetXY(85,64+0*$x);
            $pdf->Cell(100,10,"Por consumo de agua x mes",0,0,'');
            $pdf->SetXY(130,64+0*$x);
            $pdf->Cell(100,10,"$/ 3.56",0,0,'');
            //segunda fila de 
            $pdf->SetXY(85,67+1*$x);
            $pdf->Cell(100,10,"Por IGV (18%)",0,0,'');
            $pdf->SetXY(130,67+1*$x);
            $pdf->Cell(100,10,"$/ 0.64",0,0,'');
        }
        $pdf->SetXY(118,139);
        $pdf->Cell(100,10,"S/ ".$element['monto_pagar'],0,0,'');
        
        //mensaje de corte
        $pdf->SetXY(10,150);
        $pdf->Cell(0,10,utf8_decode($msjCorte),0,0,'');
    }
    
}

$pdf->Output();


    //Funcione que retorna las formas de pago para las distintas categorias de los suministros
    function modoDePago($pdf,$element,$consumo_dif){
        $categoria = $element['categoria_suministro'];
        $x = 2;
        $val1 = 0; $val2 = 0; $val3 = 0; $resIGV=0;
        switch ($categoria) {
            case 'Domestico':
                # code...
                if($consumo_dif<=20){
                    $val1 = 3.56;
                }else{
                    $val1 = 3.56;
                    $consumo_dif-=20;
                    if($consumo_dif<=20){
                        $val2 = $consumo_dif * 0.60;                        
                    }else{
                        $val2 = 20 * 0.60;
                        $consumo_dif-=20;
                        $val3 = $consumo_dif * 0.95;
                    }
                }
                $val1 = round($val1, 1);
                $val2 = round($val2, 1);
                $val3 = round($val3, 1);

                $resIGV = ($val1+$val2+$val3)*0.18; $resIGV = round($resIGV, 1);                

                //primera fila de 
                $pdf->SetXY(85,64+0*$x);
                $pdf->Cell(100,10,"(De 0 a 20)m3 * $/ 0.18",0,0,'');
                $pdf->SetXY(130,64+0*$x);
                $pdf->Cell(100,10,"$/ {$val1}",0,0,'');
                //segunda fila de 
                $pdf->SetXY(85,67+1*$x);
                $pdf->Cell(100,10,"(De 20 a 40)m3 * $/ 0.60",0,0,'');
                $pdf->SetXY(130,67+1*$x);
                $pdf->Cell(100,10,"$/ {$val2}",0,0,'');
                //tercera fila de 
                $pdf->SetXY(85,70+2*$x);
                $pdf->Cell(100,10,"(De 40 a mas)m3 * $/ 0.95",0,0,'');
                $pdf->SetXY(130,70+2*$x);
                $pdf->Cell(100,10,"$/ {$val3}",0,0,'');

                //IGV
                $pdf->SetXY(85,73+3*$x);
                $pdf->Cell(100,10,"IGV (18%)",0,0,'');
                $pdf->SetXY(130,73+3*$x);
                $pdf->Cell(100,10,"$/ {$resIGV}",0,0,'');


                break;
            case 'Comercial':
                # code...    
                
                if($consumo_dif<=20){
                    $val1=20*0.50;
                }else {
                    $val1=10;                    
                    $consumo_dif-=20;
                    $val2 = $consumo_dif*0.95;
                }
                $val1 = round($val1,1);
                $val2 = round($val2,1);

                $resIGV = ($val1+$val2)*0.18; $resIGV = round($resIGV,1);

                //primera fila de 
                $pdf->SetXY(85,64+0*$x);
                $pdf->Cell(100,10,"(De 0 a 20)m3 * $/ 0.50",0,0,'');
                $pdf->SetXY(130,64+0*$x);
                $pdf->Cell(100,10,"$/ {$val1}",0,0,'');
                //segunda fila de 
                $pdf->SetXY(85,67+1*$x);
                $pdf->Cell(100,10,"(De 20 a mas)m3 * $/ 0.95",0,0,'');
                $pdf->SetXY(130,67+1*$x);
                $pdf->Cell(100,10,"$/ {$val2}",0,0,'');
                
                //IGV
                $pdf->SetXY(85,70+2*$x);
                $pdf->Cell(100,10,"IGV (18%)",0,0,'');
                $pdf->SetXY(130,70+2*$x);
                $pdf->Cell(100,10,"$/ {$resIGV}",0,0,'');

                break;
            case 'Estatal':
                # code...
                if($consumo_dif<=20){
                    $val1=20*0.60;
                }else {
                    $val1=10;                    
                    $consumo_dif-=20;
                    $val2 = $consumo_dif*0.95;
                }
                $val1 = round($val1,1);
                $val2 = round($val2,1);

                $resIGV = ($val1+$val2)*0.18; $resIGV = round($resIGV,1);

                //primera fila de 
                $pdf->SetXY(85,64+0*$x);
                $pdf->Cell(100,10,"(De 0 a 20)m3 * $/ 0.60",0,0,'');
                $pdf->SetXY(130,64+0*$x);
                $pdf->Cell(100,10,"$/ {$val1}",0,0,'');
                //segunda fila de 
                $pdf->SetXY(85,67+1*$x);
                $pdf->Cell(100,10,"(De 20 a mas)m3 * $/ 0.95",0,0,'');
                $pdf->SetXY(130,67+1*$x);
                $pdf->Cell(100,10,"$/ {$val2}",0,0,'');
                
                //IGV
                $pdf->SetXY(85,70+2*$x);
                $pdf->Cell(100,10,"IGV (18%)",0,0,'');
                $pdf->SetXY(130,70+2*$x);
                $pdf->Cell(100,10,"$/ {$resIGV}",0,0,'');

                break;
            case 'Industrial':
                # code...
                $val1 = $consumo_dif * 2.00;
                
                $val1 = round($val1, 1);

                $resIGV = $val1 * 0.18; $resIGV = round($resIGV, 1);

                //primera fila de 
                $pdf->SetXY(85,64+0*$x);
                $pdf->Cell(100,10,"(De 0 a mas)m3 * $/ 2.00",0,0,'');
                $pdf->SetXY(130,64+0*$x);
                $pdf->Cell(100,10,"$/ {$val1}",0,0,'');

                //IGV
                $pdf->SetXY(85,67+1*$x);
                $pdf->Cell(100,10,"IGV (18%)",0,0,'');
                $pdf->SetXY(130,67+1*$x);
                $pdf->Cell(100,10,"$/ {$resIGV}",0,0,'');

                break;
            
            default:
                # code...
                break;
        }
        
    }

