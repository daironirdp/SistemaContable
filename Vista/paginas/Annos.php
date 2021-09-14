<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<?php
// put your code here
require_once '../Modelo/CM_Clientes.php';
require_once '../Modelo/CM_Annos.php';

$objeto = new Annos();
$annos = $objeto->obtenerAnnos()
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($annos as $a) {
                    ?>
                    <tr>
                        <td><?php echo $a["id_anno"]; ?></td>

                        <td><?php echo $a["nombre"]; ?></td>

                        <td>
                            
                      <a href="" id="<?php echo $a["id_anno"]; ?>" onclick="modificar(event,<?php echo $a["id_anno"]; ?>)">Modificar</a>
                      <a href="../Controlador/CC_Controlador.php?accion=eliminarAnno&&id_anno=<?php echo $a["id_anno"] ?>">Eliminar</a>  
                        </td>
                       

                    </tr>
                    <tr>
                        <td colspan="4">

                            <form action="../Controlador/CC_Controlador.php?accion=modificarAnno" class="oculto <?php echo $a["id_anno"]; ?>" method="Post">
                                <input type="text" value="<?php echo $a["id_anno"]; ?>"id="id_anno" name="id_anno" hidden />
                                <input type="number"value="<?php echo $a["nombre"]; ?>" name="nombre"  />
                                <input type="submit"  />
                            </form>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>

        <form action="../Controlador/CC_Controlador.php?accion=insertarAnno" class="insertar oculto" method="POST">
            <input type="number" min="0"name="nombre" value='20<?php echo date("y"); ?>'/>
            <input type="submit"/>

        </form>

        <a href="" onclick="insertAnno(event,'insertar')">Agregar anno</a>
    </body>
</html>
<style>
    .oculto{
        display: none;
    }
</style>

<script>
    function insertAnno(e, id) {
        e.preventDefault();
        $("." + id).toggleClass("oculto");



    }

   

</script>
