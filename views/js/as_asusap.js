/*---------------lista al momento hacer la busqueda-------------------*/

function objetoAjax() {
    var xmlhttp=false;
    try{
        xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
    }catch (e) {
        try{
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }catch (e) {
            xmlhttp=false;
        }
    }
    if (!xmlhttp && typeof XMLHttpRequest!='undefined'){
        xmlhttp=new XMLHttpRequest();
    }
    return xmlhttp;
}
function rowRecibo(idrecibo){
    //let d=$('#idfac').val();
    let x=idrecibo.cells;
    let  a = x[1].innerHTML;
    let d=a;
    ajax=objetoAjax();

   // ajax.open("POST","../views/modules/ASupdate-view.php",true);
    ajax.open("POST","../ajax/gestionASERV.php",true);
    ajax.onreadystatechange=function(){
        if (ajax.readyState==4){
            document.getElementById("resultado").innerHTML=ajax.responseText;
        }
    }

    ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax.send("varp2="+d);
    console.log(d);
}

function abrir(id_fila) {
    var myTable = document.getElementById("alltable");
    var rowCount = myTable.rows.length;
    console.log(rowCount)
    var p=$('#'+id_fila);
    var u=p[0].cells[1].innerText;
    console.log("estas en id"+u)


        if($('#'+id_fila).hasClass('seleccionada')){
            $('#'+id_fila).removeClass('seleccionada');
            console.log("has borrado el marcador")
        } else{
            console.log("has seleccionado el marcador")
            $('#'+id_fila).addClass('seleccionada');

        }

    //2702id_fila_selected=id_fila;
    id_fila_selected.push(id_fila);
    var a=$('#'+id_fila);

    var k=a[0].cells[2].innerText;
    console.log(k)
   window.location="http://localhost/SW-ASUSAP/ASupdate/"+u;



}

function listar_as(valor) {

    console.log("click en el buscar amortizae")

    if ($('#buscarAS').val()){
        $('#datos-resultAS').show();
        $.ajax({
            url:"../ajax/gestionASERV.php",
            type:'POST',
            data:'valor='+valor.trim(),
            success:function (resp) {
                let valores = JSON.parse(resp);
                console.log("valores de resp es:"+valores)
                if (valores!="") {


                    htmlSumi = `<table class='table table-bordered' id="alltable"><thead><tr><th>#</th><th>RECIBO</th><th>Nombre Apellido.</th><th>Cod. Sumistro</th><th>Año</th><th>Mes</th><th>Fecha</th><th>Tota Pago</th><th>Mont. restante</th><th>Amortizar </th></tr></thead><tbody>`;

                    for (let i = 0; i < valores.length; i++) {
                        // onclick="rowRecibo(this);"
                        let t = `${valores[i]["mont_Resta"]}`;
                        if (t != 0) {

                            htmlSumi += `
						<tr id="fila` + `${i + 1}` + `" style="background: rgba(243,240,236,0.89);" >
							<td>${i + 1}</td>
							<td style="background: rgb(217, 255, 8);">${valores[i]["idfacts"]}</td>
							<td>${valores[i]["anombre"]}</td>
							<td>${valores[i]["codSum"]}</td>
							<td>${valores[i]["anio"]}</td>
							<td>${valores[i]["mes"]}</td>
							<td>${valores[i]["fechars"]}</td>
							<td style="background:  rgba(217, 191, 241, 0.87);"><b>S/ ${valores[i]["totalPago"]}</b></td>
							<td style="background: rgba(255, 0, 0, 0.31);">S/ ${valores[i]["mont_Resta"]}</td>
                            <td><a href="#!" class="btn btn-info btn-raised btn-xs "   id="fila` + `${i + 1}` + `"   onclick="abrir(this.id);">Amortizar</a></td>
						</tr>	
					`;
                        }
                        // onclick="abrir(`+`${i + 1}`+`)
                        // <td><a href="http://localhost/SW-ASUSAP/ASupdate/?idF=${valores[i]["idfacts"]}" class="btn btn-info btn-raised btn-xs">Amortizar</a></td>

                    }
                    htmlSumi += `</tbody></table>`
                }
                else {
                    htmlSumi = `<p class="text-center"><h2><b>No se encuentra  registrado el ( RECIBO Y NOMBRE)</b></h2></p>`
                }
                $("#containerAS").html(htmlSumi);
                // $('#add-all').hide();
            }

        });
    }else {
        $('#datos-resultAS').hide();
        $('#add-all').hide();
    }

}

/*--------------collapsible------------------*/

//$(document).ready(function() {
    var coll = document.getElementsByClassName("collapsible");

    var i;

    for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function() {

            let codR = $("#toCoRS").val();

            let datos = new FormData();
            datos.append("codARSS",codR);

            $.ajax({
                url:"../ajax/gestionASERV.php",
                method:"POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                success:function (resp) {

                    console.log(resp)

                }

            });

            //console.log(codR)
            this.classList.toggle("active");
            var content = this.nextElementSibling;

            // return AJAX;

            if (content.style.maxHeight){
                content.style.maxHeight = null;

            } else {

                content.style.maxHeight = content.scrollHeight + "px";


            }

        });
    }
//});

function guardarDB(editableObj) {

    let mRST=$('#MONTRT').val();
    let mt=$('#montAll').val();
    let t=$('#montPa').val();
    console.log("-------->",mt,"-",t)
    let mp=parseFloat(mt)-parseFloat(t);
    console.log("-->> = ",mp)

    if (mp>=0 && mp<mt){
       res=mp;
    }
    else if( mp<mt) {
        res=0;
      // alert('pagaste mayot a monto restante total')
        document.getElementById("montPa").value="";
        swal({
            position: 'top-end',
            icon: 'faild',
            title: 'PAGASTE MAYOR AL MONTO RESTANTE',
            showConfirmButton: true,
           // timer: 2000
        })

        htmlmsj=`<b STYLE="color: red;">PAGASTE MAYOR AL MONTO RESTANTE</b>`

    }
    else {
       if (t<0 || t=='-'){
           res=0;
           swal({
               position: 'top-end',
               icon: 'faild',
               title: 'El numero que ingresaste es Menor a CERO',
               showConfirmButton: true
               // timer: 2000
           })
           document.getElementById("montPa").value="";
       }else {
         //  alert('pagadte mayor a cero')

           res=mt;
       }

        htmlmsj=`<p>NO puede ingresar numero <b>MAYOR A MONTO TOTAL </b></p>
                                <p>NO puede ingresar numero <b>MENOR A 0 </b></p>`
    }
    res = parseFloat(res).toFixed(2);
    htmlSumi=`<b><input type="text" name="montTot" value="`+res+`" disabled ></b>`
    $("#montP").html(htmlSumi);

//$("#msj").html(htmlmsj);


}
function resaltar(){
   //let a=$("#contentAMRS").childNodes;

  // Console.log(a)
}
//Cuando la página esté cargada ejecutará la función resaltar
$("#contentAMRS").ready(resaltar);

    /*----------------------GUARDAR AMORTIZACION-------------------------------------*/
function guardarARMOTIZACION() {

    let mpag=document.getElementById("montPa").value;
    let mTotal=document.getElementById("montAll").value;
    let ids=document.getElementById("idReciboA").innerHTML;
    let MONTOP=document.getElementById("montP").value;
    mTotal=Number.parseFloat(mTotal).toFixed(2);
    mpag = Number.parseFloat(mpag).toFixed(2);
   // let rest=mTotal-mpag;
    let rest=Number.parseFloat(mTotal).toFixed(2)-Number.parseFloat(mpag).toFixed(2);
    rest = parseFloat(rest).toFixed(2);
    console.log("monto pagado esta guardandose "+ids+mpag)
    swal({
        title: "¿Ejecutar esta acción?",
        text: "Estas seguro de AMORTIZAR?",
        type: "info",
        confirmButtonColor: '#03A9F4',
        confirmButtonText: '<i class="zmdi zmdi-run"></i> Aceptar',
        showCancelButton: true,
        cancelButtonColor: '#F44336',
        cancelButtonText: '<i class="zmdi zmdi-close-circle"></i> Cancel'
    }).then(()=> {
        if (mTotal==mpag) {
            $.ajax({
                url: "../ajax/gestionASERV.php",
                type: 'POST',
                data: 'mopagr=' + mpag + '&idsr=' + ids + '&montRes=' + rest,
                success: function (resp) {

                    window.location = "http://localhost/SW-ASUSAP/aservicio/";

                    //console.log("si ineserto monto pagado"+resp)
                }
            });
        }else {


            $.ajax({
                url: "../ajax/gestionASERV.php",
                type: 'POST',
                data: 'mopagr=' + mpag + '&idsr=' + ids + '&montRes=' + rest,
                success: function (resp) {

                    window.location = "http://localhost/SW-ASUSAP/ASupdate/" + ids;

                    //console.log("si ineserto monto pagado"+resp)
                }
            });


        }
    },function(){
        //si no le da a la opcion de aceptar
        console.log("No le dió aceptar")

    });


}
var report = true;
function ImprimerReciboAmotizacion() {

    let mpagA=document.getElementById("montPa").value;
    let mTotal=document.getElementById("montAll").value;
    let CDS=document.getElementById("codAR").innerHTML;
    let anomb=document.getElementById("nombreAR").innerHTML;
    let aservicio=document.getElementById("servicioAS").innerHTML;
    let fechae=document.getElementById("fechaAS").innerHTML;
  /*  let ids=document.getElementById("idReciboA").innerHTML;
    let MONTOP=document.getElementById("montP").value;
    let rest=mTotal-mpag;*/

 // let u='<a href="../reportes/amortizacionServ.php" target="_blank" class="btn btn-info btn-raised btn-xs"></a>';


    // console.log("monto pagado esta guardandose "+ids+mpag)

        var canal = window.location.pathname;
  //  let mpagA=document.getElementById("montPa").value;
    console.log("impsss"+mpagA+anomb+aservicio)
    window.open(`../reportes/amortizacionServ.php?PM=${mpagA}&cdsi=${CDS}&anom=${anomb}&fechae=${fechae}&servAs=${aservicio}`, '_blank');

/*
        $.ajax({
            url: `../reportes/amortizacionServ.php?`+canal,
            type: 'POST',
            data: 'mopagr=' + mpagA,

            success: function (resp) {
               // window.location.href = '../reportes/amortizacionServ.php/';
                //                 //console.log (resp)
                //console.log("si ineserto monto pagado"+resp)
            }
        });*/
        //  window.location = "http://localhost/SW-ASUSAP/aservicio/";


console.log("estas imprimiendo en la amortizacion"+mpagA)

}

document.body.onload = cargar;
function cargar(){
    htmlSumi=`<input type="submit"  onclick="impA();" class="btn btn-info btn-raised btn-xs" value="IMPRIMIR AS">`
    $("#btnimp").html(htmlSumi);
}

//-----------------------------------REPORTES----------------------------------------
function impA() {
    let mpagA=document.getElementById("montPa").value;
    console.log("impsss"+mpagA)
    window.open(`../reportes/amortizacionServ.php?PM=${mpagA}`, '_blank');


}









