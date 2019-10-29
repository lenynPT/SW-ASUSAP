
/*function editRowRS(editableObjRS){
   // console.log("valor"+editableObjRS)
    let descripcion = $("#descripcion").val();
    let monto = $("#monto").val();
    let idss = $("#ids").val();
   // console.log(idss+descripcion)
    $(editableObjRS).css("background","#FFF");
}
function cancelAdd() {
    $("#add-more").show();
    $("#new_row_ajax").remove();
}
function guardarABD(addColumn,id) {
   // let ids = $("#ids").val();
    var columnValue = $(addColumn).text();
    let m=$("#"+id).val(columnValue);
    console.log(m)

   // $('td').addClass('heiglite');
    //seleccionar hijo
    //seleccionar
    //let re=$('#new_row_ajax').find('#txt_descripcion').val();
    //console.log(idss)
    //$(editableObjRS).css("background","#FFF url(cargando.gif) no-repeat right");
    //console.log(ids+descripcion+monto);



  //console.log('OPCION'+b[2])

   //console.log( editableObjRS+id);
   /!* $.ajax({
        url: "../ajax/buscarRS.php",SSS
        type: "POST",
        data:'insertV='+$(editableObjRS).text()+'&idV='+id,
        success: function(data){
            $(editableObjRS).css("background","#00fdeb");
        }
    });*!/

}

//let co=$("#cuadro").children("#cuadro1").children("#cuadro12").children("h3").children("input");


function agregarDB() {
    var id = "INSTSL.  DE AGUA";

   var descripcion = $("#descripcion").val();
   // var cod = $("#codSR").val();
    var cod = 3;
    var monto = $("#monto").val();
    //console.log(title)

    //console.log(id+descripcion+monto)

   $("#confirmAdd").html('<img src="cargando.gif" />');
    $.ajax({
        url: "../ajax/agregarRS.php",
        type: "POST",
        data:'cod='+id+'&descripcion='+descripcion+'&monto='+monto,
        success: function(data){
            $("#new_row_ajax").remove();
            $("#add-more").show();
            $("#table-body").append(data);
        }
    });
}*/

$(document).ready(function(){
    $('#bt_add').click(function(){
        agregar();
    });
    $('#bt_del').click(function(){
        eliminar(id_fila_selected);
    });



});

/*------------------AGRAGAR ITEMS  DEL RECIBO---------------------------------*/
let cont=0;
let id_fila_selected=[];
let costoTot=0;

function agregar(){
    cont++;
   // let codsu=document.getElementById("codSR").innerHTML  ;
    let idrs=document.getElementById("idrs").innerHTML

    var nomd=$("#NomDes").val();
    var cost=$("#Costo").val();
   // console.log(nomd+cost+idrs)

    $.ajax({
        url:"../ajax/agregarRS.php",
        type:'POST',
        // data:'ids='+idf+'&monto='+cost,
        data:'NomD='+nomd+'&costD='+cost+'&CodRS='+idrs,
        success:function (resp) {
            // alert('respuesta'+resp);
        }

    });

    //console.log("has echo click"+cont+nomd)
    var fila='<tr class="selected" id="fila'+cont+'" onclick="seleccionar(this.id);"><td>'+cont+'</td><td>'+nomd+'</td><td>'+cost+'</td></tr>';
    $('#tabla').append(fila);


    costoTot=parseFloat(costoTot)+parseFloat(cost);
    document.getElementById("costTotal").innerHTML=costoTot;


    reordenar();
}

function seleccionar(id_fila){
    if($('#'+id_fila).hasClass('seleccionada')){
        $('#'+id_fila).removeClass('seleccionada');
    }
    else{
        $('#'+id_fila).addClass('seleccionada');
    }
    //2702id_fila_selected=id_fila;
    id_fila_selected.push(id_fila);
}

function eliminar(id_fila){
    cont=0
    costoTot=0;
    document.getElementById("costTotal").innerHTML=costoTot;
    var myTable = document.getElementById("tabla");
    var rowCount = myTable.rows.length;
    for (var x=rowCount-1; x>0; x--) {
        myTable.deleteRow(x);

    }
    let idf=document.getElementById("idrs").innerHTML ;
    $.ajax({
        url:"../ajax/agregarRS.php",
        type:'POST',
        // data:'ids='+idf+'&monto='+cost,
        data: "idsF="+idf,
        success:function (resp) {

                console.log("has echo click en borrar todo los items"+resp)
            // $('#add-all').hide();
        }

    });



    /*$('#'+id_fila).remove();
    reordenar();*/
    //var a=$('#tabla tbody tr').length();
   //var a=$('#tabla').not(':first').children().remove();
  //  alll=$('#tabla').slice(1).children().remove();
   // al=alll.rows;
   // console.log(a)
   /* for(var i=0; i<id_fila.length; i++){
        $('#'+id_fila[i]).remove();
    }*/
    reordenar();
}

function reordenar(){
    var num=1;
    $('#tabla tbody tr').each(function(){
        $(this).find('td').eq(0).text(num);
        num++;
    });
}
/*------------------------AL GUARDAR TODO ACTUALIZADO -----------------------------------------------*/
let num1=0;
function guardarTodo(){
    /* Agregando todo los datos */
    let idf=document.getElementById("idrs").innerHTML ;
    let  nsu = document.getElementById("nombreSR").innerHTML ;
    let  asu = document.getElementById("apellSR").innerHTML  ;
    let codsu=document.getElementById("codSR").innerHTML  ;
    let anirs=  document.getElementById("anioRS").innerHTML;
    let mesrs=document.getElementById("mesRS").innerHTML  ;
    let cost=document.getElementById("costTotal").innerHTML  ;
    let anrs=document.getElementById("anombre").value;

    let tot=parseFloat(cost);
    console.log("Has echo click en guardar"+tot+"a nombre"+anrs)

    /*############fin###############*/
 // console.log(idf+codsu+anirs+mesrs+anrs);

    var miArray=new Array()
    var i=0
    var tabla = document.getElementById("tabla");
    var total=tabla.rows.length
/*

    for(j=1;j<=total-1;j++){
        var dato=tabla.rows[j].cells[2].childNodes[0].nodeValue
        miArray[i]=dato
        i=i+1
        var arreglo=miArray.toString();






        console.log("datos"+arreglo)
    }

*/


    $.ajax({
        url:"../ajax/agregarRS.php",
        type:'POST',
       // data:'ids='+idf+'&monto='+cost,
        data: "ids="+idf+"&cost="+tot+"&anom="+anrs,
        success:function (resp) {


            // $('#add-all').hide();
        }

    });

    window.location="http://localhost/SW-ASUSAP/aservicio/";

}
/*----------------cuando seleccionas de id  EN LA BUSQUEDA---------------------------------------*/
function showRow(row) {
   /* let x=row.cells;
   // console.log(x[1]);

    //document.getElementById("idrs").innerHTML = x[0].innerHTML;
    let  n = document.getElementById("nombreSR").innerHTML = x[1].innerHTML;
    let  a = document.getElementById("apellSR").innerHTML  = x[2].innerHTML;
   let cod=document.getElementById("codSR").innerHTML  = x[3].innerHTML;
    let dr=document.getElementById("direRS").innerHTML  = x[4].innerHTML;
    let an=document.getElementById("anioRS").innerHTML  = x[5].innerHTML;
    let mes=document.getElementById("mesRS").innerHTML  = x[6].innerHTML;
    let aname=document.getElementById("anombre").value  = n+" "+a;
   $("#add-all").show();


    $.ajax({
        url:"../ajax/agregarRS.php",
        type:'POST',
        // data:'ids='+idf+'&monto='+cost,
        data: "aNom="+aname+"&aNio="+a+"&aMes="+mes+"&aCod="+cod,
        success:function (resp) {

            let valores = JSON.parse(resp);
            htmlSumi= document.getElementById("idrs").innerHTML=`${valores}`;

            alert(htmlSumi);
            // $('#add-all').hide();
        }

    });*/
    let x=row.cells;
    // console.log(x[1]);

    //document.getElementById("idrs").innerHTML = x[0].innerHTML;
    let  n = document.getElementById("nombreSR").innerHTML = x[1].innerHTML;
    let  a = document.getElementById("apellSR").innerHTML  = x[2].innerHTML;
    let cod=document.getElementById("codSR").innerHTML  = x[3].innerHTML;
    let dr=document.getElementById("direRS").innerHTML  = x[4].innerHTML;
    let an=document.getElementById("anioRS").innerHTML  = x[5].innerHTML;
    let mes=document.getElementById("mesRS").innerHTML  = x[6].innerHTML;
    let aname=document.getElementById("anombre").value  = n+" "+a;

        $("#add-all").show();

        swal({
            title: "¿Ejecutar esta acción?",
            text: "seguro??",
            type: "info",
            confirmButtonColor: '#03A9F4',
            confirmButtonText: '<i class="zmdi zmdi-run"></i> Aceptar',
            showCancelButton: true,
            cancelButtonColor: '#F44336',
            cancelButtonText: '<i class="zmdi zmdi-close-circle"></i> Cancel'
        }).then(()=>{
            $.ajax({
                url:"../ajax/agregarRS.php",
                type:'POST',
                // data:'ids='+idf+'&monto='+cost,
                data: "aNom="+aname+"&aNio="+a+"&aMes="+mes+"&aCod="+cod,
                beforeSend:function(){},
                success:function (resp) {

                    let valores = JSON.parse(resp);
                    htmlSumi= document.getElementById("idrs").innerHTML=`${valores}`;

                    // alert(htmlSumi);
                    // $('#add-all').hide();
                }

            });
            //Cuando le da aceptar
            console.log("le dio aceptar");

        },function(){
            //si no le da a la opcion de aceptar
            console.log("No le dió aceptar")
        });

    //let tot=document.getElementById("codSR").val;


   // var g=[cod];
  //console.log(tot)
  //  return g;
    //document.getElementById("codSR").value = x[2].innerHTML;
}
/*---------------lista al momento hacer la busqueda-------------------*/
function listar_rs(valor) {

    if ($('#buscarRS').val()){
        $('#datos-result').show();
        $.ajax({
            url:"../ajax/buscarRS.php",
            type:'POST',
            data:'valor='+valor,
            success:function (resp) {
                let valores = JSON.parse(resp);
                htmlSumi=`<table class='table table-bordered'><thead><tr><th>#</th><th>Nombre AS.</th><th>Apellido AS.</th><th>Suministro</th><th>Direccion</th><th>año</th><th>mes</th><th>Seleccionar </th></tr></thead><tbody>`;
                for(let i=0;i<valores.length;i++){

                    htmlSumi += `
						<tr onclick="showRow(this);">
							<td>${i+1}</td>
							<td>${valores[i]["nombreAS"]}</td>
							<td>${valores[i]["apellidoAS"]}</td>
							<td>${valores[i]["codSum"]}</td>
							<td>${valores[i]["direSR"]}</td>
							<td>${valores[i]["anio"]}</td>
							<td>${valores[i]["mes"]}</td>
                            <td><a href="#!" class="btn btn-info btn-raised btn-xs">Seleccionar</a></td>
					
						</tr>	
					`;
                }
                htmlSumi+=`</tbody></table>`
                $("#containerRS").html(htmlSumi);
               // $('#add-all').hide();
            }

        });
    }else {
        $('#datos-result').hide();
       $('#add-all').hide();
    }

}

var select = document.getElementById('servicio');
select.addEventListener('change',
    function(){
        var selectedOption = this.options[select.selectedIndex];
        console.log(selectedOption.value + ': ' + selectedOption.text);
        document.getElementById("NomDes").value=selectedOption.text;
    });