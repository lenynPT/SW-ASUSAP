
<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles">                
            <i class="zmdi zmdi-money-box zmdi-hc-fw"></i> GENERAR CONSUMO <small>para <b><?php echo "$FechaGConsumoNum $FechaGConsumo $FechLiteral[r_anio]"; ?></b></small>            
        </h1>
    </div>
</div>
<div class="container-fluid">
    <?php if($btn_xdefct){ ?>
        <a href="#" id="btnGenerarCXD" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-plus"></span> 
            GENERAR CONSUMO X DEFECTO
        </a>
    <?php 
        }else{
            echo "Ya se genero por defecto";
        } 
    ?>
</div>

<div class="container-fluid">

    <div class="col-xs-12 col-sm-12">

        <diV class="row justify-content-center page-header">
            <h3 class="tile-titles text-center font-weight-bold"><b>Generar recibo para usuarios con MEDIDOR</b></h3>
        </diV>

        <?php 
            echo $tabla_consumo?"FALTA GENERAR CONSUMOS":"YA SE GENERÓ LOS CONSUMOS";
        ?>

        <div class="col-xs-12 col-md-4 form-group">
            <input class="form-control" type="search" name="nameUser">
        </div>
        <div class="col-xs-12 col-md-4 form-group ">
            <button class="btn btn-success btn-raised btn-xs" name="nameUser">buscar</button>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">

            <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                <li><a href="#list" data-toggle="tab">Lista</a></li>
            </ul>
            <div id="myTabContent" class="tab-content">
                <div class="tab-pane fade active in" id="new">
                    <div class="container-fluid">
                    </div>
                </div>



            </div>
        </div>
        <div class="col-xs-12"
        <div class="tab-pane fade" id="list">
            <div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Codigo</th>
                        <th class="text-center">Domicilio</th>
                        <th class="text-center">Cosumo</th>
                        <th class="text-center">Monto</th>
                        <th class="text-center">Mes</th>
                        <th class="text-center">Año</th>
                        <th class="text-center">G. Consumo</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>Carlos Alfaro</td>
                        <td>564</td>
                        <td>AV. Ku 256</td>
                        <td>121.4 l</td>
                        <td>s/ 6.25</td>
                        <td>Octubre</td>
                        <td>2019</td>
                        <td><a href="#!" class="btn btn-info btn-raised btn-xs">G. Consumo</a></td>


                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Claudia Rodriguez</td>
                        <td>231</td>
                        <td>AV. Bf 256</td>
                        <td>567.4 l</td>
                        <td>s/ 4.5</td>
                        <td>Octubre</td>
                        <td>2019</td>
                        <td><a href="#!" class="btn btn-info btn-raised btn-xs">G. Consumo</a></td>

                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Ana Quispe</td>
                        <td>455</td>
                        <td>AV. FT 123</td>
                        <td>121.4 l</td>
                        <td>s/ 8.00</td>
                        <td>Octubre</td>
                        <td>2019</td>
                        <td><a href="#!" class="btn btn-info btn-raised btn-xs">G. Consumo</a></td>

                    </tr>
                    </tbody>
                </table>
                <ul class="pagination pagination-sm">
                    <li class="disabled"><a href="#!">«</a></li>
                    <li class="active"><a href="#!">1</a></li>
                    <li><a href="#!">2</a></li>
                    <li><a href="#!">3</a></li>
                    <li><a href="#!">4</a></li>
                    <li><a href="#!">5</a></li>
                    <li><a href="#!">»</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>

<script>

        document.querySelector("#btnGenerarCXD").addEventListener('click',()=>{
            console.log("clickkk btn x defecto");
            location.reload();
        });

</script>
