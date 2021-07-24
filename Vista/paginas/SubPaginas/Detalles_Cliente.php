<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
// put your code here
extract($_REQUEST);
require '../Modelo/CM_FechaCliente.php';
$objeto = new FechaCliente();
$annos = $objeto->mostrarAnnos($id_cliente);
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>

        <i class=" fa fa-user icono_user" aria-hidden="true"></i>

        <h3>Cliente: <?php echo $nombre_cliente; ?></h3>
        <?php
        foreach ($annos as $a) {
            ?>
            <div class="card text-center">
                <div class="card-header">
                    <h3>Año: <a href="?opcion=4&&id_cliente=<?php echo $id_cliente; ?>&&id_anno=<?php echo $a["id_anno"]; ?>&&nombre_cliente=<?php echo $nombre_cliente; ?>&&nombre_anno=<?php echo $a["nombre"]; ?>&&tipo=<?php echo $tipo; ?>">
                            <?php
                            echo $a["nombre"]
                            ?></a></h3>   
                </div>
                <div class="card-body">

                    <h5>Acumulado: <?php echo $objeto->acumuladoAnual($id_cliente, $a['id_anno'])[0]["acumulado"] ?> </h5> 
                    <h5>Impuestos ONAT: 0</h5>
                </div>
                <div class="card-footer text-muted">


                </div>
            </div>



            <?php
        }
        ?>




    </body>
</html>
