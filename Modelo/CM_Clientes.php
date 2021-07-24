<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../Modelo/Conexion.php';

class Clientes {

    //put your code here
    private $nombre;
    private $carnet;
    private $tipo;

    function __construct() {
        $a = func_get_args();
        $i = func_num_args();
        if ($i > 0) {
            call_user_func_array(array($this, '__constructArg'), $a);
        } else {
            call_user_func_array(array($this, '__constructEmpty'), $a);
        }
    }

    function __constructArg($nombre, $carnet, $tipo) {
        $this->nombre = $nombre;
        $this->carnet = $carnet;
        $this->tipo = $tipo;
    }

    function __constructEmpty() {
        
    }

    public function obtenerClientes() {
        $sql = "select * from clientes";
        $conexion = new Conexion();
        $clientes = $conexion->devolverResultados($sql);
        $conexion->CerrarConexion();
        return $clientes;
    }
    
  

    function existeCliente($carnet) {
        $sql = "select * from clientes where carnet='$carnet'";
        $conexion = new Conexion();
        $clientes = $conexion->devolverResultados($sql);
        $conexion->CerrarConexion();
        return $clientes;
    }

    public function insertarCliente() {
        $sql = "insert into clientes (nombre,carnet,tipo)values('$this->nombre','$this->carnet','$this->tipo')";
        $conexion = new Conexion();
        $conexion->ejecutarConsulta($sql);
        $conexion->CerrarConexion();
    }

    public function actualizarCliente($id_cliente, $nombre, $carnet) {

        $conexion = new Conexion();
        $sql = "update  clientes set nombre='$nombre',carnet='$carnet'  where id_cliente=$id_cliente";
        $conexion->ejecutarConsulta($sql);
        $conexion->CerrarConexion();
    }

    public function eliminarCliente($id_cliente) {
        $conexion = new Conexion();
        $sql = "DELETE FROM clientes where id_cliente='$id_cliente'";
        $conexion->ejecutarConsulta($sql);
        $conexion->CerrarConexion();
    }

}
