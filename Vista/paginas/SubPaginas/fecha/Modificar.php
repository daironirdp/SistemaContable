<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
extract($_REQUEST);
if ($accion = "modificar") {
    $salariotrabajador = "";
    
    ?>

 <h3>Cliente: <?php echo $nombre_cliente; ?></h3>
        <h4>Anno: <?php echo $nombre_anno; ?></h4>


    <div id="insertarFechaNueva">
        <form action="../Controlador/CC_Controlador.php?accion=modificarMesAnnoCliente" class="insertarMes "  method="POST">
            <h4>Modificando mes de <?php echo $nombre_mes ?></h4>
            <div style="display: flex; justify-content: space-around">

                <div>
                    <div class="form-group">
                        <label>Salario del trabajador</label>
                        <input class="form-control" name="salariotrabajador" type="number" min="0" value="<?php?>"/>

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
                <div style="align-self: flex-end;"><input class="btn btn-primary" type="submit" value="Modificar"/></div>
                </div>
                <input hidden name="id_clienteFecha"value="<?php echo $id_clienteFecha ?>">
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





    <?php
}
?>
