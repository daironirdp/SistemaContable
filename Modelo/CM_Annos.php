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
    private $id_cliente;

    function __construct() {
        $a = func_get_args();
        $i = func_num_args();
        if ($i > 0) {
            call_user_func_array(array($this, '__constructArg'), $a);
        } else {
            call_user_func_array(array($this, '__constructEmpty'), $a);
        }
    }

    function __constructArg($id_anno, $id_cliente) {
        $this->nombre = $id_anno;
        $this->id_cliente = $id_cliente;
    }

    function __constructEmpty() {
        
    }

//devuelve los annos dado el id del cliente
    public function obtenerAnnosdeUnCliente($id_cliente) {
        $sql = "select * from annos a,anno_cliente ac "
                . "where id_cliente='$id_cliente' and a.id_anno=ac.id_anno ";
        $conexion = new Conexion();
        $annos = $conexion->devolverResultados($sql);
        $conexion->CerrarConexion();
        return $annos;
    }

    //Obtener todos los annos
    public function obtenerAnnos() {
        $sql = "select * from annos";
        $conexion = new Conexion();
        $annos = $conexion->devolverResultados($sql);
        $conexion->CerrarConexion();
        return $annos;
    }

//inserta un anno disponble en el cliente
    public function insertarAnnoDeUnCliente() {
        $sql = "insert into anno_cliente(id_anno,id_cliente)values('$this->id_anno','$this->id_cliente')";
        $conexion = new Conexion();
        $conexion->ejecutarConsulta($sql);
        $conexion->CerrarConexion();
    }

//inserta un mes en el anno del cliente
    public function insertarMesEnAnnoDeUnCliente($id_mes, $id_anno_cliente) {
        $sql = "insert into mes_anno_cliente(id_mes,id_anno_cliente)values('$id_mes','$id_anno_cliente')";
        $conexion = new Conexion();
        $conexion->ejecutarConsulta($sql);
        $conexion->CerrarConexion();
    }

    public function obtenerMesesAnnodeUnCliente($id_anno, $id_cliente) {
        $sql = "select * from meses m ,mes_anno_cliente mac,anno_cliente ac "
                . "where"
                . " ac.id_anno_cliente=mac.id_anno_cliente and"
                . " m.id_mes= mac.id_mes and"
                . " ac.id_anno='$id_anno' and"
                . " ac.id_cliente='$id_cliente' ";
        $conexion = new Conexion();
        $meses = $conexion->devolverResultados($sql);
        $conexion->CerrarConexion();
        return $meses;
    }

    //inserta un anno en la apk
    public function insertarAnno($nombre) {
        $sql = "insert into annos (nombre)values('$nombre')";
        $conexion = new Conexion();
        $conexion->ejecutarConsulta($sql);

        $conexion->CerrarConexion();
    }

//cambia el anno del cliente
    public function actualizarAnnoDeUnCliente($id_cliente, $id_anno) {

        $conexion = new Conexion();
        $sql = "update  anno_cliente set id_anno='$id_anno'  where id_cliente=$id_cliente";
        $conexion->ejecutarConsulta($sql);
        $conexion->CerrarConexion();
    }

//eleimina el anno de un cliente
    public function eliminarAnnoDeUnCliente($id_cliente, $id_anno) {
        $conexion = new Conexion();
        $sql = "DELETE FROM anno_cliente where id_cliente='$id_cliente' and id_anno='$id_anno'";
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
