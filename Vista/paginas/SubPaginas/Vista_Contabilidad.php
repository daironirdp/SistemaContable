<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
extract($_REQUEST);
//require '../Modelo/CM_Contabilidad.php';
//require_once '../Modelo/Conexion.php';
$objeto = new Contabilidad();
$datos_mes = $objeto->obtenerInstanciasDiario($id_clienteFecha);
$datos = $objeto->obtenerInstanciasComprobantes($id_clienteFecha);
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
         <div class="card text-center">
            <div class="card-header">
                <a href="" class="enlace">
       <i class=" fa fa-user icono_user" aria-hidden="true"></i>
       
             
                </a>
            </div>
            <div class="card-body">
           <h5>Cliente: <?php echo $nombre_cliente; ?></h5>

            </div>
            <div class="card-footer text-muted">
                <h3>Anno: <?php echo $nombre_anno; ?></h3>
               <h4>Mes: <?php echo $nombre_mes; ?></h4> 
        </div>
         </div>
        
        <?php
        if ($datos_mes != false || $opcion2 == 3) {
            $utilidadPerdida = 0;
            if ($tipo == "Mini")
                $cuotaFija = 1200;
            else
                $cuotaFija = 700;
            $impuestos = 0;
            $gastosJ = 0;
            $gastosI = 0;
            $ingresos = 0;
            $renta = 0;

            if (ctype_digit($opcion2)) {
                switch ($opcion2) {
                    case 0: $page = './paginas/SubPaginas/Vista_Diario.php';
                        $diario = 'active';
                        break;
                    case 1: $page = './paginas/SubPaginas/Vista_Comprobantes.php';
                        $comprobantes = 'active';
                        break;
                    case 2: $page = './paginas/SubPaginas/Vista_Mayor.php';
                        $mayor = 'active';
                        break;
                    case 3: $page = './paginas/SubPaginas/Vista_EmpezarContabilidad.php';
                        break;

                    default: $annos = './paginas/error.php';
                        break;
                }
            } else {
                $page = './paginas/error.php';
            }
            ?>
            <script>
                var cuotaFija =<?php echo $cuotaFija ?>;
            </script>
            <div style="display: flex;justify-content: space-around">
                <?php if ($opcion2 != 3) { ?>
                    <div style="flex: 0.1">
                        <ul>
                            <li><a onclick="" id="diario"href="?opcion=5&&id_cliente=<?php echo $id_cliente; ?>&&id_anno=<?php echo $id_anno; ?>&&nombre_cliente=<?php echo $nombre_cliente; ?>&&nombre_anno=<?php echo $nombre_anno; ?>&&nombre_mes=<?php echo $nombre_mes; ?>&&id_clienteFecha=<?php echo $id_clienteFecha; ?>&&opcion2=0&&activa=diario">Diario Contable</a></li>
                            <ul class="<?php
                    if ($activa == 'diario') {
                        echo 'activa';
                    } else {
                        echo'desactiva';
                    }
                    ?>">
                                <li><a href="?">Insertar</a></li>
                                <li><a href="?">Modificar</a></li>
                                <li><a href="?">Eliminar</a></li>
                            </ul>
                            <li><a onclick=""href="?opcion=5&&id_cliente=<?php echo $id_cliente; ?>&&id_anno=<?php echo $id_anno; ?>&&nombre_cliente=<?php echo $nombre_cliente; ?>&&nombre_anno=<?php echo $nombre_anno; ?>&&nombre_mes=<?php echo $nombre_mes; ?>&&id_clienteFecha=<?php echo $id_clienteFecha; ?>&&opcion2=1&&activa=comprobante" id="comprobante">Comprobantes</a> </li>
                            <ul class="<?php
                    if ($activa == 'comprobante') {
                        echo 'activa';
                    } else {
                        echo'desactiva';
                    }
                    ?>">
                                <li><a href="?">Insertar</a></li>
                                <li><a href="?">Modificar</a></li>
                                <li><a href="?">Eliminar</a></li>
                            </ul>
                            <li><a onclick=""href="?opcion=5&&id_cliente=<?php echo $id_cliente; ?>&&id_anno=<?php echo $id_anno; ?>&&nombre_cliente=<?php echo $nombre_cliente; ?>&&nombre_anno=<?php echo $nombre_anno; ?>&&nombre_mes=<?php echo $nombre_mes; ?>&&id_clienteFecha=<?php echo $id_clienteFecha; ?>&&opcion2=2&&activa=mayor" id="mayor">Libro Mayor</a></li>
                            <ul class="<?php
                    if ($activa == 'mayor') {
                        echo 'activa';
                    } else {
                        echo'desactiva';
                    }
                    ?>">
                                <li><a href="?">Insertar</a></li>
                                <li><a href="?">Modificar</a></li>
                                <li><a href="?">Eliminar</a></li>
                            </ul>
                        </ul>
                    </div>



                    <?php
                }
                include $page;
                ?>





            </div>


            <style>
                .desactiva{
                    display: none;
                }
                .activa{
                    margin-left: 10px;
                    margin-bottom: 10px;
                }

            </style>



        </body>
    </html>
    <script>

        function EnvioDatosAjax(accion, instancias) {


            $.ajax({
                type: "POST",
                url: "../Controlador/CC_Controlador.php?accion=" + accion + "&&id_clienteFecha=<?php echo $id_clienteFecha; ?>&&nombre_cliente=<?php echo $nombre_cliente; ?>&&nombre_anno=<?php echo $nombre_anno; ?>&&nombre_mes=<?php echo $nombre_mes; ?>&&id_anno=<?php echo $id_anno; ?>",
                data: {'instancias': JSON.stringify(instancias)},
                success: function (response) {
                    console.log(insertados);

                    insertados = [];
                }

            });
        }

    </script>
    <?php
} else {
    ?>

    <h5> La contabilidad de este cliente no ha empezado :<a href="?opcion=5&&id_cliente=<?php echo $id_cliente; ?>&&id_anno=<?php echo $id_anno; ?>&&nombre_cliente=<?php echo $nombre_cliente; ?>&&nombre_anno=<?php echo $nombre_anno; ?>&&nombre_mes=<?php echo $nombre_mes; ?>&&id_clienteFecha=<?php echo $id_clienteFecha; ?>&&opcion2=3&&tipo=<?php echo $tipo; ?>">Click para empezar</a></h5>
    <?php
}
// put your code here
?>

