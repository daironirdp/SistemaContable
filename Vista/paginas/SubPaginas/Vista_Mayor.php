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

$var = 0;
$var_anterior = 0;
foreach ($impuestos_datos as $value) {

    if ($value['tipo_impuesto'] == 'impuesto10%' || $value['tipo_impuesto'] == 'fuerzaTrabajo') {

        array_push($impuestos_gastos, $value);
    } else if ($value['tipo_impuesto'] == 'cuotaFija' || $value['tipo_impuesto'] == 'seguridadSocial') {

        array_push($impuestos_patrimonio, $value);
    }
    $var += $value["valor"];
}

//complatando los valores     


if ($impuestos_datos_mes_anterior != false) {
    /*    for ($i = 0; $i < count($impuestos_datos_mes_anterior); $i++) {
      if ($impuestos_datos_mes_anterior[$i]["tipo_impuesto"] == "cuotaFija") {
      $impuestos_datos_mes_anterior[$i]["valor"] = $impuestos_datos_mes_anterior[$i]["valor"] * ($id_mes - 1);
      }
      } */

    foreach ($impuestos_datos_mes_anterior as $value) {

        if ($value['tipo_impuesto'] == 'impuesto10%' || $value['tipo_impuesto'] == 'fuerzaTrabajo') {
            array_push($impuestos_gastos_mes_anterior, $value);
        } else if ($value['tipo_impuesto'] == 'cuotaFija' || $value['tipo_impuesto'] == 'seguridadSocial') {

            array_push($impuestos_patrimonio_mes_anterior, $value);
        }
        if ($value['saldo_final'] < 0)
            $var_anterior += $value['saldo_final'] * -1;
        else
            $var_anterior += $value['saldo_final'];
    }

    for ($i = 0; $i < count($impuestos_gastos_mes_anterior); $i++) {
        $gastos['saldoAnterior'] += $impuestos_gastos_mes_anterior[$i]['saldo_final'];
    }
    for ($i = 0; $i < count($impuestos_patrimonio_mes_anterior); $i++) {

        $patrimonio['saldoAnterior'] += $impuestos_patrimonio_mes_anterior[$i]['saldo_final'];
    }
}


//depurando todavia los datos
//patrimonio
for ($i = 0; $i < count($impuestos_patrimonio); $i++) {
    if (!$objeto->buscar('600', $libroMayor, "id_cuenta")) {
        $patrimonio["debe"] += $impuestos_patrimonio[$i]['valor'];
        $debito_total += $impuestos_patrimonio[$i]['valor'];
    }
}

foreach ($impuestos_gastos as $impuesto) {
    $gastos['debe'] += $impuesto['valor'];
    $debito_total += $impuesto['valor'];
}


$haber_total += $var;
//var_dump($mayor_depurado[2]);
//var_dump($patrimonio);
//var_dump($impuestos_datos);
//var_dump($impuestos_patrimonio);
//var_dump($impuestos_gastos);
//var_dump($impuestos_datos_mes_anterior);
//var_dump($impuestos_patrimonio_mes_anterior);
//var_dump($impuestos_gastos_mes_anterior);
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
                <td><?php echo ($activoCirculante[0]['saldoAnterior'] + $activoCirculante[1]["saldoAnterior"]) + ($activoCirculante[0]["debe"] + $activoCirculante[1]["debe"]) - ($activoCirculante[0]["haber"] + $activoCirculante[1]["haber"]) ?></td>
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
                        echo $fila ["saldoAnterior"] + $fila["debe"] - $fila["haber"];
                        if (is_numeric(array_search('800', array_column($libroMayor, 'id_cuenta'))))
                            if ($fila["subcuentas"] == 0) {
                                $objeto->crearInstanciaMayor($fila["id_cuenta"], $id_clienteFecha, $fila ["saldoAnterior"] + $fila["debe"] - $fila["haber"], $fila["subcuentas"], "cuenta");
                            } else {
                                $objeto->crearInstanciaMayor($fila["id_cuenta"], $id_clienteFecha, $fila ["saldoAnterior"] + $fila["debe"] - $fila["haber"], $fila["subcuentas"], "subcuenta");
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
                <td><?php echo $gastos['saldoAnterior'] + $gastos["debe"] - $gastos["haber"] ?></td>
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
                        echo $fila["saldoAnterior"] + $fila["valor"];
                        if (is_numeric(array_search('800', array_column($libroMayor, 'id_cuenta'))) && is_numeric(array_search($fila["id_subcuenta"], array_column($libroMayor[$objeto->buscar('800', $libroMayor, 'id_cuenta')]['subcuentas'], 'id_subcuenta'))))
                            $objeto->crearInstanciaMayor('800', $id_clienteFecha, $fila["saldoAnterior"] + $fila["valor"], $fila["id_subcuenta"], "subcuenta");
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
                            $tipoImp = "impuesto10%";
                            if ($impuestos_gastos_mes_anterior != false) {
                                $valor_anterior = $impuestos_gastos_mes_anterior[0]['saldo_final'];
                            } else {
                                $valor_anterior = 0;
                            }
                        } else {
                            echo "Impuesto F. trabajo";
                            $tipoImp = "fuerzaTrabajo";
                            if ($impuestos_gastos_mes_anterior != false) {
                                $valor_anterior = $impuestos_gastos_mes_anterior[1]['saldo_final'];
                            } else {
                                $valor_anterior = 0;
                            }
                        }
                        ?></td>
                    <td><?php echo $valor_anterior ?></td>
                    <td><?php echo $value["valor"] ?></td>
                    <td>0</td>
                    <td><?php
                        echo $value["valor"] + $valor_anterior;
                        $objeto->actualizarImpuesto($id_clienteFecha, $tipoImp, $value['valor'] + $valor_anterior);
                        ?></td>
                </tr>


            <?php } ?>

            <tr class="fondo">
                <td>Pasivos Circulantes</td>
                <td><?php echo $var_anterior; ?></td>

                <td><?php echo 0 ?></td>
                <td><?php
                    echo $var;
                    ?></td>
                <td><?php echo $var + $var_anterior; ?></td>
            </tr>
            <tr class="">
                <td>Cuentas por pagar</td>
                <td><?php echo $var_anterior; ?></td>

                <td><?php echo 0; ?></td>
                <td><?php echo $var; ?></td>
                <td><?php echo $var + $var_anterior; ?></td>
            </tr>

            <tr class="fondo">
                <td>Patrimonio del TCP</td>
                <td><?php
                    if ($patrimonio != null) {
                        echo $patrimonio['saldoAnterior'];
                        ?></td>

                    <td><?php echo $patrimonio["debe"] ?></td>
                    <td><?php echo $patrimonio["haber"] ?></td>
                    <td><?php echo $patrimonio['saldoAnterior'] + $patrimonio["haber"] - $patrimonio["debe"];
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
                            echo $fila['saldoAnterior'] + $fila["valor"];
                            if (is_numeric(array_search('600', array_column($libroMayor, 'id_cuenta'))) && is_numeric(array_search($fila["id_subcuenta"], array_column($libroMayor[$objeto->buscar('600', $libroMayor, 'id_cuenta')]['subcuentas'], 'id_subcuenta'))))
                                $objeto->crearInstanciaMayor('600', $id_clienteFecha, $fila['saldoAnterior'] + $fila["valor"], $fila["id_subcuenta"], "subcuenta");
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
            if ($value['valor'] != 0 || $value['saldo_final'] != 0) {
                ?>

                <tr class="">
                    <td><?php
                        if ($value['tipo_impuesto'] == "cuotaFija") {
                            echo "Cuota Fija";
                            $tipoImp = "cuotaFija";
                        } else {
                            echo "Seguridad Social";

                            $tipoImp = "seguridadSocial";
                        }
                        ?></td>
                    <td><?php
                        if (count($impuestos_patrimonio_mes_anterior) != 0) {

                            if ($impuestos_patrimonio_mes_anterior[$cont]["valor"] == 0) {
                                echo 0;
                                $actual = 0;
                            } else {
                                $actual = $impuestos_patrimonio_mes_anterior[$cont]["saldo_final"];
                                echo $actual;
                                $cont++;
                            }
                        } else {
                            $actual = 0;
                            echo 0;
                        }
                        ?></td>
                    <td><?php echo $value["valor"] ?></td>
                    <td>0</td>
                    <td><?php
                        if (count($impuestos_patrimonio_mes_anterior) != 0) {
                            echo $actual - $value["valor"];
                            $objeto->actualizarImpuesto($id_clienteFecha, $tipoImp, $actual - $value["valor"]);
                        } else {
                            echo - $value["valor"];
                            $objeto->actualizarImpuesto($id_clienteFecha, $tipoImp, $actual - $value["valor"]);
                        }
                        ?></td>
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
            <td><?php echo $ingresoBruto["saldoAnterior"] + $ingresoBruto["haber"] - $ingresoBruto["debe"] ?></td>
        </tr>
        <?Php ?>
        <tr class="">
            <td><?php echo $ingresoBruto["nombre"] ?></td>
            <td><?php echo $ingresoBruto["saldoAnterior"] ?> </td>
            <td><?php echo $ingresoBruto["debe"] ?></td>
            <td><?php echo $ingresoBruto["haber"] ?></td>

            <td><?php
                echo $ingresoBruto["saldoAnterior"] + $ingresoBruto["haber"] - $ingresoBruto["debe"];
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
