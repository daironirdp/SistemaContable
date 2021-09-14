<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once '../Modelo/Conexion.php';

class Annos {

    //put your code here
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

    function __constructArg($id_anno) {
        $this->nombre = $id_anno;
       
    }

    function __constructEmpty() {
        
    }

    //Obtener todos los annos
    public function obtenerAnnos() {
        $sql = "select * from annos";
        $conexion = new Conexion();
        $annos = $conexion->devolverResultados($sql);
        $conexion->CerrarConexion();
        return $annos;
    }

    //inserta un anno en la apk
    public function insertarAnno($nombre) {
        $sql = "insert into annos (nombre)values('$nombre')";
        $conexion = new Conexion();
        $conexion->ejecutarConsulta($sql);

        $conexion->CerrarConexion();
    }


    //elimina un anno
    public function eliminarAnno($id_anno) {
        $conexion = new Conexion();
        $sql = "DELETE FROM annos where  id_anno='$id_anno'";
        $conexion->ejecutarConsulta($sql);
        $conexion->CerrarConexion();
    }

    public function encontrarAnno($nombre) {
        $sql = "select * from annos where nombre='$nombre'";
        $conexion = new Conexion();
        $annos = $conexion->devolverResultados($sql);
        $conexion->CerrarConexion();
        return $annos;
    }

    public function actualizarAnno($id_anno, $nombre) {
     

            $conexion = new Conexion();
            $sql = "update  annos set nombre='$nombre' where id_anno='$id_anno'";
            $conexion->ejecutarConsulta($sql);
            $conexion->CerrarConexion();
        
    }

}
