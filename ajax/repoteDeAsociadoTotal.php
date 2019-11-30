<?php


//fetch.php
$AjaxRequest = true;
require_once "../core/configSite.php";

require_once "../controllers/adminController.php";

$inst = new adminController();
//$insth = new mainModel();

//$connect = mysqli_connect("localhost", "root", "cardenas", "dbasusap2");
$columns = array('dni', 'nombre', 'apellido', 'telefono', 'cant_suministro');
// $query= "SELECT a.idfactura_recibo,f.nombre,s.cod_suministro,s.direccion,a.consumo,a.monto_pagar,a.anio,a.mes,a.fecha_emision,a.hora_emision,a.fecha_vencimiento,a.consumo,a.monto_pagar, s.cod_suministro
//                            FROM ((factura_recibo a INNER JOIN suministro s ON a.suministro_cod_suministro = s.cod_suministro)
//                            INNER JOIN asociado f ON f.dni = s.asociado_dni) WHERE a.suministro_cod_suministro  like '%" . $valor . "%' OR f.nombre  like '%" . $valor . "%' OR s.direccion  like '%" . $valor . "%'";
//
$query = "SELECT DISTINCT  s.asociado_dni, a.nombre,a.apellido,a.telefono,a.cant_suministro/*,s.estado_corte*/ FROM  asociado a  INNER JOIN suministro s ON s.asociado_dni=a.dni   WHERE ";
//$query = "SELECT DISTINCT   s.asociado_dni, a.nombre,a.apellido,a.telefono,a.cant_suministro/*,s.estado_corte*/ FROM  asociado a  INNER JOIN suministro s ON s.asociado_dni=a.dni   WHERE ";


if($_POST["is_date_search"] == "yes")
{
    $all=$_POST["start_date"] ;
    $esta=$_POST["estad"] ;

    session_start(['name'=>'ASUSAP']);
    $_SESSION['aestado']=$esta;
   // $catA=$_POST["catA"] ;
    // if ($all=="TODOS"){
    //-----------------------DIRECCION  ------------------------------------------------------------
   if ( $all==="TODOS" && $esta==3 ){
        // $query .= 'direccion BETWEEN "'.$_POST["start_date"].'" AND ';
        $query .= 'direccion BETWEEN "'.$_POST["start_date"].'" AND ';
    }
    else if ($all=="TODOS" && $esta==0){


        $query .= ' estado_corte="'.$_POST["estad"].'" AND ';
    }
    else if ($all=="TODOS" && $esta==2){
        /*if ($catA=="Domestico"){

            $query .= 'estado_corte ="'.$_POST["estad"].'" AND   categoria_suministro="'.$_POST["catA"] .'" AND';
        }
        if ($catA=="Comercial"){

            $query .= 'estado_corte ="'.$_POST["estad"].'" AND   categoria_suministro="'.$_POST["catA"] .'" AND';
        }
        if ($catA=="Estatal"){
            $query .= 'estado_corte ="'.$_POST["estad"].'" AND   categoria_suministro="'.$_POST["catA"] .'" AND';
        }
        if ($catA=="Industrial"){
            $query .= 'estado_corte ="'.$_POST["estad"].'" AND   categoria_suministro="'.$_POST["catA"] .'" AND';
        }*/
    //    $query = "SELECT DISTINCT  s.asociado_dni, a.nombre,a.apellido,a.telefono,a.cant_suministro/*,s.estado_corte*/ FROM  asociado a  INNER JOIN suministro s ON s.asociado_dni=a.dni   WHERE ";

        $query .= ' estado_corte="'.$_POST["estad"].'" AND ';


    }
//-----------------------------TIPO DE ESTADO DE DIVERSOS DIRECCIONES--------------------------------------------
  /*  else if ($all!="TODOS" && $esta==3){
        if ($catA=="Domestico"){

            $query .= ' direccion ="'.$_POST["start_date"].'" AND categoria_suministro="'.$_POST["catA"] .'" AND';
        }
        if ($catA=="Comercial"){

            $query .= ' direccion ="'.$_POST["start_date"].'" AND  categoria_suministro="'.$_POST["catA"] .'" AND';
        }
        if ($catA=="Estatal"){
            $query .= ' direccion ="'.$_POST["start_date"].'" AND   categoria_suministro="'.$_POST["catA"] .'" AND';
        }
        if ($catA=="Industrial"){
            $query .= ' direccion ="'.$_POST["start_date"].'" AND   categoria_suministro="'.$_POST["catA"] .'" AND';
        }
        $query .= ' direccion ="'.$_POST["start_date"].'" AND ';
    }

    else if ($all!="TODOS" && $esta==2){
        if ($catA=="Domestico"){

            $query .= ' direccion ="'.$_POST["start_date"].'" AND categoria_suministro="'.$_POST["catA"] .'" AND';
        }
        if ($catA=="Comercial"){

            $query .= ' direccion ="'.$_POST["start_date"].'" AND  categoria_suministro="'.$_POST["catA"] .'" AND';
        }
        if ($catA=="Estatal"){
            $query .= ' direccion ="'.$_POST["start_date"].'" AND   categoria_suministro="'.$_POST["catA"] .'" AND';
        }
        if ($catA=="Industrial"){
            $query .= ' direccion ="'.$_POST["start_date"].'" AND   categoria_suministro="'.$_POST["catA"] .'" AND';
        }

        $query .= ' direccion ="'.$_POST["start_date"].'" AND estado_corte ="'.$_POST["estad"].'" AND';

    }

    else if ($all!="TODOS" && $esta==0){
        if ($catA=="Domestico"){

            $query .= ' direccion ="'.$_POST["start_date"].'" AND estado_corte ="'.$_POST["estad"].'" AND   categoria_suministro="'.$_POST["catA"] .'" AND';
        }
        if ($catA=="Comercial"){

            $query .= ' direccion ="'.$_POST["start_date"].'" AND estado_corte ="'.$_POST["estad"].'" AND   categoria_suministro="'.$_POST["catA"] .'" AND';
        }
        if ($catA=="Estatal"){
            $query .= ' direccion ="'.$_POST["start_date"].'" AND estado_corte ="'.$_POST["estad"].'" AND   categoria_suministro="'.$_POST["catA"] .'" AND';
        }
        if ($catA=="Industrial"){
            $query .= ' direccion ="'.$_POST["start_date"].'" AND estado_corte ="'.$_POST["estad"].'" AND   categoria_suministro="'.$_POST["catA"] .'" AND';
        }
        $query .= ' direccion ="'.$_POST["start_date"].'" AND estado_corte ="'.$_POST["estad"].'" AND';
    }
//-------------------------CATEGORIA DE SUMINISTRO-------------------------------------

    else if ($all=="TODOS" && $esta==3 && $catA=="Domestico"){

        $query .= 'categoria_suministro="'.$_POST["catA"] .'" AND';
        //$query .= ' direccion ="'.$_POST["start_date"].'" AND estado_corte ="'.$_POST["estad"].'" AND   categoria_suministro="'.$_POST["catA"] .'" AND';
    }
    else if ($all=="TODOS" && $esta==3 && $catA=="Comercial"){

        $query .= 'categoria_suministro="'.$_POST["catA"] .'" AND';
        //$query .= ' direccion ="'.$_POST["start_date"].'" AND estado_corte ="'.$_POST["estad"].'" AND   categoria_suministro="'.$_POST["catA"] .'" AND';
    }
    else if ($all=="TODOS" && $esta==3 && $catA=="Estatal"){

        $query .= 'categoria_suministro="'.$_POST["catA"] .'" AND';
        //$query .= ' direccion ="'.$_POST["start_date"].'" AND estado_corte ="'.$_POST["estad"].'" AND   categoria_suministro="'.$_POST["catA"] .'" AND';
    }
    else if ($all=="TODOS" && $esta==3 && $catA=="Industrial"){

        $query .= 'categoria_suministro="'.$_POST["catA"] .'" AND';
        //$query .= ' direccion ="'.$_POST["start_date"].'" AND estado_corte ="'.$_POST["estad"].'" AND   categoria_suministro="'.$_POST["catA"] .'" AND';
    }*/
    //-----------------------

}


if(isset($_POST["search"]["value"]))
{
    $query .= '
  ( dni LIKE "%'.$_POST["search"]["value"].'%" 
  OR  nombre LIKE "%'.$_POST["search"]["value"].'%" 
  OR apellido LIKE "%'.$_POST["search"]["value"].'%" 
  OR telefono LIKE "%'.$_POST["search"]["value"].'%")
 ';
}

if(isset($_POST["order"]))
{
    $query .= 'ORDER BY '.$columns[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' 
 ';
}
else
{
    $query .= 'ORDER BY apellido ASC ';
}
$query1 = '';

if($_POST["length"] != -1)
{
    $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$rs = $inst->consultaAsociado($query);
$number_filter_row = $rs->rowCount($rs);
//$number_filter_row = mysqli_num_rows(mysqli_query($connect, $query));

//$result = mysqli_query($connect, $query . $query1);
$result = $inst->consultaAsociado($query . $query1);

$data = array();
$s = 1;
//$columns = array('cod_suministro', 'direccion', 'tiene_medidor', 'categoria_suministro', 'asociado_dni');

$query3='';
$instsa = new adminController();
while ($row = $result->fetch()) {


//total de suministros
    $query2= 'SELECT asociado_dni FROM  suministro WHERE asociado_dni="'.$row['asociado_dni'].'"';
    $result2 = $inst->consultaAsociado( $query2 );
    $va= $result2->fetchAll();
    $h=count($va);


    //$sub_array = array();

    $estd=$_SESSION['aestado'];
    if ($estd==3){
        $sub_array = array();
        $sub_array[] = $s++;
        $sub_array[] = $row["asociado_dni"];
        $sub_array[] = $row["apellido"] . " " . $row["nombre"];
        $sub_array[] = $row["telefono"];
        $sub_array[] = $h;
        $data[] = $sub_array;
    }
    if ($estd==2) {

//ACTIVOS 0
        $query3 = 'SELECT asociado_dni FROM  suministro WHERE asociado_dni="' . $row['asociado_dni'] . '" AND estado_corte="0"';
        $result3 = $inst->consultaAsociado($query3);
        $va3 = $result3->fetchAll();
        $h3 = count($va3);
//INACTIVOS 2
        $query4 = 'SELECT asociado_dni FROM  suministro WHERE asociado_dni="' . $row['asociado_dni'] . '" AND estado_corte="2"';
        $result4 = $inst->consultaAsociado($query4);
        $va4 = $result4->fetchAll();
        $h4 = count($va4);

        /* if ($h3>0  || $h3>$h4){
              $pdf->Cell(30,$off,$h." A: ".$h3." IA:".$h4,1,0,'C');
              $pdf->Cell(100,$off,utf8_decode($row['apellido']." ".$row['nombre']),1,0,'L');
              $pdf->Cell(25,$off,utf8_decode($row['telefono']),1,0,'C');
              if ($row['cant_suministro']>=1){
                  $v="2 a más";
              }else{
                  $v="1";
              }
              $pdf->Cell(15,$off,utf8_decode($v),1,1,'C');
          }*/

        if ($h4 > 0) {

            if ($h3 >= 1) {

            }
            else{
                $sub_array = array();
                $sub_array[] = $s++;
                $sub_array[] = $row["asociado_dni"];
                $sub_array[] = $row["apellido"] . " " . $row["nombre"];
                $sub_array[] = $row["telefono"];
                $sub_array[] = $h;
                $data[] = $sub_array;
            }

        }
    }
    if ($estd==0){
        $sub_array = array();
        $sub_array[] = $s++;
        $sub_array[] = $row["asociado_dni"];
        $sub_array[] = $row["apellido"] . " " . $row["nombre"];
        $sub_array[] = $row["telefono"];
        $sub_array[] = $h;
        $data[] = $sub_array;
    }



   /* $sub_array[] = $s++;
    $sub_array[] = $row["asociado_dni"];
    $sub_array[] = $row["apellido"] . " " . $row["nombre"];
    $sub_array[] = $row["telefono"];
    $sub_array[] = $h;

    $data[] = $sub_array;*/




}


function get_all_data($connect)
{
    $insts = new adminController();
    $query = "SELECT * FROM asociado";
    $result = $insts->consultaAsociado($query);
    // $result = mysqli_query($connect, $query);
    return $result->rowCount($result);
}

$output = array(
    "draw" => intval($_POST["draw"]),
    "recordsTotal" => get_all_data($inst->consultaAsociado($this)),
    "recordsFiltered" => $number_filter_row,
    "data" => $data
);

echo json_encode($output);


