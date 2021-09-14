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
//muestra los annos de un cliente


    function mostrarAnnos($id_cliente) {
        $sql = "SELECT * FROM  clientefecha cf ,annos a "
                . " WHERE id_cliente='$id_cliente'"
                . " AND cf.id_anno=a.id_anno";
        $conexion = new Conexion();
        $annos = $conexion->devolverResultados($sql);
        $conexion->CerrarConexion();
        $nombre = [];
        $resultado = array();
        if ($annos != false) {
            foreach ($annos as $v) {
                if (!in_array($v["nombre"], $nombre)) {
                    array_push($resultado, $v);
                    array_push($nombre, $v["nombre"]);
                }
            }
        }

        return $resultado;
    }

    function crearFecha($id_mes, $id_anno, $id_cliente, $tipomes, $salariotrabajador, $impuestos) {
        $sql = "INSERT INTO clientefecha (id_mes,id_anno,id_cliente,tipomes,salariotrabajador)"
                . " values('$id_mes','$id_anno','$id_cliente','$tipomes','$salariotrabajador') ";
        $conexion = new Conexion();
        $conexion->ejecutarConsulta($sql);
        $sql = "Select id_clienteFecha "
                . "from clientefecha"
                . " where id_mes = '$id_mes' and id_anno = '$id_anno' and id_cliente = '$id_cliente'";
        $id_clienteFecha = $conexion->devolverResultados($sql)[0]["id_clienteFecha"];
        if ($impuestos != 0) {
            foreach ($impuestos as $impuesto) {
                $tipo_impuesto = $impuesto["tipo_impuesto"];
                $tipo_pago = $impuesto["tipo_pago"];
                $valor = $impuesto["valor"];
                $sql = "INSERT INTO impuestos (tipo_impuesto, tipo_pago, valor, id_clienteFecha)"
                        . "values('$tipo_impuesto','$tipo_pago','$valor','$id_clienteFecha')";

                $conexion->ejecutarConsulta($sql);
            }
        } else {
            echo "aki";
            $array = ["impuesto10%", "cuotaFija", "fuerzaTrabajo", "seguridadSocial"];
            for ($index = 0; $index < 4; $index++) {
                if ($array[$index] == "cueotaFija" || $array[$index] == "impuesto10%") {
                    $sql = "INSERT INTO impuestos (tipo_impuesto, tipo_pago, valor, id_clienteFecha)"
                            . "values('$array[$index]','0','0','$id_clienteFecha')";
                } else {
                    $sql = "INSERT INTO impuestos (tipo_impuesto, tipo_pago, valor, id_clienteFecha)"
                            . "values('$array[$index]','-1','0','$id_clienteFecha')";
                }


                $conexion->ejecutarConsulta($sql);
            }
        }

        $conexion->CerrarConexion();
    }

    function eliminarAnnoParaUnCliente($id_cliente, $id_anno) {
        $sql = "Delete from clientefecha where id_cliente='$id_cliente'and id_anno='$id_anno'";
        $conexion = new Conexion();
        $conexion->ejecutarConsulta($sql);
        $conexion->CerrarConexion();
    }

    function modificarFecha($id_clienteFecha, $tipomes, $salariotrabajador, $impuestos) {
        $conexion = new Conexion();
        $sql = "update  clientefecha "
                . "set tipomes='$tipomes',salariotrabajador ='$salariotrabajador' "
                . "where id_clienteFecha = '$id_clienteFecha'";
        foreach ($impuestos as $impuesto) {

            $tipo_impuesto = $impuesto["tipo_impuesto"];
            $tipo_pago = $impuesto["tipo_pago"];
            $valor = $impuesto["valor"];
            $sql = "update  impuestos "
                    . "set tipo_pago ='$tipo_pago',valor='$valor' "
                    . "where tipo_impuesto = '$tipo_impuesto' and id_clienteFecha = '$id_clienteFecha'";

            $conexion->ejecutarConsulta($sql);
        }


        $conexion->CerrarConexion();
    }

    function actualizarXcientoInjust($xcientoInjust, $id_clienteFecha) {
        $sql = "update  clientefecha "
                . "set xcientoInjust='$xcientoInjust' "
                . "where id_clienteFecha = '$id_clienteFecha'";
        $conexion = new Conexion();
        $conexion->ejecutarConsulta($sql);
        $conexion->CerrarConexion();
    }

    function eliminarMesParaUnCliente($id_clienteFecha) {

        $sql = "Delete From clientefecha where id_clienteFecha = '$id_clienteFecha'";


        $conexion = new Conexion();
        $conexion->ejecutarConsulta($sql);
        $conexion->CerrarConexion();
    }

    function acumuladoAnual($id_cliente, $id_anno) {
        $sql = "SELECT sum(acumulado)as acumulado FROM  clientefecha  "
                . " WHERE id_cliente='$id_cliente'"
                . " AND id_anno='$id_anno'";
        $conexion = new Conexion();
        $acumuladoAnual = $conexion->devolverResultados($sql);
        $conexion->CerrarConexion();
        return $acumuladoAnual;
    }

    function actualizarAcumulado($acumulado, $id_clienteFecha, $tributo) {
        $sql = "UPDATE clientefecha SET acumulado='$acumulado',tributo='$tributo'"
                . " WHERE id_clienteFecha='$id_clienteFecha'";
        $sql2 = "UPDATE impuestos SET valor='$tributo'"
                . " WHERE id_clienteFecha='$id_clienteFecha' and tipo_impuesto = 'impuesto10%'";
        $conexion = new Conexion();

        if ($conexion->ejecutarConsulta($sql) && $conexion->ejecutarConsulta($sql2)) {
            $conexion->CerrarConexion();
            return "ok";
        } else {
            $conexion->CerrarConexion();
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
        $conexion->CerrarConexion();
        return $resultado;
    }

    function actualizarImpuestoDiez($id_clienteFecha, $impuesto) {
        $sql = "UPDATE impuestos SET valor='$impuesto'"
                . " WHERE id_clienteFecha='$id_clienteFecha'and tipo_impuesto ='impuesto10%'";
        $conexion = new Conexion();
        $conexion->ejecutarConsulta($sql);
        if ($conexion->ejecutarConsulta($sql)) {
            $conexion->CerrarConexion();
            return "ok";
        } else {
            $conexion->CerrarConexion();
            return "error";
        }
    }

    function mostrarMesDatosComplementarios($id_clienteFecha) {
        $sql = "Select * "
                . "from clientefecha cf"
                . " where cf.id_clienteFecha='$id_clienteFecha'";

        $conexion = new Conexion();
        $resultado = $conexion->devolverResultados($sql);

        $conexion->CerrarConexion();
        return $resultado;
    }

    function mostrarDatosImpuestos($id_clienteFecha) {
        $sql = "Select * "
                . "from impuestos "
                . " where  id_clienteFecha='$id_clienteFecha'  ";
        $conexion = new Conexion();
        $resultado = $conexion->devolverResultados($sql);

        $conexion->CerrarConexion();
        return $resultado;
    }

    function devolverMesSiguiente($id_mes) {
        $id_mes = intval($id_mes) + 1;
        $sql = "Select * from meses where id_mes='$id_mes'";
        $conexion = new Conexion();
        $resultado = $conexion->devolverResultados($sql);
        $conexion->CerrarConexion();
        return $resultado;
    }

    function devolverFechaAnterior($id_mes, $id_anno) {
        $id_mes = $id_mes - 1;
        if ($id_mes != 0) {
          ;
            $sql = "Select id_clienteFecha from clientefecha "
                    . "where id_mes ='$id_mes' and id_anno = '$id_anno'";
            $conexion = new Conexion();
            $resultado = $conexion->devolverResultados($sql);
            $conexion->CerrarConexion();
            return $resultado;
        } else {
            return 0;
        }
    }

}
