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
require '../Modelo/CM_Annos.php';
require_once '../Modelo/CM_Meses.php';
$disponibles = new Annos();
$meses = new Meses();
$disponibles = $disponibles->obtenerAnnos();

$objeto = new FechaCliente();
$annos = $objeto->mostrarAnnos($id_cliente);
$meses = $meses->obtenerMeses();
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>

        <i class=" fa fa-user icono_user" aria-hidden="true"></i>

        <h3>Cliente: <?php echo $nombre_cliente; ?></h3>
        <div class=" elementosFlexibles1">
            <?php
            if (!empty($annos)) {


                foreach ($annos as $a) {
                    ?>
                    <div class="card text-center" style="margin-left: <?php echo floatval($a["nombre"]) / 100 ?>px">
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

                            <a href="../Controlador/CC_Controlador.php?accion=eliminarAnnoCliente&id_anno=<?php echo $a['id_anno'] ?>&id_cliente=<?php echo $id_cliente ?>&nombre_cliente=<?php echo $nombre_cliente ?>&tipo=<?php echo $tipo ?>">Eliminar </a>
                        </div>
                    </div>



                    <?php
                }
            }
            ?>
        </div>
        <form action="../Controlador/CC_Controlador.php?accion=insertarAnnoCliente" class="insertarAnno oculto"  method="POST">
            <select name="id_anno">
                <?php foreach ($disponibles as $d) {
                    ?>
                    <option value="<?php echo $d["id_anno"] ?>">
                        <?php echo $d["nombre"] ?>
                    </option>


                <?php } ?>

            </select>

            <select name="id_mes">
                <?php foreach ($meses as $m) {
                    ?>
                    <option value="<?php echo $m["id_mes"] ?>">
                        <?php echo $m["nombre"] ?>
                    </option>


                <?php } ?>

            </select>
            <input hidden name="id_cliente"value="<?php echo $id_cliente ?>">
            <input hidden name="nombre_cliente"value="<?php echo $nombre_cliente ?>">
            <input hidden name="tipo"value="<?php echo $tipo ?>">
            <input type="submit"/>

        </form>
        <style>
            .oculto{
                display: none;
            }
        </style>

        <a href="" onclick="insertAnno(event, 'insertarAnno')">Adicionar </a>


        <script>
            function insertAnno(e, id) {
                e.preventDefault();
                $("." + id).toggleClass("oculto");



            }



        </script>
    </body>
</html>
