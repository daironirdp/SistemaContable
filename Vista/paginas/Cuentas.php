<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<?php
// put your code here
require_once '../Modelo/CM_Cuentas.php';
$objeto = new Cuentas();
$cuentas = $objeto->obtenerCuentas();
$subcuentas = $objeto->obtenerSubCuentas();
//esta vista se encarga de mostrar las cuentas y subcuentas disponibles Es el CRUD de Cuentas y subcuentas
?>
<script>

    var id_subcuentas = [];
</script>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body >
        <div id="contenedorCuentas" style="display: flex;justify-content: space-around ; text-align: center;flex-direction: column">
            <div style="display: flex;flex-direction: column; text-align: center" >
                <h3>Cuentas</h3>
                <table style="text-align: center"class="table table-hover table-condensed" >
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Naturaleza</th>
                            <th>SubCuenta</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 0;
                        foreach ($cuentas as $c) {
                            ?>
                            <tr>
                                <td><?php echo $c["id_cuenta"]; ?></td>

                                <td><?php echo $c["nombre_cuenta"]; ?></td>
                                <td><?php echo $c["naturaleza"]; ?></td>
                                <td><?php echo $c["subcuenta"]; ?></td>
                                <td>
                                    <a href="" onclick="agregar(event, 'c<?php echo $c["id_cuenta"]; ?>')">Modificar</a>
                                    <a style="margin-left: 10px"href="../Controlador/CC_Controlador.php?accion=eliminarCuenta&&id_cuenta=<?php echo $c["id_cuenta"] ?>">Eliminar</a>
                                </td>


                            </tr>
                            <tr>
                                <td colspan="6">
                                    <form action="../Controlador/CC_Controlador.php?accion=modificarCuenta" method="post"id="c<?php echo $c["id_cuenta"]; ?>" class="oculto">

                                        <input type="number"  value="<?php echo $c["id_cuenta"]; ?>" hidden name="id_cuentaViejo">
                                        <input type="number"  value="<?php echo $c["id_cuenta"]; ?>" name="id_cuenta">
                                        <input type="text"    value="<?php echo $c["nombre_cuenta"]; ?>" name="nombre">
                                        <select  name="naturaleza">
                                            <option  value="0">
                                                ---Naturaleza---
                                            </option>
                                            <option value="1">
                                                ---Deudora---
                                            </option>
                                            <option value="2">
                                                ---Acreedora---
                                            </option>
                                        </select>


                                        <input type="submit"  value="enviar" name="">

                                    </form>
                                </td>
                            </tr>
                        <script>

                            id_subcuentas[<?php echo $count++ ?>] =<?php echo $c["id_cuenta"] ?>;

                        </script>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
                <form id="cuentForm" action="../Controlador/CC_Controlador.php?accion=insertarCuenta" method="post" class="oculto">
                    <label for="numero">Numero</label>
                    <input type="number" min="0" name="id_cuenta" id="" value=""/>
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" id="" value=""/>
                    <select id="" name="naturaleza">
                        <option value="0">
                            ---Naturaleza---
                        </option>
                        <option value="1">
                            ---Deudora---
                        </option>
                        <option value="2">
                            ---Acreedora---
                        </option>

                    </select>

                    <input type="submit"/>
                </form>
                <a href="" onclick="agregar(event, 'cuentForm')">Agregar cuenta</a>
            </div>

            <div style="display: flex;flex-direction: column; text-align: center;">
                <h3>Subcuentas</h3>
                <table style="text-align: center" class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Naturaleza</th>
                            <th>idCuenta</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($subcuentas as $subc) {
                            ?>
                            <tr>
                                <td><?php echo $subc["id_subcuenta"]; ?></td>

                                <td><?php echo $subc["nombre_subcuenta"]; ?></td>
                                <td><?php echo $subc["naturaleza"]; ?></td>
                                <td><?php echo $subc["id_cuenta"]; ?></td>
                                <td>
                                    <a href="" onclick="agregar(event, 'sub<?php echo $subc["id_subcuenta"]; ?>')">Modificar</a>
                                    <a style="margin-left: 10px "href="../Controlador/CC_Controlador.php?accion=eliminarSubCuenta&&id_subcuenta=<?php echo $subc["id_subcuenta"] ?>&&id_cuenta=<?php echo $subc["id_cuenta"]; ?>">Eliminar</a>
                                </td>


                            </tr>
                            <tr>
                                <td colspan="6">
                                    <form id="sub<?php echo $subc["id_subcuenta"]; ?>" action="../Controlador/CC_Controlador.php?accion=modificarSubCuenta"method="post"class="oculto">
                                        <input hidden value="<?php echo $subc["id_subcuenta"]; ?>" name="id_SubcuentaViejo"/>
                                        <label for="numero">Numero</label>
                                        <input type="number" min="0"name="id_subcuenta" value="<?php echo $subc["id_subcuenta"]; ?>"/>
                                        <label for="nombre">Nombre</label>
                                        <input type="text" value="<?php echo $subc["nombre_subcuenta"]; ?>" name="nombre"/>
                                        <select id="" name="naturaleza">
                                            <option value="0">
                                                ---Naturaleza---
                                            </option>
                                            <option value="1">
                                                ---Deudora---
                                            </option>
                                            <option value="2">
                                                ---Acreedora---
                                            </option>

                                        </select>
                                        <select id="options2" name="id_cuenta" style="display: none">
                                            <option value="0">
                                                ---Cuenta---
                                            </option>

                                        </select>
                                        <input type="submit"/>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>

                <form id="subCForm" action="../Controlador/CC_Controlador.php?accion=insertarSubCuenta"method="post"class="oculto">
                    <label for="numero">Numero</label>
                    <input type="number" min="0"name="id_Subcuenta" id=""/>
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre"/>
                    <select id="" name="naturaleza">
                        <option value="0">
                            ---Naturaleza---
                        </option>
                        <option value="1">
                            ---Deudora---
                        </option>
                        <option value="2">
                            ---Acreedora---
                        </option>

                    </select>
                    <select id="options" name="id_cuenta">
                        <option value="0">
                            ---Cuenta---
                        </option>

                    </select>
                    <input type="submit"/>
                </form>
                <a href="" onclick="agregar(event, 'subCForm')">Agregar subcuenta</a>
            </div>



        </div>
        <script>
            //este script posibilita agregar nuevas cuentas
            for (var i = 0; i < id_subcuentas.length; i++) {
                $("#options").append("<option value='" + (id_subcuentas[i]) + "'>" + id_subcuentas[i] + "</option>");
                $("#options2").append("<option value='" + (id_subcuentas[i]) + "'>" + id_subcuentas[i] + "</option>");
            }
            function agregar(e, id) {
                e.preventDefault();
                console.log("dasdsad");
                $("#" + id).toggleClass("oculto");
            }

        </script>
        <style>
            .oculto{
                display: none;
            }
        </style>

    </body>
</html>
