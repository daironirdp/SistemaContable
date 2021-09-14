<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//require_once '../Modelo/Conexion.php';

class Cuentas {

    private $nombre;
    private $id_cuenta;
    private $subcuentas;
    private $valor;
    private $tipo;

    function __construct() {
        $args = func_get_args();
        $i = func_num_args();
        if ($i > 0) {
            call_user_func_array(array($this, '__constructArg'), $args);
        } else {
            call_user_func_array(array($this, '__constructEmpty'),$args);
        }
    }

    function __constructArg($id_cuenta, $nombre, $subcuentas, $valor, $tipo) {
        $this->id_cuenta = $id_cuenta;
        $this->nombre = $nombre;
        $this->subcuentas = $subcuentas;
        $this->valor = $valor;
        $this->tipo = $tipo;
    }

    function __constructEmpty() {
        
    }

    //put your code here

    function obtenerCuentas() {
        $sql = "select * from cuentas ";
        $conexion = new Conexion();
        $cuentas = $conexion->devolverResultados($sql);
        $conexion->CerrarConexion();
        return $cuentas;
    }

    function obtenerCuentaXid($id_cuenta) {
        $sql = "Select * from cuentas where id_cuenta='$id_cuenta'";
        $conexion = new Conexion();
        $cuenta = $conexion->devolverResultados($sql);
        $conexion->CerrarConexion();
        return $cuenta;
    }

    function eliminarCuenta($id_cuenta) {
        $conexion = new Conexion();
        $sql = "DELETE FROM cuentas where id_cuenta='$id_cuenta'";
        $conexion->ejecutarConsulta($sql);
        $conexion->CerrarConexion();
    }

    function modificarCuenta($nombre, $naturaleza, $id_cuenta, $id_cuentaViejo) {
        $conexion = new Conexion();
        $sql = "update  cuentas "
                . "set nombre='$nombre',naturaleza='$naturaleza',id_cuenta='$id_cuenta'"
                . "  where id_cuenta=$id_cuentaViejo";
        $conexion->ejecutarConsulta($sql);
        $conexion->CerrarConexion();
    }

    function modificarCuentaSubcuenta($subcuenta, $id_cuenta) {
        $conexion = new Conexion();
        $sql = "update  cuentas "
                . "set subcuenta='$subcuenta'"
                . "  where id_cuenta=$id_cuenta";
        $conexion->ejecutarConsulta($sql);
        $conexion->CerrarConexion();
    }

    function existeCuenta($id_cuenta) {
        $sql = "select * from cuentas where id_cuenta='$id_cuenta'";
        $conexion = new Conexion();
        $cuentas = $conexion->devolverResultados($sql);
        $conexion->CerrarConexion();
        return $cuentas;
    }

    function existeSubCuenta($id_Subcuenta) {
        $sql = "select * from subcuentas where id_subcuenta='$id_Subcuenta'";
        $conexion = new Conexion();
        $cuentas = $conexion->devolverResultados($sql);
        $conexion->CerrarConexion();
        return $cuentas;
    }

    function insertarCuenta($nombre, $naturaleza, $subcuenta, $id_cuenta) {
        $conexion = new Conexion();
        $sql = "insert into  cuentas (nombre,naturaleza,subcuenta,id_cuenta)values('$nombre','$naturaleza','$subcuenta','$id_cuenta')";
        $conexion->ejecutarConsulta($sql);
        $conexion->CerrarConexion();
    }

    function obtenerSubCuentas() {
        $sql = "select * from subcuentas ";
        $conexion = new Conexion();
        $subcuentas = $conexion->devolverResultados($sql);
        $conexion->CerrarConexion();
        return $subcuentas;
    }

    function obtenerSubCuentasXid($id_cuenta) {
        $sql = "select * from subcuentas where id_cuenta='$id_cuenta'";
        $conexion = new Conexion();
        $subcuentas = $conexion->devolverResultados($sql);
        $conexion->CerrarConexion();
        return $subcuentas;
    }

    function eliminarSubCuenta($id_subcuenta) {
        $conexion = new Conexion();
        $sql = "DELETE FROM subcuentas where id_subcuenta='$id_subcuenta'";
        $conexion->ejecutarConsulta($sql);
        $conexion->CerrarConexion();
    }

    function eliminarSubCuentaDeunaCuenta($id_cuenta) {

        $conexion = new Conexion();
        $sql = "DELETE FROM subcuentas where id_cuenta='$id_cuenta'";
        $conexion->ejecutarConsulta($sql);
        $conexion->CerrarConexion();
    }

    function insertarSubCuentas($nombre, $naturaleza, $id_subcuenta, $id_cuenta) {
        $conexion = new Conexion();
        $sql = "insert into  subcuentas"
                . " (nombre,naturaleza,id_subcuenta,id_cuenta)"
                . "values('$nombre','$naturaleza','$id_subcuenta','$id_cuenta')";
        $conexion->ejecutarConsulta($sql);
        $conexion->CerrarConexion();
    }

    function modificarSubCuenta($nombre, $naturaleza, $id_subcuenta, $id_SubcuentaViejo) {
        $conexion = new Conexion();
        $sql = "update  subcuentas "
                . "set nombre='$nombre',naturaleza='$naturaleza',id_subcuenta='$id_subcuenta'"
                . "  where id_subcuenta=$id_SubcuentaViejo";
        $conexion->ejecutarConsulta($sql);
        $conexion->CerrarConexion();
    }

}
