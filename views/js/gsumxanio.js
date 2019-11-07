console.log("gsumxanio.js");

generarSumxAnio();

function generarSumxAnio(){
    let el = document.querySelector("#bscSumxAniok");
    if(el){
        let chbximprimir = document.querySelector('#chbxImprimir');
        Listsumxanio('',chbximprimir.checked);
        el.addEventListener('keyup',function(){              
            console.log("lala->",chbximprimir.checked);        
            let inputBsc = this.value;          
            Listsumxanio(inputBsc,chbximprimir.checked)
        })
    }
}
function Listsumxanio($inputBsc,$imprimir){

    let data = new FormData();
    data.append('OPTION','bscSumxAnio');
    data.append('inputBsc',$inputBsc);
    data.append('chbximprimir',$imprimir);
    fetch('../ajax/gestionRcbAjax.php',{
        method:'POST',
        body:data
    }).then(res=>res.json())
    .then(data=>{
        //console.log(data);        
        let cont = 0;
        let html = ``;
        data.forEach(element => {
            html +=`
			<tr>
				<td>${++cont}</td>
				<td>${element.cod_suministro}</td>
				<td class="text-center">${element.contador_deuda==0?"<span class='text-info'>No</span>":"<span class='text-danger'>"+element.contador_deuda+"</span>"}</td>
				<td>${element.nombre} ${element.apellido}</td>
				<td>${element.categoria_suministro}</td>
                <td>${element.direccion}</td>
                <td class="text-center">${element.contador_deuda==0?"<a href='../reportes/rxp.php?codigoSum="+element.cod_suministro+"' target='_blank' class='btn btn-info btn-raised btn-xs'>G. Recibo</a>":"<span class='text-danger'>---</span>"}</td>
                <td class="text-center">
                    ${ (element.contador_deuda==0 && $imprimir==false)?
                        `<a href="#" class="btn btn-danger btn-raised btn-xs" id="btnCobrarxAnio" onclick="cobrarXanio(this,'${element.cod_suministro}')">Cobrar</a>` 
                        : `<span class='text-danger'>---</span>`
                    }
                </td>				
			</tr>			
			`;
        });
        document.querySelector('#tblSumxAnio').innerHTML=html;
    })
}


function cobrarXanio($this,$cod_sum){
    //console.log($this);
    let fechaHoy = mesAnioActual();//Note: 0=January, 1=February etc.    
    let monto = calcularMonto(fechaHoy['n_mes']);
    let lmes_del = NombreMes(fechaHoy['n_mes']);
    let lmes_al = NombreMes(12);
    swal({
		title: "¿Realizar cobro?",
		text: "<p>Desde <b>"+lmes_del+"</b> hasta <b>"+lmes_al+"</b></p>SUMINISTRO: <b>" + $cod_sum + "</b><br>COBRAR:    <b>S/ " + monto +" Soles</b>",
		type: "info",				
		confirmButtonColor: '#03A9F4',		  		
		confirmButtonText: '<i class="zmdi zmdi-run"></i> Aceptar',		
		showCancelButton: true,
		cancelButtonColor: '#F44336',
		cancelButtonText: '<i class="zmdi zmdi-close-circle"></i> Cancel'		
	}).then(()=>{
		//Cuando le de la opción de ok
        console.log("le dió aceptar")
        let data = new FormData();
        data.append('OPTION','cobrarXanio');
        data.append('cod_sum',$cod_sum);
        data.append('del_mes',fechaHoy['n_mes']);
        data.append('anio',fechaHoy['anio']);
        data.append('monto',monto);
        fetch('../ajax/gestionRcbAjax.php',{
            method:'post',
            body:data
        }).then(res => res.json())
        .then(data=>{
            console.log(data);
            let chbximprimir = document.querySelector('#chbxImprimir');
            Listsumxanio('',chbximprimir.checked);

        })

	},function(){
		//si no le da a la opcion de aceptar
		console.log("No le dió aceptar")
	});
}

function mesAnioActual() {
    let d = new Date();
    let n_mes = d.getMonth();
    let anio = d.getFullYear();
    n_mes++;
    return {'n_mes':n_mes,'anio':anio};
}
function calcularMonto(del_mes){
    let al_mes = 12;
    let cant_mss = (al_mes - del_mes)+1;
    let monto = 4.2*cant_mss;
    monto = monto.toFixed(1);
    return monto;
}