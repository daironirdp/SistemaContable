<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
$comprobantes = array();

for ($i = 0; $i < count($datos); $i++) {

    if ($datos[$i]["grupoComprobante"] == 1) {
        $comprobantes[0][] = $datos[$i];
    } else if ($datos[$i]["grupoComprobante"] == 2) {
        $comprobantes[1][] = $datos[$i];
    } else if ($datos[$i]["grupoComprobante"] == 3) {
        $comprobantes[2][] = $datos[$i];
    } else if ($datos[$i]["grupoComprobante"] == 4) {
        $comprobantes[3][] = $datos[$i];
    } else if ($datos[$i]["grupoComprobante"] == 5) {
        $comprobantes[4][] = $datos[$i];
    }
}

?>
<div class="" style="
     display: flex;
     flex-direction: column;
     flex: 0.9;
     align-items: center;"  >  

    <H3>Comprobantes de Operaciones</H3>
    <div style="display: flex; flex-wrap: wrap">
        <?php
        for ($i = 0; $i < count($comprobantes); $i++) {
            ?>

            <div style="">


                <table border="2" style="text-align: center;border: 1px solid">
                    <thead>
                    <th>Numero</th>
                    <th>Subcuenta</th>
                    <th>Folio</th>
                    <th>Concepto</th>
                    <th>Parcial</th>
                    <th>Debe</th>
                    <th>Haber</th>
                    </thead>

                    <tbody>
                        <?php
                        for ($j = 0; $j < count($comprobantes[$i]); $j++) {
                            ?>
                            <tr>
                                <td><?php echo $comprobantes[$i][$j]["id_cuenta"]?></td>
                                <td><?php echo $comprobantes[$i][$j]["id_subcuenta"]?></td>
                                <td><?php echo $i+1?></td>
                                <td><?php echo $comprobantes[$i][$j]["nombre_cuenta"]?></td>
                                <td>--</td>
                                <td><?php echo $comprobantes[$i][$j]["valor"]?></td>
                                <td></td>
                            </tr>

                            <?php
                        }
                        ?>
                      <!--  <tr>
                            <td>1</td>
                            <td></td>
                            <td>1</td>
                            <td>Transferencia</td>
                            <td>--</td>
                            <td>5000</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>100</td>
                            <td></td>
                            <td>1</td>
                            <td>Caja</td>
                            <td>--</td>
                            <td>1000</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>900</td>
                            <td></td>
                            <td>1</td>
                            <td>Ingreso B.</td>
                            <td>--</td>
                            <td></td>
                            <td>14746</td>
                        </tr>
                        <tr>
                            <td>600</td>
                            <td></td>
                            <td>1</td>
                            <td>Patrimonio</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="1"></td>
                            <td colspan="1">20</td>
                            <td></td>
                            <td colspan="2">Deposito</td>
                            <td></td>
                            <td colspan="2">1000</td>
                        </tr>
                      !-->

                    </tbody>
                </table>


            </div>

        <?php }
        ?>

    </div>


</div>