<?php 

    //Permite incluir los archivos necesarios para las funciones de consulta.
    $AjaxRequest=true;

    require_once "../controllers/adminController.php";
    require "fpdf/fpdf.php";

    class PDF extends FPDF
    {
        function Header()
        {
            $this->SetFont('Arial','B',20);
            $this->SetXY(5,4.5);
            $this->Cell(200,10,'RELACION DE USUARIOS CON CORTE',1,0,'C');
            $this->Ln(20);

        }
        function Footer()
        {
           // $this->SetXY(0,-24);
            //$this->SetFont('Arial','B',10);
            //$this->Cell(25,8,'Codigo Suministro: ',0,0,'');
        }
    }

    
    $dataObj = new adminController();

    //mensaje admin
    $usuariosCorte = $dataObj->obtenerRegSumCnCorteIMPRIMIRController('');
    
    //New PDF
    $pdf = new PDF('P','mm','A4');

    $hc = 7; //altura de los registros
    $nextPY = 0; // Posición 'y' del proximo registro dede el primero.

    $pxinit=5; // posición 'x' de los registros
    $wCol = ['w-1'=>10, 'w-2'=>20, 'w-3'=>100, 'w-4'=>70];
    $px2 = $pxinit + $wCol['w-1'];
    $px3 = $px2 + $wCol['w-2'];
    $px4 = $px3 + $wCol['w-3'];
    $pxCol = ['pxc-1'=>$pxinit, 'pxc-2'=>$px2, 'pxc-3'=>$px3, 'pxc-4'=>$px4];

    //Dimensiones titulo
    $pyTitle=20;$hcTitle=8;

    $LIMIT_REG = 35;
    $CONT_REG = -1;
    $NUMERACION = 0;

    foreach ($usuariosCorte as $registro)  { 
        # code...  
        //$pdf->ln();
        $NUMERACION +=1;
        $CONT_REG++; // En el primer ciclo suma a CERO '0'
        if($LIMIT_REG == $CONT_REG || $CONT_REG == 0){
            $pdf->AddPage(); // AGREGA UNA PÁGINA
            $nextPY = $pyTitle + $hcTitle; //Dimenesiones para los registros
            $CONT_REG = 0; // Inicializando contador de registros
            
            //titulo
            $pdf->SetFont('Arial','B',11);

            $pdf->SetXY($pxCol['pxc-1'], $pyTitle);
            $pdf->Cell($wCol['w-1'], $hcTitle,'Nro',1,0,'C');

            $pdf->SetXY($pxCol['pxc-2'], $pyTitle);
            $pdf->Cell($wCol['w-2'], $hcTitle,'CODIGO',1,0,'C');
            $pdf->SetXY($pxCol['pxc-3'], $pyTitle);
            $pdf->Cell($wCol['w-3'], $hcTitle,'NOMBRE Y APELLIDOS',1,0,'C');
            $pdf->SetXY($pxCol['pxc-4'], $pyTitle);
            $pdf->Cell($wCol['w-4'], $hcTitle,'DIRECCION',1,0,'C');

            $pdf->SetFont('Arial','B',8);

        }else{
            $nextPY += $hc;
        }
        
        $pdf->SetXY($pxCol['pxc-1'],$nextPY);
        $pdf->Cell($wCol['w-1'], $hc,$NUMERACION,1, 0,'');

        $pdf->SetXY($pxCol['pxc-2'],$nextPY);
        $pdf->Cell($wCol['w-2'], $hc,"{$registro['cod_suministro']}",1, 0,'');
        $pdf->SetXY($pxCol['pxc-3'],$nextPY);
        $pdf->Cell($wCol['w-3'], $hc,utf8_decode($registro['nombre'])." ".utf8_decode($registro['apellido']),1, 0,'');
        $pdf->SetXY($pxCol['pxc-4'],$nextPY);
        $pdf->Cell($wCol['w-4'], $hc,utf8_decode($registro['direccion']),1, 0,'C');        
        
    }


    

    $pdf->Output();
