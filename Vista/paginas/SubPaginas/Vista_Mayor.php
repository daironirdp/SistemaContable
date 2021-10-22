<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
$fechaObjeto = new FechaCliente();



$id_clienteFecha2 = $fechaObjeto->devolverFechaAnterior($id_mes, $id_anno);
if ($id_clienteFecha2 == 0) {

    $id_clienteFecha2 = 0;
} else {
    $id_clienteFecha2 = $id_clienteFecha2[0][0];
}

$libroMayor = $objeto->obtenerInstanciasMayor($id_clienteFecha, $id_clienteFecha2, $tipo);
$impuestos_datos_mes_anterior = $objeto2->mostrarDatosImpuestos($id_clienteFecha2);
$mayor_depurado = $objeto->depuradorMayor($libroMayor, $id_clienteFecha2);
$activoCirculante = array();
$gastos = null;
$patrimonio = null;
$ingresoBruto = null;
$impuestos_gastos = array();
$impuestos_patrimonio = array();
$impuestos_gastos_mes_anterior = array();
$impuestos_patrimonio_mes_anterior = array();
//variables de totales
$debito_total = 0;
$haber_total = 0;
$saldoFinal_total = 0;

foreach ($mayor_depurado as $fila) {
    if ($fila["id_cuenta"] == 100 || $fila["id_cuenta"] == 110) {
        array_push($activoCirculante, $fila);
        $debito_total += $fila['debe'];
        $haber_total += $fila['haber'];
    } else if ($fila["id_cuenta"] == 800) {
        $gastos = $fila;
        $debito_total += $fila['debe'];
        $haber_total += $fila['haber'];
    } else if ($fila["id_cuenta"] == 600) {
        $patrimonio = $fila;
        $debito_total += $fila['debe'];
        $haber_total += $fila['haber'];
    } else if ($fila["id_cuenta"] == 900) {
        $ingresoBruto = $fila;
        $debito_total += $fila['debe'];
        $haber_total += $fila['haber'];
    }
}

foreach ($impuestos_datos as $value) {

    if ($value['tipo_impuesto'] == 'impuesto10%' || $value['tipo_impuesto'] == 'fuerzaTrabajo') {
        array_push($impuestos_gastos, $value);
    } else if ($value['tipo_impuesto'] == 'cuotaFija' || $value['tipo_impuesto'] == 'seguridadSocial') {
        array_push($impuestos_patrimonio, $value);
    }
}

//complatando los valores     
$var = 0;
$var_anterior = 0;
if ($impuestos_datos_mes_anterior != false) {
    foreach ($impuestos_datos_mes_anterior as $value) {

        $var_anterior += $value['valor'];
    }

    foreach ($impuestos_datos_mes_anterior as $value) {

        if ($value['tipo_impuesto'] == 'impuesto10%' || $value['tipo_impuesto'] == 'fuerzaTrabajo') {
            array_push($impuestos_gastos_mes_anterior, $value);
        } else if ($value['tipo_impuesto'] == 'cuotaFija' || $value['tipo_impuesto'] == 'seguridadSocial') {
            array_push($impuestos_patrimonio_mes_anterior, $value);
        }
    }

    $gastos['saldoAnterior'] = $gastos['saldoAnterior'] + $impuestos_gastos_mes_anterior[0]['valor'] + $impuestos_gastos_mes_anterior[1]['valor'];
    $patrimonio['saldoAnterior'] = $patrimonio['saldoAnterior'] - $impuestos_patrimonio_mes_anterior[0]['valor'] - $impuestos_patrimonio_mes_anterior[1]['valor'];
}
foreach ($impuestos_gastos as $value1) {
    $var += $value1["valor"];
}
foreach ($impuestos_patrimonio as $value2) {
    $var += $value2["valor"];
}


$gastos['debe'] = $gastos['debe'] + $ingresoBruto['haber'] * 0.10;
$debito_total += $ingresoBruto['haber'] * 0.10;
$haber_total += $var;
//var_dump($gastos['subcuentas'][3]);
?>



<div class="" style="
     display: flex;
     flex-direction: column;
     flex: 0.9;
     align-items: center;"  >  

    <H3>Libro Mayor</H3>
    <table border="2" class="table table-bordered table-striped  table-hover" style="text-align: center;">
        <thead>
        <th>Descripcion</th>
        <th>S/Anterior</th>
        <th>Debito</th>
        <th>Credito</th>
        <th>Saldo final</th>

        </thead>

        <tbody>
            <tr class="fondo" id="activoCirculante">
                <td>Activo Circulante</td>
                <td><?php
                    echo $activoCirculante[0]['saldoAnterior'] + $activoCirculante[1]["saldoAnterior"];
                    ?></td>
                <td><?php echo $activoCirculante[0]["debe"] + $activoCirculante[1]["debe"] ?></td>
                <td><?php echo $activoCirculante[0]["haber"] + $activoCirculante[1]["haber"] ?></td>
                <td><?php echo ($activoCirculante[0]["debe"] + $activoCirculante[1]["debe"]) - ($activoCirculante[0]["haber"] + $activoCirculante[1]["haber"]) ?></td>
            </tr>
            <?php
            foreach ($activoCirculante as $fila) {
                ?>
                <tr class="">
                    <td><?php echo $fila["nombre"]; ?></td>
                    <td><?php echo $fila ["saldoAnterior"]; ?></td>
                    <td><?php echo $fila["debe"] ?></td>
                    <td><?php echo $fila["haber"] ?></td>
                    <td><?php
                        echo /* saldo anterior + */ $fila["debe"] - $fila["haber"];
                        if (is_numeric(array_search('800', array_column($libroMayor, 'id_cuenta'))))
                            if ($fila["subcuentas"] == 0) {
                                $objeto->crearInstanciaMayor($fila["id_cuenta"], $id_clienteFecha, $fila["debe"] - $fila["haber"], $fila["subcuentas"], "cuenta");
                            } else {
                                $objeto->crearInstanciaMayor($fila["id_cuenta"], $id_clienteFecha, $fila["debe"] - $fila["haber"], $fila["subcuentas"], "subcuenta");
                            }
                        ?></td>
                </tr>

                <?php
            }
            ?>
            <tr class="fondo">
                <td>Gastos</td>
                <td><?php echo $gastos['saldoAnterior'] ?></td>
                <td><?php echo $gastos["debe"] ?></td>
                <td><?php echo $gastos["haber"] ?></td>
                <td><?php echo $gastos["debe"] - $gastos["haber"] ?></td>
            </tr>
            <?php
            foreach ($gastos['subcuentas'] as $fila) {
                ?>
                <tr class="">
                    <td><?php echo $fila["nombre_subcuenta"] ?></td>
                    <td><?php echo $fila["saldoAnterior"]; ?></td>
                    <td><?php echo $fila["valor"] ?></td>
                    <td>0</td>
                    <td><?php
                        echo $fila["valor"];
                        if (is_numeric(array_search('800', array_column($libroMayor, 'id_cuenta'))) && is_numeric(array_search($fila["id_subcuenta"], array_column($libroMayor[$objeto->buscar('800', $libroMayor, 'id_cuenta')]['subcuentas'], 'id_subcuenta'))))
                            $objeto->crearInstanciaMayor('800', $id_clienteFecha, $fila["valor"], $fila["id_subcuenta"], "subcuenta");
                        ?></td>
                </tr>

                <?Php
            }
            ?>
            <?php foreach ($impuestos_gastos as $value) { ?>

                <tr class="">
                    <td><?php
                        if ($value['tipo_impuesto'] == "impuesto10%") {
                            echo "Impuestos servicios";
                            if ($impuestos_datos_mes_anterior != false) {
                                $valor_anterior = $impuestos_datos_mes_anterior[0]['valor'];
                            } else {
                                $valor_anterior = 0;
                            }
                        } else {
                            echo "Impuesto F. trabajo";
                            if ($impuestos_datos_mes_anterior != false) {
                                $valor_anterior = $impuestos_datos_mes_anterior[3]['valor'];
                            } else {
                                $valor_anterior = 0;
                            }
                        }
                        ?></td>
                    <td><?php echo $valor_anterior ?></td>
                    <td><?php echo $value["valor"] ?></td>
                    <td>0</td>
                    <td><?php echo $value["valor"] ?></td>
                </tr>


            <?php } ?>

            <tr class="fondo">
                <td>Pasivos Circulantes</td>
                <td><?php echo $var_anterior; ?></td>

                <td><?php echo 0 ?></td>
                <td><?php
                    echo $var;
                    ?></td>
                <td><?php echo $var; ?></td>
            </tr>
            <tr class="">
                <td>Cuentas por pagar</td>
                <td><?php echo $var_anterior; ?></td>

                <td><?php echo 0; ?></td>
                <td><?php echo $var; ?></td>
                <td><?php echo $var; ?></td>
            </tr>

            <tr class="fondo">
                <td>Patrimonio del TCP</td>
                <td><?php
                    if ($patrimonio != null) {
                        echo $patrimonio['saldoAnterior'];
                        ?></td>

                    <td><?php echo $patrimonio["debe"] ?></td>
                    <td><?php echo $patrimonio["haber"] ?></td>
                    <td><?php echo $patrimonio["haber"] - $patrimonio["debe"];
                        ?></td>
                </tr>
                <?Php
                foreach ($patrimonio['subcuentas'] as $fila) {
                    ?>
                    <tr class="">
                        <td><?php echo $fila["nombre_subcuenta"] ?></td>
                        <td><?php echo $fila['saldoAnterior'] ?></td>
                        <td>0</td>
                        <td><?php echo $fila["valor"] ?></td>
                        <td><?php
                            echo $fila["valor"];
                            if (is_numeric(array_search('600', array_column($libroMayor, 'id_cuenta'))) && is_numeric(array_search($fila["id_subcuenta"], array_column($libroMayor[$objeto->buscar('600', $libroMayor, 'id_cuenta')]['subcuentas'], 'id_subcuenta'))))
                                $objeto->crearInstanciaMayor('600', $id_clienteFecha, $fila["valor"], $fila["id_subcuenta"], "subcuenta");
                            ?></td>
                    </tr>
                    <?Php
                }
            } else {

                //Escribir lo que va aki patrimonio con impuestos solamente
                if ($id_mes != 1) {
                    echo $impuestos_patrimonio[0]['valor'] + $impuestos_patrimonio[1]['valor'];
                } else {
                    echo 0;
                }
                ?>

                </td>      
            <td><?php echo $impuestos_patrimonio[0]['valor'] + $impuestos_patrimonio[1]['valor']; ?></td>
            <td>0</td>
            <td><?php echo $impuestos_patrimonio[0]['valor'] + $impuestos_patrimonio[1]['valor']; ?></td>



            <?php
        } $cont = 0;
        foreach ($impuestos_patrimonio as $value) {
            if ($value['valor'] != 0) {
                ?>

                <tr class="">
                    <td><?php
                        if ($value['tipo_impuesto'] == "cuotaFija") {
                            echo "Cuota Fija";
                            $agrego = "-";
                        } else {
                            echo "Seguridad Social";
                            $agrego = "";
                        }
                        ?></td>
                    <td><?php
                        if (count($impuestos_patrimonio_mes_anterior) != 0) {

                            if ($impuestos_patrimonio_mes_anterior[$cont]["valor"] == 0) {
                                echo 0;
                            } else {
                                echo $agrego . $impuestos_patrimonio_mes_anterior[$cont]["valor"];
                                $cont++;
                            }
                        } else {
                            echo 0;
                        }
                        ?></td>
                    <td><?php echo $value["valor"] ?></td>
                    <td>0</td>
                    <td><?php echo -$value["valor"] ?></td>
                </tr>


                <?php
            }
        }
        ?>

        <tr class="fondo">
            <td>Ingresos Brutos</td>
            <td><?php echo $ingresoBruto["saldoAnterior"] ?> </td>
            <td><?php echo $ingresoBruto["debe"] ?></td>
            <td><?php echo $ingresoBruto["haber"] ?></td>
            <td><?php echo $ingresoBruto["haber"] - $ingresoBruto["debe"] ?></td>
        </tr>
        <?Php ?>
        <tr class="">
            <td><?php echo $ingresoBruto["nombre"] ?></td>
            <td><?php echo $ingresoBruto["saldoAnterior"] ?> </td>
            <td><?php echo $ingresoBruto["debe"] ?></td>
            <td><?php echo $ingresoBruto["haber"] ?></td>

            <td><?php
                echo $ingresoBruto["haber"] - $ingresoBruto["debe"];
                if (is_numeric(array_search('900', array_column($libroMayor, 'id_cuenta'))))
                    $objeto->crearInstanciaMayor('900', $id_clienteFecha, $ingresoBruto["haber"] - $ingresoBruto["debe"], $ingresoBruto['subcuentas'], "cuenta");
                ?></td>
        </tr>
        <?Php ?>
        <tr class="fondo">
            <td colspan="2"><?php echo "Total" ?></td>

            <td><?php echo $debito_total ?></td>
            <td><?php echo $haber_total ?></td>
            <td><?php echo $debito_total - $haber_total ?></td>
        </tr>

        </tbody>

    </table>

</div>
