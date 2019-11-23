
<?php
//fetch.php
$AjaxRequest=true;
require_once "../core/configSite.php";

require_once "../controllers/adminController.php";

$inst = new adminController();


//$connect = mysqli_connect("localhost", "root", "cardenas", "dbasusap2");
$columns = array('idfactura_servicio','monto_amorti');

$query = "SELECT  f.idfactura_servicio,f.suministro_cod_suministro,f.total_pago,a.fechaA, a.monto_amorti FROM  amorti_servicio a INNER  JOIN factura_servicio f ON f.idfactura_servicio = a.factura_servicio_idfactura_servicio WHERE ";
//$query = "SELECT a.factura_servicio_idfactura_servicio,fs.suministro_cod_suministro, a.fecha, a.monto_amorti FROM  amorti_servicio a INNER JOIN factura_servicio fs ON fs.idfactura_servicio = a.factura_servicio_idfactura_servicio  WHERE ";

if($_POST["is_date_search"] == "yes")
{
    $query .= 'fechaA BETWEEN "'.$_POST["start_date"].'" AND "'.$_POST["end_date"].'" AND ';
}

if(isset($_POST["search"]["value"]))
{
    $query .= '
  (idfactura_servicio LIKE "%'.$_POST["search"]["value"].'%" 
  OR monto_amorti LIKE "%'.$_POST["search"]["value"].'%" 
  OR suministro_cod_suministro LIKE "%'.$_POST["search"]["value"].'%" 
  OR fechaA LIKE "%'.$_POST["search"]["value"].'%" 
  )
 ';
    // OR total_pago LIKE "%'.$_POST["search"]["value"].'%"
}

if(isset($_POST["order"]))
{
    $query .= 'ORDER BY '.$columns[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' 
 ';
}
else
{
    $query .= 'ORDER BY fechaA DESC ';
}

$query1 = '';

if($_POST["length"] != -1)
{
    $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}
$rs=$inst->consultaAsociado($query);
$number_filter_row = $rs->rowCount($rs);

$result = $inst->consultaAsociado( $query . $query1);

$data = array();
$s=1;
while($row = $result->fetch())
{
  //  if ($row["fecha"]!=0){
        $sub_array = array();

        $sub_array[] =$s++;
        $sub_array[] =$row["idfactura_servicio"];
        $sub_array[] =$row["suministro_cod_suministro"];
        $sub_array[] ="S/ ".$row["total_pago"];
        $sub_array[] ="S/ ".$row["monto_amorti"];
        $sub_array[] = $row["fechaA"];
    // $sub_array[] = $row["mes"];
       // $sub_array[] = $row["total_pago"];
        //$sub_array[] = $row["fecha"];
        $data[] = $sub_array;
   // }

}


function get_all_data($connect)
{   $insts = new adminController();
    $query = "SELECT * FROM amorti_servicio";
    $result = $insts->consultaAsociado($query);
   // $result = mysqli_query($connect, $query);
    return $result->rowCount($result);
}

$output = array(
    "draw"    => intval($_POST["draw"]),
    "recordsTotal"  =>  get_all_data($inst->consultaAsociado($this)),
    "recordsFiltered" => $number_filter_row,
    "data"    => $data
);

echo json_encode($output);


