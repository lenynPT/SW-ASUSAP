<?php
    //Permite incluir los archivos necesarios para las funciones de consulta.
    $AjaxRequest=true;

    require_once "../controllers/adminController.php";
    require "fpdf/fpdf.php";

    $cod_sum = $_GET['codigoSum'];
    $anio = $_GET['anio'];
    $mes = $_GET['mes'];

    $dataObj = new adminController();

    //mensaje admin
    $msjAdmin = utf8_decode($dataObj->getmensajeReciboController());

    $arrData = $dataObj->recibosObtenerDataSumXCod($cod_sum,$anio,$mes);
    $fechaL = $dataObj->obtenerNombrefecha($anio,$mes);
    $mesLit = $fechaL['r_mes'];
    //var_dump($arrData['data']->fetch());

    $_POST['urlimg'] = $arrData['res']?'img/reciboAgua.jpg':'img/sinResultado.jpg';
    $_POST['codigo_pie'] = $cod_sum;

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
        /*        
        */
        $this->SetXY(0,-24);
        $this->SetFont('Arial','B',10);
        $this->Cell(25,8,'Codigo Suministro: '.$_POST['codigo_pie'],0,0,'');
    }
}

    //pdf
    $pdf = new PDF('P','mm',Array(148, 218.01));

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

            $esta_cancelado = $element['esta_cancelado']?'RECIBO CANCELADO':'FALTA CANCELAR';                    
            //verifica que sea una institución para solo imprimir el nombre            
            $_POST['urlimg'] = $element['estado_corte'] || $element['contador_deuda']>=2?'img/corte.jpg':'img/reciboAgua.jpg';

            //recuperar los meses de las deudas anteriores
            $deudasMes = $dataObj->consultaDeudasMes($element['cod_suministro']);

            $medidor = $element['tiene_medidor']?'Si':'No';    
            $cortado = $element['estado_corte']?'Si':'No';
            $nombre_completo = $element['categoria_suministro']=='Estatal'?utf8_decode($element['nombre']):utf8_decode($element['apellido']." ".$element['nombre']); 
                        
            //MESAJE DE CORTE O NO CORTE
            $msjCorte = ($element['contador_deuda']>=2)?'PRÓXIMO MES EN CORTE!!':'NO ESTÁ EN CORTE';
            $msjCorte = ($element['contador_deuda']>=3)?'EN CORTE':$msjCorte;

            //Operacion de importe total
            $lectura_ant="#";
            $lectura_act = "#";
            $consumo_dif = "#";            
            if($medidor == "Si"){
                $lectura_act = $element['consumo'];
                $lectura_ant = $dataObj->obtenerConsumoAnterior($element['cod_suministro'],$anio,$mes);
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
            $pdf->SetXY(24,35.5);
            $pdf->Cell(50,5,$nombre_completo ,0,0,'');  //nombre de tituar
            $pdf->SetXY(27,40.8);
            $pdf->Cell(50,5,$element['direccion'],0,0,'');    //direccion del titular 
            $pdf->SetXY(9,43.5);
            $pdf->Cell(100,10,utf8_decode("SAN JERÓNIMO"),0,0,''); //distrito
            $pdf->SetXY(11,48.8);
            $pdf->Cell(100,10,$element['categoria_suministro'],0,0,''); //categoria del suministro

            //SEGUNDA COLUMNA - INFORMACIÓN DE PAGO ****************************************************
            $pdf->SetXY(119,35.5);
            $pdf->Cell(50,5,"{$mesLit} del {$anio}",0,0,''); //mes facturado 
            $pdf->SetXY(130.5,40.7);
            $pdf->Cell(50,5,"MENSUAL",0,0,''); //frecuencia de facturación
            $pdf->SetXY(121.8,46);
            $pdf->Cell(50,5,$element['fecha_emision'],0,0,''); //fecha emision
            $pdf->SetXY(127,51.2);
            $pdf->Cell(50,5,$element['fecha_vencimiento'],0,0,''); //fecha vencimiento


            //REGISTROS DEL MEDIDOR ****************************************************
            $pdf->SetXY(0,63);
            $pdf->Cell(18,9,$medidor,0,0,'C'); // Tiene medidor??
            $pdf->SetXY(18,63);
            $pdf->Cell(19,9,$lectura_ant." m3",0,0,'C'); // lectura anterior
            $pdf->SetXY(37,63);
            $pdf->Cell(19,9,$lectura_act." m3",0,0,'C'); // lectura Actual
            $pdf->SetXY(56,63);
            $pdf->Cell(19,9,$consumo_dif." m3",0,0,'C'); // consumo


            //INFORMACIÓN COMPLEMENTARIA ****************************************************              
            
            $pdf->SetXY(10,82);
            $pdf->Cell(100,5,"Cantidad deudas: {$element['contador_deuda']}",0,0,'');   
            
            $pdf->SetXY(10,115);
            $pdf->Cell(46,10,$esta_cancelado,1,0,'C'); // Está cancelado el recibo ??

            //Imprime los meses endeudados
            $pdf->SetXY(90,125);   
            $total_suma_deudas=0;  
            $enum = 0;   
            foreach ($deudasMes as $value) {
                # code...
                $total_suma_deudas += $value['monto_pagar'];
                if($value['mes'] == $mes)
                    continue;
                $enum++;
                $nombreMes =  $dataObj->obtenerNombrefecha($value['anio'],$value['mes']);
                $pdf->SetX(90);
                $pdf->Cell(50,4,$enum.".- ".$nombreMes['r_mes']." del ".$nombreMes['r_anio'],0,0,'');
                $pdf->SetX(130);
                $pdf->Cell(50,4,"S/. ".$value['monto_pagar'],0,0,'');                
                $pdf->ln();
            }  
            
            //DETALLE DE LA FACTURACIÓN ****************************************************
            if($medidor=="Si"){
                $subT = modoDePago($pdf,$element,$consumo_dif);
                //nueva seccion igv y subtotal***************************************************
                //Sub total
                $pdf->SetXY(130,103.5);
                $pdf->Cell(100,10,"S/. ".number_format($subT['subt'],2),0,0,'');
                //IGV Total
                $pdf->SetXY(130,107);
                $pdf->Cell(100,10,"S/. ".number_format($subT['igvt'],2),0,0,'');
            }else{
                //primera fila de 
                $pdf->SetXY(85,64);
                $pdf->Cell(100,10,"Por consumo de agua x mes",0,0,'');
                $pdf->SetXY(130,64);
                $pdf->Cell(100,10,"$/. 3.56",0,0,'');
                //segunda fila de 
                /* 
                $pdf->SetXY(85,67);
                $pdf->Cell(100,10,"Por IGV (18%)",0,0,'');
                $pdf->SetXY(130,67);
                $pdf->Cell(100,10,"$/. 0.64",0,0,'');
                */
                //nueva seccion igv y subtotal***************************************************
                //Sub total
                $pdf->SetXY(130,103.5);
                $pdf->Cell(100,10,"S/. 3.56",0,0,'');
                //IGV Total
                $pdf->SetXY(130,107);
                $pdf->Cell(100,10,"S/. 0.64",0,0,'');
            }
            $pdf->SetXY(74.3,111);
            $pdf->Cell(100,10,"Deuda Mes Actual",0,0,'');
            $pdf->SetXY(125,111);
            $pdf->Cell(20,10,"S/. ".number_format($element['monto_pagar'],2),0,0,'C');

            $pdf->SetFont('Arial','B',10);  
            $pdf->SetXY(118,140.5);
            $pdf->Cell(100,10,"S/. ".number_format($total_suma_deudas,2),0,0,'');

            //MENSAJE DE CORTE
            $pdf->SetXY(10,150);
            $pdf->Cell(0,10,utf8_decode($msjCorte),0,0,'');  
            $pdf->SetFont('Arial','B',7);
            $pdf->SetXY(2,160);
            $pdf->MultiCell( 70, 5,$msjAdmin, 0,'C');
            //SECCIÓN RECORTAR -**********************************
            $pdf->SetFont('Arial','B',7); 
            $pdf->SetXY(21.5, 182.5);
            $pdf->Cell(100,10,$nombre_completo ,0,0,'');
            
            $pdf->SetXY(118, 182.5);
            $pdf->Cell(100,10,"{$mesLit} del {$anio}",0,0,'');

            $pdf->SetXY(24, 188);
            $pdf->Cell(100,10,$element['direccion'] ,0,0,'');
            
            $pdf->SetFont('Arial','B',10); 
            $pdf->SetXY(117.2, 187.5);
            $pdf->Cell(100,10,"S/. ".number_format($total_suma_deudas,2),0,0,'');

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
            $pdf->Cell(100,10,"$/. ".number_format($val1,2),0,0,'');
            //segunda fila de 
            $pdf->SetXY(85,67+1*$x);
            $pdf->Cell(100,10,"(De 20 a 40)m3 * $/ 0.60",0,0,'');
            $pdf->SetXY(130,67+1*$x);
            $pdf->Cell(100,10,"$/. ".number_format($val2,2),0,0,'');
            //tercera fila de 
            $pdf->SetXY(85,70+2*$x);
            $pdf->Cell(100,10,"(De 40 a mas)m3 * $/ 0.95",0,0,'');
            $pdf->SetXY(130,70+2*$x);
            $pdf->Cell(100,10,"$/. ".number_format($val3,2),0,0,'');
            
            /*
            //IGV
            $pdf->SetXY(85,73+3*$x);
            $pdf->Cell(100,10,"IGV (18%)",0,0,'');
            $pdf->SetXY(130,73+3*$x);
            $pdf->Cell(100,10,"$/ {$resIGV}",0,0,'');
            */


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
            $pdf->Cell(100,10,"$/. ".number_format($val1,2),0,0,'');
            //segunda fila de 
            $pdf->SetXY(85,67+1*$x);
            $pdf->Cell(100,10,"(De 20 a mas)m3 * $/ 0.95",0,0,'');
            $pdf->SetXY(130,67+1*$x);
            $pdf->Cell(100,10,"$/. ".number_format($val2,2),0,0,'');
            
            /*
            //IGV
            $pdf->SetXY(85,70+2*$x);
            $pdf->Cell(100,10,"IGV (18%)",0,0,'');
            $pdf->SetXY(130,70+2*$x);
            $pdf->Cell(100,10,"$/ {$resIGV}",0,0,'');
            */

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
            $pdf->Cell(100,10,"$/. ".number_format($val1,2),0,0,'');
            //segunda fila de 
            $pdf->SetXY(85,67+1*$x);
            $pdf->Cell(100,10,"(De 20 a mas)m3 * $/ 0.95",0,0,'');
            $pdf->SetXY(130,67+1*$x);
            $pdf->Cell(100,10,"$/. ".number_format($val2,2),0,0,'');
            
            /*
            //IGV
            $pdf->SetXY(85,70+2*$x);
            $pdf->Cell(100,10,"IGV (18%)",0,0,'');
            $pdf->SetXY(130,70+2*$x);
            $pdf->Cell(100,10,"$/ {$resIGV}",0,0,'');
            */

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
            $pdf->Cell(100,10,"$/. ".number_format($val1,2),0,0,'');
            
            
            /* 
            //IGV
            $pdf->SetXY(85,67+1*$x);
            $pdf->Cell(100,10,"IGV (18%)",0,0,'');
            $pdf->SetXY(130,67+1*$x);
            $pdf->Cell(100,10,"$/ {$resIGV}",0,0,'');
            */

            break;
        
        default:
            # code...
            break;
    }
    $subT = ($val1+$val2+$val3);
    return ['subt'=>$subT,'igvt'=>$resIGV];
    
}
