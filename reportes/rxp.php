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
    //reciboAgua.jpg
    $_POST['urlimg'] = $arrData['res']?'img/sinResultado.jpg':'img/sinResultado.jpg';
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
        $this->SetXY(5,-21);
        $this->SetFont('Arial','B',10);
        $this->Cell(25,8,'Cod. Sum.: '.$_POST['codigo_pie'],0,0,'');
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
            
            $_POST['urlimg'] = $element['estado_corte'] || $element['contador_deuda']>=2?'img/sinResultado.jpg':'img/sinResultado.jpg';
            // $_POST['urlimg'] = $element['estado_corte'] || $element['contador_deuda']>=2?'img/corte.jpg':'img/reciboAgua.jpg';

            //recuperar los meses de las deudas anteriores
            $deudasMes = $dataObj->consultaDeudasMes($element['cod_suministro']);

            $medidor = $element['tiene_medidor']?'Si':'No';    
            $cortado = $element['estado_corte']?'Si':'No';
            $nombre_completo = $element['categoria_suministro']=='Estatal'?utf8_decode($element['nombre'] . " " . $element['nombre_sum']):utf8_decode($element['apellido']." ".$element['nombre'] . " " . $element['nombre_sum']); 
                        
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
            $pdf->Image($_POST['urlimg'] ,0,0,148,218);

            //código del suministro
            $pdf->SetFont('Arial','B',15);     
            $pdf->SetXY(100,12);
            $pdf->Cell(42,7,$element['cod_suministro'],0,0,'C');
            
            //CONFIGURANDO EL TAMAÑO Y TIPO DE LETRA
            $pdf->SetFont('Arial','B',7);  

            //PRIMERA COLUMNA - INFORMACIÓN GENERAL ****************************************************
            $pdf->SetXY(27,40.8);
            $pdf->Cell(50,5,$nombre_completo ,0,0,'');  //nombre de tituar
            $pdf->SetXY(31,45.5);
            $pdf->Cell(50,5,$element['direccion'],0,0,'');    //direccion del titular 
            $pdf->SetXY(15,48.8);
            $pdf->Cell(100,10,utf8_decode("SAN JERÓNIMO"),0,0,''); //distrito
            $pdf->SetXY(17,54);
            $pdf->Cell(100,10,$element['categoria_suministro'],0,0,''); //categoria del suministro

            //SEGUNDA COLUMNA - INFORMACIÓN DE PAGO ****************************************************
            $pdf->SetXY(116.5,40.7);
            $pdf->Cell(50,5,"{$mesLit} del {$anio}",0,0,''); //mes facturado 
            $pdf->SetXY(125.8,46);
            $pdf->Cell(50,5,"MENSUAL",0,0,''); //frecuencia de facturación
            $pdf->SetXY(120,51.2);
            $pdf->Cell(50,5,$element['fecha_emision'],0,0,''); //fecha emision
            $pdf->SetXY(122,56.5);
            $pdf->Cell(50,5,$element['fecha_vencimiento'],0,0,''); //fecha vencimiento


            //REGISTROS DEL MEDIDOR ****************************************************
            $pdf->SetXY(0+5,68);
            $pdf->Cell(18,9,$medidor,0,0,'C'); // Tiene medidor??
            $pdf->SetXY(18+5,68);
            $pdf->Cell(17,9,$lectura_ant." m3",0,0,'C'); // lectura anterior
            $pdf->SetXY(37+4,68);
            $pdf->Cell(17,9,$lectura_act." m3",0,0,'C'); // lectura Actual
            $pdf->SetXY(56+2,68);
            $pdf->Cell(17,9,$consumo_dif." m3",0,0,'C'); // consumo


            //INFORMACIÓN COMPLEMENTARIA ****************************************************    
            /*
            $pdf->SetXY(10,139);
            $pdf->Cell(60,8,$esta_cancelado,1,0,'C'); // Está cancelado el recibo ??
            */
            //Imprime los meses endeudados
            $pdf->SetXY(90,85);   
            $total_suma_deudas=0;  
            $enum = 0;   
            foreach ($deudasMes as $value) {
                # code...
                $total_suma_deudas += $value['monto_pagar'];
                if($value['mes'] == $mes)
                    continue;
                $enum++;
                $nombreMes =  $dataObj->obtenerNombrefecha($value['anio'],$value['mes']);
                $pdf->SetX(90-80);
                $pdf->Cell(50,4,$enum.".- ".$nombreMes['r_mes']." del ".$nombreMes['r_anio'],0,0,'');
                $pdf->SetX(130-80);
                $pdf->Cell(50,4,"S/. ".$value['monto_pagar'],0,0,'');                
                $pdf->ln();
            }  
            //CANTIDAD de deudas
            $pdf->SetXY(10,140);
            $pdf->Cell(100,5,"Cantidad deudas: {$element['contador_deuda']}",0,0,'');   
            //ESTADO Corte
            $pdf->SetFont('Arial','B',7);
            $pdf->SetXY(5,145);
            $pdf->Cell(70,5,utf8_decode($msjCorte),0,0,'C'); 
            
            //DETALLE DE LA FACTURACIÓN ****************************************************
            if($medidor=="Si" || $element['categoria_suministro'] == 'Mantenimiento' || $element['categoria_suministro'] == 'Tarifa Plana'){

                $subT = modoDePago($pdf,$element,$consumo_dif);
                //conceptos----
                $pdf->SetXY(85,68+20);
                $pdf->Cell(100,10,"Cargo fijo",0,0,'');
                $pdf->SetXY(130,68+20);
                $pdf->Cell(100,10,"$/. 0.00",0,0,'');

                $pdf->SetXY(85,72+20);
                $pdf->Cell(100,10,"Alcantarillado",0,0,'');
                $pdf->SetXY(130,72+20);
                $pdf->Cell(100,10,"$/. 0.00",0,0,'');

                //nueva seccion igv y subtotal***************************************************
                $y_inc_sub_total = 3.7;
                //Sub total
                $pdf->SetXY(130,103.5+$y_inc_sub_total);
                $pdf->Cell(100,10,"S/. ".number_format($subT['subt'],2),0,0,'');
                //IGV Total
                $pdf->SetXY(130,107+$y_inc_sub_total);
                $pdf->Cell(100,10,"S/. ".number_format($subT['igvt'],2),0,0,'');
            }else{
                //primera fila de 
                $pdf->SetXY(85,64);
                $pdf->Cell(100,10,"Por consumo de agua x mes",0,0,'');
                $pdf->SetXY(130,64);
                $pdf->Cell(100,10,"$/. 3.56",0,0,'');

                //conceptos----
                $pdf->SetXY(85,68+2);
                $pdf->Cell(100,10,"Cargo fijo",0,0,'');
                $pdf->SetXY(130,68+2);
                $pdf->Cell(100,10,"$/. 0.00",0,0,'');

                $pdf->SetXY(85,72+2);
                $pdf->Cell(100,10,"Alcantarillado",0,0,'');
                $pdf->SetXY(130,72+2);
                $pdf->Cell(100,10,"$/. 0.00",0,0,'');
                //segunda fila de 
                /* 
                $pdf->SetXY(85,67);
                $pdf->Cell(100,10,"Por IGV (18%)",0,0,'');
                $pdf->SetXY(130,67);
                $pdf->Cell(100,10,"$/. 0.64",0,0,'');
                */
                //nueva seccion igv y subtotal***************************************************
                $y_inc_sub_total = 3.7;
                //Sub total
                $pdf->SetXY(130,103.5+$y_inc_sub_total);
                $pdf->Cell(100,10,"S/. 3.56",0,0,'');
                //IGV Total
                $pdf->SetXY(130,107+$y_inc_sub_total);
                $pdf->Cell(100,10,"S/. 0.64",0,0,'');
            }
            $y_mesActual=111+4.1;
            $pdf->SetXY(74.3,$y_mesActual);
            $pdf->Cell(100,10,"Deuda Mes Actual",0,0,'');
            $pdf->SetXY(125,$y_mesActual);
            $pdf->Cell(20,10,"S/. ".number_format($element['monto_pagar'],2),0,0,'C');

            $y_importeTotal = 140.5+3;
            $pdf->SetFont('Arial','B',10);  
            $pdf->SetXY(118,$y_importeTotal);
            $pdf->Cell(100,10,"S/. ".number_format($total_suma_deudas,2),0,0,'');

            
            //MENSAJE DE CORTE
            $pdf->SetFont('Arial','B',7);
            $pdf->SetXY(5,156);
            $pdf->MultiCell( 70, 5,$msjAdmin, 1,'C');

            //SECCIÓN RECORTAR -**********************************
            $pdf->SetFont('Arial','B',7); 
            $pdf->SetXY(25, 184.5);
            $pdf->Cell(100,10,$nombre_completo ,0,0,'');
            
            $pdf->SetXY(117, 184.5);
            $pdf->Cell(100,10,"{$mesLit} del {$anio}",0,0,'');

            // $pdf->SetFont('Arial','B',10); 
            $espacio_direccion = "\n"; //18 espacios
            $pdf->SetXY(28, 187);
            $pdf->MultiCell(150, 5,$espacio_direccion . $element['direccion'], 0,'');
            // $pdf->Cell(100,10,$element['direccion'] ,0,0,'');
            
            // $espacio="------------------";
            $espacio_deuda="\n"; //18 espacios
            $pdf->SetFont('Arial','B',10); 
            $pdf->SetXY(115, 186.8);
            $pdf->MultiCell(25, 5,$espacio_deuda.'S/.'.number_format($total_suma_deudas,2), 0,'C');
            // $pdf->Cell(100,10,"S/. ".number_format($total_suma_deudas,2),0,0,'');


        }        
    }
    
    $pdf->Output();

//Funcione que retorna las formas de pago para las distintas categorias de los suministros
function modoDePago($pdf,$element,$consumo_dif){
    $categoria = $element['categoria_suministro'];
    $x = 2;
    $yc_inc = 5; // incrememento de espacio para el concepto
    $yc1=64+$yc_inc; $yc2=67+$yc_inc; $yc3=70+$yc_inc;
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
            $pdf->SetXY(85,$yc1+0*$x);
            $pdf->Cell(100,10,"(De 0 a 20)m3 * $/ 0.18",0,0,'');
            $pdf->SetXY(130,$yc1+0*$x);
            $pdf->Cell(100,10,"$/. ".number_format($val1,2),0,0,'');
            //segunda fila de 
            $pdf->SetXY(85,$yc2+1*$x);
            $pdf->Cell(100,10,"(De 20 a 40)m3 * $/ 0.60",0,0,'');
            $pdf->SetXY(130,$yc2+1*$x);
            $pdf->Cell(100,10,"$/. ".number_format($val2,2),0,0,'');
            //tercera fila de 
            $pdf->SetXY(85,$yc3+2*$x);
            $pdf->Cell(100,10,"(De 40 a mas)m3 * $/ 0.95",0,0,'');
            $pdf->SetXY(130,$yc3+2*$x);
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
            $pdf->SetXY(85,$yc1+0*$x);
            $pdf->Cell(100,10,"(De 0 a 20)m3 * $/ 0.50",0,0,'');
            $pdf->SetXY(130,$yc1+0*$x);
            $pdf->Cell(100,10,"$/. ".number_format($val1,2),0,0,'');
            //segunda fila de 
            $pdf->SetXY(85,$yc2+1*$x);
            $pdf->Cell(100,10,"(De 20 a mas)m3 * $/ 0.95",0,0,'');
            $pdf->SetXY(130,$yc2+1*$x);
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
                $val1=12;                    
                $consumo_dif-=20;
                $val2 = $consumo_dif*0.95;
            }
            $val1 = round($val1,1);
            $val2 = round($val2,1);

            $resIGV = ($val1+$val2)*0.18; $resIGV = round($resIGV,1);

            //primera fila de 
            $pdf->SetXY(85,$yc1+0*$x);
            $pdf->Cell(100,10,"(De 0 a 20)m3 * $/ 0.60",0,0,'');
            $pdf->SetXY(130,$yc1+0*$x);
            $pdf->Cell(100,10,"$/. ".number_format($val1,2),0,0,'');
            //segunda fila de 
            $pdf->SetXY(85,$yc2+1*$x);
            $pdf->Cell(100,10,"(De 20 a mas)m3 * $/ 0.95",0,0,'');
            $pdf->SetXY(130,$yc2+1*$x);
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
            $pdf->SetXY(85,$yc1+0*$x);
            $pdf->Cell(100,10,"(De 0 a mas)m3 * $/ 2.00",0,0,'');
            $pdf->SetXY(130,$yc1+0*$x);
            $pdf->Cell(100,10,"$/. ".number_format($val1,2),0,0,'');
            
            
            /* 
            //IGV
            $pdf->SetXY(85,67+1*$x);
            $pdf->Cell(100,10,"IGV (18%)",0,0,'');
            $pdf->SetXY(130,67+1*$x);
            $pdf->Cell(100,10,"$/ {$resIGV}",0,0,'');
            */

            break;
        case 'Mantenimiento':
            # code...
            $val1 = 2.11;
            
            $val1 = round($val1, 1);

            $resIGV = $val1 * 0.18; $resIGV = round($resIGV, 1);

            //primera fila de 
            $pdf->SetXY(85,$yc1+0*$x);
            $pdf->Cell(100,10,"Por mantenimiento",0,0,'');
            $pdf->SetXY(130,$yc1+0*$x);
            $pdf->Cell(100,10,"$/. ".number_format($val1,2),0,0,'');
            
            
            /* 
            //IGV
            $pdf->SetXY(85,67+1*$x);
            $pdf->Cell(100,10,"IGV (18%)",0,0,'');
            $pdf->SetXY(130,67+1*$x);
            $pdf->Cell(100,10,"$/ {$resIGV}",0,0,'');
            */

            break;
        case 'Tarifa Plana':
            # code...
            $val1 = number_format($element['monto_pagar'],2);
            
            $val1 = round($val1, 1);

            $resIGV = $val1 * 0.18; 
            $resIGV = round($resIGV, 1);
            //sacando la diferencia para obtener el subtotal
            $val1 -=$resIGV; 
            
            //primera fila de 
            $pdf->SetXY(85,$yc1+0*$x);
            $pdf->Cell(100,10,"Por Tarifa Plana",0,0,'');
            $pdf->SetXY(130,$yc1+0*$x);
            $pdf->Cell(100,10,"$/. ".number_format($val1,2),0,0,'');
            
            
            break;
        default:
            # code...
            break;
    }
    $subT = ($val1+$val2+$val3);
    return ['subt'=>$subT,'igvt'=>$resIGV];
    
}
