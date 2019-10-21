<?php

    require_once "controllers/adminController.php";
    $fechas = new adminController();
    $fecha_hoy = $fechas->consultar_fecha_actual();

    $FechaER = $fecha_hoy['mes']-1;//mes anterior (Emision Recibo - ER)
    $FechLiteral = $fechas->obtenerNombrefecha($fecha_hoy['anio'],$FechaER);    
?>

<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class="zmdi zmdi-money-box zmdi-hc-fw"></i> Emitir <small>Recibo</small> <?php echo "{$FechLiteral['r_mes']} {$FechLiteral['r_anio']}";?></h1>
    </div>
<!--    <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse voluptas reiciendis tempora voluptatum eius porro ipsa quae voluptates officiis sapiente sunt dolorem, velit quos a qui nobis sed, dignissimos possimus!</p>
-->
</div>

<div class="container-fluid">
    <!-- EMITIR X CALLE -->
    <div class="row">
        <h3 class="tile-titles text-center font-weight-bold"><b>Recibos X Dirección</b></h3>
        <div class="col-xs-12 col-sm-12">
            <div class="col-xs-12 col-md-4 form-group">
                <input class="form-control" type="search" name="nombreDirec" id="nombreDirec" placeholder="NOMBRE DIRECCIÓN">
            </div>
            <div class="col-xs-12 col-md-2 form-group ">
                <button class="btn btn-success btn-raised btn-xs" name="nameUser">buscar</button>
            </div>
            <div class="col-xs-12 col-md-3 form-group ">
                <select name="fecha_mes" id="fecha_mes" class="form-control">
                    <option value="<?php echo $FechaER;?>"><?php echo $FechLiteral["r_mes"];?></option>
                    <?php 
                        //Gnerea los nombres de los meses
                        for ($i_mes=1; $i_mes <= 12; $i_mes++) { 
                            # code...
                            if($FechaER == $i_mes)continue;

                            $L_mes = $fechas->obtenerNombrefecha($fecha_hoy['anio'],$i_mes);
                            echo "<option value='{$i_mes}'>{$L_mes['r_mes']}</option>";
                        }
                    ?>
                </select>              
            </div>
            <div class="col-xs-12 col-md-3 form-group ">
                <select name="fecha_anio" id="fecha_anio" class="form-control">
                    <option value="<?php echo $FechLiteral["r_anio"];?>"><?php echo $FechLiteral["r_anio"];?></option>
                    <option value="<?php echo $FechLiteral["r_anio"]-1;?>"><?php echo $FechLiteral["r_anio"]-1;?></option>
                    <option value="<?php echo $FechLiteral["r_anio"]-2;?>"><?php echo $FechLiteral["r_anio"]-2;?></option>
                    <option value="<?php echo $FechLiteral["r_anio"]-3;?>"><?php echo $FechLiteral["r_anio"]-3;?></option>                    
                </select>              
            </div>
        </div>

        <div class="col-xs-12">
            <div class="tab-pane " id="list">
                <div class="table-responsive">
                    <table class="table table-hover text-center">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Calle / Dirección</th>                                                                                                
                                <th class="text-center">Generar recibo</th>
                            </tr>
                        </thead>
                        <tbody id="resTablaRD">
                           <!--<tr>
                                <td>1</td>
                                <td>Av. los celajes</td>          
                                <td><a href="#!" class="btn btn-info btn-raised btn-xs">G. Recibo</a></td>
                            </tr>
                            <tr>
                                <td>2</td>                                
                                <td>AV. Bf 256</td>
                                <td><a href="#!" class="btn btn-info btn-raised btn-xs">G. Recibo</a></td>
                            </tr>-->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- EMITIR X PERSONA -->
    <div class="row">
        <h3 class="tile-titles text-center font-weight-bold"><b>Recibos X Persona</b></h3>
        <div class="col-xs-12 col-sm-12">
            <div class="col-xs-12 col-md-4 form-group">
                <input class="form-control" type="search" name="nameUser" placeholder="CÓDIGO SUMINISTRO Ejm. 70598957-0">
            </div>
            <div class="col-xs-12 col-md-4 form-group ">
                <button class="btn btn-success btn-raised btn-xs" name="nameUser">buscar</button>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="tab-pane " id="list">
                <div class="table-responsive">
                    <table class="table table-hover text-center">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Cod Suministro</th>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Direccion</th>
                                <th class="text-center">Monto</th>
                                <th class="text-center">Generar recibo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>564</td>
                                <td>Carlos Alfaro</td>
                                <td>AV. Ku 256</td>
                                <td>s/ 6.25</td>
                                <td><a href="#!" class="btn btn-info btn-raised btn-xs">G. Recibo</a></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>231</td>
                                <td>Claudia Rodriguez</td>
                                <td>AV. Bf 256</td>
                                <td>s/ 4.5</td>
                                <td><a href="#!" class="btn btn-info btn-raised btn-xs">G. Recibo</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
</div>