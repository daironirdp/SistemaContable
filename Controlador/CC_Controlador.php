<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$plantilla = "location:../Vista/plantilla.php?opcion=";
extract($_REQUEST);
require '../Modelo/Conexion.php';
//verifica si existe el cliente
//insertar cliente
if ($accion == "insertarCliente") {
    require_once '../Modelo/CM_Clientes.php';
    $objeto = new Clientes();

    if ($objeto->existeCliente($carnet) == false) {

        if ($tipo == 0) {
            $tipo = "Mini";
        } else if ($tipo == 1) {
            $tipo = "Carro";
        }
        $objeto = new Clientes($nombre, $carnet, $tipo);
        $objeto->insertarCliente();

        header($plantilla . "0");
    } else {
        echo 'existe el cliente';
    }
}//eliminar cliente 
else if ($accion == "eliminarCliente") {
    require_once '../Modelo/CM_Clientes.php';
    $objeto = new Clientes();
    $objeto->eliminarCliente($id_cliente);
    header($plantilla . "0");
}
//modificar cliente
else if ($accion == "modificarCliente") {
    
}
//insertar cuenta
else if ($accion == "insertarCuenta") {
    require_once '../Modelo/CM_Cuentas.php';
    $objeto = new Cuentas();

    if ($objeto->existeCuenta($id_cuenta) == false) {
        if ($naturaleza == 1) {
            $naturaleza = "Deudora";
        } else if ($naturaleza == 2) {
            $naturaleza = "Acreedora";
        } else {
            $naturaleza = "Indefinido";
        }

        $objeto->insertarCuenta($nombre, $naturaleza, 0, $id_cuenta);
    } else {
        echo 'existe cuenta';
    }

    header($plantilla . "1");
}
//modificar cuenta
else if ($accion == "modificarCuenta") {
    require_once '../Modelo/CM_Cuentas.php';
    $objeto = new Cuentas();
    if ($naturaleza == 1) {
        $naturaleza = "Deudora";
    } else if ($naturaleza == 2) {
        $naturaleza = "Acreedora";
    } else {
        $naturaleza = "Indefinido";
    }
    //hacer que se eliminen las cuentas una vez modificado subcuenta 0 a 1


    $objeto->modificarCuenta($nombre, $naturaleza, $id_cuenta, $id_cuentaViejo);
    header($plantilla . "1");
}
//eliminar cuenta
else if ($accion == "eliminarCuenta") {
    require_once '../Modelo/CM_Cuentas.php';
    $objeto = new Cuentas();
    $objeto->eliminarSubCuentaDeunaCuenta($id_cuenta);
    $objeto->eliminarCuenta($id_cuenta);
    header($plantilla . "1");
}
//insertar subcuenta
else if ($accion == "insertarSubCuenta") {

    require_once '../Modelo/CM_Cuentas.php';
    $objeto = new Cuentas();

    if ($objeto->existeSubCuenta($id_Subcuenta) == false) {
        if ($naturaleza == 1) {
            $naturaleza = "Deudora";
        } else if ($naturaleza == 2) {
            $naturaleza = "Acreedora";
        } else {
            $naturaleza = "Indefinido";
        }
        $cuenta = $objeto->obtenerCuentaXid($id_cuenta);

        $objeto->modificarCuentaSubcuenta($cuenta[0]["subcuenta"] + 1, $cuenta[0]["id_cuenta"]);
        $objeto->insertarSubCuentas($nombre, $naturaleza, $id_Subcuenta, $id_cuenta);
        header($plantilla . "1");
    } else {
        echo 'existe cuenta';
    }
}
//eliminar subcuenta
else if ($accion == "eliminarSubCuenta") {
    require_once '../Modelo/CM_Cuentas.php';
    $objeto = new Cuentas();
    $cuenta = $objeto->obtenerCuentaXid($id_cuenta);

    $objeto->modificarCuentaSubcuenta($cuenta[0]["subcuenta"] - 1, $cuenta[0]["id_cuenta"]);
    $objeto->eliminarSubCuenta($id_subcuenta);
    header($plantilla . "1");
}
//modificar subcuenta
else if ($accion == "modificarSubCuenta") {

    require_once '../Modelo/CM_Cuentas.php';
    $objeto = new Cuentas();

    if ($naturaleza == 1) {
        $naturaleza = "Deudora";
    } else if ($naturaleza == 2) {
        $naturaleza = "Acreedora";
    } else {
        $naturaleza = "Indefinido";
    }


    $objeto->modificarSubCuenta($nombre, $naturaleza, $id_subcuenta, $id_SubcuentaViejo);
    header($plantilla . "1");
}
//insertar un anno dentro de un cliente
else if ($accion == "insertarAnnoCliente") {
    
}
//eliminar anno de un cliente
else if ($accion == "eliminarAnnoCliente") {
    
}
//insertar mes de un anno de un cliente
else if ($accion == "insertarMesAnnoCliente") {
    
}
//eliminar mes de un anno de un cliente
else if ($accion == "eliminarMesAnnoCliente") {
    
}
//crear instancia de cuenta
else if ($accion == "crearInstanciaCuenta") {

    require '../Modelo/CM_Contabilidad.php';
    $instancias = json_decode($instancias);

    $id_cuenta = $instancias[0][0];
    $valor = $instancias[0][2];
    $id_contrapartida = $instancias[0][3];
    $valor2 = $instancias[0][5];
    $grupoComprobante = $instancias[0][6];
    $estado = $instancias[0][7];
    $objeto = new Contabilidad();
    if (count($instancias[0]) == 8) {

        $id_subcuenta = 0;
        $objeto->crearInstanciaCuenta($id_cuenta, $id_subcuenta, $valor, $id_clienteFecha, $id_contrapartida, $grupoComprobante, $estado);
        echo 'ok';
    } else if (count($instancias[0]) > 8) {
        $id_Subcuenta = [];
        $valor_subcuenta = [];
        if (count($instancias[0]) == 10) {

            if ($instancias[0][8] == 1) {
                $estado = 1;
            } else if ($instancias[0][8] == 2) {
                $estado = 2;
            } else if ($instancias[0][8] == 3) {
                $estado = 3;
            }
            for ($i = 0; $i < count($instancias[0][9]); $i++) {
                $id_Subcuenta[$i] = $instancias[0][9][$i][0];
                $valor_subcuenta[$i] = $instancias[0][9][$i][2];
                $objeto->crearInstanciaCuenta($id_cuenta, $id_Subcuenta[$i], $valor_subcuenta[$i], $id_clienteFecha, $id_contrapartida, $grupoComprobante, $estado);
            }
        } else {

            for ($i = 0; $i < count($instancias[0][8]); $i++) {
                $id_Subcuenta[$i] = $instancias[0][8][$i][0];
                $valor_subcuenta[$i] = $instancias[0][8][$i][2];
                $objeto->crearInstanciaCuenta($id_cuenta, $id_Subcuenta[$i], $valor_subcuenta[$i], $id_clienteFecha, $id_contrapartida, $grupoComprobante, $estado);
            }
        }


        echo 'ok';
    } else {
        echo "failure";
    }
} else if ($accion == "eliminarInstancia") {

    require '../Modelo/CM_Contabilidad.php';
    $objeto = new Contabilidad();
    $objeto->eliminarInstancia($id_cuenta, $id_contrapartida, $id_clienteFecha);
    header($plantilla . "5&&id_cliente=$id_cliente&&id_anno=$id_anno&&nombre_cliente=$nombre_cliente&&nombre_anno=$nombre_anno&&nombre_mes=$nombre_mes&&id_clienteFecha=$id_clienteFecha&&opcion2=3");
} else if ($accion == "modificarInstancia") {

    require '../Modelo/CM_Contabilidad.php';
    $objeto = new Contabilidad();
    $objeto->modificarInstancia($id_instanciaCuenta, $id_cuenta, $id_subcuenta, $valor, $id_clienteFecha, $id_contrapartida, $grupoComprobante, $estado);
    echo "ok";
} else if ($accion == "modificarInstancias") {

    require '../Modelo/CM_Contabilidad.php';
    $instancias = json_decode($instancias);
    echo json_encode($instancias);
    $objeto = new Contabilidad;
    foreach ($instancias as $v) {
        $objeto->modificarInstancias($v[0], $v[1]);
    }
}
//obtener subcuentas de una cuenta
else if ($accion == "mostrarSubcuetasDeid") {

    require '../Modelo/CM_Contabilidad.php';
    $objeto = new Contabilidad();

    echo json_encode($objeto->mostrarSubcuetasDeid($id_cuenta));
}

//insertar un anno para que este disponible
else if ($accion == "insertarAnno") {


    require '../Modelo/CM_Annos.php';
    $objeto = new Annos();
    $bandera = $objeto->encontrarAnno($nombre);

    if ($bandera === false) {
        $objeto->insertarAnno($nombre);
        header($plantilla . "2");
    } else {
        echo "ese anno esta";
    }
}
//eliminar un anno
else if ($accion == "eliminarAnno") {


    require '../Modelo/CM_Annos.php';
    $objeto = new Annos();
    $objeto->eliminarAnno($id_anno);
    header($plantilla . "2");
}
//modificar un anno
else if ($accion == "modificarAnno") {
    require '../Modelo/CM_Annos.php';
    $objeto = new Annos();
    $bandera = $objeto->encontrarAnno($nombre);
    if ($bandera === false) {


        $objeto->actualizarAnno($id_anno, $nombre);
        header($plantilla . "2");
    } else {
        echo 'ya ese anno esta';
    }
}
//actualizar acumulado
else if ($accion == "actualizarAcumulado") {
    require '../Modelo/CM_FechaCliente.php';
    $objeto = new FechaCliente();
    return $objeto->actualizarAcumulado($acumulado, $id_clienteFecha,$tributo);
}

        
        
