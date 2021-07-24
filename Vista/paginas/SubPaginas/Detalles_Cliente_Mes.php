<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
require '../Modelo/CM_FechaCliente.php';
extract($_REQUEST);
$objeto = new FechaCliente();
$meses = $objeto->mostrarMeses($id_cliente, $id_anno);
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <i class=" fa fa-user icono_user" aria-hidden="true"></i>
        <h3>Cliente: <?php echo $nombre_cliente; ?></h3>
        <h4>Anno: <?php echo $nombre_anno; ?></h4>

        <?php
        foreach ($meses as $m) {
            // put your code here
            ?>
           
             <div class="card text-center">
                <div class="card-header">
                    <h4> Mes: <a href="?opcion=5&&id_cliente=<?php echo $id_cliente; ?>&&id_anno=<?php echo $id_anno; ?>&&nombre_cliente=<?php echo $nombre_cliente; ?>&&nombre_anno=<?php echo $nombre_anno; ?>&&nombre_mes=<?php echo $m["nombre"]; ?>&&id_clienteFecha=<?php echo $m["id_clienteFecha"]; ?>&&opcion2=0&&activa=diario&&id_mes=<?php echo $m["id_mes"]; ?>&&tipo=<?php echo $tipo; ?>"><?php echo $m["nombre"]; ?></a></h4>   
                </div>
                <div class="card-body">

                    <h5>Acumulado: <?php echo $m["acumulado"]; ?> </h5> 
                    <h5>Impuesto mensual:  <?php echo $m["tributo"] ?></h5>
                </div>
                <div class="card-footer text-muted">


                </div>
            </div>
        
               
            
           
                
            
            
               
            
        

    


    <?php
}
?>
</body>
</html>
