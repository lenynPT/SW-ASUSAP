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
                <td class="text-center">${element.contador_deuda==0 && $imprimir==false?"<a href='#' class='btn btn-danger btn-raised btn-xs'>Cobrar</a>":"<span class='text-danger'>---</span>"}</td>				
			</tr>			
			`;
        });
        document.querySelector('#tblSumxAnio').innerHTML=html;
    })

}