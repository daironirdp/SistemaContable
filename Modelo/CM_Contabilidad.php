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

    private function existeCuenta($entrega, $cuenta) {
        $pos = -1;
        for ($i = 0; $i < count($entrega); $i++) {
            if ($entrega[$i]["id_cuenta"] == $cuenta) {
                $pos = $i;
            }
        }
        return $pos;
    }

    private function saldoAnterior($id_cuenta, $id_clienteFecha) {
        $sql1 = "SELECT sum(valor) FROM instanciacuenta ic,subcuentas sub,cuentas c "
                . "WHERE ic.id_clienteFecha='$id_clienteFecha' and"
                . " c.id_cuenta=ic.id_cuenta and"
                . " sub.id_subcuenta=ic.id_subcuenta and ic.id_cuenta = '$id_cuenta'";

        $sql2 = "SELECT sum(valor) FROM instanciacuenta ic,subcuentas sub,cuentas c "
                . "WHERE ic.id_clienteFecha='$id_clienteFecha' and"
                . " c.id_cuenta=ic.id_cuenta and"
                . " sub.id_subcuenta=ic.id_subcuenta and ic.contrapartida = '$id_cuenta'";
        $conexion = new Conexion();
        $instancias1 = $conexion->devolverResultados($sql1);
        $instancias2 = $conexion->devolverResultados($sql2);
        return [$instancias1[0][0], $instancias2[0][0]];
    }

    private function EsSubcuentaDe($id_cuenta, $id_subcuenta) {
        $sql = "SELECT id_cuenta FROM subcuentas  "
                . "WHERE id_subcuenta='$id_subcuenta'";
        $conexion = new Conexion();
        $instancia = $conexion->devolverResultados($sql);
        if ($instancia[0]['id_cuenta'] == $id_cuenta) {
            return true;
        } else {
            return false;
        }
    }

    private function obtenerNombreCuenta($id_cuenta) {
        $sql = "SELECT nombre_cuenta FROM cuentas  "
                . "WHERE id_cuenta='$id_cuenta'";
        $conexion = new Conexion();
        $instancia = $conexion->devolverResultados($sql);
        return $instancia;
    }

    function obtenerInstanciasMayor($id_clienteFecha, $id_clienteFecha2) {

        $sql = "SELECT * FROM instanciacuenta ic,subcuentas sub,cuentas c "
                . "WHERE ic.id_clienteFecha='$id_clienteFecha' and"
                . " c.id_cuenta=ic.id_cuenta and"
                . " sub.id_subcuenta=ic.id_subcuenta";

        $conexion = new Conexion();
        $instancias1 = $conexion->devolverResultados($sql);

        $entrega = array();


        for ($k = 0; $k < count($instancias1); $k++) {

            if ($this->existeCuenta($entrega, $instancias1[$k]['id_cuenta']) == -1) {
                if ($id_clienteFecha2 != 0) {
                    $saldoAnterior = $this->saldoAnterior($instancias1[$k]['id_cuenta'], $id_clienteFecha2);
                } else {
                    $saldoAnterior = 0;
                }


                if ($this->EsSubcuentaDe($instancias1[$k]['id_cuenta'], $instancias1[$k]['id_subcuenta'])) {

                    array_push($entrega, [
                        'id_cuenta' => $instancias1[$k]['id_cuenta'],
                        'nombre' => $instancias1[$k]['nombre_cuenta'],
                        'debe' => $instancias1[$k]['valor'],
                        'haber' => 0,
                        'clasificacion' => '',
                        'saldoAnterior' => $saldoAnterior,
                        'subcuentas' => [
                            ['id_subcuenta' => $instancias1[$k]['id_subcuenta'],
                                'valor' => $instancias1[$k]['valor'],
                                'nombre_subcuenta' => $instancias1[$k]['nombre_subcuenta']]]]);
                } else {
                    array_push($entrega, [
                        'id_cuenta' => $instancias1[$k]['id_cuenta'],
                        'nombre' => $instancias1[$k]['nombre_cuenta'],
                        'debe' => $instancias1[$k]['valor'],
                        'haber' => 0,
                        'clasificacion' => '',
                        'saldoAnterior' => $saldoAnterior,
                        'subcuentas' => 0]);
                }
            } else {
                $pos1 = $this->existeCuenta($entrega, $instancias1[$k]['id_cuenta']);
                $entrega[$pos1]['debe'] += $instancias1[$k]['valor'];
                if ($this->EsSubcuentaDe($instancias1[$k]['id_cuenta'], $instancias1[$k]['id_subcuenta'])) {
                    array_push($entrega[$pos1]['subcuentas'],
                            ['id_subcuenta' => $instancias1[$k]['id_subcuenta'],
                                'valor' => $instancias1[$k]['valor'],
                                'nombre_subcuenta' => $instancias1[$k]['nombre_subcuenta'],
                                'saldoAnterior' => 0]);
                }
            }

            if ($this->existeCuenta($entrega, $instancias1[$k]['contrapartida']) == -1) {
                if ($id_clienteFecha2 != 0) {
                    $saldoAnterior2 = $this->saldoAnterior($instancias1[$k]['contrapartida'], $id_clienteFecha2);
                } else {
                    $saldoAnterior2 = 0;
                }

                $nombre = $this->obtenerNombreCuenta($instancias1[$k]['contrapartida'])[0][0];
                if ($this->EsSubcuentaDe($instancias1[$k]['contrapartida'], $instancias1[$k]['id_subcuenta'])) {

                    array_push($entrega, [
                        'id_cuenta' => $instancias1[$k]['contrapartida'],
                        'nombre' => $nombre,
                        'debe' => 0,
                        'haber' => $instancias1[$k]['valor'],
                        'clasificacion' => '',
                        'saldoAnterior' => $saldoAnterior2,
                        'subcuentas' => [
                            ['id_subcuenta' => $instancias1[$k]['id_subcuenta'],
                                'valor' => $instancias1[$k]['valor'],
                                'nombre_subcuenta' => $instancias1[$k]['nombre_subcuenta']]]]);
                } else {
                    array_push($entrega, [
                        'id_cuenta' => $instancias1[$k]['contrapartida'],
                        'nombre' => $nombre,
                        'debe' => 0,
                        'haber' => $instancias1[$k]['valor'],
                        'clasificacion' => '',
                        'saldoAnterior' => $saldoAnterior2,
                        'subcuentas' => 0]);
                }
            } else {
                $pos2 = $this->existeCuenta($entrega, $instancias1[$k]['contrapartida']);
                $entrega[$pos2]['haber'] += $instancias1[$k]['valor'];
                if ($this->EsSubcuentaDe($instancias1[$k]['contrapartida'], $instancias1[$k]['id_subcuenta'])) {
                    array_push($entrega[$pos2]['subcuentas'],
                            ['id_subcuenta' => $instancias1[$k]['id_subcuenta'],
                                'valor' => $instancias1[$k]['valor'],
                                'nombre_subcuenta' => $instancias1[$k]['nombre_subcuenta'],
                                'saldoAnterior' => 0]);
                }
            }
        }



        $conexion->CerrarConexion();
        return $entrega;
    }

    function obtenerInstanciasComprobantes($id_clienteFecha) {

        $sql = "SELECT * FROM instanciacuenta ic,subcuentas sub,cuentas c "
                . "WHERE ic.id_clienteFecha='$id_clienteFecha' and"
                . " c.id_cuenta=ic.id_cuenta and"
                . " sub.id_subcuenta=ic.id_subcuenta and (ic.estado=0 or ic.estado=1 or ic.estado=2 or ic.estado=3 or ic.estado=4)";
        $conexion = new Conexion();
        $sql2 = "SELECT Max(grupoComprobante)as maximo FROM instanciacuenta ic,subcuentas sub,cuentas c "
                . "WHERE ic.id_clienteFecha='$id_clienteFecha' and"
                . " c.id_cuenta=ic.id_cuenta and"
                . " sub.id_subcuenta=ic.id_subcuenta and (ic.estado=0 or ic.estado=1 or ic.estado=2 or ic.estado=3 or ic.estado=4)";
        $resultado = $conexion->devolverResultados($sql);
        $maximoComprobante = $conexion->devolverResultados($sql2);
        $maximoComprobante = $maximoComprobante[0]["maximo"];

        $aux = array();
        $instancias = array();
        $array_pos = array();
        $pos = 0;
        for ($i = 0; $i < $maximoComprobante; $i++) {


            foreach ($resultado as $instancia) {

                if ($instancia['grupoComprobante'] == $i + 1) {
                    if ($instancia['id_subcuenta'] == 0) {

                        array_push($aux, ['id_cuenta' => $instancia['id_cuenta'],
                            'nombre_cuenta' => $instancia['nombre_cuenta'],
                            'contrapartida' => $instancia['contrapartida'],
                            'valor' => $instancia['valor'],
                            'subcuentas' => $instancia['id_subcuenta']]);
                        $pos++;
                    } else {

                        if (!is_numeric(array_search('800', array_column($array_pos, 'id')))) {

                            array_push($aux, ['id_cuenta' => $instancia['id_cuenta'],
                                'nombre_cuenta' => $instancia['nombre_cuenta'],
                                'contrapartida' => $instancia['contrapartida'],
                                'valor' => $instancia['valor'],
                                'subcuentas' => [['id_subcuenta' => $instancia['id_subcuenta'],
                                'nombre_subcuenta' => $instancia['nombre_subcuenta'],
                                'valor' => $instancia['valor'],
                                'alcance' => $instancia['alcance']]]]);
                            array_push($array_pos, ['id' => $instancia['id_cuenta'],
                                'pos' => $pos]);
                            $pos++;
                        } else {

                            for ($k = 0; $k < count($array_pos); $k++) {
                                if ($array_pos[$k]['id'] == $instancia['id_cuenta']) {
                                    $valor = $array_pos[$k]['pos'];
                                }
                            }
                            //agregarle una subcuenta
                            array_push($aux[$valor]['subcuentas'],
                                    ['id_subcuenta' => $instancia['id_subcuenta'],
                                        'nombre_subcuenta' => $instancia['nombre_subcuenta'],
                                        'valor' => $instancia['valor'],
                                        'alcance' => $instancia['alcance']]);
                            //sumarle el valor
                            $aux[$valor]['valor'] += $instancia['valor'];
                        }
                    }
                }
            }
        }




        $conexion->CerrarConexion();
        return $aux;
    }

    function ExisteComprobante($id_clienteFecha, $grupoComprobante) {
        $sql = "select * from comprobantes where id_clienteFecha = '$id_clienteFecha' and grupoComprobante = '$grupoComprobante'";
        $conexion = new Conexion();
        $instancias = $conexion->devolverResultados($sql);
        $conexion->CerrarConexion();
        return $instancias;
    }

    function ObtenerComprobantes($id_clienteFecha) {
        $sql = "select * from comprobantes where id_clienteFecha = '$id_clienteFecha'";
        $conexion = new Conexion();
        $instancias = $conexion->devolverResultados($sql);
        $conexion->CerrarConexion();
        return $instancias;
    }

    function ModificarComprobante($id_clienteFecha, $grupoComprobante, $descripcion) {
        $sql = "update  comprobantes "
                . "set descripcion='$descripcion'"
                . "where id_clienteFecha='$id_clienteFecha' and grupo_Comprobante='$grupoComprobante'";

        $conexion = new Conexion();
        $conexion->ejecutarConsulta($sql);
        $conexion->CerrarConexion();
    }

    function crearInstanciaCuenta($id_cuenta, $id_subcuenta, $valor, $id_clienteFecha, $id_contrapartida, $grupoComprobante, $estado, $alcance) {

        $sql = "Insert into instanciacuenta (id_cuenta,id_subcuenta,valor,id_clienteFecha,contrapartida,grupoComprobante,estado,alcance)"
                . " values('$id_cuenta','$id_subcuenta','$valor','$id_clienteFecha','$id_contrapartida','$grupoComprobante','$estado','$alcance')";

        $conexion = new Conexion();
        $instancias = $conexion->ejecutarConsulta($sql);

        if (!$this->ExisteComprobante($id_clienteFecha, $grupoComprobante) != false) {
            $sql = "Insert into comprobantes (descripcion,id_clienteFecha,grupo_comprobante)"
                    . " values('','$id_clienteFecha',$grupoComprobante')";
        }
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

    function eliminarInst($id_instanciaCuenta) {
        $sql = "delete from instanciacuenta "
                . "where id_instanciaCuenta='$id_instanciaCuenta'";
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

    function modificarInstancias($id_instanciaCuenta, $valor, $estado) {
        if ($estado == "gastoJ" || $estado == "gastoI") {
            if ($estado == "gastoJ") {
                $estado = 1;
            } else {
                $estado = 2;
            }
            $sql = "update  instanciacuenta "
                    . "set valor='$valor',estado ='$estado' "
                    . "where id_instanciaCuenta='$id_instanciaCuenta'";
        } else {
            $sql = "update  instanciacuenta "
                    . "set valor='$valor'"
                    . "where id_instanciaCuenta='$id_instanciaCuenta'and valor<>$valor";
        }


        $conexion = new Conexion();
        $conexion->ejecutarConsulta($sql);
        $conexion->CerrarConexion();
    }

}
