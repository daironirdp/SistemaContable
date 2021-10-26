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
require_once '../Modelo/CM_FechaCliente.php';
require_once '../Modelo/CM_Cuentas.php';
$objeto = new Contabilidad();
$datos_mes = $objeto->obtenerInstanciasDiario($id_clienteFecha);
$datos = $objeto->obtenerInstanciasComprobantes($id_clienteFecha);
$objeto2 = new FechaCliente();
$impuestos_datos = $objeto2->mostrarDatosImpuestos($id_clienteFecha);
$datos_complementarios = $objeto2->mostrarMesDatosComplementarios($id_clienteFecha);
$bonificaciones = $objeto->obtenerBonificaciones($id_clienteFecha);

$boni_impuesto = 0;
$boni_seguridad = 0;
$boni_fw = 0;
$boni_cf = 0;
$boni_valor = 0;
foreach ($bonificaciones as $bonificacion) {
    switch ($bonificacion["impuesto"]) {
        case "impuesto10%": {
                $boni_impuesto = $bonificacion;
            }
            break;
        case"cuotaFija": {
                $boni_cf = $bonificacion;
            }
            break;
        case"seguridadSocial": {
                $boni_seguridad = $bonificacion;
            }
            break;
        case"fuerzaTrabajo": {
                $boni_fw = $bonificacion;
            }
            break;
    }
    $boni_valor += $bonificacion['valor'];
}
?>
<script>

    var id_cliente = <?php echo $id_cliente; ?>;
    var id_clienteFecha = <?php echo $id_clienteFecha; ?>;
    var nombre_cliente = '<?php echo $nombre_cliente; ?>';
    var nombre_anno = <?php echo $nombre_anno; ?>;
    var nombre_mes = '<?php echo $nombre_mes; ?>';
    var id_mes = '<?php echo $id_mes; ?>';
    var id_anno = <?php echo $id_anno; ?>;
    var tipo = '<?php echo $tipo; ?>';
    var xcientoInjust = <?php echo $datos_complementarios[0]["xcientoInjust"] ?>
</script>


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


        $tipomes = $datos_complementarios[0]["tipomes"];




        $salariotrabajador = $datos_complementarios[0]["salariotrabajador"];
        $xcientoInjust = $datos_complementarios[0]["xcientoInjust"];
        //balance
        $utilidadPerdida = 0;
        //gastos
        $gastosJ = 0;
        $gastosI = 0;
        $renta = 0;
        //ingresos
        $ingresos = 0;
        $ingresosx = 0;

        //impuestos
        if ($impuestos_datos != false) {
            foreach ($impuestos_datos as $i) {

                switch ($i["tipo_impuesto"]) {
                    case "impuesto10%": {

                            $impuestos = [$i["valor"], $i["tipo_pago"]];
                        }break;
                    case "cuotaFija": {

                            $cuotaFija = [$i["valor"], $i["tipo_pago"]];
                        }break;
                    case "seguridadSocial": {

                            $SeguridadSocial = [$i["valor"], $i["tipo_pago"]];
                        }break;
                    case "fuerzaTrabajo": {
                            if ($tipomes == 1) {
                                $mesesXannoXcliente = $objeto2->mostrarMeses($id_cliente, $id_anno);

                                $id = $datos_complementarios[0]["id_mes"];
                                $valor = $datos_complementarios[0]["salariotrabajador"];

                                foreach ($mesesXannoXcliente as $mes) {
                                    if (($mes['id_mes'] + 2) == $id) {
                                        $valor += $mes['salariotrabajador'];
                                    } else if (($mes['id_mes'] + 1) == $id) {
                                        $valor += $mes['salariotrabajador'];
                                    }
                                }

                                $FuerzaW = [$valor * 0.05, $i["tipo_pago"]];
                            } else {
                                $FuerzaW = [$i["valor"], $i["tipo_pago"]];
                            }
                        }break;
                }
            }
        }



        if (ctype_digit($opcion2)) {
            switch ($opcion2) {
                case 0: $page = './paginas/SubPaginas/Vista_Diario.php';
                    $activa = 'diario';
                    break;
                case 1: $page = './paginas/SubPaginas/Vista_Comprobantes.php';
                    $activa = 'comprobante';
                    break;
                case 2: $page = './paginas/SubPaginas/Vista_Mayor.php';
                    $activa = 'mayor';
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

        <div style="display: flex;justify-content: space-around">
            <?php if ($opcion2 != 3) { ?>
                <div style="flex: 0.1">
                    <ul>
                        <li><a class="<?php
                            if ($activa == 'diario') {
                                echo 'activa';
                            } else {
                                echo'desactiva';
                            }
                            ?>"onclick="" id="diario"href="?opcion=5&&id_mes=<?php echo $id_mes; ?>&&id_cliente=<?php echo $id_cliente; ?>&&id_anno=<?php echo $id_anno; ?>&&nombre_cliente=<?php echo $nombre_cliente; ?>&&nombre_anno=<?php echo $nombre_anno; ?>&&nombre_mes=<?php echo $nombre_mes; ?>&&id_clienteFecha=<?php echo $id_clienteFecha; ?>&&opcion2=0&&activa=diario&&tipo=<?php echo $tipo ?>">Diario Cont.</a></li>


                        <li><a class="<?php
                            if ($activa == 'comprobante') {
                                echo 'activa';
                            } else {
                                echo'desactiva';
                            }
                            ?>" onclick=""href="?opcion=5&&id_mes=<?php echo $id_mes; ?>&&id_cliente=<?php echo $id_cliente; ?>&&id_anno=<?php echo $id_anno; ?>&&nombre_cliente=<?php echo $nombre_cliente; ?>&&nombre_anno=<?php echo $nombre_anno; ?>&&nombre_mes=<?php echo $nombre_mes; ?>&&id_clienteFecha=<?php echo $id_clienteFecha; ?>&&opcion2=1&&activa=comprobante&&tipo=<?php echo $tipo ?>" id="comprobante">Comprobantes</a> </li>

                        <li><a class="<?php
                            if ($activa == 'mayor') {
                                echo 'activa';
                            } else {
                                echo'desactiva';
                            }
                            ?>" onclick=""href="?opcion=5&&id_mes=<?php echo $id_mes; ?>&&id_cliente=<?php echo $id_cliente; ?>&&id_anno=<?php echo $id_anno; ?>&&nombre_cliente=<?php echo $nombre_cliente; ?>&&nombre_anno=<?php echo $nombre_anno; ?>&&nombre_mes=<?php echo $nombre_mes; ?>&&id_clienteFecha=<?php echo $id_clienteFecha; ?>&&opcion2=2&&activa=mayor&&tipo=<?php echo $tipo ?>" id="mayor">Libro Mayor</a></li>

                    </ul>
                </div>



                <?php
            }
            include $page;
            ?>


            <script>
                var cuotaFija = '<?php
        if ($impuestos_datos != false) {

            echo $cuotaFija[0];
        } else {
            echo 0;
        }
        ?>';
    <?php ?>

                var fuerzaW = '<?php
    if ($impuestos_datos != false) {

        echo $FuerzaW[0];
    } else {
        echo 0;
    }
    ?>';
                var seguridadSocial = '<?php
    if ($impuestos_datos != false) {

        echo $SeguridadSocial[0];
    } else {
        echo 0;
    }
    ?>';

            </script>


        </div>

        <div id="agregar">


        </div>
        <style>
            .oculto{
                display: none;
            }
        </style>

        <style>
            .desactiva{
                color: black;
            }
            .activa{

            }

        </style>



    </body>


    <?php
} else {
    ?>

    <h5> La contabilidad de este cliente en este mes no ha empezado no ha empezado  :<a href="?opcion=5&&id_cliente=<?php echo $id_cliente; ?>&&id_mes=<?php echo $id_mes; ?>&&id_anno=<?php echo $id_anno; ?>&&nombre_cliente=<?php echo $nombre_cliente; ?>&&nombre_anno=<?php echo $nombre_anno; ?>&&nombre_mes=<?php echo $nombre_mes; ?>&&id_clienteFecha=<?php echo $id_clienteFecha; ?>&&opcion2=3&&tipo=<?php echo $tipo; ?>">Click para empezar</a></h5>
    <?php
}
// put your code here
?>

