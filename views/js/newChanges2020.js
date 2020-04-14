
function habilitarConsumos(){

    //MENSAJE DE ALERTA PARA HABILITAR EL CONSUMO
    swal({
        title: "¿Habilitar Consumo?",
        type: "warning",				
        confirmButtonColor: '#03A9F4',		  		
        confirmButtonText: '<i class="zmdi zmdi-run"></i> Aceptar',		
        showCancelButton: true,
        cancelButtonColor: '#F44336',
        cancelButtonText: '<i class="zmdi zmdi-close-circle"></i> Cancel'		
    }).then(()=>{
        //Cuando le de la opción de ok
        console.log("le dio aceptar");

        ajaxKev('POST',{
            OPTION:'habilitaConsumo'
        }, data =>{
            console.log(data);        
            if(data.eval){
                location.reload();
            }
        })

    },function(){
        //si no le da a la opcion de aceptar
        console.log("No le dió aceptar")
    });    


}





/**
 * 
 * @param {String} metodo 
 * @param {Object} datajson 
 * @param {Function} bloqueCode 
 * 
 * Función ajax modificado 
 */
function ajaxKev(metodo, datajson, bloqueCode){

    let method = metodo.toUpperCase().trim();
    let envget,envpost;
    if(method === "POST"){
        envpost = "data=" + JSON.stringify(datajson);
        envget = "";
    }else if (method === "GET"){
        envpost = "";
        envget = "?"+"data=" + JSON.stringify(datajson);
    }else{
        method = "POST";
        envpost = "data=" + JSON.stringify(datajson);
        envget = "";
    }
    
    let xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){            
            let data = JSON.parse(this.responseText);            
            bloqueCode(data);
        }
    }
    xhr.open(method, "../ajax/procesarAjax2020.php"+envget, true);

    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.send(envpost);
}