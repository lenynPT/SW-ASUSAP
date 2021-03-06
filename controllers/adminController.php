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
						return ["res" => "success"];
					}else{
						//echo "No se pudo registrar";
						return ["res"=>"error"];
					}
					//return true;
				}else{
					//echo "DNI ya registardo";
					return ["res"=>"dniExist"];
				}

            }

		}

	/*================================GENERADOR DE SUMINISTRO============================================*/
		public function pruebaController($msj){
			return $msj;
		}

	/*================================GENERADOR DE CONSUMO============================================*/

	/*================================GENERADOR DE SUMINISTRO============================================*/

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
					$n_mes = 12;
					$r_mes = "Diciembre";
					$r_anio -=1;
					return ["r_mes"=>$r_mes,"r_anio"=>$r_anio,"n_mes"=>$n_mes];
				}else{ // cuando sea enero y ya e hayan generado todos los consumos.
					$n_mes = 11;
					$r_mes = "Noviembre";
					$r_anio -=1;
					return ["r_mes"=>$r_mes,"r_anio"=>$r_anio,"n_mes"=>$n_mes];
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
					# code... por defeecto... sn efecto --error:
					$n_mes=12;
					$r_mes = "Diciembre";
					$r_anio -=1;
					break;
			}
			return ["r_mes"=>$r_mes,"r_anio"=>$r_anio,"n_mes"=>$n_mes];
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
			
			$query = "SELECT cod_suministro FROM suministro 
			WHERE tiene_medidor = 0 AND estado_corte = 0
			AND suministro.cod_suministro NOT IN (SELECT factura_recibo_anio.cod_sum_anio FROM factura_recibo_anio 
			WHERE factura_recibo_anio.anio = $anio_hoy) LIMIT 0,10";
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
				
				/**
				 * Esto se debe de hacer en el Modelo por cuestiones de orden 
				 * Funcion que actualiza la deuda - Cuándo haya tiempo se debe actualizar				 
				 */
				$ok = self::actualizarContadorDeudaController($regis['cod_suministro']);			
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

			$query = "SELECT suministro.cod_suministro, suministro.direccion, suministro.pasaje, suministro.categoria_suministro, suministro.contador_deuda, asociado.nombre, asociado.apellido 
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
					"contador_deuda"=>$rgs['contador_deuda'],
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
		//cuando se realiza inserción de consumo
		public function actualizarContadorDeudaController($cod_sum){
			$cont_deuda=0;
			$query = "SELECT suministro.contador_deuda FROM suministro WHERE suministro.cod_suministro =  '{$cod_sum}'";
			$reg_deuda = mainModel::execute_single_query($query);
			if($reg_deuda->rowCount()==1){
				$rsdeud = $reg_deuda->fetch();
				$cont_deuda = $rsdeud['contador_deuda'];
				$cont_deuda++;
			}
			$query2 = "UPDATE suministro SET contador_deuda = $cont_deuda WHERE cod_suministro = '{$cod_sum}'";
			$resqr2 = mainModel::execute_single_query($query2);
			if($cont_deuda >= 3){
				$query3 = "UPDATE suministro SET estado_corte = 1 WHERE cod_suministro = '{$cod_sum}'";
				$resqr3 = mainModel::execute_single_query($query3);
			}
			return $cont_deuda;
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
			$query = "SELECT suministro.direccion FROM suministro WHERE suministro.direccion LIKE '%{$data['direccion']}%' GROUP BY suministro.direccion LIMIT 0, 5";
			$result = mainModel::execute_single_query($query);

			$arrDirec = [];
			while($reg = $result->fetch()){
				$arrDirec[] = [
					"direccion"=>$reg['direccion']
				];
			}			
			return $arrDirec;
		}		


		//consulta de suministros con corte
		public function obtenerRegSumCnCorteController($cod_sum){
			$query = "SELECT asociado.nombre,asociado.apellido, asociado.telefono, 
			suministro.cod_suministro, suministro.direccion, suministro.categoria_suministro, suministro.contador_deuda 
			FROM suministro INNER JOIN asociado ON suministro.asociado_dni = asociado.dni 
			WHERE asociado.estado = 1 AND suministro.estado_corte=1 AND suministro.cod_suministro LIKE '%$cod_sum%' LIMIT 0,15";
			//$query = "SELECT * FROM suministro WHERE estado_corte=1 AND cod_suministro LIKE '%$cod_sum%' LIMIT 0,15";
			$arrReg = mainModel::execute_single_query($query);
			$responseStruc = [];
			while($reg = $arrReg->fetch(PDO::FETCH_ASSOC)){
				$responseStruc[] = $reg;
			}
			return $responseStruc;
		}

		//GR emitir recibo
		public function obtenerSumGRxCod($data){			
			$query = "SELECT asociado.dni,suministro.tiene_medidor,suministro.estado_corte,factura_recibo.anio,factura_recibo.mes,factura_recibo.consumo,factura_recibo.monto_pagar,factura_recibo.esta_cancelado,factura_recibo.suministro_cod_suministro 
				FROM factura_recibo INNER JOIN suministro ON factura_recibo.suministro_cod_suministro=suministro.cod_suministro
				INNER JOIN asociado ON asociado.dni = suministro.asociado_dni 
				WHERE suministro.estado_corte<>2 AND factura_recibo.anio={$data['anio']} AND factura_recibo.mes={$data['mes']} AND factura_recibo.suministro_cod_suministro LIKE '%{$data['cod_sum']}%' limit 0,3";
			$arrReg = mainModel::execute_single_query($query);
			$respData = [];
			while($reg = $arrReg->fetch(PDO::FETCH_ASSOC)){
				$respData[] = $reg;
			}
			return $respData;
		}

		//
		public function recibosObtenerDataSumXdirec($direccion,$anio,$mes){
			$direccion = trim($direccion);
			$query="SELECT asociado.dni,asociado.nombre,asociado.apellido,suministro.cod_suministro,suministro.direccion,
			 suministro.estado_corte,suministro.tiene_medidor,suministro.categoria_suministro,suministro.contador_deuda,
			 factura_recibo.anio,factura_recibo.mes,factura_recibo.fecha_emision,factura_recibo.fecha_vencimiento,
			 factura_recibo.consumo, factura_recibo.monto_pagar,factura_recibo.esta_cancelado,factura_recibo.esta_impreso 
			 FROM asociado INNER JOIN suministro ON asociado.dni=suministro.asociado_dni 
			 INNER JOIN factura_recibo ON factura_recibo.suministro_cod_suministro=suministro.cod_suministro 
			 WHERE suministro.estado_corte<>2 AND suministro.direccion = '$direccion' AND
			  factura_recibo.anio=$anio AND factura_recibo.mes=$mes ORDER BY asociado.apellido";
			//$query = "SELECT * FROM suministro WHERE direccion='{$direccion}'";
			$arrData = mainModel::execute_single_query($query);
			if($arrData->rowCount()>0){
				return ['res'=>true, 'data'=>$arrData];
			}
			return ['res'=>false,'data'=>[]];
		}

		//RG
		public function recibosObtenerDataSumXCod($cod_sum,$anio,$mes){
			
			$cod_sum = trim($cod_sum);
			$query="SELECT asociado.dni,asociado.nombre,asociado.apellido,suministro.cod_suministro,suministro.direccion,
			 suministro.estado_corte,suministro.tiene_medidor,suministro.categoria_suministro,suministro.contador_deuda,
			 factura_recibo.anio,factura_recibo.mes,factura_recibo.fecha_emision,factura_recibo.fecha_vencimiento,
			 factura_recibo.consumo, factura_recibo.monto_pagar,factura_recibo.esta_cancelado,factura_recibo.esta_impreso 
			 FROM asociado INNER JOIN suministro ON asociado.dni=suministro.asociado_dni 
			 INNER JOIN factura_recibo ON factura_recibo.suministro_cod_suministro=suministro.cod_suministro 
			 WHERE suministro.estado_corte<>2 AND suministro.cod_suministro = '$cod_sum' AND
			  factura_recibo.anio=$anio AND factura_recibo.mes=$mes";

			$arrData = mainModel::execute_single_query($query);
			if($arrData->rowCount()>0){
				return ['res'=>true, 'data'=>$arrData];
			}
			return ['res'=>false,'data'=>[]];			
		}

		//function para COBRAR
		public function obtenerSumParaCobrar($cod_sum){

			$query = "SELECT * FROM suministro INNER JOIN factura_recibo ON factura_recibo.suministro_cod_suministro = suministro.cod_suministro
						WHERE factura_recibo.esta_cancelado=0 AND factura_recibo.suministro_cod_suministro LIKE '%$cod_sum%' LIMIT 0,10";
			$arrData = mainModel::execute_single_query($query);
			$arrResp = [];
			while($reg = $arrData->fetch(PDO::FETCH_ASSOC)){
				$arrResp[] = $reg;
			}
			return $arrResp;
		}

		public function cobrarRecibo($cod_sum,$anio,$mes){
			//$query = "SELECT * FROM suministro WHERE suministro.estado_corte=1 AND suministro.contador_deuda=0";
			$query = "UPDATE factura_recibo SET esta_cancelado = 1, esta_impreso = 1
					WHERE factura_recibo.suministro_cod_suministro = '$cod_sum' AND factura_recibo.anio = $anio AND factura_recibo.mes = $mes";
			$arrData = mainModel::execute_single_query($query);
			if($arrData){
				$resCont = self::reducirContadorDeuda($cod_sum);
			}
			return $arrData;			
		}

		public function reducirContadorDeuda($cod_sum){			
			$query = "SELECT contador_deuda FROM suministro WHERE cod_suministro = '$cod_sum'";
			$queryRes = mainModel::execute_single_query($query);
			$dbCont = $queryRes->fetch();
			$contador_deuda = $dbCont['contador_deuda'];
			$contador_deuda--;
			
			//Actualizando corte de manera automática
			if($contador_deuda == 0){
				$query0 = "UPDATE suministro SET estado_corte=$contador_deuda WHERE suministro.cod_suministro='$cod_sum'";
				$queryRes0 = mainModel::execute_single_query($query0);
			}
			//actualizando contador de deudas
			$query1 = "UPDATE suministro SET contador_deuda=$contador_deuda WHERE suministro.cod_suministro='$cod_sum'";
			$queryRes1 = mainModel::execute_single_query($query1);

			return true;
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

        public function mostImpItem($cod_sum){



            $cod_sum = trim($cod_sum);
            $query1 ="SELECT descripcion,costo FROM 
                 suministro   WHERE s.cod_suministro  like '%" . $cod_sum . "%' ";
            $results = mainModel::execute_single_query($query1);

            $registroModal = [];
            while($dataModal = $results->fetchAll()){
                $registroModal[] = [
                    "desItems"=>$dataModal['descripcion'],
                    "costItems"=>$dataModal['costo']
                ];

            }

            return $registroModal;
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

        /*##########################SELECTORES DEL SERVICIO###############################*/
        public function SelectorS(){


            $conexion=mainModel::connect();

            $datos=$conexion->query(" SELECT * FROM servicio");

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
                $query1="SELECT idfactura_servicio,a_nombre,suministro_cod_suministro,anio,mes,fecha,total_pago,mont_restante FROM factura_servicio WHERE idfactura_servicio like '%" . $valorS . "%' OR a_nombre like '%" . $valorS . "%'OR  suministro_cod_suministro like '%" . $valorS . "%'";
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
                        "totalPago"=>$dataModal['total_pago'],
                        "mont_Resta"=>$dataModal['mont_restante']
                    ];

                }

                return $registroModal;

            }
        }


        /*-------------------------------------*/

        public function vistaAmortizarController($pagina,$registros){

            $tabla="";

            $pagina= (isset($pagina)&&$pagina>0) ?(int)$pagina:1;
            //------------contador de datos en la base de datos---------------------
            $inicio=($pagina>0) ?(($pagina*$registros)-$registros) :0;

            $conexion=mainModel::connect();

            $datos=$conexion->query("
        SELECT SQL_CALC_FOUND_ROWS * FROM factura_servicio WHERE idfactura_servicio!='1'
         ORDER BY idfactura_servicio DESC LIMIT $inicio,$registros
        ");

            $datos=$datos->fetchAll();

            $total=$conexion->query("SELECT FOUND_ROWS()");
            $total=(int)$total->fetchColumn();
            //total de numeros de paginas
            $Npaginas=ceil($total/$registros);

            /*-------------------------paginando en una lista---------------------------*/
            $tabla.='<div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">ID</th>
                        <th class="text-center">A nombre</th>
                        <th class="text-center">SUMINISTRO</th>


                        <th class="text-center">AÑO</th>
                        <th class="text-center">MES</th>
                        <th class="text-center">FECHA</th>
                        <th class="text-center">TOTAL DE PAGO</th>
                        <th class="text-center">MONT RESTANTE</th>
                    </tr>
                    </thead>
                    <tbody  class="BusquedaRapida">
                    ';
            if ($total>=1 && $pagina<=$Npaginas){
                $contador=$inicio+1;
                foreach ($datos as $rows){

                    if ($rows['mont_restante'] != 0){

                    $tabla.='<tr>
                            <td>'.$contador.'</td>
                            <td>'.$rows['idfactura_servicio'].'</td>
                            <td>'.$rows['a_nombre'].'</td>
                            <td>'.$rows['suministro_cod_suministro'].'</td>
                            <td>'.$rows['anio'].'</td>
                            <td>'.$rows['mes'].'</td>
                            <td>'.$rows['fecha'].'</td>
                            <td>'.$rows['total_pago'].'</td>
                            <td>'.$rows['mont_restante'].'</td>
                        </tr>
                        ';
                    $contador++;
                    }


                }
            }else{
                /*---------------------para eliminar el mensaje que muestra-----------------------*/
                if($total>=1){
                    $tabla.='
            <tr>
                <td colspan="5"></td>

            </tr>
            ';
                }else{
                    $tabla.='
            <tr>
                <td colspan="5">No hay registros en el sistema</td>

            </tr>
            ';
                }

            }
            $tabla.='</tbody></table></div>';


            /*.--------------PAGINADOR--------------------*/

            if ($total>=1 && $pagina<=$Npaginas){
                $tabla.='<nav class="text-center">
                        <ul class="pagination pagination-sm">';

                if ($pagina==1){
                    $tabla.='<li class="disabled"><a ><i class="zmdi zmdi-arrow-left"></i></a></li>';
                }else{
                    $tabla.='<li ><a href="'.SERVERURL.'aservicio/'.($pagina-1).'"><i class="zmdi zmdi-arrow-left"></i></a></li>';
                }

                for ($i=1;$i<=$Npaginas;$i++){

                    if ($pagina==$i){
                        $tabla.='<li class="active"><a href="'.SERVERURL.'aservicio/'.$i.'">'.$i.'</a></li>';
                    }
                    else{
                        $tabla.='<li ><a href="'.SERVERURL.'aservicio/'.$i.'">'.$i.'</a></li>';


                    }
                }

                if ($pagina==$Npaginas){
                    $tabla.='<li class="disabled"><a ><i class="zmdi zmdi-arrow-right"></i></a></li>';
                }else{
                    $tabla.='<li ><a href="'.SERVERURL.'aservicio/'.($pagina+1).'"><i class="zmdi zmdi-arrow-right"></i></a></li>';

                }

                $tabla.='</ul></nav>';
            }








            return  $tabla;



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

		/*================================ IMPRESION RECIBOS ============================================*/ 
		public function consultaDeudasMes($cod_sum){
			$query = "SELECT factura_recibo.suministro_cod_suministro,factura_recibo.anio,factura_recibo.mes,factura_recibo.esta_cancelado 
			FROM factura_recibo 
			WHERE factura_recibo.esta_cancelado=0 AND factura_recibo.suministro_cod_suministro='$cod_sum'";

			$resArr = mainModel::execute_single_query($query);
			$arrData = [];
			while($reg = $resArr->fetch(PDO::FETCH_ASSOC)){				
				$arrData[] = $reg;
			}

			return $arrData;
		}


		/*================================ GENERAR X ANIOS PARA SUM SIN MEDIDOR ============================================*/ 
		public function obtenerSumSnMxAnioController($inputBsc,$imprimir){
			//Esta función es para poder generar los consumo por AÑO
			$fecha_hoy = self::consultar_fecha_actual();

			if($imprimir=='true'){
				$query = "SELECT suministro.cod_suministro,suministro.direccion,suministro.categoria_suministro,suministro.contador_deuda, asociado.nombre,asociado.apellido
				FROM asociado INNER JOIN suministro ON suministro.asociado_dni = asociado.dni 
				INNER JOIN factura_recibo_anio ON factura_recibo_anio.cod_sum_anio=suministro.cod_suministro
				WHERE factura_recibo_anio.anio = {$fecha_hoy['anio']} AND suministro.cod_suministro LIKE '%$inputBsc%'";
			}else{
				$query = "SELECT suministro.cod_suministro,suministro.direccion,suministro.categoria_suministro,suministro.contador_deuda, asociado.nombre,asociado.apellido
				FROM suministro INNER JOIN asociado ON suministro.asociado_dni = asociado.dni 
				WHERE suministro.estado_corte=0 AND suministro.tiene_medidor=0 AND suministro.cod_suministro LIKE '%$inputBsc%'
				AND suministro.cod_suministro NOT IN 
				(SELECT factura_recibo_anio.cod_sum_anio FROM factura_recibo_anio WHERE factura_recibo_anio.anio={$fecha_hoy['anio']})";				
			}
			$resArr = mainModel::execute_single_query($query);
			$arrData = [];
			while($reg = $resArr->fetch(PDO::FETCH_ASSOC)){				
				$arrData[] = $reg;
			}

			return $arrData;
		}
		
		public function cobrarSumSnMxAnioController($data){
			//insertar datos en la tabla de años
			$fecha_actual = self::consultar_fecha_actual();
			$dia_v = 28;
			$fecha_e = "{$fecha_actual['anio']}-{$fecha_actual['mes']}-{$fecha_actual['dia']}";
			$hora_e = "{$fecha_actual['hora']}:{$fecha_actual['minuto']}:{$fecha_actual['segundo']}";
			$fecha_v = "{$fecha_actual['anio']}-{$fecha_actual['mes']}-{$dia_v}";

			$query1 = "INSERT INTO factura_recibo_anio(cod_sum_anio,anio,del_mes,al_mes,monto) 
			VALUES ('{$data['cod_sum']}',{$data['anio']},{$data['del_mes']},12,{$data['monto']})";
			$resInsrt1 = mainModel::execute_single_query($query1);

			for ($del_mes=intval($data['del_mes']); $del_mes <=12 ; $del_mes++) { 
				# code...	
				$query2 = "INSERT INTO factura_recibo (idfactura_recibo, anio, mes, fecha_emision, hora_emision, fecha_vencimiento, consumo, monto_pagar, esta_cancelado, esta_impreso, suministro_cod_suministro) 
				VALUES (NULL, {$data['anio']}, {$del_mes}, '{$fecha_e}', '{$hora_e}', '{$fecha_v}', 0, {$data['monto']}, 1, 1, '{$data['cod_sum']}')";
				$resInsrt2 = mainModel::execute_single_query($query2);

			}

			return "Ready";
		}

		public function dataReciboXanio($cod_sum){
			$fecha_hoy = self::consultar_fecha_actual();
			$query = "SELECT factura_recibo_anio.cod_sum_anio,factura_recibo_anio.anio,factura_recibo_anio.del_mes,factura_recibo_anio.monto,suministro.direccion,suministro.categoria_suministro,asociado.nombre,asociado.apellido,asociado.telefono 
			FROM factura_recibo_anio INNER JOIN suministro 
			ON suministro.cod_suministro=factura_recibo_anio.cod_sum_anio INNER JOIN asociado 
			ON asociado.dni=suministro.asociado_dni 
			WHERE suministro.cod_suministro='$cod_sum' AND factura_recibo_anio.anio={$fecha_hoy['anio']}";
			$response = mainModel::execute_single_query($query);
			if($response->rowCount()){
				$exist = true;
				$arrData = $response->fetch(PDO::FETCH_ASSOC);
			}else {
				$exist = false;
				$arrData = [];
			}			
			return ['res'=>$exist,'data'=>$arrData];
		}
	}
