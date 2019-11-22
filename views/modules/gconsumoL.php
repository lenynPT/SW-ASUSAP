
<style>
    .btn-init-consumo{
        text-transform:uppercase;
        background-color:rgba(220,151,10,.5);
        padding:20px!important;
        font-size:1.5em!important;
    }
</style>
<!-- Este btn actualiza la tabla de los meses y años en la tabla 'estado_gconsumo'-->
<?php 
    if($btn_habilitarConsumo){    
?>
    <button id="btn_updGC" class="btn-init-consumo btn btn-danger btn-lg btn-block p-5">
        HABILITAR CONSUMO PARA EL MES DE <b><?php echo "$fecha_btn_habCons del año $anio_btn_habCons"; ?></b>
    </button>   
<?php }else{
    echo "<br><br><br>";
} ?>

<h3 class="text-center lead"> 
    SE GENERÓ LOS CONSUMOS PARA <br> <b><?php echo "$fechaGenerado Del $anioGenerado";?> </b>
</h3>
