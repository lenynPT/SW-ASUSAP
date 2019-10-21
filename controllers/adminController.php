<?php
	if($AjaxRequest){
		require_once "../models/adminModel.php";
	}else{
		require_once "./models/adminModel.php";
	}
	class adminController extends adminModel{

    /*================================AGREGAR ASOCIADO============================================*/

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

		protected function obtenerFechasConsumo($anio,$n_mes){
			$r_anio = $anio;
			$r_mes = $n_mes - 1;

			if($n_mes == 1 ){
				$r_mes = 12;
				$r_anio -= 1;
			}
			return [
				"anio_GC"=>$r_anio,
				"mes_GC"=>$r_mes
			];
		}
		public function obtenerNombrefecha($anio,$n_mes){
			$r_anio = $anio;
			$r_mes = "";
			if($n_mes <= 0){
				//para controlar los mensajes referidos a la generación de consumo para el mes ya generado(-2 mes), y para el mes que falta generar(-1 mes). 
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
				"dia"   => date("d"),
				"hora" 	=> date("H"),
				"minuto" 	=> date("i"),
				"segundo" 	=> date("s"),
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
              // $query = "SELECT * FROM factura_recibo WHERE suministro_cod_suministro like '%" . $valor . "%'";
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

        public function listaRservicio($valorS){

            if (!empty($valorS)) {

                $conexion = mainModel::connect();
                $query1 ="SELECT a.idfactura_servicio,f.nombre,f.apellido,s.cod_suministro,s.direccion,a.anio,a.mes,a.a_nombre,a.monto_pagado,a.total_pago,a.esta_cancelado
                            FROM ((factura_servicio a INNER JOIN suministro s ON a.suministro_cod_suministro = s.cod_suministro)
                            INNER JOIN asociado f ON f.dni = s.asociado_dni) WHERE a.suministro_cod_suministro  like '%" . $valorS . "%' OR f.nombre  like '%" . $valorS . "%' OR s.direccion  like '%" . $valorS . "%'";

                $results = mainModel::execute_single_query($query1);

                $registroModal = [];
                while($dataModal = $results->fetch()){
                    $registroModal[] = [
                        "idfacts"=>$dataModal['idfactura_servicio'],
                        "nombreAS"=>$dataModal['nombre'],
                        "apellidoAS"=>$dataModal['apellido'],
                        "codSum"=>$dataModal['cod_suministro'],
                        "direSR"=>$dataModal['direccion'],
                        "anio"=>$dataModal['anio'],
                        "mes"=>$dataModal['mes'],
                        "anombre"=>$dataModal['a_nombre'],
                        "cancel"=>$dataModal['esta_cancelado']
                    ];

                }

                return $registroModal;

            }



		}
		
		/**
		 * FUNCION que inserta los consumos del mes anterior para los suministros sin medidor
		 */
		public function insertarConsumoSnMController(){
			/**
			 * Obtener los registros de todos los sumi. que no tengan Medidor.
			 * Generar los codigos para la factura_recibo. (cod_sum + anio + mes)
			 * insertar los datos e la taba factura. 
			 */
			//(x)FALTA TERMINAR CON LAS FECHAS... ANALIZAR PARA MES DE ENERO - OJO-> Parece que ya se Creo una solución con un metodo para fechas escrito anteriormente <-OJO. 
			$fecha_actual = self::consultar_fecha_actual();

			$Diasum = $fecha_actual['dia'] + 10;
			$dia_v = ($Diasum<28)? $Diasum:28;

			$anio_hoy=$fecha_actual['anio'];
			$mes_hoy=$fecha_actual['mes'];
			$FConsumo = self::obtenerFechasConsumo($anio_hoy,$mes_hoy);
			
			$query = "SELECT * FROM suministro WHERE tiene_medidor = 0";
			$regSumiSnMed = mainModel::execute_single_query($query);

			$Datos = array(
				"codigos" => [
					'suministro'=>[],					
				],
				"datosAdi"=>[
					'anio'=>$FConsumo['anio_GC'],
					'mes'=>$FConsumo['mes_GC'],
					'fecha_e'=>"{$fecha_actual['anio']}-{$fecha_actual['mes']}-{$fecha_actual['dia']}",
					'hora_e'=>"{$fecha_actual['hora']}:{$fecha_actual['minuto']}:{$fecha_actual['segundo']}",
					'fecha_v'=>"{$fecha_actual['anio']}-{$fecha_actual['mes']}-{$dia_v}",
					'consumo'=>0,
					'monto'=>4.2
				]);

			$listCodSum = [];			
			while($regis = $regSumiSnMed->fetch()){
				$listCodSum[] = $regis['cod_suministro'];				
			}
			$Datos['codigos']['suministro'] = $listCodSum;	
			
			$result = adminModel::insertarConsumoSnMModel($Datos);

			return $result;
		}

		public function datosSumiAsocController($codigoSum){
			/*	antiguoo
				SELECT suministro.cod_suministro, suministro.direccion, suministro.pasaje, suministro.casa_nro, asociado.nombre, asociado.apellido 
					FROM suministro INNER JOIN asociado ON suministro.asociado_dni = asociado.dni WHERE suministro.tiene_medidor = 0  AND suministro.cod_suministro LIKE '%$codigoSum%'
				----->>>
				SELECT * FROM suministro 
				LEFT JOIN factura_recibo 
				ON suministro.cod_suministro = factura_recibo.suministro_cod_suministro 
				WHERE suministro.tiene_medidor=1 AND suministro.estado_corte = 0 AND 
				suministro.cod_suministro NOT IN (SELECT factura_recibo.suministro_cod_suministro 
				FROM factura_recibo WHERE factura_recibo.mes = 10 AND factura_recibo.anio = 2019)
				----------
				SELECT * FROM asociado INNER JOIN suministro ON asociado.dni=suministro.asociado_dni LEFT JOIN factura_recibo ON suministro.cod_suministro = factura_recibo.suministro_cod_suministro WHERE suministro.cod_suministro LIKE '%74%' AND suministro.tiene_medidor=1 AND suministro.estado_corte = 0 AND suministro.cod_suministro NOT IN (SELECT factura_recibo.suministro_cod_suministro FROM factura_recibo WHERE factura_recibo.mes = 10 AND factura_recibo.anio = 2019)
				-----------
				SELECT * FROM asociado INNER JOIN suministro ON asociado.dni=suministro.asociado_dni LEFT JOIN factura_recibo ON suministro.cod_suministro = factura_recibo.suministro_cod_suministro WHERE suministro.cod_suministro LIKE '%74%' AND suministro.tiene_medidor=1 AND suministro.estado_corte = 0 AND suministro.cod_suministro NOT IN (SELECT factura_recibo.suministro_cod_suministro FROM factura_recibo INNER JOIN suministro ON factura_recibo.suministro_cod_suministro = suministro.cod_suministro WHERE suministro.tiene_medidor=1 AND suministro.estado_corte=0 AND factura_recibo.mes = 10 AND factura_recibo.anio = 2019)
				-----
				SELECT suministro.cod_suministro, suministro.direccion, suministro.pasaje, suministro.casa_nro, asociado.nombre, asociado.apellido FROM asociado INNER JOIN suministro ON asociado.dni=suministro.asociado_dni LEFT JOIN factura_recibo ON suministro.cod_suministro = factura_recibo.suministro_cod_suministro WHERE suministro.cod_suministro LIKE '%74%' AND suministro.tiene_medidor=1 AND suministro.estado_corte = 0 AND suministro.cod_suministro NOT IN (SELECT factura_recibo.suministro_cod_suministro FROM factura_recibo INNER JOIN suministro ON factura_recibo.suministro_cod_suministro = suministro.cod_suministro WHERE suministro.tiene_medidor=1 AND suministro.estado_corte=0 AND factura_recibo.mes = 10 AND factura_recibo.anio = 2019)
				*/
			//comprueba si aún falta suministros con medidor por registrar.
			if(self::completadoGCSumCnM()){
				return "LISTO";
			}

			$fecha_actual = self::consultar_fecha_actual();
			$anio_hoy=$fecha_actual['anio'];
			$mes_hoy=$fecha_actual['mes'];
			$FConsumo = self::obtenerFechasConsumo($anio_hoy,$mes_hoy);

			$query = "SELECT suministro.cod_suministro, suministro.direccion, suministro.pasaje, suministro.casa_nro, suministro.categoria_suministro, asociado.nombre, asociado.apellido 
					FROM asociado INNER JOIN suministro ON asociado.dni=suministro.asociado_dni 
					LEFT JOIN factura_recibo ON suministro.cod_suministro = factura_recibo.suministro_cod_suministro 
					WHERE suministro.cod_suministro LIKE '%{$codigoSum}%' AND suministro.tiene_medidor=1 AND suministro.estado_corte = 0 
					AND suministro.cod_suministro NOT IN (SELECT factura_recibo.suministro_cod_suministro 
					FROM factura_recibo INNER JOIN suministro 
					ON factura_recibo.suministro_cod_suministro = suministro.cod_suministro 
					WHERE suministro.tiene_medidor=1 AND suministro.estado_corte=0 
					AND factura_recibo.mes = {$FConsumo['mes_GC']} AND factura_recibo.anio = {$FConsumo['anio_GC']}) LIMIT 0,5";			
			$regSumiCnMed = mainModel::execute_single_query($query);
			
			$rsptRegist = [];						
			while($rgs = $regSumiCnMed->fetch()){
				$consm_ant = self::obtenerConsumoAnterior($rgs['cod_suministro'],$FConsumo['anio_GC'],$FConsumo['mes_GC']);
				$rsptRegist[] = [
					"codigo_sum"=>$rgs['cod_suministro'],
					"direccion"=>$rgs['direccion'],
					"pasaje"=>$rgs['pasaje'],
					"casa_nro"=>$rgs['casa_nro'],
					"nombre"=>$rgs['nombre'],
					"apellido"=>$rgs['apellido'],
					"categoria"=>$rgs['categoria_suministro'],
					"consm_ant"=>$consm_ant
				];
			}		
			
			return $rsptRegist;
		}

		public function obtenerConsumoAnterior($cod_sum,$anio,$mes){
			$resltConsm=0;
			$FCAnterior = self::obtenerFechasConsumo($anio,$mes);
			$query = "SELECT factura_recibo.consumo FROM factura_recibo 
				WHERE factura_recibo.suministro_cod_suministro = '{$cod_sum}' 
				AND factura_recibo.anio = {$FCAnterior['anio_GC']} AND factura_recibo.mes={$FCAnterior['mes_GC']}
			";
			$dataConsm = mainModel::execute_single_query($query);
			if($dataConsm->rowCount()==1){
				$regConsm = $dataConsm->fetch();
				$resltConsm = $regConsm['consumo'];
			}
			return $resltConsm;
		}

		public function insertarCSumCnMController($dataController){
			
			
			$fecha_actual = self::consultar_fecha_actual();

			$Diasum = $fecha_actual['dia'] + 10;
			$dia_v = ($Diasum<28)? $Diasum:28;

			$anio_hoy=$fecha_actual['anio'];
			$mes_hoy=$fecha_actual['mes'];
			$FConsumo = self::obtenerFechasConsumo($anio_hoy,$mes_hoy);

			$dataModal = [				
				'anio'=>$FConsumo['anio_GC'],
				'mes'=>$FConsumo['mes_GC'],
				'fecha_e'=>"{$fecha_actual['anio']}-{$fecha_actual['mes']}-{$fecha_actual['dia']}",
				'hora_e'=>"{$fecha_actual['hora']}:{$fecha_actual['minuto']}:{$fecha_actual['segundo']}",
				'fecha_v'=>"{$fecha_actual['anio']}-{$fecha_actual['mes']}-{$dia_v}",
				'consumo'=>$dataController['consumo'],
				'monto'=>$dataController['monto'],
				'cod_sum'=>$dataController['cod_sum']
			];			


			$responseModel = adminModel::insertarCSumCnMModel($dataModal);
			return $responseModel;

		}

		protected function completadoGCSumCnM(){
			$fecha_actual = self::consultar_fecha_actual();
			$anio_hoy=$fecha_actual['anio'];
			$mes_hoy=$fecha_actual['mes'];
			$FConsumo = self::obtenerFechasConsumo($anio_hoy,$mes_hoy);

			$query = "SELECT suministro.cod_suministro FROM suministro 
					WHERE suministro.tiene_medidor=1 AND suministro.estado_corte = 0 AND suministro.cod_suministro
					NOT IN (SELECT factura_recibo.suministro_cod_suministro FROM factura_recibo 
					INNER JOIN suministro ON factura_recibo.suministro_cod_suministro = suministro.cod_suministro 
					WHERE suministro.tiene_medidor=1 AND suministro.estado_corte=0 
					AND factura_recibo.mes = {$FConsumo['mes_GC']} AND factura_recibo.anio = {$FConsumo['anio_GC']})";
			$regResult = mainModel::execute_single_query($query);
			if($regResult->rowCount() == 0){
				$rs = adminModel::actualizarEGcnConsumoModel();
				return true;
			}
			return false;
		}

		public function obtenerRegXDirecController($data){
			$query = "SELECT suministro.direccion FROM suministro WHERE suministro.direccion LIKE '%{$data['direccion']}%' GROUP BY suministro.direccion LIMIT 0, 4";
			$result = mainModel::execute_single_query($query);

			$arrDirec = [];
			while($reg = $result->fetch()){
				$arrDirec[] = [
					"direccion"=>$reg['direccion']
				];
			}			
			return $arrDirec;
		}		


	}
