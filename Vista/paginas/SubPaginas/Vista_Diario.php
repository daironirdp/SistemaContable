<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<script>

    var state = [];
    var toogle = 0;

</script>
<div style="
     display: flex;
     flex-wrap: wrap;">
    <div class="" style="
         display: flex;
         flex-direction: column;
         flex: 0.9;
         align-items: center;"  >  

        <H3>Diario Contable</H3>

        <form style="
              text-align: center;
              "
              action="">
            <table border="2" class=" table table-striped  table-bordered table-hover"style="text-align: center; height: 10%">
                <thead>
                <th>Numero</th>
                <th>Cuenta</th>
                <th>Numero_Subcuenta</th>
                <th>Subcuenta</th>
                <th>Valor</th>
                <th colspan="2">Accion</th>

                </thead>

                <tbody>
                    <?php
                    foreach ($datos_mes as $d) {
                        ?>

                        <tr>
                            <td><?php echo $d["id_cuenta"] ?></td>
                            <td <?php
                            if ($d["id_cuenta"] == "800" and $d["estado"] != '3') {
                                ?>
                                    ondblclick="modificar2('gasto<?php echo $d['id_instanciaCuenta'] ?>', 'estado<?php echo $d['id_instanciaCuenta'] ?>')";
                                    <?php
                                }
                                ?>> <?php
                                    if ($d["id_cuenta"] == "800") {
                                        if ($d["estado"] == '1') {
                                            ?>   
                                        <span id="gasto<?php echo $d['id_instanciaCuenta'] ?>"><?php echo $d["nombre_cuenta"] . " justif."; ?></span>



                                        <?php
                                    } else if ($d["estado"] == '2') {
                                        ?>

                                        <span id="gasto<?php echo $d['id_instanciaCuenta'] ?>"><?php echo $d["nombre_cuenta"] . " injustif."; ?></span>
                                        <?php
                                    } else {
                                        echo $d["nombre_cuenta"];
                                    }
                                    ?>
                                    <select  onchange="actualizarTipoGastoEstado('<?php echo $d['id_instanciaCuenta'] ?>', 'estado<?php echo $d['id_instanciaCuenta'] ?>')" class="oculto form-control" id="estado<?php echo $d['id_instanciaCuenta'] ?>">
                                        <option <?php
                                        if ($d["estado"] == '1') {
                                            echo "selected";
                                        }
                                        ?> value="1">Justificado</option>
                                        <option  <?php
                                        if ($d["estado"] == '2') {
                                            echo "selected";
                                        }
                                        ?> value="2">Injustificado</option>
                                    </select>   
                                    <?php
                                } else {
                                    ?> 


                                    <?php
                                    echo $d["nombre_cuenta"];
                                }
                                ?>

                            </td>
                            <td><?php
                                if ($d["id_subcuenta"] == 0) {
                                    echo '--';
                                } else {
                                    $flag = $objeto->EsSubcuentaDe($d["id_cuenta"], $d["id_subcuenta"]);
                                    if ($flag) {
                                        echo $d["id_subcuenta"];
                                    } else {
                                        echo "--";
                                    }
                                };
                                ?></td>
                            <td><?php
                                if ($d["id_subcuenta"] == 0) {
                                    echo "--";
                                } else {
                                    if ($flag) {
                                        echo $d["nombre_subcuenta"];
                                    } else {
                                        echo "--";
                                    }
                                }
                                ?></td>
                            <td ondblclick="modificar('label<?php echo $d["id_instanciaCuenta"] ?>', 'valor<?php echo $d["id_instanciaCuenta"] ?>')" colspan="1">

                                <span id="label<?php echo $d["id_instanciaCuenta"] ?>"><?php echo $d["valor"] ?></span> 
                                <span id="valor<?php echo $d["id_instanciaCuenta"] ?>"  class="oculto"> <input class="form-control" id="v<?php echo $d["id_instanciaCuenta"] ?>"onchange="actualizarEstado('v<?php echo $d["id_instanciaCuenta"] ?>')"type="number" value="<?php echo $d["valor"] ?>"/></span> 

                            </td>

                            <td colspan="2">
                                <a href="../Controlador/CC_Controlador.php?accion=Eliminar&id_instanciaCuenta=<?php echo $d["id_instanciaCuenta"] ?>&&&id_cliente=<?php echo $id_cliente; ?>&&id_anno=<?php echo $id_anno; ?>&&nombre_cliente=<?php echo $nombre_cliente; ?>&&nombre_anno=<?php echo $nombre_anno; ?>&&nombre_mes=<?php echo $nombre_mes; ?>&&id_clienteFecha=<?php echo $id_clienteFecha; ?>&&opcion2=0&&tipo=<?php echo $tipo; ?>">Eliminar</a>

                            </td>

                        </tr>
                    <script>
    <?php if ($d['estado'] == 0) { ?>
        <?php if ($d['contrapartida'] == 900) { ?>
                                state.push(['<?php echo $d["id_instanciaCuenta"]; ?>', '<?php echo $d["valor"]; ?>', 'ingreso']);
        <?php } else { ?>

                                state.push(['<?php echo $d["id_instanciaCuenta"]; ?>', '<?php echo $d["valor"]; ?>', 'ingresox']);

            <?php
        }
    } else if ($d['estado'] == 1) {
        ?>

                            state.push(['<?php echo $d["id_instanciaCuenta"]; ?>', '<?php echo $d["valor"]; ?>', 'gastoJ']);
    <?php } else if ($d['estado'] == 2) { ?>

                            state.push(['<?php echo $d["id_instanciaCuenta"]; ?>', '<?php echo $d["valor"]; ?>', 'gastoI'])
    <?php } else if ($d['estado'] == 3) { ?>
                            state.push(['<?php echo $d["id_instanciaCuenta"]; ?>', '<?php echo $d["valor"]; ?>', 'especial'])

    <?php } ?>
                    </script>
                    <?php
                    if ($d['estado'] == 0) {
//esto esta mal
                        if ($d['contrapartida'] == 900) {

                            $ingresos += $d['valor'];
                        } else {
                            $ingresosx += $d['valor'];
                        }
                    } else if ($d['estado'] == 1) {

                        $gastosJ += $d['valor'];
                    } else if ($d['estado'] == 2) {

                        $gastosI += $d['valor'];
                    } else if ($d['estado'] == 3) {

                        $renta = $d['valor'];
                    }
                }
                ?>

                <tr>
                    <td colspan="3">Cuota Fija:</td>
                    <td colspan="<?php
                if ($cuotaFija[1] == 1) {
                    echo 2;
                } else {
                    echo 3;
                }
                ?>"><?php echo $cuotaFija[0] ?></td>

                    <?php
                    // si se pago con tranfermovil descontarle un 3%  
                    if ($cuotaFija[1] == 1) {
                        ?>
                        <td colspan="2" id="cuotafija2"><?php
                    echo $cuotaFija[0] - $cuotaFija[0] * 0.03;
                        ?></td>
                        <?php } ?>

                </tr> 

                <tr>
                    <td colspan="3">Tributo 10%:</td>
                    <td colspan="<?php
                        if ($impuestos[1] == 1) {
                            echo 2;
                        } else {
                            echo 3;
                        }
                        ?>" id="tributo"><?php echo $ingresos * 0.10; ?></td>
                        <?php
                        // si se pago con tranfermovil descontarle un 3% y  si no esta guardado el valor calcularlo 
                        if ($impuestos[1] == 1) {
                            ?>
                        <td colspan="2" id="tributo2"><?php
                        if ($impuestos[0] == 0)
                            echo $ingresos * 0.10 - ($ingresos * 0.10) * 0.03;
                        else
                            echo $impuestos[0] - $impuestos * 0.03;
                            ?></td>
                        <?php } ?>
                </tr>
                <?php
//si es un mes trimestre mostrar :
                if ($tipomes == 1) {
                    ?>
                    <tr>
                        <td colspan="3">Fuerza trabajo 5%:</td>
                        <td colspan="<?php
                // si se pago con trandermovil dividir el espacio en 2
                if ($FuerzaW[1] == 1) {
                    echo 2;
                } else {
                    echo 3;
                }
                    ?>" id="fuerzaW"><?php echo $FuerzaW[0] ?></td>
                            <?php
                            // si se pago con tranfermovil descontarle un 3% 
                            if ($FuerzaW[1] == 1) {
                                ?>
                            <td colspan="2" id="fuerzaW2"><?php
                        echo $FuerzaW[0] - ($FuerzaW[0] * 0.03);
                                ?></td>
                            <?php } ?>
                    </tr>
                    <tr>
                        <td colspan="3">Seguridad social:</td>
                        <td colspan="<?php
                        if ($SeguridadSocial[1] == 1) {
                            echo 1;
                        } else {
                            echo 3;
                        }
                            ?>" id="SeguridadSocial"><?php echo $SeguridadSocial[0] ?></td>

                        <?php
                        // si se pago con tranfermovil descontarle un 3%  
                        if ($SeguridadSocial[1] == 1) {
                            ?>
                            <td colspan="2" id="seguridadSocial2"><?php
                    echo $SeguridadSocial[0] - $SeguridadSocial[0] * 0.03;
                            ?></td>
                            <?php } ?>

                    </tr>

                <?php } ?>
                <tr>
                    <td colspan="3" >Utilidad/Perdidad:</td>
                    <td colspan="3" id="utilidadPerdida">
                        <?php echo $utilidadPerdida = $ingresos - ($gastosI + $gastosJ + $renta + $cuotaFija[0] + $ingresos * 0.10 + $SeguridadSocial[0] + $FuerzaW[0] ) ?>

                    </td>
                <script>



                    $.ajax({
                        type: "POST",
                        url: "../Controlador/CC_Controlador.php?accion=actualizarAcumulado&&id_clienteFecha=<?php echo $id_clienteFecha; ?>&&acumulado= <?php echo round($utilidadPerdida) ?>&&tributo=<?php echo $ingresos * 0.10 ?>",
                        success: function (response) {
                            console.log(response);
                        }

                    });
                    $.ajax({
                        type: "POST",
                        url: "../Controlador/CC_Controlador.php?accion=actualizarImpuestos&&id_clienteFecha=<?php echo $id_clienteFecha; ?>&&FuerzaW=<?php echo $FuerzaW[0] ?>&&impuesto=<?php echo $ingresos * 0.10 ?>",
                        success: function (response) {
                            console.log(response);
                        }

                    });
                </script>
                </tr>




                <tr>
                    <td colspan="6">Gastos</td>

                </tr>
                <tr>
                    <td colspan="1">Justif</td>
                    <td colspan="1" id="gastoJustificadoXciento1">
                        <?php echo round((( $gastosJ) / $ingresos) * 100, 2) . "%"; ?>

                    </td>
                    <td colspan="1" id="gastoJustificado"><?php echo $gastosJ ?></td> 

                </tr>

                <tr>

                    <td colspan="1">Injustif</td>
                    <td colspan="1" id="gastoInjustificadoXciento1"><?php
                        echo round(($gastosI / $ingresos) * 100, 2);
                        ?></td>

                    <td colspan="1">

                        <span id="labelInjusti" class=""><?php
                        echo $gastosI;
//                       
                        ?></span>

                    </td>

                </tr>

                <tr>
                    <td>
                        Total:
                    </td>
                    <td id="total1"> <?php echo round((( $gastosJ) / $ingresos) * 100, 2) + round(($gastosI / $ingresos) * 100, 2); ?></td>
                    <td id="total2"><?php echo $gastosJ + $gastosI ?></td>

                </tr>
                </tbody>
            </table>
            <span style="margin-right: 40px" id="totalGastos">Total de gastos: <?php echo $gastosJ + $gastosI + $renta + ($FuerzaW[0] + $SeguridadSocial[0] + $cuotaFija[0] + $ingresos * 0.10 ) ?></span>
            <a href=""onclick="toogleAgregar(event, id_cliente, id_clienteFecha, nombre_cliente, nombre_anno, nombre_mes, id_anno, tipo, id_mes)">Agregar</a>
            <input class="btn btn-primary" type="submit" onclick="guardarCambios(event)" value="Guardar">

        </form>

    </div>




</div>

<script>
    console.log("El estado es :");
    console.log(state);

</script>
