<?php
    require_once "controllers/adminController.php";

    $fechas = new adminController();
    
    $gsn_cosumo = $fechas->consultar_stado_gconsumo();
    $fecha_hoy = $fechas->consultar_fecha_actual();

    //VERIFICAMOS QUE LA GENERACIÓN DE CONSUMOS ESTÉ ABIERTO PARA EL MES ACTUAL. SINO SE DEBE HABILITAR EN <<GR->GC>>
    if($fecha_hoy['mes'] == $gsn_cosumo['mes']){

        $fecha_hoy = $fechas->consultar_fecha_actual();
        $FechaGConsumo = $fecha_hoy['mes'];   
        $FechLiteral = $fechas->obtenerNombrefecha($fecha_hoy['anio'],$FechaGConsumo);
        $FechaGConsumo=$FechLiteral['r_mes'];

        $btn_xdefct = false;
        if($gsn_cosumo['gsn_consumo'] == 0){
            $btn_xdefct = true; //habilita el btn de consumo x defecto
        }
?>
<?php 
        if($btn_xdefct){ ?>
            <div class="container-fluid">
                    <a href="#" id="btnGenerarCXD" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-plus"></span> 
                        GENERAR CONSUMO X DEFECT
                    </a>
            </div>
<?php 
        }else{        
?>
  

            <div class="container-fluid">
                <div class="page-header">
                    <h1 class="text-titles">                
                        <i class="zmdi zmdi-money-box zmdi-hc-fw"></i> GENERAR <small>desde <b><?php echo "$FechaGConsumo para el año $FechLiteral[r_anio]"; ?></b></small>            
                    </h1>
                </div>
            </div>

            <div class="card">
                <div class="card-header text-center">

                    <div class="row form-horizontal">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_consultar" data-toggle="tab">
                                    Generar Recibos Por Año
                                </a>                                        
                            </li>                                
                                
                        </ul>
                    </div>
                    <br>

                    <div class="form-group row">
                        <label for="bscSumxAniok" class="col-sm-1 col-form-label">
                            <i class="zmdi zmdi-search zmdi-hc-2x pull-left"></i>
                        </label>
                        <div class="col-sm-11">                       
                            <input  type="search" name="bscSumxAniok" id="bscSumxAniok" class="form-control" placeholder="Ingrese Cod. Suministro o DNI"/>
                        </div>
                    </div> 

                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="chbxImprimir">
                        <label class="custom-control-label" for="chbxImprimir">Imprimir</label>
                    </div>

                </div>

                <div class="card-body">        
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Suministro</th>
                                    <th scope="col" class="text-center">Cant. Deudas</th>
                                    <th scope="col">Asociado</th>
                                    <th scope="col">Categoria</th>
                                    <th scope="col">Direccion</th>
                                    <th scope="col" class="text-center">IMPRIMIR</th>
                                    <th scope="col" class="text-center">COBRAR</th>
                                    
                                </tr>
                            </thead>
                            <tbody id="tblSumxAnio">

                            </tbody>
                        </table>
                    </div> 
                </div>

                <div class="card-footer text-muted">

                </div>

            </div>
            <hr>
<?php 
        }        
    }else{
?>
        <h1>HABILITAR MES EN GENERAR CONSUMO</h1>
<?php
    }
?>