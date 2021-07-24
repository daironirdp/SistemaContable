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
            <table border="2" style="text-align: center;border: 1px solid">
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
                            <td><?php echo $d["nombre_cuenta"] ?></td>
                            <td><?php
                                if ($d["id_subcuenta"] == 0) {
                                    echo '--';
                                } else {
                                    echo $d["id_subcuenta"];
                                };
                                ?></td>
                            <td><?php
                                if ($d["id_subcuenta"] == 0) {
                                    echo "--";
                                } else {
                                    echo $d["nombre_subcuenta"];
                                }
                                ?></td>
                            <td ondblclick="modificar('label<?php echo $d["id_instanciaCuenta"] ?>', 'valor<?php echo $d["id_instanciaCuenta"] ?>')" colspan="1">

                                <span id="label<?php echo $d["id_instanciaCuenta"] ?>"><?php echo $d["valor"] ?></span> 
                                <span id="valor<?php echo $d["id_instanciaCuenta"] ?>"  class="oculto"> <input id="v<?php echo $d["id_instanciaCuenta"] ?>"onchange="actualizarEstado('v<?php echo $d["id_instanciaCuenta"] ?>')"type="number" value="<?php echo $d["valor"] ?>"/></span> 

                            </td>
                            <td colspan="2">
                                <a href="">Eliminar</a>

                            </td>
                        </tr>
                    <script>
    <?php if ($d['estado'] == 0) { ?>

                            state.push(['<?php echo $d["id_instanciaCuenta"]; ?>', '<?php echo $d["valor"]; ?>', 'ingreso']);

    <?php } else if ($d['estado'] == 1) { ?>

                            state.push(['<?php echo $d["id_instanciaCuenta"]; ?>', '<?php echo $d["valor"]; ?>', 'gastoJ']);
    <?php } else if ($d['estado'] == 2) { ?>

                            state.push(['<?php echo $d["id_instanciaCuenta"]; ?>', '<?php echo $d["valor"]; ?>', 'gastoI'])
    <?php } else if ($d['estado'] == 3) { ?>
                            state.push(['<?php echo $d["id_instanciaCuenta"]; ?>', '<?php echo $d["valor"]; ?>', 'especial'])

    <?php } ?>
                    </script>
                    <?php
                    if ($d['estado'] == 0) {

                        if ($d['id_cuenta'] == 100 || $d['id_cuenta'] == 110 || $d['id_cuenta'] == 1)
                            $ingresos += $d['valor'];
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
                    <td colspan="2"><?php echo $cuotaFija ?></td>
                </tr> 

                <tr>
                    <td colspan="3">Tributo 10%:</td>
                    <td colspan="2" id="tributo"><?php echo $ingresos * 0.10 ?></td>
                </tr>
                <tr>
                    <td colspan="3" >Utilidad/Perdidad:</td>
                    <td colspan="2" id="utilidadPerdida">
                        <?php echo $utilidadPerdida = $ingresos - ($gastosI + $gastosJ + $renta + $cuotaFija + $ingresos * 0.10 ) ?>

                    </td>
                <script>



                    $.ajax({
                        type: "POST",
                        url: "../Controlador/CC_Controlador.php?accion=actualizarAcumulado&&id_clienteFecha=<?php echo $id_clienteFecha; ?>&&acumulado= <?php echo round($utilidadPerdida) ?>&&tributo=<?php echo $ingresos * 0.10 ?>",
                        success: function (response) {
                            console.log(response);
                        }

                    });
                </script>
                </tr>




                <tr>
                    <td colspan="1">Gastos</td>
                    <td colspan="1">% Justif / valor:</td>
                    <td colspan="1">
                        <?php echo 80 //round(($gastosJ / ($gastosI + $gastosJ)) * 100, 2) ?>

                    </td>
                    <td colspan="1" id="gastoJustificado"><?php echo $gastosJ ?></td>
                    <td colspan="1">% Injustf / valor</td>
                    <td colspan="1"><?php echo 20 //round(($gastosI / ($gastosI + $gastosJ)) * 100, 2)                                     ?></td>
                    <td ondblclick="modificar('labelInjusti', 'inputInjusti')" colspan="1">

                        <span id="labelInjusti" class=""> <?php echo ($gastosJ / 0.8) - $gastosJ  //echo $gastosI                                     ?></span>
                        <span id="inputInjusti" class="oculto"><input type="number" value="<?php echo ($gastosJ / 0.8) - $gastosJ //echo $gastosI                                     ?>"/></span>
                    </td>
                </tr>

                </tbody>
            </table>
            <span style="margin-right: 40px" id="totalGastos">Total de gastos: <?php echo ($gastosJ / 0.8) ?></span>
            <a href=""onclick="toogleAgregar(event)">Agregar</a>
            <input type="submit" onclick="guardarCambios(event)" value="Guardar Cambios">

        </form>

    </div>

    <div id="agregar">


    </div>


</div>
<style>
    .oculto{
        display: none;
    }
</style>
<script>

    function modificar(id1, id2) {
        $("#" + id1).replaceWith("<span id='" + id1 + "' class='oculto'>" + $('#' + id2 + " input").val() + "</span>");
        $("#" + id1).toggleClass("oculto");
        $("#" + id2).toggleClass("oculto");
    }

    function actualizarEstado(id) {
        var id_inst = id.slice(1);

        if (state.length == 0) {
            state.push([id_inst, $("#" + id).val()]);

        } else {
            var encontrado = -1;
            for (var i = 0; i < state.length && encontrado == -1; i++) {
                encontrado = state[i].indexOf(id_inst);
            }

            if (encontrado != -1) {
                var utilidadPerdida = 0;
                var ingresoB = 0;
                var gastoj = 0;
                var gastoi = 0;
                var renta = 0;

                state[i - 1][1] = $("#" + id).val();
                for (i = 0; i < state.length; i++) {
                    if (state[i][2] == "ingreso") {
                        ingresoB += parseFloat(state[i][1]);

                    } else if (state[i][2] == "gastoJ") {
                        gastoj += parseFloat(state[i][1]);

                    } else if (state[i][2] == "gastoI") {
                        gastoi += parseFloat(state[i][1]);

                    } else if (state[i][2] == "especial") {
                        renta += parseFloat(state[i][1]);
                    }


                }
                var tributo = Math.round(ingresoB * 0.10);

                var utilidadPerdida = Math.round(ingresoB - (gastoi + gastoj + renta + cuotaFija + tributo));
                var posibleInjustinj = Math.round((gastoj / 0.8) - gastoj);
                var totalGastos = Math.round((gastoj / 0.8));
                $("#tributo").replaceWith("<td colspan='2'id='tributo'>" + tributo + "</td>");
                $("#gastoJustificado").replaceWith("<td colspan='1' id='gastoJustificado'>" + gastoj + "</td>");
                $("#utilidadPerdida").replaceWith("<td id='utilidadPerdida'colspan='2'>" + utilidadPerdida + "</td>");
                $("#labelInjusti").replaceWith("<span id='labelInjusti'>" + posibleInjustinj + "</span>");
                $("#inputInjusti").replaceWith("<span id='inputInjusti' class='oculto'><input type='number' value='" + posibleInjustinj + "'/></span>");
                $("#totalGastos").replaceWith("<span style='margin-right: 40px' id='totalGastos'>Total de gastos:" + totalGastos + "</span>");
            } else {

                state.push([id_inst, $("#" + id).val()]);

            }

        }


    }
    function guardarCambios(e) {
        e.preventDefault();
        EnviarDatosAjax("modificarInstancias", state);
    }

    function EnviarDatosAjax(accion, instancias) {


        $.ajax({
            type: "POST",
            url: "../Controlador/CC_Controlador.php?accion=" + accion + "&&id_clienteFecha=<?php echo $id_clienteFecha; ?>",
            data: {'instancias': JSON.stringify(instancias)},
            success: function (response) {
                console.log(response);
            }

        });
    }


    function toogleAgregar(e) {
        e.preventDefault();
        if (toogle == 0) {
            toogle = 1;
            mostrarInsertar();
        } else {
            $("#agregar").replaceWith("<div id='agregar'></div>");
            toogle = 0;

        }

    }

    function mostrarInsertar() {

        $.ajax({
            type: "POST",
            url: "paginas/SubPaginas/Vista_EmpezarContabilidad.php?id_clienteFecha=<?php echo $id_clienteFecha; ?>&&nombre_cliente=<?php echo $nombre_cliente; ?>&&nombre_anno=<?php echo $nombre_anno; ?>&&nombre_mes=<?php echo $nombre_mes; ?>&&id_anno=<?php echo $id_anno; ?>&&id_cliente=<?php echo $id_cliente ?>&&include=1",

            success: function (response) {
                $("#agregar").html(response);

            }

        });

    }

</script>