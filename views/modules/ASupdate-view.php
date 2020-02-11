<div class="container-fluid" onload="guardarDB('');" id="contentAMRS">
    <div class="page-header">
        <h1 class="text-titles text-center" >
            <i class="zmdi zmdi-book zmdi-hc-fw"></i>
            Amortizar Servicio
        </h1>
    </div>

    <script language='javascript'>
        function volverA(){
            window.location="http://localhost/SW-ASUSAP/aservicio/";
        }
    </script>

    <?
        $datos=explode("/", $_GET['views']);
        require_once "./controllers/adminController.php";
        $classAdmin=new adminController();

        $filesAS=$classAdmin->datos_AmortizarCServ($datos[1]);
        $filesAS->rowCount();
        $camposS = $filesAS->fetch();

        $filesA=$classAdmin->datos_AmortizarC($datos[1]);
        if($filesA->rowCount()){
        $campos = $filesA->fetch();

        $serV=$camposS['descripcion'];
        //$arrServicio = ["CORTE Y RECONEXION","NUEVA INSTALACION","SERVICIO DE GASFITERIA","AVERIAS DE REDES TENDIDAS","OTROS"];
    ?>

    <div class="container-fluid" onload="guardarDB('');" >

        <button type="submit"  class="btn btn-info btn-raised btn-sm" id="volverAmo" onclick="volverA();"><i class="zmdi zmdi-floppy"></i> Volver</button>

        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 rs-asusap" style="background: rgba(25,25,25,.1);" id="cuadro">

                <p class="text-center">
                    <label class="text-danger" name="nameUser">N. servicio <small id="idReciboA"><? echo $campos['idfactura_servicio']?></small></label>
                </p>
                <div class="col-xs-12 col-sm-12">

                    <div class="col-xs-12 col-md-6 ">
                        <h3 class="tile-titles text-left font-weight-bold"><b>Fecha de Deuda: </b><small id="fechaAS"><? echo $campos['fecha']?></small></h3>
                    </div>
                    <div class="col-xs-12 col-md-6 ">
                        <?php
                        /*
                        foreach ($arrServicio as $value) {
                            # code...
                            if($value == $serV){
                                echo '<h3 class="tile-titles text-left font-weight-bold"><b>Servicio: </b><small id="servicioAS">'.$value.'</small></h3>';
                            }
                        }
                        */
                        echo '<h3 class="tile-titles text-left font-weight-bold"><b>Servicio: </b><small id="servicioAS">'.$serV.'</small></h3>';
                        ?>


                    </div>
                </div>

                <div class="col-xs-12 col-sm-12">
                    <div class="col-xs-12 col-md-6 ">
                        <h3 class="tile-titles text-left font-weight-bold"class="text-titles" ><b> Nombre Apellido: </b><small id="nombreAR"><? echo $campos['a_nombre']?></small></h3>
                    </div>
                    <div class="col-xs-12 col-md-6 " id="cuadro12">
                        <h3 class="tile-titles text-left font-weight-bold" ><b> Cod. Suministro: </b><small id="codAR"><? echo $campos['suministro_cod_suministro']?></small></h3>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="col-xs-12 container" id="list">

                        <p style="color: black;">
                            <b>Monto TOTAL:</b>S/<small id="montTotal"><input  type="text" id="montTotall" name="montTotal" value="<? echo $campos['total_pago']?>" disabled ></small>
                        </p>
                        
                        <div id="contAMORT" class="col-xs-12 col-sm-3  justify-content-center" style="background: rgba(253,140,0,0.77); color: white;">
                            <div class="col-xs-12 col-sm-12 col align-self-center">
                                <h3><b>Monto Restante:</b> </h3>
                                <p style="color: black;">S/<small id="mntTotal"><input  type="text" id="montAll" name="montTot" value="<? echo $campos['mont_restante']?>" disabled ></small>
                                </p>
                            </div>
                            <div class="col-xs-12 col-sm-12 col align-self-center">
                                <h3><b>Monto a Pagar:</b></h3>
                                <p style="color: black;">S/ <small >
                                <input type="number" id="montPa" name="montPag" onkeyup="guardarDB(this.value);" >
                                    </small></p>
                            </div>
                            <div class="col-xs-12 col-sm-12 col align-self-center" style="background: rgb(255,0,7);">
                                <h3><b>Monto Pendiente:</b></h3>
                                <p style="color: black;">S/ <small id="montP"><? echo $campos['mont_restante']?></small>
                            </div>
                            </div>
                            <div class="col-xs-12 col-sm-9 " >
                                <p>PAGO DEL ANTERIOR: <b style="color: red;"> S/ <? echo $campos['monto_pagado']?> </b></p>
                            </div>
                            <div class="col-xs-12 col-sm-9 " id="msj">
                                <p>NO puede ingresar numero <b>MAYOR A MONTO TOTAL </b></p>
                                <p>NO puede ingresar numero <b>MENOR A 0 </b></p>
                            </div>
                        </div>
                        <p class="text-center">

                            <button type="submit"  class="btn btn-danger btn-raised btn-sm" onclick="ImprimerReciboAmotizacion();"><i class="zmdi zmdi-floppy"></i> IMPRIMIR</button>
                            <button type="submit"  class="btn btn-info btn-raised btn-sm pull-right" id="guardarARMOTIZACION" onclick="guardarARMOTIZACION();"><i class="zmdi zmdi-floppy"></i> GUARDAR</button>

                        <!-- <a href="../reportes/amortizacionServ.php" target="_blank" class="btn btn-info btn-raised btn-xs"  >Imprimir</a>-->
                        </p>
                    </div>

                </div>

        </div>

    </div>
</div>

<?}?>
