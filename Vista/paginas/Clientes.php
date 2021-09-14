
<?php
// put your code here
require_once '../Modelo/CM_Clientes.php';
require_once '../Modelo/CM_Annos.php';

$cliente = new Clientes();
$valor2 = $cliente->obtenerClientes();
$annosDelCliente = new Annos();

//$valor = $annosDelCliente->obtenerAnnosdeUnCliente(1);
//$meses=$annosDelCliente->insertarMesEnAnnoDeUnCliente(1, 1);
//$aki = $valor[0]["id_anno_cliente"];
//echo $aki;
//echo $annosDelCliente->obtenerMesesAnnodeUnCliente($aki)[0]["nombre"];
?>

<div class="contenedor_cliente">


    <?php
    foreach ($valor2 as $value) {
        ?>


        <div class="card text-center">
            <div class="card-header">
                <a href="" class="enlace">

                    <i class=" fa fa-user icono_user" aria-hidden="true"></i>
                    <label><?php echo $value["nombre"]; ?></label> 
                </a>
            </div>
            <div class="card-body">


                <div> <label>No carnet: <?php echo $value["carnet"]; ?> </label></div>
                <div><label>Tipo: <?php echo $value["tipo"]; ?> </label></div>


            </div>
            <div class="card-footer text-muted">
                <div> 
                    <a href="?opcion=3&&id_cliente=<?php echo $value["id_cliente"] ?>&&nombre_cliente=<?php echo $value["nombre"]; ?>&&tipo=<?php echo $value["tipo"]; ?>">Detalles</a>
                    <a href="#">Modificar</a>
                    <a href="../Controlador/CC_Controlador.php?accion=eliminarCliente&&id_cliente=<?php echo $value["id_cliente"] ?>">Eliminar</a> </div>
                <form action="../Controlador/CC_Controlador.php?accion=insertarCliente" method="post" id="" class="oculto">
                    <label>Nombre</label>
                    <input type="text" placeholder="nombre aki" name="nombre"/>
                    <label>Carnet</label>
                    <input name="carnet" type="number"/>
                    <label>Tipo</label>
                    <select name="tipo">
                        <option value="0">
                            Micro
                        </option>
                        <option value="1">
                            Carro
                        </option>
                    </select>
                    <input type="submit" class="btn-success" value="Insertar">
                </form>
            </div>
        </div>













        <?php
    }
    ?>


</div>
<div style="text-align: center">

    <a href="#" onclick="insertar(event, 'formInsert')">Agregar</a>
    <div style="display: flex;justify-content: center;" >
        <form  style="width: 50%"action="../Controlador/CC_Controlador.php?accion=insertarCliente" method="post" id="formInsert" class="oculto form ">
            <label>Nombre</label>
            <input type="text" placeholder="nombre aki" class="form-control" name="nombre"/>
            <label>Carnet</label>
            <input class="form-control" name="carnet" type="number"/>
            <label >Tipo</label>
            <select name="tipo" class="form-control">
                <option value="0">
                    Micro
                </option>
                <option value="1">
                    Carro
                </option>
            </select>
            <input type="submit" class="btn btn-primary " value="Insertar">
        </form>
    </div>
</div>


<style>
    .oculto{
        display: none;

    }
    .form{
        margin-top: 20px;
    }
</style>
<script>
    function insertar(e, id) {
        e.preventDefault();
        $("#" + id).toggleClass("oculto");
    }

</script>