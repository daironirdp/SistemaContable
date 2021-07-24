<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CM_FechaCliente
 *
 * @author Dairon
 */
require_once '../Modelo/Conexion.php';

class FechaCliente {

    //put your code here

    function mostrarAnnos($id_cliente) {
        $sql = "SELECT * FROM  clientefecha cf ,annos a "
                . " WHERE id_cliente='$id_cliente'"
                . " AND cf.id_anno=a.id_anno";
        $conexion = new Conexion();
        $annos = $conexion->devolverResultados($sql);
        return $annos;
    }

    function crearFecha($id_mes, $id_anno, $id_cliente) {
        $sql = "INSERT INTO clientefecha (id_mes,id_anno,id_cliente) values('$id_mes','$id_anno','$id_cliente') ";
        $conexion = new Conexion();
        $conexion->ejecutarConsulta($sql);
    }

    function acumuladoAnual($id_cliente, $id_anno) {
        $sql = "SELECT sum(acumulado)as acumulado FROM  clientefecha  "
                . " WHERE id_cliente='$id_cliente'"
                . " AND id_anno='$id_anno'";
        $conexion = new Conexion();
        $acumuladoAnual = $conexion->devolverResultados($sql);
        return $acumuladoAnual;
    }

    function actualizarAcumulado($acumulado, $id_clienteFecha, $tributo) {
        $sql = "UPDATE clientefecha SET acumulado='$acumulado',tributo='$tributo'"
                . " WHERE id_clienteFecha='$id_clienteFecha'";
        $conexion = new Conexion();
        $conexion->ejecutarConsulta($sql);
        if ($conexion->ejecutarConsulta($sql)) {
            return "ok";
        } else {
            return "error";
        }
    }

    function mostrarMeses($id_cliente, $id_anno) {
        $sql = "SELECT * FROM "
                . " clientefecha cf,meses m "
                . "WHERE id_cliente='$id_cliente' AND"
                . "  cf.id_mes=m.id_mes AND"
                . " id_anno='$id_anno'";
        $conexion = new Conexion();
        $resultado = $conexion->devolverResultados($sql);

        return $resultado;
    }

}
