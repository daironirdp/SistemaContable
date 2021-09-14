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
    $libroMayor = $objeto->obtenerInstanciasMayor($id_clienteFecha, 0);
} else {
    $libroMayor = $objeto->obtenerInstanciasMayor($id_clienteFecha, $id_clienteFecha2[0][0]);
}

$activoCirculante = array();
$gastos = null;
$patrimonio = null;
$ingresoBruto = null;
$impuestos_gastos = array();
$impuestos_patrimonio = array();
foreach ($libroMayor as $fila) {
    if ($fila["id_cuenta"] == 100 || $fila["id_cuenta"] == 110) {
        array_push($activoCirculante, $fila);
    } else if ($fila["id_cuenta"] == 800) {
        $gastos = $fila;
    } else if ($fila["id_cuenta"] == 600) {
        $patrimonio = $fila;
    } else if ($fila["id_cuenta"] == 900) {
        $ingresoBruto = $fila;
    }
}
foreach ($impuestos_datos as $value) {

    if ($value['tipo_impuesto'] == 'impuesto10%' || $value['tipo_impuesto'] == 'fuerzaTrabajo') {
        array_push($impuestos_gastos, $value);
    } else if ($value['tipo_impuesto'] == 'cuotaFija' || $value['tipo_impuesto'] == 'seguridadSocial') {
        array_push($impuestos_patrimonio, $value);
    }
}

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
                <td><?php echo ($activoCirculante[0]["saldoAnterior"][0] + $activoCirculante[1]["saldoAnterior"][0]) - ($activoCirculante[1]["saldoAnterior"][1] + $activoCirculante[1]["saldoAnterior"][1]) ?></td>
                <td><?php echo $activoCirculante[0]["debe"] + $activoCirculante[1]["debe"] ?></td>
                <td><?php echo $activoCirculante[0]["haber"] + $activoCirculante[1]["haber"] ?></td>
                <td><?php echo ($activoCirculante[0]["debe"] + $activoCirculante[1]["debe"]) - ($activoCirculante[0]["haber"] + $activoCirculante[1]["haber"]) ?></td>
            </tr>
            <?php
            foreach ($activoCirculante as $fila) {
                ?>
                <tr class="">
                    <td><?php echo $fila["nombre"]; ?></td>
                    <td><?php echo $fila["saldoAnterior"][1] - $fila["saldoAnterior"][0] ?></td>
                    <td><?php echo $fila["debe"] ?></td>
                    <td><?php echo $fila["haber"] ?></td>
                    <td><?php echo $fila["debe"] - $fila["haber"] ?></td>
                </tr>

                <?php
            }
            ?>

            <tr class="fondo">
                <td>Gastos</td>
                <td><?php echo $gastos["saldoAnterior"][0] - $gastos["saldoAnterior"][1] ?></td>
                <td><?php echo $gastos["debe"] ?></td>
                <td><?php echo $gastos["haber"] ?></td>
                <td><?php echo $gastos["debe"] - $gastos["haber"] ?></td>
            </tr>
            <?php
            foreach ($gastos['subcuentas'] as $fila) {
                ?>
                <tr class="">
                    <td><?php echo $fila["nombre_subcuenta"] ?></td>
                    <td><?php ?></td>
                    <td><?php echo $fila["valor"] ?></td>
                    <td>0</td>
                    <td><?php echo $fila["valor"] ?></td>
                </tr>

                <?Php
            }
            ?>
            <?php foreach ($impuestos_gastos as $value) { ?>

                <tr class="">
                    <td><?php
                        if ($value['tipo_impuesto'] == "impuesto10%") {
                            echo "Impuestos servicios";
                        } else {
                            echo "Impuesto F. trabajo";
                        }
                        ?></td>
                    <td></td>
                    <td><?php echo $value["valor"] ?></td>
                    <td>0</td>
                    <td><?php echo $value["valor"] ?></td>
                </tr>


<?php } ?>
            <tr class="fondo">
                <td>Patrimonio del TCP</td>
                <td><?php echo $patrimonio["saldoAnterior"][0] - $patrimonio["saldoAnterior"][1] ?></td>
                <td><?php echo $patrimonio["debe"] ?></td>
                <td><?php echo $patrimonio["haber"] ?></td>
                <td><?php echo $patrimonio["debe"] - $patrimonio["haber"] ?></td>
            </tr>
            <?Php
            foreach ($patrimonio['subcuentas'] as $fila) {
                ?>
                <tr class="">
                    <td><?php echo $fila["nombre_subcuenta"] ?></td>
                    <td><?php ?></td>
                    <td><?php echo $fila["valor"] ?></td>
                    <td>0</td>
                    <td><?php echo $fila["valor"] ?></td>
                </tr>
                <?Php
            }
            ?>
<?php foreach ($impuestos_patrimonio as $value) { ?>

                <tr class="">
                    <td><?php
                        if ($value['tipo_impuesto'] == "cuotaFija") {
                            echo "Cuota Fija";
                        } else {
                            echo "Seguridad Social";
                        }
                        ?></td>
                    <td><?php echo $value["valor"] ?></td>
                    <td><?php echo $value["valor"] ?></td>
                    <td>0</td>
                    <td><?php echo $value["valor"] ?></td>
                </tr>


<?php } ?>

            <tr class="fondo">
                <td>Ingresos Brutos</td>
                <td><?php echo $ingresoBruto["saldoAnterior"][1] - $ingresoBruto["saldoAnterior"][0] ?> </td>
                <td><?php echo $ingresoBruto["debe"] ?></td>
                <td><?php echo $ingresoBruto["haber"] ?></td>
                <td><?php echo $ingresoBruto["debe"] - $ingresoBruto["haber"] ?></td>
            </tr>
<?Php ?>
            <tr class="">
                <td><?php echo $ingresoBruto["nombre"] ?></td>
                <td><?php echo $ingresoBruto["saldoAnterior"][1] - $ingresoBruto["saldoAnterior"][0] ?></td>
                <td><?php echo $ingresoBruto["debe"] ?></td>
                <td><?php echo $ingresoBruto["haber"] ?></td>

                <td><?php echo $ingresoBruto["debe"] - $ingresoBruto["haber"] ?></td>
            </tr>
<?Php ?>

        </tbody>

    </table>

</div>