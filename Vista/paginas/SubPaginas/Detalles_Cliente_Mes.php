<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
require '../Modelo/CM_FechaCliente.php';
extract($_REQUEST);
$objeto = new FechaCliente();
$meses = $objeto->mostrarMeses($id_cliente, $id_anno);

$mes_nuevo = end($meses);

if ($mes_nuevo["nombre"] != "Diciembre") {

    $mes_nuevo = $objeto->devolverMesSiguiente($mes_nuevo["id_mes"]);
} else {
    $mes_nuevo = "";
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <i class=" fa fa-user icono_user" aria-hidden="true"></i>
        <h3>Cliente: <?php echo $nombre_cliente; ?></h3>
        <h4>Anno: <?php echo $nombre_anno; ?></h4>

        <div class="elementosFlexibles1">
            <?php
            foreach ($meses as $m) {
                // put your code here
                ?>

                <div class="card text-center" style="margin-left: <?php echo floatval($nombre_anno) / 100 ?>px">
                    <div class="card-header">
                        <h4> Mes: <a href="?opcion=5&&id_cliente=<?php echo $id_cliente; ?>&&id_anno=<?php echo $id_anno; ?>&&nombre_cliente=<?php echo $nombre_cliente; ?>&&nombre_anno=<?php echo $nombre_anno; ?>&&nombre_mes=<?php echo $m["nombre"]; ?>&&id_clienteFecha=<?php echo $m["id_clienteFecha"]; ?>&&opcion2=0&&activa=diario&&id_mes=<?php echo $m["id_mes"]; ?>&&tipo=<?php echo $tipo; ?>"><?php echo $m["nombre"]; ?></a></h4>   
                    </div>
                    <div class="card-body">

                        <h5>Acumulado: <?php echo $m["acumulado"]; ?> </h5> 
                        <h5>Impuesto mensual:  <?php echo $m["tributo"] ?></h5>
                    </div>
                    <div class="card-footer text-muted">

                        <a href="../Controlador/CC_Controlador.php?accion=eliminarMesAnnoCliente&id_clienteFecha=<?php echo $m["id_clienteFecha"] ?>&id_anno=<?php echo $id_anno; ?>&id_cliente=<?php echo $id_cliente; ?>&nombre_cliente=<?php echo $nombre_cliente; ?>&nombre_anno=<?php echo $nombre_anno; ?>&tipo=<?php echo $tipo; ?>">Eliminar </a>
                        <a href="?opcion=6&accion=modificar&id_cliente=<?php echo $id_cliente; ?>&&id_anno=<?php echo $id_anno; ?>&&nombre_cliente=<?php echo $nombre_cliente; ?>&&nombre_anno=<?php echo $nombre_anno; ?>&&nombre_mes=<?php echo $m["nombre"]; ?>&&id_clienteFecha=<?php echo $m["id_clienteFecha"]; ?>&&opcion2=4&&activa=diario&&id_mes=<?php echo $m["id_mes"]; ?>&&tipo=<?php echo $tipo; ?>">Modificar </a>
                    </div>
                </div>

                <?php
            }
            ?>
        </div>

        <div id="accion">

        </div>
        <script>

            function PeticionAjax_ModificarUsuario(elemento) {
                elemento.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "paginas/SubPaginas/fecha/Insertar.php",
                    success: function (response) {
                        $("#accion").html(response);


                    }

                });



            }
        </script>


        <div id="insertarFechaNueva">
            <form action="../Controlador/CC_Controlador.php?accion=insertarMesAnnoCliente" class="insertarMes oculto "  method="POST">
                <h4>Creando mes de <?php echo $mes_nuevo[0]["nombre"] ?></h4>
                <div style="display: flex; justify-content: space-around">

                    <div>
                        <div class="form-group">
                            <label>Salario del trabajador</label>
                            <input class="form-control" name="salariotrabajador" type="number" min="0" value=""/>

                        </div>

                        <div class="form-group">
                            <label>Tipo de mes </label>
                            <select class="form-control" name="tipomes"id="trimestral" onChange="MostrarImpuestos(event, 'FuerzaW', 'SeguridadSocial')">
                                <option value="0"> Estándar</option>
                                <option value="1">Trimestral</option>
                            </select>
                        </div>
                    </div>
                    <div style=" display: flex"> 

                        <div style=" display: flex;flex-direction: column">
                            <h5 style="font-weight: bold;"class="form-group">Tipo de impuesto</h5>
                            <div class="form-group"><h5>Cuota Fija</h5></div>
                            <div class="form-group"><h5>Impuesto 10%</h5></div>
                            <div class="form-group FuerzaW oculto"><h5>Fuerza de Trabajo</h5></div>
                            <div class="form-group SeguridadSocial oculto"><h5>Seguridad Social</h5></div>
                        </div>
                        <div style="margin-left: 40px">
                            <h5 style="font-weight: bold;"> Tipo de pago</h5> 
                            <div class="form-group"> 
                                <select class="form-control" name="CuotaFija" >
                                    <option hidden value="-1">Seleccione</option>
                                    <option value="0">Efectivo</option>
                                    <option value="1">Transfermóvil</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="ImpuestoDiez">
                                    <option hidden value="-1">Seleccione</option>
                                    <option value="0">Efectivo</option>
                                    <option value="1">Transfermóvil</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select class="form-control FuerzaW oculto"  name="FuerzaW">
                                    <option hidden value="-1">Seleccione</option>
                                    <option value="0">Efectivo</option>
                                    <option value="1">Transfermóvil</option>
                                </select>
                            </div>
                            <div class="form-group"> 
                                <select class="form-control SeguridadSocial oculto" name="SeguridadSocial">
                                    <option hidden value="-1">Seleccione</option>
                                    <option value="0">Efectivo</option>
                                    <option value="1">Transfermóvil</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div style="align-self: flex-end;"><input class="btn btn-primary" type="submit" value="Crear"/></div>
                </div>
                <input hidden name="id_cliente"value="<?php echo $id_cliente ?>">
                <input hidden name="nombre_cliente"value="<?php echo $nombre_cliente ?>">
                <input hidden name="nombre_anno"value="<?php echo $nombre_anno ?>">
                <input hidden name="id_anno"value="<?php echo $id_anno ?>">
                <input hidden name="tipo"value="<?php echo $tipo ?>">
                <input hidden name="id_mes"value="<?php echo $mes_nuevo[0]["id_mes"] ?> ?>">



            </form>
            <style>
                .oculto{
                    display: none;
                }
            </style>
            <a href="" onclick="insertMes(event, 'insertarMes')">Adicionar </a>


            <script>
                function insertMes(e, id) {
                    e.preventDefault();
                    $("." + id).toggleClass("oculto");



                }
                function MostrarImpuestos(e, id1, id2) {
                    e.preventDefault();
                    if ($("#trimestral").val() == "1") {
                        $("." + id1).removeClass("oculto");
                        $("." + id2).removeClass("oculto");
                        $("footer").removeClass("navbar-fixed-bottom");
                    } else {
                        $("." + id1).addClass("oculto");
                        $("." + id2).addClass("oculto");
                        $("footer").addClass("navbar-fixed-bottom");
                    }


                }




            </script>  
        </div>
    </body>
</html>
