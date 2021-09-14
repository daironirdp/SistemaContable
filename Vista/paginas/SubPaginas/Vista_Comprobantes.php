<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
//echo var_dump($datos);
$cuentaAux = new Cuentas();
$comprobantes = $objeto->ObtenerComprobantes($id_clienteFecha);

?>

<div class="" style="
     display: flex;
     flex-direction: column;
     flex: 0.9;
     align-items: center;"  >  

    <H3>Comprobantes de Operaciones</H3>
    <div style="display: flex; flex-wrap: wrap">
        <?php
        for ($i = 0; $i < count($datos); $i++) {
            ?>

            <div style="">


                <table border="2" class="table table-hover table-striped table-bordered" style="text-align: center;margin-top: 15px; ">
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
                        <?php ?>
                        <tr>
                            <td><?php echo $datos[$i]["id_cuenta"] ?></td>
                            <?php if (!is_array($datos[$i]["subcuentas"])) { ?>

                                <td><?php echo $datos[$i]["subcuentas"] ?></td>

                                <td><?php echo '--' ?></td>
                                <td><?php echo $datos[$i]["nombre_cuenta"] ?></td>
                                <td>--</td>
                                <td><?php echo $datos[$i]["valor"] ?></td>
                                <td></td>
                            </tr>

                        <?php } else {
                            ?>
                        <td></td>
                        <td>--</td>
                        <td><?php echo $datos[$i]["nombre_cuenta"] ?></td>
                        <td>--</td>
                        <td><?php echo $datos[$i]["valor"] ?></td>
                        <td></td>
                        </tr>
                        <?php
                        for ($j = 0; $j < count($datos[$i]["subcuentas"]); $j++) {
                            ?>
                            <?php if ($datos[$i]["subcuentas"][$j]['alcance'] == 'subcuenta1') { ?>
                                <tr>
                                    <td>--</td>
                                    <td><?php echo $datos[$i]["subcuentas"][$j]['id_subcuenta'] ?></td>
                                    <td>--</td>
                                    <td><?php echo $datos[$i]["subcuentas"][$j]['nombre_subcuenta'] ?></td>
                                    <td><?php echo $datos[$i]["subcuentas"][$j]['valor'] ?></td>
                                </tr>


                                <?php
                            }
                        }
                    }
                    ?>
                    <tr>

                        <td><?php echo $datos[$i]["contrapartida"] ?></td>

                        <?php if (!is_array($datos[$i]["subcuentas"])) {
                            ?>

                            <td><?php echo $datos[$i]["subcuentas"] ?></td>
                            <td>--</td>
                            <td><?php echo $cuentaAux->obtenerCuentaXid($datos[$i]["contrapartida"])[0]['nombre_cuenta']; ?></td>
                            <td>--</td>

                            <td></td>
                            <td><?php echo $datos[$i]["valor"] ?></td>
                        </tr>
                    <?php } else { ?>
                        <td></td>

                        <td>--</td>
                        <td><?php echo $cuentaAux->obtenerCuentaXid($datos[$i]["contrapartida"])[0]['nombre_cuenta']; ?></td>
                        <td></td>
                        <td></td>
                        <td><?php echo $datos[$i]["valor"] ?></td>
                        </tr>
                        <?php
                        for ($j = 0; $j < count($datos[$i]["subcuentas"]); $j++) {
                            if ($datos[$i]["subcuentas"][$j]['alcance'] == 'subcuenta2') {
                                ?>
                                <tr>
                                    <td>--</td>
                                    <td><?php echo $datos[$i]["subcuentas"][$j]['id_subcuenta'] ?></td>
                                    <td>--</td>
                                    <td><?php echo $datos[$i]["subcuentas"][$j]['nombre_subcuenta'] ?></td>
                                    <td><?php echo $datos[$i]["subcuentas"][$j]['valor'] ?></td>
                                </tr>

                                <?php
                            }
                        }
                        ?>


                    <?php } ?>

                    <tr><td colspan="7">No.comprobante <?php echo $i + 1 ?></td> </tr>

                    <tr>
                        <td>Descripcion </td>
                        <td colspan="5">

                            <p ondblclick="ModificarComprobante(event, 'descripcion'<?php echo $i + 1 ?>)" class=" <?php
                    if ($comprobantes[$i]['descripcion'] == '') {
                        echo 'oculto';
                    }
                    ?>" id="descripcionOculta<?php echo $i + 1 ?>"><?php echo $comprobantes[$i]['descripcion'] ?></p>
                            <textarea class="form-control <?php
                        if ($comprobantes[$i]['descripcion'] != '') {
                            echo 'oculto';
                        }
                    ?>" rows="5" style="resize: none" cols="45" id="descripcion<?php echo $i + 1 ?>"  name="descripcion"><?php echo $comprobantes[$i]['descripcion'] ?></textarea>  </td>

                        <td style="border: none;">
                            <button class="btn btn-primary <?php
                        if ($comprobantes[$i]['descripcion'] != '') {
                            echo 'oculto';
                        }
                    ?>" id="guardar<?php echo $i + 1 ?>"  onClick="GuardarComprobante(event, 'descripcion<?php echo $i + 1 ?>', 'descripcionOculta<?php echo $i + 1 ?>')">Guardar</button>
                            <button class="btn btn-primary <?php
                        if ($comprobantes[$i]['descripcion'] == '') {
                            echo 'oculto';
                        }
                    ?>" id="modificar<?php echo $i + 1 ?>"  onClick="GuardarComprobante(event, 'descripcion<?php echo $i + 1 ?>', 'descripcionOculta<?php echo $i + 1 ?>')">Modificar</button>
                        </td>
                    </tr>


                    </tbody>
                </table>



            </div>

        <?php }
        ?>
        <table border="2" class="table table-hover table-striped table-bordered" style="text-align: center;margin-top: 15px;width: 65%">

            <thead>
            <th>Numero</th>
            <th>Subcuenta</th>
            <th>Folio</th>
            <th>Concepto</th>
            <th>Parcial</th>
            <th>Debe</th>
            <th>Haber</th>                        
            </thead>
            <tr>
                <td>810</td>
                <td></td>
                <td>--</td>
                <td>Tazas</td>
                <td>--</td>
                <td><?php echo $impuestos[0] + $FuerzaW[0]; ?></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>126</td>
                <td>--</td>
                <td>Impuesto10%</td>
                <td><?php echo $impuestos[0]; ?></td>

            </tr>
            <?php if ($FuerzaW[0] != 0) { ?>
                <tr>
                    <td></td>
                    <td>90</td>
                    <td>--</td>
                    <td>Fuerza de trabajo</td>
                    <td><?php echo $FuerzaW[0]; ?></td>

                </tr>
            <?php } ?>
            <tr>
                <td>600</td>
                <td></td>
                <td>--</td>
                <td>Patrimonio</td>
                <td>--</td>
                <td><?php echo $cuotaFija[0] + $SeguridadSocial[0]; ?></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>37</td>
                <td>--</td>
                <td>Cuota Fija</td>
                <td><?php echo $cuotaFija[0]; ?></td>

            </tr>
            <?php if ($SeguridadSocial[0] != 0) { ?>
                <tr>
                    <td></td>
                    <td>50</td>
                    <td>--</td>
                    <td>Seguridad Social</td>
                    <td><?php echo $SeguridadSocial[0]; ?></td>

                </tr>
            <?php } ?>
            <tr>
                <td>100</td>
                <td></td>
                <td>--</td>
                <td>Caja</td>
                <td>--</td>
                <td></td>
                <td><?php echo $cuotaFija[0] + $SeguridadSocial[0] + $impuestos[0] + $FuerzaW[0]; ?></td>
            </tr>
            <tr><td colspan="7">No.comprobante <?php echo $i + 1 ?></td> </tr>

            <tr>
                <td>Descripcion </td>
                <td colspan="6">

                    <p>Contab.el pago de los tributos</p>

            </tr>
        </table>
    </div>


</div>

<script>

    function GuardarComprobante(e, id1, id2) {
        e.preventDefault();
        var id = id1.slice(11);
        var valor = $("#" + id1).val();
        var array = [valor, id];
        $("#" + id2).replaceWith("<p ondblclick='ModificarComprobante(event," + id1 + ")' class='oculto' id='" + id2 + "'>" + $("#" + id1).val() + "</p>")
        $('#' + id2).toggleClass("oculto");
        $('#' + id1).toggleClass("oculto");
        $('#modificar' + id).toggleClass("oculto");
        $('#guardar' + id).toggleClass("oculto");
        EnviarDatosAjax('actualizarDescripcionComprobante', array, '0');

    }




</script>