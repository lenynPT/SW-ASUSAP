<?php
	if($AjaxRequest){
		require_once "../models/adminModel.php";
	}else{
		require_once "./models/adminModel.php";
	}
	class adminController extends adminModel{

    /*================================AGREGAR ASOCIADO============S================================*/

        public function guardarUsuarioController(){
            if (isset($_POST["registrarAsoc"])){

				$dniTemp = $_POST['dniAsoc'];
				$consultDni = "SELECT dni FROM asociado WHERE dni = $dniTemp";
                $query1=mainModel::execute_single_query($consultDni);
				
				if($query1->rowCount() == 0){
					$codigo_sum=mainModel::generate_codigo_sum($dniTemp,0);
					$datosController = array(
						"categoria" => $_POST['categoriaAsoc'],
						"direccion" => $_POST['direccionAsoc'],						
						"pasaje" => $_POST['direccionPsjAsoc'],						
						"casa_nro" => $_POST['direccionNroAsoc'],						
						"nombre" => $_POST['nombreAsoc'],
						"apellido" => $_POST['apellidoAsoc'],
						"dni" => $_POST['dniAsoc'],
						"telefono" => $_POST['telefonoAsoc'],
						"tiene_medidor" => $_POST['medidorAsoc'],
						"codigo_sum" => $codigo_sum 
					);
					$rsptaModel = adminModel::guardarUsuario($datosController);
					if($rsptaModel){
						//echo "Registro exitoso";
						echo '<script>
								swal({
									title: "¡OK!",
									text: "¡Usuario ha sido creado correctamente!",
									type: "success",
									confirmButtonText: "Cerrar",
									closeOnConfirm: false
								},
								function(isConfirm){
										if (isConfirm) {	   
											window.location = "newaasociat";
										} 
								});
							</script>';
					}else{
						echo "No se pudo registrar";
					}
					//return true;
				}else{
					echo "DNI ya registardo";
					return false;
				}

				/*
                    $datosController = array(
                        "idsociado" => $code,
                        "dni" => $_POST["dniUser"],
                        "direccion" => $_POST["direUser"],
                        "nombre" => $_POST["nameUser"],
                        "apellido" => $_POST["apellUser"],
                        "telefono" => $_POST["telefUser"],
                        "estado" => $_POST["estadoUser"]
                    );
                    $respuesta = adminModel::guardarUsuario($datosController);
                    echo '<script>
                    swal({
                          title: "¡OK!",
                          text: "¡Usuario ha sido creado correctamente!",
                          type: "success",
                          confirmButtonText: "Cerrar",
                          closeOnConfirm: false
                    },
                    function(isConfirm){
                             if (isConfirm) {	   
                                window.location = "newaasociat";
                              } 
                    });
					</script>';
				*/


            }

		}

	/*================================GENERADOR DE SUMINISTRO============================================*/
		public function pruebaController($msj){
			return $msj;
		}

		public function obtenerNombrefecha($anio,$n_mes){
			$r_anio = $anio;
			$r_mes = "";
			if($n_mes <= 0){
				if($n_mes == 0){
					$r_mes = "Diciembre";
					$r_anio -=1;
					return ["r_mes"=>$r_mes,"r_anio"=>$r_anio];
				}else{ // cuando sea enero y ya e hayan generado todos los consumos.
					$r_mes = "Noviembre";
					$r_anio -=1;
					return ["r_mes"=>$r_mes,"r_anio"=>$r_anio];
				}
			}
			
			switch ($n_mes) {
				case '1':
					$r_mes = "Enero";
					break;
				case '2':
					$r_mes = "Febrero";
					break;
				case '3':
					$r_mes = "Marzo";
					break;
				case '4':
					$r_mes = "Abril";
					break;				
				case '5':
					$r_mes = "Mayo";
					break;
				case '6':
					$r_mes = "Junio";
					break;
				case '7':
					$r_mes = "Julio";
					break;
				case '8':
					$r_mes = "Agosto";
					break;
				case '9':
					$r_mes = "Septiembre";
					break;
				case '10':
					$r_mes = "Octubre";
					break;
				case '11':
					$r_mes = "Noviembre";
					break;
				case '12':
					$r_mes = "Diciembre";
					break;												
				default:
					# code...
					$r_mes = "Diciembre";
					$r_anio -=1;
					break;
			}
			return ["r_mes"=>$r_mes,"r_anio"=>$r_anio];
		}

		public function consultaAsociado($query){
			$result = mainModel::execute_single_query($query);
			return $result;
		}
		public function consultar_stado_gconsumo(){
			/* recuperar
			*/
			$query = "SELECT * FROM estado_gonsumo WHERE id=0";
			$stmt = mainModel::execute_single_query($query);
			$data_gc = $stmt->fetch();
			$result = [
				"anio"  => $data_gc['anio'],
				"mes"   => $data_gc['mes'],
				"gcn_consumo"   => $data_gc['con_medidor'],
				"gsn_consumo"   => $data_gc['sin_medidor']
			];
			/*
			$result = [
				"anio"  => 2019,
				"mes"   => 10,
				"gcn_consumo"   => 0,
				"gsn_consumo"   => 0
			];
			*/
			return $result;
		}
		public function consultar_fecha_actual(){
			date_default_timezone_set('America/Lima');

			$fecha_hoy = [
				"anio"  => date("Y"),
				"mes"   => date("n"),
				"dia"   => date("d")
			];
			return $fecha_hoy;
		}

		public function actualizarFGConsumoController(){
			$fecha_actual = self::consultar_fecha_actual();
			$dataController = [
				'anio' => $fecha_actual['anio'],
				'mes' => $fecha_actual['mes'],
				'gcn_consumo' => 0,
				'gsn_consumo' => 0
			];
			$result = adminModel::actualizarFGConsumoModel($dataController);
			return $result;
		}

		public function insertarSuministroController(){

			$dni = $_POST['dni'];

			//Obtiene cant suministro del asociado. Luego suma más 1 para generar el códgio del sumin
			$query = "SELECT cant_suministro FROM asociado WHERE dni = $dni";
			$cant_sumi_asoc = mainModel::execute_single_query($query);
			$cant_sumi_asoc = $cant_sumi_asoc->fetch();
			$cant_sumi = $cant_sumi_asoc['cant_suministro'] + 1;

			//genera códgio para el suministro de acuerdo a la cantidad e sum del asociado
			$codigo_sum = mainModel::generate_codigo_sum($dni, $cant_sumi);

			$arrdataSumi = array(
				"asociado_dni" => $dni,
				"cod_suministro" => $codigo_sum,
				"direccion" => $_POST['direccionSumi'],
				"pasaje" => $_POST['direccionPsjSumi'],
				"casa_nro" => $_POST['direccionNroSumi'],
				"corte" => $_POST['corteSumi'],
				"medidor" => $_POST['medidorSumi'],
				"categoria" => $_POST['categoriaSumi'],
				"contador_deuda" => 0,
	
				"cant_suministro" => $cant_sumi
	
			);
			
			$rspModel = adminModel::insertarSuministroModel($arrdataSumi);

			return "insertando suministro de codigo :{$codigo_sum} con numSum: {$cant_sumi}";
		}

	/*================================GENERADOR DE CONSUMO============================================*/

        public function listaGconsumo($valor){

            if (!empty($valor)) {
                $conexion = mainModel::connect();
             /// $query = "SELECT cod_suministro FROM suministro WHERE cod_suministro like '%" . $valor . "%'";
                $query= "SELECT a.idfactura_recibo,f.nombre,s.cod_suministro,s.direccion,a.consumo,a.monto_pagar,a.anio,a.mes,a.fecha_emision,a.hora_emision,a.fecha_vencimiento,a.consumo,a.monto_pagar, s.cod_suministro
                            FROM ((factura_recibo a INNER JOIN suministro s ON a.suministro_cod_suministro = s.cod_suministro)
                            INNER JOIN asociado f ON f.dni = s.asociado_dni) WHERE a.suministro_cod_suministro  like '%" . $valor . "%' OR f.nombre  like '%" . $valor . "%' OR s.direccion  like '%" . $valor . "%'";

                $result = mainModel::execute_single_query($query);

                $registroModal = [];
                while($dataModal = $result->fetch()){
                    $registroModal[] = [
                        "idfact"=>$dataModal['idfactura_recibo'],
                        "nombre"=>$dataModal['nombre'],
                        "codSu"=>$dataModal['cod_suministro'],
                        "dire"=>$dataModal['direccion'],
                        "consumo"=>$dataModal['consumo'],
                        "monto"=>$dataModal['monto_pagar'],
                        "anio"=>$dataModal['anio'],
                        "mes"=>$dataModal['mes'],
                        "fechE"=>$dataModal['fecha_emision'],
                        "horaE"=>$dataModal['hora_emision']
                    ];

                }



                return $registroModal;

            }





        }

        public function updGConsumo($valor,$id){
                $res=0;
            if (!empty($id)){
                $dataAd = [
                    "id" => $id,
                    "consumo" => $valor,
                ];
                $res = adminModel::actualizarGC($dataAd);
            }
            return $res;
        }

    /*================================REGISTRAR SERVICIO  ============================================*/
        public function idRegistroServ(){
            $query2=self::execute_single_query("SELECT idfactura_servicio FROM factura_servicio");
            $correlative=($query2->rowCount())+1;

            $code=self::generate_code("RS",4,$correlative);
            $IDS=['Idcod'=>$code];

            return $IDS;
        }

        public function listaRservicio($valorS){

            $fecha_actual = self::consultar_fecha_actual();


            if (!empty($valorS)) {

                $conexion = mainModel::connect();
                $query1 ="SELECT f.nombre,f.apellido,s.cod_suministro,s.direccion FROM 
                 suministro s INNER JOIN asociado f ON f.dni = s.asociado_dni
                 WHERE s.cod_suministro  like '%" . $valorS . "%' OR f.nombre  like '%" . $valorS . "%' OR s.direccion  like '%" . $valorS . "%'";

                $results = mainModel::execute_single_query($query1);

                $registroModal = [];
                while($dataModal = $results->fetch()){
                    $registroModal[] = [
                        "idfacts"=>$dataModal['idfactura_servicio'],
                        "nombreAS"=>$dataModal['nombre'],
                        "apellidoAS"=>$dataModal['apellido'],
                        "codSum"=>$dataModal['cod_suministro'],
                        "direSR"=>$dataModal['direccion'],
                        "anio"=>$fecha_actual['anio'],
                        "mes"=>$fecha_actual['mes'],
                        "anombre"=>$dataModal['a_nombre'],
                        "cancel"=>$dataModal['esta_cancelado']
                    ];

                }

                return $registroModal;

            }



        }

        public function agregarRS($ANOM,$ANIO,$AMES,$ACOD){
            $fecha_actual = self::consultar_fecha_actual();

                $fecha=$fecha_actual['anio']+$fecha_actual['mes'];

            $idRS=self::idRegistroServ();


            $datosController = array(
                "codRS" => $idRS['Idcod'],
                "anombre" => $ANOM,
                "anio" =>$fecha_actual['anio'],
                "mes" => $fecha_actual['mes'],
                "fecha" => $fecha,
                "monto" => 0,
                "totalp" => 0,
                "estac" => 0,
                "cods" => $ACOD


            );
            $rsptaModel = adminModel::guardarRS($datosController);

          /*
            if($rsptaModel){
                //echo "Registro exitoso";
                echo '<script>
								swal({
									title: "¡OK!",
									text: "¡Usuario ha sido creado correctamente!",
									type: "success",
									confirmButtonText: "Cerrar",
									closeOnConfirm: false
								},
								function(isConfirm){
										if (isConfirm) {	   
											window.location = "aservicio";
										} 
								});
							</script>';
            }else{
                echo '<script>
								swal({
									title: "¡OK!",
									text: "¡Usuario ha sido creado correctamente!",
									type: "success",
									confirmButtonText: "Cerrar",
									closeOnConfirm: false
								},
								function(isConfirm){
										if (isConfirm) {	   
											window.location = "aservicio";
										} 
								});
							</script>';
            }*/

            $rsptaModel = $idRS['Idcod'];


            return $rsptaModel;

        }

        public function modificarRS($idFactRS,$CostTotal,$Anombre){
            date_default_timezone_set('America/Lima');
            $created_date = date("Y-m-d H:i:s");
          //  "ids="+idf+"&cost="+tot+"&cod="+codsu+"&anom="+anrs,
            $res=0;
            if (!empty($idFactRS)){
                $dataAd = [
                    "id" => $idFactRS,
                    "cosTotal" => $CostTotal,
                    "fecha" =>$created_date,
                    "anombre" =>$Anombre
                ];
                $res = adminModel::actualizarRS($dataAd);
            }
            return $res;

        }
        /*################ADREGANDO LOS ITEMS###############33*/
        public function agregarRSI($Des,$DCost,$DCS){

            //$idRS=self::idRegistroServ();


            $res=0;
            if (!empty($Des)){
                $dataAd = [
                    "descrip" => $Des,
                    "dcosto" => $DCost,
                    "cds" => $DCS

                ];
                $res = adminModel::insertsRS($dataAd);
            }
            else{
                echo "no se guardo";
            }

            return $res;

        }

        public function eliminarRSI($IDF){
            $res=0;
            if (!empty($IDF)){
                $dataAd = [
                    "idfi" => $IDF
                ];
                $res = adminModel::eliminarRSI($dataAd);
            }
            else{
                echo "no se guardo";
            }
            return $res;
        }
/*        public function viewsRS(){
            $tabla="";

            $conexion=mainModel::connect();

            $datos=$conexion->query("
            SELECT * FROM detalle_servicio ");
            $datos=$datos->fetchAll();


            $tabla.='<div class="table-responsive">
                                        <table class="table table-bordered  text-center" >
                                            <thead class="tabla-cabezera">
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Nombre - Descripcion</th>
                                                <th class="text-center">Costo</th>
                                                <th class="text-center">Accion</th>
                                            </tr>
                                            </thead>
                                            <tbody class="table table-striped" id="table-body">';

            foreach ($datos as $k=>$v){

                $tabla.='<tr class="table-row" id="table-row-">
                        
                        <td contenteditable="true" onClick="editRowRS(this);">'.$datos[$k]["factura_servicio_idfactura_servicio"].'</td>
                        <td contenteditable="true" onClick="editRowRS(this);">'.$datos[$k]["servicio_idservicio"].'</td>
                        <td contenteditable="true"  onClick="editRowRS(this);">'.$datos[$k]["descripcion"].'</td>
                        <td><a class="ajax-action-links" >BorrarASS</a></td>
                        </tr>';


           }

            $tabla.=' </tbody>
                                        </table>
                                    </div>';

            return $tabla;
        }*/

        /*##########################SELECTORES DEL SERVICIO###############################*/
        public function SelectorS(){


            $conexion=mainModel::connect();

            $datos=$conexion->query("
            SELECT * FROM servicio");

           $datoss=$datos->fetchAll();
            $groups = array();


            foreach ($datoss as $k){

                echo '<option value="'.$k['idservicio'].'" id="servi'.$k['idservicio'].'">'.$k['nombre_servicio'].'</option>';


            }





        }

        /*:::::::::::::::::::::::::::::::::::::::AMORTIZAR SERVICIO:::::::::::::::::::::::::::::::::::::::::::::::::*/

        public function listaAservicio($valorS){

            //$ca=mainModel::buscarMontoRest($valorS);


            if (!empty($valorS)) {

                $conexion = mainModel::connect();
                 $query1="SELECT idfactura_servicio,a_nombre,suministro_cod_suministro,anio,mes,fecha,total_pago FROM factura_servicio WHERE idfactura_servicio like '%" . $valorS . "%' OR a_nombre like '%" . $valorS . "%'";
                $results = mainModel::execute_single_query($query1);

                $registroModal = [];
                while($dataModal = $results->fetch()){
                    $registroModal[] = [
                        "idfacts"=>$dataModal['idfactura_servicio'],
                        "anombre"=>$dataModal['a_nombre'],
                        "codSum"=>$dataModal['suministro_cod_suministro'],
                        "anio"=>$dataModal['anio'],
                        "mes"=>$dataModal['mes'],
                        "fechars"=>$dataModal['fecha'],
                        "totalPago"=>$dataModal['total_pago']
                    ];

                }

                return $registroModal;

            }
        }
        /*---------------------------AMORTIZAR-----------------------------------------------*/
        public function datos_AmortizarC($codigo){

            return adminController::datos_Amortizar($codigo);
        }

        public function montoPagado($mopagr,$IDS,$MR){
            $res=0;
            $SS=$mopagr;
            if (!empty($SS)){
                $dataAd = [
                    "id" => $IDS,
                    "MPAGADO" => $mopagr,
                    "MREST" => $MR
                ];

                $res = adminModel::actualizarASR($dataAd);
            }
            return $res;
        }
        /*------------------------AMORTIZACION SU DETALLE-------------------------------------*/
        public function listaAservicioDetalle($valorS){
            $conexion = mainModel::connect();

            $query1 ="SELECT descripcion,costo FROM detalle_servicio
                 WHERE factura_servicio_idfactura_servicio = '.$valorS.'";

            $results = mainModel::execute_single_query($query1);

            $registroModal = [];
            while($dataModal = $results->fetch()){
                $registroModal[] = [
                    "descrRA"=>$dataModal['descripcion'],
                    "costoRA"=>$dataModal['costo']
                ];

            }

            return $registroModal;
        }

	}
