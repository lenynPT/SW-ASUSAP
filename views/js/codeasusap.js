console.log("probando...");

//cuando ingresen código para buscar al asiciado
document.getElementById("btnBuscarAsoc").addEventListener('click',function(){
    
    var usuario = $("#txtBuscarAsoc").val();
    
    var datos = new FormData();
    datos.append("codAsoc",usuario);
    datos.append("btnaccion","buscarAsociado");
    
    $.ajax({
		url:"../ajax/buscarAjax.php",
		method:"POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		success:function(respuesta){
			if(respuesta){

				let datos = JSON.parse(respuesta);
				let datostabla = datos.tabla;
				let datosmodal = datos.modal;
				
				let htmlAsoc = `
					<tr>
						<td>1</td>
						<td>${datostabla["dni"]}</td>
						<td>${datostabla["nombre"]}</td>
						<td>${datostabla["apellido"]}</td>
						<td>${datostabla["telefono"]}</td>
						<td>${datostabla["estado"]}</td>
						<td>${datostabla["cant_suministro"]}</td>
						<td><a href="#cantSumin" data-toggle="modal" dni="485666" class="cantSumin btn btn-success btn-raised btn-xs" ><i class="zmdi zmdi-refresh"></i></a></td>
						<td><a href="#!" class="btn btn-success btn-raised btn-xs"><i class="zmdi zmdi-refresh"></i></a></td>
						<td><a href="#!" class="btn btn-danger btn-raised btn-xs"><i class="zmdi zmdi-delete"></i></a></td>
					</tr>   				
				`; 
				let htmlSumi = ``;
				for (let index = 0; index < datosmodal.length; index++) { //El index tiene que comenzar desde cero. Porque cada uno de los suministros tiene como indice un numeral, y este comienza con el cero.
					
					htmlSumi += `
						<tr>
							<td>${index+1}</td>
							<td>${datosmodal[index]["codigo"]}</td>
							<td>${datosmodal[index]["direccion"]}</td>
							<td>${datosmodal[index]["categoria"]}</td>
							<td>${datosmodal[index]["medidor"]}</td>
							<td>${datosmodal[index]["corte"]}</td>
						</tr>	
					`;
					console.log(htmlSumi);					
				}

				//Escribe datos en el modal del suministro. 
				$("#txtDniAsocModal").val(datostabla["dni"]);
				$("#txtNombreAsocModal").val(datostabla["nombre"]);
				
				//IMPRIME DATOS PARA LOS ASOCIADOS Y SUMINISTROS		
				$(".responseAsoc").html(htmlAsoc);//Escribe datos en la tabla
				$(".responseSumi").html(htmlSumi);//Escribe datos en el modal
				
				//Limpia el msj de no se encontró nada.
				$(".infoAsoc").html("");

				//CONTROL DEL BTN PARA AGREGAR SUMINISTRO
				$('#btnAddSumiModal').attr("disabled", false)
				$('#btnAddSumiModal').attr("data-toggle", "modal")							

			}else{
				//Limpia la tabla de asociado.
				$(".responseAsoc").html("");
				//Imprime msj de error cuando no coincide el dato con los registro de la db
				$(".infoAsoc").html("<div class='text-danger'>No se encontró asociado...</div>");
				
				//CONTROL DEL BTN PARA AGREGAR SUMINISTRO
				$('#btnAddSumiModal').attr("disabled", true)
				$('#btnAddSumiModal').attr("data-toggle", "#")
				
			}
        
		}
	});

});