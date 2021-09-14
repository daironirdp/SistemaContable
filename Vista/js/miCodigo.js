


//funcion para enviar los datos para crear instancias habilitada para:
//Diario Contable,Comprobante de operaciones, Mayor,Inicio de contabilidad
function EnvioDatosAjax(accion, instancias, id_clienteFecha, nombre_cliente, nombre_anno, nombre_mes, id_anno) {


    $.ajax({
        type: "POST",
        url: "../Controlador/CC_Controlador.php?accion=" + accion + "&&id_clienteFecha=" + id_clienteFecha + "&&nombre_cliente=" + nombre_cliente + "&&nombre_anno=" + nombre_anno + "&&nombre_mes=" + nombre_mes + "id_anno=" + id_anno,
        data: {'instancias': JSON.stringify(instancias)},
        success: function (response) {
            console.log(insertados);

            insertados = [];
        }

    });
}

//funcion para enviar los datos y actualizarlos
//Habilitada para diario contable,Comprobante de operaciones, Mayor
function EnviarDatosAjax(accion, instancias, extra) {


    $.ajax({
        type: "POST",
        url: "../Controlador/CC_Controlador.php?accion=" + accion + "&&id_clienteFecha=" + id_clienteFecha + "&extra=" + extra + "",
        data: {'instancias': JSON.stringify(instancias)},
        success: function (response) {
            console.log(response);
        }

    });
}



//mi codigo de diario 

function modificar(id1, id2) {
    $("#" + id1).replaceWith("<span id='" + id1 + "' class='oculto'>" + $('#' + id2 + " input").val() + "</span>");
    $("#" + id1).toggleClass("oculto");
    $("#" + id2).toggleClass("oculto");
}
function modificar2(id1, id2) {
    if ($('#' + id2).val() == '2') {
        var tipo = "Gastos injustif.";
    } else {
        var tipo = "Gasto Justif.";
    }
    $("#" + id1).replaceWith("<span id='" + id1 + "' class='oculto'>" + tipo + "</span>");
    console.log($('#' + id2).val());
    $("#" + id1).toggleClass("oculto");
    $("#" + id2).toggleClass("oculto");
}



function actualizarTipoGastoEstado(id_inst, id_select) {
    var encontrado = -1;
    for (var i = 0; i < state.length && encontrado == -1; i++) {
        encontrado = state[i].indexOf(id_inst);

    }
    if (encontrado != -1)
        if ($("#" + id_select).val() == '1') {
            state[i - 1][2] = "gastoJ";
        } else {
            state[i - 1][2] = "gastoI";
        }

    actualizarEstado("v" + id_inst);
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
            var totalGastos = gastoi + gastoj;
            var utilidadPerdida = ingresoB - gastoi - gastoj - renta - cuotaFija - seguridadSocial - fuerzaW - tributo;
            var porCientoJ = (Math.round((gastoj / ingresoB) * 10000)) / 100;
            var porCientoI = (Math.round(((gastoi / ingresoB) * 10000))) / 100;
            var porcientoTotalGastos = (Math.round((porCientoI + porCientoJ) * 100)) / 100;
            console.log($("#inputInjustiXciento input").val());

            $("#tributo").replaceWith("<td colspan='2'id='tributo'>" + tributo + "</td>");
            $("#gastoJustificado").replaceWith("<td colspan='1' id='gastoJustificado'>" + gastoj + "</td>");
            $("#gastoJustificadoXciento1").replaceWith("<td colspan='1' id='gastoJustificadoXciento1'>" + porCientoJ + "</td>");
            $("#gastoInjustificadoXciento1").replaceWith("<td colspan='1' id='gastoInjustificadoXciento1'>" + porCientoI + "</td>");
            $("#utilidadPerdida").replaceWith("<td id='utilidadPerdida'colspan='2'>" + utilidadPerdida + "</td>");
            $("#labelInjusti").replaceWith("<span id='labelInjusti'>" + gastoi + "</span>");
            $("#inputInjusti").replaceWith("<span id='inputInjusti' class='oculto'><input type='number' value='" + gastoi + "'/></span>");
            $("#totalGastos").replaceWith("<span style='margin-right: 40px' id='totalGastos'>Total de gastos:" + Math.round((totalGastos+renta) * 100) / 100 + "</span>");

            $("#total1").replaceWith("<td id='total1'>" + porcientoTotalGastos + "</td>");
            $("#total2").replaceWith("<td id='total2'>" + totalGastos + "</td>");
            // $("#total3").replaceWith("<td id='total3'>" + (parseFloat($("#inputInjustiXciento input").val()) + 80) + "</td>");

            var sobreIngreso = (Math.round(((gastoi / ingresoB) * 100) * 100)) / 100;
            var sobreTgastos = $("#inputInjustiXciento input").val();


        } else {

            state.push([id_inst, $("#" + id).val()]);

        }

    }

    console.log("El estado es :");
    console.log(state);
}
function guardarCambios(e) {
    e.preventDefault();
    EnviarDatosAjax("modificarInstancias", state, $("#inputInjustiXciento input").val());
}

function toogleAgregar(e, id_cliente, id_clienteFecha, nombre_cliente, nombre_anno, nombre_mes, id_anno, tipo) {
    e.preventDefault();
    if (toogle == 0) {
        toogle = 1;
        mostrarInsertar(id_cliente, id_clienteFecha, nombre_cliente, nombre_anno, nombre_mes, id_anno, tipo);
    } else {
        $("#agregar").replaceWith("<div id='agregar'></div>");
        toogle = 0;

    }

}

function mostrarInsertar(id_cliente, id_clienteFecha, nombre_cliente, nombre_anno, nombre_mes, id_anno, tipo) {

    $.ajax({
        type: "POST",
        url: "paginas/SubPaginas/Vista_EmpezarContabilidad.php?id_clienteFecha=" + id_clienteFecha + "&&nombre_cliente=" + nombre_cliente + "&&nombre_anno=" + nombre_anno + "&&nombre_mes=" + nombre_mes + "&&id_anno=" + id_anno + "&&id_cliente=" + id_cliente + "&&include=1&tipo=" + tipo + "",

        success: function (response) {
            $("#agregar").html(response);

        }

    });

}