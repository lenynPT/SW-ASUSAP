
<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles">                
            <i class="zmdi zmdi-money-box zmdi-hc-fw"></i> GENERAR CONSUMO <small>para <b><?php echo "$FechaGConsumo del $FechLiteral[r_anio]"; ?></b></small>            
        </h1>
    </div>
</div>
<div class="container-fluid">
    <?php if($btn_xdefct){ ?>
        <a href="#" id="btnGenerarCXD" class="btn btn-primary btn-lg p-4"><span class="glyphicon glyphicon-plus"></span> 
            GENERAR CONSUMO X DEFECTO
        </a>
        <div id="loadBtnGCXD" class="lead bg-secondary px-4 text-center">
            <!--
                <i class="zmdi zmdi-spinner zmdi-hc-spin zmdi-hc-4x"></i> 
                </br>Porfavor Espere... </br>Esta operación podría tardar un momento...    
            -->   
        </div>        
        <br>        
    <?php 
        }else{
            echo '
                <div class="alert alert-success lead" role="alert">
                    YA SE GENERO CONSUMO 4.20
                </div>

                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">Muy bien!</h4>
                    <p>Se generaron los consumos para los suministros sin medidor .</p>
                    <hr>
                    <p class="mb-0">Ahora toca llenar los consumos para los suministros con medidor!!</p>
                </div>   
                ';
        } 
    ?>
  
</div>
<!--
-->
<div class="card">
    <div class="card-header text-center">

        <div class="row form-horizontal">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab_consultar" data-toggle="tab">
                        Suministros con medidor 
                        <?php 
                            // echo $tabla_consumo?"FALTA GENERAR CONSUMOS":"YA SE GENERÓ LOS CONSUMOS";
                        ?>
                        <span id="alertOfCompl">
                            <?php 
                                echo $tabla_consumo?'<span class="text-danger "> FALTA</span>':'<span class="text-success blockquote"> LISTO!!</span>';
                            ?>
                        </span>
                    </a>                    
                </li>                                
            </ul>
        </div>
        <br>

                <div class="form-group row">                    
                    <label for="btnBuscarSumCorte" class="col-sm-1 col-form-label"><i class="zmdi zmdi-search zmdi-hc-2x pull-left"></i></label>
                    <div class="col-sm-11">                       
                        <input  type="search" name="buscar" id="buscarSumCnM" class="form-control" onkeyup="generarConsumoConMedidor(this.value)" placeholder="Ingrese Cod. Suministro, Nombre ó apellido"/>
                    </div>
                    <label for="optTP">TARIFA PLANA </label> </br>
                    <input type="checkbox" name="optTP" id="optTP">
                </div> 

    </div>
    <div class="card-body">        
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead id="titRspSumi">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Suministro</th>
                        <th scope="col">CONSUMO</th>
                        <th scope="col">Consumo Anterior</th>
                        <th scope="col">Cant. Deudas</th>
                        <th scope="col">Asociado</th>
                        <th scope="col">Categoria</th>
                        <th scope="col">Direccion</th>                        
                    </tr>
                </thead>
                <tbody id="rspSumi">

                </tbody>
            </table>
        </div> 
    </div>

    <div class="card-footer text-muted">

    </div>

</div>
<hr>
