<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CM_Meses
 *
 * @author acer
 */
include_once '../Modelo/Conexion.php';

class Meses {

    //put your code here
    private $id_anno;

    function __construct() {
        $a = func_get_args();
        $i = func_num_args();
        if ($i > 0) {
            call_user_func_array(array($this, '__constructArg'), $a);
        } else {
            call_user_func_array(array($this, '__constructEmpty'), $a);
        }
    }

    function __constructArg($id_mes) {
        $this->nombre = $id_mes;
    }

    function __constructEmpty() {
        
    }

    //Obtener todos los annos
    public function obtenerMeses() {
        $sql = "select * from meses";
        $conexion = new Conexion();
        $annos = $conexion->devolverResultados($sql);
        $conexion->CerrarConexion();
        return $annos;
    }

    //inserta un anno en la apk
    public function insertarMes($nombre) {
        $sql = "insert into meses (nombre)values('$nombre')";
        $conexion = new Conexion();
        $conexion->ejecutarConsulta($sql);

        $conexion->CerrarConexion();
    }

    //elimina un anno
    public function eliminarMes($id_mes) {
        $conexion = new Conexion();
        $sql = "DELETE FROM meses where  id_anno='$id_anno'";
        $conexion->ejecutarConsulta($sql);
        $conexion->CerrarConexion();
    }

    public function encontrarMes($nombre) {
        $sql = "select * from meses where nombre='$nombre'";
        $conexion = new Conexion();
        $annos = $conexion->devolverResultados($sql);
        $conexion->CerrarConexion();
        return $annos;
    }

}
