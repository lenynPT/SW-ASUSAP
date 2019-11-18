
<?php
//fetch.php
$AjaxRequest=true;
require_once "../core/configSite.php";

require_once "../controllers/adminController.php";

$inst = new adminController();

//$connect = mysqli_connect("localhost", "root", "cardenas", "dbasusap2");
$columns = array('fecha_emision', 'consumo', 'monto_pagar', 'suministro_cod_suministro', 'mes');

$query = "SELECT * FROM factura_recibo WHERE ";

if($_POST["is_date_search"] == "yes")
{
    $query .= 'fecha BETWEEN "'.$_POST["start_date"].'" AND "'.$_POST["end_date"].'" AND ';
}

if(isset($_POST["search"]["value"]))
{
    $query .= '
  (suministro_cod_suministro LIKE "%'.$_POST["search"]["value"].'%" 
  OR fecha_emision LIKE "%'.$_POST["search"]["value"].'%" 
  OR consumo LIKE "%'.$_POST["search"]["value"].'%" 
  OR mes LIKE "%'.$_POST["search"]["value"].'%")
 ';
}

if(isset($_POST["order"]))
{
    $query .= 'ORDER BY '.$columns[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' 
 ';
}
else
{
    $query .= 'ORDER BY fecha_emision DESC ';
}

$query1 = '';

if($_POST["length"] != -1)
{
    $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}
$rs=$inst->consultaAsociado($query);
$number_filter_row = $rs->rowCount($rs);
//$number_filter_row = mysqli_num_rows(mysqli_query($connect, $query));

//$result = mysqli_query($connect, $query . $query1);
$result = $inst->consultaAsociado( $query . $query1);

$data = array();
$s=1;
//$columns = array('fecha_emision', 'consumo', 'monto_pagar', 'suministro_cod_suministro', 'mes');
while($row = $result->fetch())
{
    if ($row["esta_cancelado"]!=0){
        $sub_array = array();

        $sub_array[] =$s++;
        $sub_array[] =$row["suministro_cod_suministro"];
        $sub_array[] =$row["fecha_emision"];

       /// if ($row["consumo"]!=0){
            $sub_array[] = $row["consumo"];
        //}

        // $sub_array[] = $row["mes"];
        $sub_array[] = $row["monto_pagar"];
        $sub_array[] = $row["mes"];
        $data[] = $sub_array;
    }

}

function get_all_data($connect)
{   $insts = new adminController();
    $query = "SELECT * FROM factura_recibo";
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


