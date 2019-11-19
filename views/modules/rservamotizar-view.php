
<style>

    .box
    {
        width:1270px;
        padding:20px;
        background-color:#fff;
        border:1px solid #ccc;
        border-radius:5px;
        margin-top:25px;
    }
</style>

<div class="container-fluid">
    <div class="page-header">
<!--        <h1 class="text-titles"><i class="zmdi zmdi-money-box zmdi-hc-fw"></i> Registrar <small>Servicio</small></h1>
-->    </div>
    <!--    <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse voluptas reiciendis tempora voluptatum eius porro ipsa quae voluptates officiis sapiente sunt dolorem, velit quos a qui nobis sed, dignissimos possimus!</p>
    --></div>


<div class="container box">
    <h1 align="center">REPORTE DE CUANTO INGRESO DE RICIBOS DE SERVICIO </h1>

    <div class="justify-content-between ">
        <p class="text-right text-al justify-content-between align-items-center">
            <button type="submit"  class="btn btn-danger btn-raised btn-sm" onclick="ImprimerRC();"><i class="zmdi zmdi-floppy"></i> IMPRIMIR</button>
        </p>
    </div>
    <br />
    <div class="table-responsive">
        <br />
        <div class="container">
        <div class="row justify-content-center">
            <div class="input-daterange ">
                <div class="col-md-5">
                    <input type="text" name="start_date" id="start_date" class="form-control" />
                </div>
                <div class="col-md-5">
                    <input type="text" name="end_date" id="end_date" class="form-control" />
                </div>
            </div>
            <div class="col-md-1">
                <input type="button" name="search" id="search" value="Search" class="btn btn-success btn-raised btn-sm" />
            </div>

        </div>
        </div>
        <br />
        <table id="order_data" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>N° RECIBO</th>
                <th>SUMINISTRO</th>
                <th>A NOMBRE COMPLETO</th>
                <th>MONTO</th>
                <th>FECHAs</th>
            </tr>
            </thead>
        </table>

    </div>
</div>





<script type="text/javascript" language="javascript" >
    $(document).ready(function(){

        $('.input-daterange').datepicker({
            todayBtn:'linked',
            format: "yyyy-mm-dd",
            autoclose: true
        });

        fetch_data('no');

        function fetch_data(is_date_search, start_date='', end_date='')
        {
            var dataTable = $('#order_data').DataTable({
                "language":{
                    "lengthMenu":"Mostrar _MENU_ registros por página.",
                    "zeroRecords": "Lo sentimos. No se encontraron registros.",
                  "info": "Total de _MAX_ registros",
                  //"info": "Mostrando página _PAGE_ de _PAGES_ filtrados de un total de _MAX_ registros",
                    "infoEmpty": "No hay registros aún.",
                    "infoFiltered": "(filtrados de un total de _MAX_ registros)",
                  //  "search" : "Búsqueda",
                    "LoadingRecords": "Cargando ...",
                    "Processing": "Procesando...",
                   // "SearchPlaceholder": "Comience a teclear...",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente",
                    }
                },

                "processing" : true,
                "serverSide" : true,
                "order" : [],
                "ajax" : {
                    url:"../ajax/reportesAmortizacion.php",
                    type:"POST",
                    data:{
                        is_date_search:is_date_search, start_date:start_date, end_date:end_date
                    }
                }
            });
        }

        $('#search').click(function(){
            var start_date = $('#start_date').val();

            var end_date = $('#end_date').val();
            if(start_date != '' && end_date !='')
            {
                $('#order_data').DataTable().destroy();
                fetch_data('yes', start_date, end_date);
            }
            else
            {
                alert("Both Date is Required");
            }

        });

    });
</script>

