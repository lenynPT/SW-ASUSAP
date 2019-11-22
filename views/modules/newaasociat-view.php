<?php
    require_once "./controllers/adminController.php";
    $obj = new adminController();
    $arrDirec = $obj->obtenerDireccionCalleController("");                        
    //var_dump($arrDirec);
    $htmlDirecciones = "";
    foreach ($arrDirec as $direccion) {
        # code...
        $htmlDirecciones .= "<option>{$direccion['nombre']}</option>";
    }    
?>

<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-money-box zmdi-hc-fw"></i> Nuevo Persona <small>Registration</small></h1>
	</div>
	<p class="lead">            
    </p>    
</div>
<div class="container-fluid">
    <!--Aquí estaba el btn de agregar asociado-->
</div>
<!--AGREGAR ASOCIADO FORMULARIO-->
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12">
			<ul class="nav nav-tabs" style="margin-bottom: 15px;">
			  	<li class=""><a href="#new" data-toggle="tab">Nuevo Asociado</a></li>
			</ul>

			<div id="myTabContent" class="tab-content">
				<div class="formNewAss" id="new">
					<div class="container-fluid">
                        <!---formulario nuevo asociado -->
						<div class="row">

                            <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12">
							    <form action="" method="post" enctype="multipart/form-data" autocomplete="off" onsubmit="return validarUsuario()" id="formUser">
									
                                    <div class="col-md-12">
										<div class="col-md-4 form-group row label-floating">
											<label for="direccionAsoc" class="col-md-4 control-label">DIRECIÓN</label>
											<div class="col-md-12">
												<select class="form-control" id="direccionAsoc" name="direccionAsoc" onkeydown="obtenerListDirecciones(event)">
                                                    <?=$htmlDirecciones?>
												</select>
											</div>	                                            
										</div>                                        
                                        <div id="direccionNroAsocVal" class="col-md-4 form-group label-floating ">
                                            <label class="control-label">Nro Dirección </label>
                                            <input class="form-control" type="number" name="direccionNroAsoc" id="direccionNroAsoc">
                                        </div>
                                        <div id="direccionPsjAsocVal" class="col-md-4 form-group label-floating ">
                                            <!--<label class="control-label">Pasaje</label>-->
                                            <input class="form-control" type="hidden" name="direccionPsjAsoc" id="direccionPsjAsoc">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
										<div class="col-md-6 form-group label-floating">
											<label for="categoriaAsoc" class="col-md-4 control-label">CATEGORIA</label>
											<div class="col-md-12">
												<select class="form-control" id="categoriaAsoc" name="categoriaAsoc">
													<option>Domestico</option>
													<option>Comercial</option>
													<option>Estatal</option>
													<option>Industrial</option>
												</select>
											</div>											
                                        </div>
                                        
                                        <div class="col-md-6 form-group label-floating">
											<label for="medidorAsoc" class="col-md-4 control-label">MEDIDOR</label>
											<div class="col-md-12">
												<select class="form-control" id="medidorAsoc" name="medidorAsoc">
													<option value="0">No</option>
													<option value="1">Si</option>													
												</select>
											</div>											
										</div>
									</div>

                                    <div class="col-xs-12 col-sm-12">                                        
                                        <div id="nombreAsocVal" class="col-xs-6 col-md-6 form-group label-floating ">
                                          <label class="control-label">NOMBRE</label>
                                          <input class="form-control" type="search" name="nombreAsoc" id="nombreAsoc" required>
                                        </div>
                                        <div id="apellidoAsocVal" class="col-xs-6 col-md-6 form-group label-floating ">
                                          <label class="control-label">APELLIDO</label>
                                          <input class="form-control" type="text" name="apellidoAsoc" id="apellidoAsoc">
                                        </div>

                                    </div>

                                    <div class="col-xs-12 col-sm-12">

                                        <div id="dniAsocVal" class="col-xs-12 col-md-6 form-group label-floating">
                                          <label class="control-label">DNI</label>
                                          <input class="form-control" type="number" name="dniAsoc" id="dniAsoc" min="100" max="99999999999" required>
                                        </div>

                                        <div id="telefonoAsocVal" class="col-xs-12 col-md-6 form-group label-floating">
                                          <label class="control-label">TELEFONO</label>
                                          <input class="form-control" type="number" name="telefonoAsoc" id="telefonoAsoc" min="111111111" max="999999999">
                                        </div>

                                    </div>

								    <p class="text-center">
								    	<button type="submit" class="btn btn-info btn-raised btn-lg" name="registrarAsoc" id="registrarAsoc"><i class="zmdi zmdi-floppy"></i> GUARDAR</button>
								    </p>
							    </form>
							</div>
						</div>

                        <!--AQUÍ HABIA PHP -->                        
					</div>
				</div>
			</div>
		</div>
<!-- FIN - AGREGAR ASOCIADO FORMULARIO-->

<!-- BUSCAR ASOCIADO A TRAVÉS DE UN INPUT-->
        <div class="col-md-12"> 
            <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                <li class=""><a href="#newtwo" data-toggle="tab">Nuevo Suministro</a></li>
            </ul>
            <div class="col-xs-12 col-sm-12">

                <div class="col-xs-12 col-md-4 form-group">
                    <input class="form-control" type="number" placeholder="Buscar Asociado por DNI/RUC" name="txtBuscarAsoc" id="txtBuscarAsoc" required>
                </div>
                <div class="col-xs-12 col-md-2 form-group ">
                    <button class="btn btn-success btn-raised btn-sm" name="btnBuscarAsoc" id="btnBuscarAsoc">Buscar</button>
                </div>                
            </div>
            <!--BTN PARA AGREGAR SUMINISTRO-->
            <a href="#addSumiModal" class="btn btn-primary" id="btnAddSumiModal" data-toggle="#" disabled><span class="glyphicon glyphicon-plus"></span>Agregar suministro</a>
            <!-- FIN - BTN PARA AGREGAR SUMINISTRO-->
            <div class="col-md-12">                            
                
					<div class="table-responsive">
						<table class="table table-hover text-center">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">DNI</th>
									<th class="text-center">Nombre</th>
									<th class="text-center">Apellido</th>
									<th class="text-center">Telefono</th>
									
									<th class="text-center">Cant. Suministro</th>
									<th class="text-center">Ver Suministros</th>
									<th class="text-center">Actualizar</th>
									<!--<th class="text-center">Eliminar</th>-->
								</tr>
                            </thead>
                            <!--Respuesta servidor tabla de asociado-->                            
							<tbody class="responseAsoc">
								<!--
                                <tr>
									<td>1</td>
									<td>00001111</td>
									<td>Nombre</td>
									<td>Apellidos</td>
									<td>Telefono</td>
									<td>Estado</td>
									<td>Cantidad</td>
									<td><a href="#!" class="btn btn-success btn-raised btn-xs" ><i class="zmdi zmdi-refresh"></i></a></td>
									<td><a href="#!" class="btn btn-success btn-raised btn-xs"><i class="zmdi zmdi-refresh"></i></a></td>
									<td><a href="#!" class="btn btn-danger btn-raised btn-xs"><i class="zmdi zmdi-delete"></i></a></td>
								</tr>	
                                -->
							</tbody>
                        </table>
                        <div class="infoAsoc">
                        </div>  
					</div>
			  	
                <!--MODAL PARA AGREGAR SUMINISTRO PARA USUARIO ESPECIFICO-->
                <div class="">
                    <!-- Agregar Nuevos Registros modal 1 -->
                    <div class="modal fade" id="addSumiModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabelAddSumi" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="formularioSumi" enctype="multipart/form-data" autocomplete="off">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title text-center" id="myModalLabelAddSumi">INSERTAR SUMINISTRO</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                                <div class="col-xs-12 col-sm-12">
                                                    <div class="col-md-6 form-group label-floating ">
                                                        <label class="control-label">DNI</label>
                                                        <input class="form-control txtDniAsocModal" type="text" name="dniAsocSumi" id="txtDniAsocModal" value="#" disabled>
                                                    </div>
                                                    <div class="col-md-6 form-group label-floating ">
                                                        <label class="control-label">NOMBRE</label>
                                                        <input class="form-control txtNombreAsocModal" type="text" name="nombreAsocSumi" id="txtNombreAsocModal" value="#" disabled>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="col-md-12 form-group label-floating ">
                                                        <label for="listaCategoriaSumi" class="col-md-4 control-label">CATEGORIA</label>
                                                        <div class="col-md-12">
                                                            <select class="form-control" id="listaCategoriaSumi" name="categoriaSumi">
                                                                <option>Domestico</option>
                                                                <option>Comercial</option>
                                                                <option>Estatal</option>
                                                                <option>Industrial</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 col-sm-12">

                                                    <div class="col-md-12 form-group row label-floating">
                                                        <label for="listaDireccionSumi" class="col-md-4 control-label">DIRECIÓN</label>
                                                        <div class="col-md-12">
                                                            <select class="form-control" id="listaDireccionSumi" name="direccionSumi" >
                                                                <?=$htmlDirecciones;?>
                                                            </select>
                                                        </div>	                                            
                                                    </div> 
                                                    <div class="col-md-6 form-group label-floating ">
                                                        <label class="control-label">Nro Dirección </label>
                                                        <input type="number" class="form-control" id="direccionNroSumi" name="direccionNroSumi">
                                                    </div>                                                            
                                                    <div class="col-md-6 form-group label-floating">
                                                        <label for="medidorSumi" class="col-md-4 control-label">MEDIDOR</label>
                                                        <div class="col-md-12">
                                                            <select class="form-control" id="medidorSumi" name="medidorSumi">
                                                                <option value="0">No</option>
                                                                <option value="1">Si</option>													
                                                            </select>
                                                        </div>											
                                                    </div>

                                                </div>                                                        
                                                
                                                <div class="col-md-12">
                                                    <div class="col-md-8 form-group label-floating ">
                                                        <!--<label class="control-label">Pasaje</label>-->
                                                        <input type="hidden" class="form-control"  id="direccionPsjSumi" name="direccionPsjSumi" value="NINGUNO"> 
                                                    </div>
                                                    
                                                    <!--Validar en el ajax para procesar la petición-->
                                                    <input type="hidden" name="insertSumin" value="true">
                                                    <input type="hidden" name="corteSumi" id="corteSumi" value="0">
                                                </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal" id="btncancelarIS"><span class="glyphicon glyphicon-remove"></span> Cancelar</button>
                                        <button type="submit" class="btn btn-primary btnaSum" id="btnisum"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar Suministro</button>                                        
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                    <!-- Agregar Nuevos Registros fin modal 1 -->
                </div>
                <!--FIN - MODAL PARA AGREGAR SUMINISTRO PARA USUARIO ESPECIFICO-->
            </div>

        </div>
<!-- FIN - BUSCAR ASOCIADO A TRAVÉS DE UN INPUT-->

<!-- MODAL PARA LISTAR LOS SUMINISTROS DEL ASOCIADO -->
<div class="modal fade" id="cantSumin" tabindex="-1" role="dialog" aria-labelledby="myModalcantSumin" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">            
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-center" id="myModalcantSumin"> LISTA DE SUMINISTROS POR ASOCIADO</h4>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                            
                            <div class="col-xs-12 col-sm-12">
                                <div class="col-xs-12 col-md-6 form-group label-floating ">
                                    <label class="control-label">DNI</label>
                                    <input class="form-control txtDniAsocModal" type="text" id="txtDniAsocModal" value="--" disabled>
                                </div>
                                <div class="col-xs-12 col-md-6 form-group label-floating">
                                    <label class="control-label">ASOCIADO</label>
                                    <input class="form-control txtNombreAsocModal" type="text" id="txtNombreAsocModal" value="--" disabled>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12">
                                <!-- Tabla del modal para mostrar suministro -->
                                <div class="table-responsive">
                                    <table class="table table-hover text-center">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Codigo</th>
                                                <th class="text-center">dirección</th>
                                                <th class="text-center">Categoria</th>
                                                <th class="text-center">Medidor</th>
                                                <th class="text-center">Corte</th>                                            
                                            </tr>
                                        </thead>
                                        <tbody class="responseSumi">
                                            <tr>
                                                <td>1</td>
                                                <td>66661111-0</td>
                                                <td>direccion</td>
                                                <td>categoria</td>
                                                <td>medidor</td>
                                                <td>corte</td>
                                            </tr>							
                                        </tbody>
                                    </table>
                                </div>                                
                            </div>
                    </div>
                </div>
                <div class="modal-footer">                    
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> ACEPTAR </button>
                </div>            
        </div>
    </div>

</div>

	</div>
</div>

<!-- RELLENO IMAGEN-->
<hr>
<div class="card mb-3 text-center pb-3">
    <div class="card-body">   

        <div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Recordatorio!!</h4>
        <p class="card-text">Recuerda rellenar los datos correctamente para no tener inconvenientes a la hora de realizar una consulta, el registro de un asociado o suministro</p>
        <hr>
            <img src="../views/assets/img/cara.png" class="mb-3 card-img-top" style="height:50px; margin:0 0 15px 0">    
        <p class="mb-0"><small class="">Modulo gestión asociado</small></p>
        </div>

    </div>
</div>  