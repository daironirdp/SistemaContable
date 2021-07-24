<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
// put your code here
extract($_REQUEST);

if (isset($include)) {
    require_once '../../../Modelo/CM_Contabilidad.php';
    require_once '../../../Modelo/Conexion.php';
    require_once '../../../Modelo/CM_Cuentas.php';
} else {
    require_once '../Modelo/CM_Contabilidad.php';
    require_once '../Modelo/CM_Cuentas.php';
    require_once '../Modelo/CM_Contabilidad.php';
    require_once '../Modelo/Conexion.php';
}

$contabilidad = new Contabilidad();
$cuentas = $contabilidad->obtenerInstanciasDiario($id_clienteFecha);
$objeto = new Cuentas();
$tipos_cuenta = $objeto->obtenerCuentas();
$subcuentas = $objeto->obtenerSubCuentas();
//$subcuentas = $contabilidad->obtenerInstanciasSubCuentas($id_mes_anno_cliente, $id_cuenta)
?>

<script>
    var cuentas = [];
    var subcuentas = [];
    var subcuentas2 = [];
    var insertados = [];
    var subcuenta_insertada = [];
    var subcuenta_insertada2 = [];
    function ObtenerSubcuentas(id_cuenta, target) {
        $.ajax({
            type: "POST",
            url: "../Controlador/CC_controlador.php?id_cuenta=" + id_cuenta + "&&accion=mostrarSubcuetasDeid",
            dataType: 'json',
            success: function (response) {
                //  $("#cuadroRespuesta").replaceWith("<div id='cuadroRespuesta'><div>");
                // $("#cuadroRespuesta").html(response);
                $("#" + target + " option").remove();
                if (target == 'subcuenta') {
                    subcuentas = response;
                } else {
                    subcuentas2 = response;
                }


                for (var i = 0; i < response.length; i++) {

                    $("#" + target).append("<option value=" + response[i]["id_subcuenta"] + ">" + response[i]["nombre_subcuenta"] + "</option")


                }
            }

        });
    }
    function mostrar(e) {

        if (e.target == contrapartida) {
            $("#gastos2").addClass("esconder");
            const valor = $("#contrapartida").val();
            ObtenerSubcuentas(valor, "subcuenta2");
            if (valor == "600") {
                $("#subcuentaD2").replaceWith(" <h5 id='subcuentaD2'></h5>");
                $("#subcuentaD2").append(valor + "  Patrimonio");
                $("#subcuenta2_contenedor").removeClass("esconder");
            } else if (valor == "800") {
                $("#subcuentaD2").replaceWith(" <h5 id='subcuentaD2'></h5>");
                $("#subcuentaD2").append(valor + "  Gastos");
                $("#subcuenta2_contenedor").removeClass("esconder");
                $("#gastos2").removeClass("esconder");
            } else if (valor == "810") {
                $("#subcuentaD2").replaceWith(" <h5 id='subcuentaD2'></h5>");
                $("#subcuentaD2").append(valor + " Impuestos y tasas ");
                $("#subcuenta2_contenedor").removeClass("esconder");
            } else {
                $("#subcuentaD2").replaceWith(" <h5 id='subcuentaD'></h5>");
                $("#subcuenta2_contenedor").addClass("esconder");
                subcuentas2 = [];
            }
        } else if (e.target == cuenta) {
            $("#gastos1").addClass("esconder");
            const valor = $("#cuenta").val();
            ObtenerSubcuentas(valor, "subcuenta");
            if (valor == "600") {
                $("#subcuentaD").replaceWith(" <h5 id='subcuentaD'></h5>");
                $("#subcuentaD").append(valor + "  Patrimonio");
                $("#subcuenta_contenedor").removeClass("esconder");
            } else if (valor == "800") {
                $("#subcuentaD").replaceWith(" <h5 id='subcuentaD'></h5>");
                $("#subcuentaD").append(valor + "  Gastos");
                $("#subcuenta_contenedor").removeClass("esconder");
                $("#gastos1").removeClass("esconder");
            } else if (valor == "810") {
                $("#subcuentaD").replaceWith(" <h5 id='subcuentaD'></h5>");
                $("#subcuentaD").append(valor + " Impuestos y tasas ");
                $("#subcuenta_contenedor").removeClass("esconder");
            } else {
                $("#subcuentaD").replaceWith(" <h5 id='subcuentaD'></h5>");
                $("#subcuenta_contenedor").addClass("esconder");
                subcuentas = [];
            }
        }


    }



</script>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>

        <h3>Instancias de Cuentas</h3>
        <form >


            <div id="contenedor" style="display: flex;justify-content: space-around;">

                <div id="debe">
                    <h5>Debe</h5>
                    <div id="principal">

                        <ul id="insertados">

                        </ul>
                        <label>Cuenta</label>

                        <select id="cuenta" onchange="mostrar(event)">
                            <option class="neutral"value="0">--seleccione--</option>
                            <?php
                            foreach ($tipos_cuenta as $tipo) {
                                ?>
                                <script>
                                    cuentas.push([<?php echo $tipo["id_cuenta"]; ?>, '<?php echo $tipo["nombre_cuenta"] ?>'])
                                </script>

                                <option value="<?php echo $tipo["id_cuenta"]; ?>"><?php echo $tipo["nombre_cuenta"] ?></option>
                            <?php }
                            ?>
                            <script>

                            </script>
                        </select>
                        <input type="number"value="0" id="debeValue" min="0">

                    </div>



                    <div id="subcuenta_contenedor" class="esconder">
                        <h4 id="">Subcuentas</h4>
                        <h5 id="subcuentaD"></h5>
                        <ul id="subcuentaInsertadas"></ul>
                        <label>Cuenta</label>
                        <select id="subcuenta">


                        </select>
                        <select id="gastos1" class="esconder">
                            <option value="1">Justificado</option>
                            <option value="2">Injustificado</option>
                            <option value="3">Especial</option>
                        </select>
                        <input type="number" value="0" id="valueSub1" >
                        <button onclick="insertar(event)" id="agregarSub1">Insertar</button>

                    </div>

                </div>

                <div id="haber">
                    <h5>Haber</h5>
                    <div id="principal2">

                        <ul id="insertados2">

                        </ul>
                        <select  id="contrapartida" onchange="mostrar(event)">
                            <option class="neutral"value="0">--seleccione--</option>
                            <?php
                            foreach ($tipos_cuenta as $tipo) {
                                ?>

                                <option value="<?php echo $tipo["id_cuenta"]; ?>"><?php echo $tipo["nombre_cuenta"] ?></option>
                            <?php }
                            ?>
                        </select>
                        <input type="number" value="0" id="haberValue" min="0">

                    </div>

                    <div id="subcuenta2_contenedor" class="esconder">
                        <h4 id="">Subcuentas</h4>
                        <h5 id="subcuentaD2"></h5>
                        <ul id="subcuentaInsertadas2"></ul>
                        <label>Cuenta</label>
                        <select id="subcuenta2">

                        </select>
                        <select id="gastos2" class="oculto">
                            <option value="1">Justificado</option>
                            <option value="2">Injustificado</option>
                            <option value="3">Especial</option>
                        </select>
                        <input type="number" value="0"id="valueSub2" >
                        <button onclick="insertar(event)" id="agregarSub2">Insertar</button>
                    </div>


                </div>

            </div>
            <select id="grupoComprobante">
                <option value="1">Comprobante 1</option>
                <option value="2">Comprobante 2</option>
                <option value="3">Comprobante 3</option>
                <option value="4">Comprobante 4</option>
                <option value="5">Comprobante 5</option>
                <option value="6">Comprobante 6</option>
                <option value="7">Comprobante 7</option>
                <option value="8">Comprobante 8</option>
                <option value="9">Comprobante 9</option>
                <option value="10">Comprobante 10</option>

            </select>

            <select id="estado">
                <option value="0">Nivel 1 (Diario)</option>
                <option value="4">Nivel 2 (Comprobantes)</option>
                <option value="5">Nivel 3 (Mayor)</option>


            </select>


            <button onclick="insertar(event)" id="i_ingresos">Insertar</button>
            <a href="?opcion=5&&opcion2=0&&nombre_cliente=<?php echo $nombre_cliente ?>&&nombre_anno=<?php echo $nombre_anno ?>&&nombre_mes=<?php echo $nombre_mes ?>&&id_clienteFecha=<?php echo $id_clienteFecha ?>&&id_cliente=<?php echo $id_cliente ?>&&id_anno=<?php echo $id_anno ?>&&tipo=<?php echo $tipo; ?>" >Generar</a>



        </form>






        <style>

            .esconder{
                display: none
            }
        </style>
        <script>

            function buscar(array, criterio, tipo) {
                var dato = false;
                for (var i = 0; i < array.length; i++) {
                    if (array[i][0] == criterio) {
                        if (tipo == "cuenta") {
                            dato = [array[i][0], array[i][1]]
                        } else {
                            dato = [array[i][0], array[i][1], array[i][3]]
                        }

                    }

                }
                return dato;

            }
            function insertar(e) {
                e.preventDefault();
                if (e.target == i_ingresos) {
                    var d = buscar(cuentas, $("#cuenta").val(), "cuenta");
                    var h = buscar(cuentas, $("#contrapartida").val(), "cuenta");
                    $("#insertados2").append("<li>" + $("#contrapartida").val() + "  " + h[1] + "    $ " + $("#haberValue").val() + "<a href='../Controlador/CC_Controlador.php?accion=eliminarInstancia&&id_cliente=<?php echo $id_cliente ?>&&id_anno=<?php echo $id_anno ?>&&nombre_cliente=<?php echo $nombre_cliente ?>&&nombre_anno=<?php echo $nombre_anno ?>&&nombre_mes=<?php echo $nombre_mes ?>&&id_clienteFecha=<?php echo $id_clienteFecha ?>&&opcion2=3&&id_cuenta=" + $("#cuenta").val() + "&&id_contrapartida=" + $("#contrapartida").val() + "'>Eliminar</a> </li>");
                    if (subcuenta_insertada.length == 0) {
                        $("#insertados").append("<li>" + $("#cuenta").val() + "  " + d[1] + "  $ " + $("#debeValue").val() + "</li>");
                        if (subcuenta_insertada2.length == 0) {
                            insertados.push([d[0], d[1], $("#debeValue").val(), h[0], h[1], $("#haberValue").val(), $("#grupoComprobante").val(), $("#estado").val()]);
                        } else {

                            $("#insertados2 li ").replaceWith("<li id='" + h[1] + $("#haberValue").val() + "'>" + $("#contrapartida").val() + "  " + h[1] + "  $ " + $("#haberValue").val() + "      <a href='../Controlador/CC_Controlador.php?accion=eliminarInstancia&&id_cliente=<?php echo $id_cliente ?>&&id_anno=<?php echo $id_anno ?>&&nombre_cliente=<?php echo $nombre_cliente ?>&&nombre_anno=<?php echo $nombre_anno ?>&&nombre_mes=<?php echo $nombre_mes ?>&&id_clienteFecha=<?php echo $id_clienteFecha ?>&&opcion2=3&&id_cuenta=" + $("#cuenta").val() + "&&id_contrapartida=" + $("#contrapartida").val() + "'>Eliminar</a> <ul></ul></li>");
                            for (var i = 0; i < subcuenta_insertada2.length; i++) {
                                $("#" + h[1] + $("#haberValue").val() + " ul").append("<li>" + subcuenta_insertada2[i][0] + "  " + subcuenta_insertada2[i][1] + "  $ " + subcuenta_insertada2[i][2] + "</li>")
                            }

                            if (d[0] == 800) {

                                insertados.push([d[0], d[1], $("#debeValue").val(), h[0], h[1], $("#haberValue").val(), $("#grupoComprobante").val(), $("#estado").val(), $("#gastos1").val(), subcuenta_insertada2]);

                            } else {

                                insertados.push([d[0], d[1], $("#debeValue").val(), h[0], h[1], $("#haberValue").val(), $("#grupoComprobante").val(), $("#estado").val(), subcuenta_insertada2]);
                            }


                            subcuenta_insertada2 = [];
                            $("#subcuentaInsertadas").replaceWith("<ul id='subcuentaInsertadas'></ul>");
                            $("#subcuentaInsertadas2").replaceWith("<ul id='subcuentaInsertadas2'></ul>");
                            $("#subcuentaD").replaceWith("<h5 id='subcuentaD2'></h5>");
                            $("#subcuentaD2").replaceWith("<h5 id='subcuentaD2'></h5>");

                        }



                    } else {
                        $("#insertados").append("<li id='" + d[1] + $("#debeValue").val() + "'>" + $("#cuenta").val() + "  " + d[1] + "  $ " + $("#debeValue").val() + " <ul></ul></li>");
                        for (var i = 0; i < subcuenta_insertada.length; i++) {
                            $("#" + d[1] + $("#debeValue").val() + " ul").append("<li>" + subcuenta_insertada[i][0] + "  " + subcuenta_insertada[i][1] + "  $ " + subcuenta_insertada[i][2] + "</li>")
                        }




                        if (d[0] == 800) {

                            insertados.push([d[0], d[1], $("#debeValue").val(), h[0], h[1], $("#haberValue").val(), $("#grupoComprobante").val(), $("#estado").val(), $("#gastos1").val(), subcuenta_insertada]);

                        } else {

                            insertados.push([d[0], d[1], $("#debeValue").val(), h[0], h[1], $("#haberValue").val(), $("#grupoComprobante").val(), $("#estado").val(), subcuenta_insertada]);
                        }
                        subcuenta_insertada = [];
                        $("#subcuentaInsertadas").replaceWith("<ul id='subcuentaInsertadas'></ul>");
                        $("#subcuentaInsertadas2").replaceWith("<ul id='subcuentaInsertadas2'></ul>");
                        $("#subcuentaD").replaceWith("<h5 id='subcuentaD2'></h5>");
                        $("#subcuentaD2").replaceWith("<h5 id='subcuentaD2'></h5>");


                    }

                    $(".neutral").attr("selected", "true");
                    $("#cuenta").change();
                    $("#debeValue").attr("value", "0");
                    $("#haberValue").attr("value", "0");
                    $("#valueSub2").attr("value", "0");
                    $("#valueSub1").attr("value", "0");
                    EnvioDatosAjax("crearInstanciaCuenta", insertados);


                } else if (e.target == agregarSub1) {

                    var d = buscar(subcuentas, $("#subcuenta").val(), "subcuenta");

                    $("#subcuentaInsertadas").append("<li>" + $("#subcuenta").val() + " " + d[2] + "    $ " + $("#valueSub1").val() + "</li>");
                    subcuenta_insertada.push([d[0], d[2], $("#valueSub1").val(), $("#gastos1").val()]);

                    $("#debeValue").attr("value", parseFloat($("#debeValue").val()) + parseFloat($("#valueSub1").val()));

                } else if (e.target == agregarSub2) {
                    var h = buscar(subcuentas2, $("#subcuenta2").val(), "subcuenta");
                    $("#subcuentaInsertadas2").append("<li>" + $("#subcuenta2").val() + "   " + h[2] + "   $ " + $("#valueSub2").val() + "</li>");
                    subcuenta_insertada2.push([h[0], h[2], $("#valueSub2").val(), $("#gastos2").val()]);
                    $("#haberValue").attr("value", parseFloat($("#haberValue").val()) + parseFloat($("#valueSub2").val()));

                }




            }




            /*  function EnvioDatosAjax(accion, instancias) {
             
             
             $.ajax({
             type: "POST",
             url: "../Controlador/CC_Controlador.php?accion=" + accion + "&&id_clienteFecha=<?php echo $id_clienteFecha; ?>",
             data: {'instancias': JSON.stringify(instancias)},
             success: function (response) {
             console.log(insertados);
             
             insertados = [];
             }
             
             });
             }*/

        </script>
    </body>
</html>