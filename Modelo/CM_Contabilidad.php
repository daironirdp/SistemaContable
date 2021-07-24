<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Contabilidad
 *
 * @author Dairon
 */
//require_once '../Modelo/Conexion.php';

class Contabilidad {

    //put your code here

    function obtenerInstanciasDiario($id_clienteFecha) {
        $sql = "SELECT * FROM instanciacuenta ic,subcuentas sub,cuentas c "
                . "WHERE ic.id_clienteFecha='$id_clienteFecha' and"
                . " c.id_cuenta=ic.id_cuenta and"
                . " sub.id_subcuenta=ic.id_subcuenta and (ic.estado=0 or ic.estado=1 or ic.estado=2 or ic.estado=3)";
        $conexion = new Conexion();
        $instancias = $conexion->devolverResultados($sql);
        $conexion->CerrarConexion();
        return $instancias;
    }
    function obtenerInstanciasComprobantes($id_clienteFecha) {
        $sql = "SELECT * FROM instanciacuenta ic,subcuentas sub,cuentas c "
                . "WHERE ic.id_clienteFecha='$id_clienteFecha' and"
                . " c.id_cuenta=ic.id_cuenta and"
                . " sub.id_subcuenta=ic.id_subcuenta and (ic.estado=0 or ic.estado=1 or ic.estado=2 or ic.estado=3 or ic.estado=4)";
        $conexion = new Conexion();
        $instancias = $conexion->devolverResultados($sql);
        $conexion->CerrarConexion();
        return $instancias;
    }

    function crearInstanciaCuenta($id_cuenta, $id_subcuenta, $valor, $id_clienteFecha, $id_contrapartida, $grupoComprobante, $estado) {

        $sql = "Insert into instanciacuenta (id_cuenta,id_subcuenta,valor,id_clienteFecha,contrapartida,grupoComprobante,estado)"
                . " values('$id_cuenta','$id_subcuenta','$valor','$id_clienteFecha','$id_contrapartida','$grupoComprobante','$estado')";
        $conexion = new Conexion();
        $instancias = $conexion->ejecutarConsulta($sql);
        $conexion->CerrarConexion();
        return $instancias;
    }

    function mostrarSubcuetasDeid($id_cuenta) {
        $sql = "SELECT * FROM subcuentas sub where"
                . " id_cuenta='$id_cuenta' ";
        $conexion = new Conexion();
        $subcuentas = $conexion->devolverResultados($sql);
        $conexion->CerrarConexion();
        return $subcuentas;
    }

    function eliminarInstancia($id_cuenta, $contrapartida, $id_clienteFecha) {
        $sql = "delete from instanciacuenta "
                . "where id_cuenta='$id_cuenta'"
                . " and contrapartida='$contrapartida' "
                . "and id_clienteFecha='$id_clienteFecha'";

        $conexion = new Conexion();
        $conexion->ejecutarConsulta($sql);
        $conexion->CerrarConexion();
    }

    function modificarInstancia($id_instanciaCuenta, $id_cuenta, $id_subcuenta, $valor, $id_clienteFecha, $id_contrapartida, $grupoComprobante, $estado) {
        $sql = "update  instanciacuenta "
                . "set id_cuenta='$id_cuenta',id_subcuenta='$id_subcuenta',valor='$valor',"
                . "   id_clienteFecha=$id_clienteFecha , id_contapartida='$id_contrapartida',"
                . "grupoComprobante='$grupoComprobante',estado='$estado'"
                . "where id_instanciaCuenta='$id_instanciaCuenta'";

        $conexion = new Conexion();
        $conexion->ejecutarConsulta($sql);
        $conexion->CerrarConexion();
    }

    function modificarInstancias($id_instanciaCuenta, $valor) {
        $sql = "update  instanciacuenta "
                . "set valor='$valor'"
                . "where id_instanciaCuenta='$id_instanciaCuenta'and valor<>$valor";

        $conexion = new Conexion();
        $conexion->ejecutarConsulta($sql);
        $conexion->CerrarConexion();
    }

}
