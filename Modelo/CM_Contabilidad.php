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

    function actualizarImpuesto($id_clienteFecha, $tipoImp, $saldo_final) {
        $sql = "UPDATE impuestos "
                . "SET saldo_final='$saldo_final' "
                . "WHERE id_clienteFecha = '$id_clienteFecha' and tipo_impuesto = '$tipoImp'";
        $conexion = new Conexion();
        $conexion->ejecutarConsulta($sql);
        $conexion->CerrarConexion();
    }

    function encontrarUltimaInstanciaCuenta($id_clienteFecha, $id_cuenta, $id_subcuenta) {
        $sql = "SELECT saldo_final FROM instanciamayor "
                . "WHERE id_cuenta='$id_cuenta' and id_subcuenta = '$id_subcuenta' and id_clienteFecha <> '$id_clienteFecha' "
                . "ORDER By id_clienteFecha DESC LIMIT 1 ";
        $conexion = new Conexion();
        $instancias = $conexion->devolverResultados($sql);

        $conexion->CerrarConexion();
        if ($instancias != false) {
            return $instancias[0][0];
        } else {
            return $instancias;
        }
    }

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

    function buscar($elemento, $array, $clave) {
        $pos = 0;
        $i = 0;
        while ($i < count($array)) {
            if ($array[$i][$clave] == $elemento) {
                $pos = $i;
                break;
            }
            $i++;
        }
        return $pos;
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

    public function getSaldoAnterior($id_cuenta, $id_clienteFecha, $tipo) {
        return $this->saldoAnterior($id_cuenta, $id_clienteFecha, $tipo);
    }

    private function saldoAnterior($id_cuenta, $id_clienteFecha, $tipo) {
        //calcula el saldo que tuvo una cuenta en el mes anterior tanto por debe como por el haber
        if ($tipo == "cuenta") {
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
        } else if ($tipo == "subcuenta1") {

            $sql1 = "SELECT sum(valor) FROM instanciacuenta ic,subcuentas sub,cuentas c "
                    . "WHERE ic.id_clienteFecha='$id_clienteFecha' and"
                    . " c.id_cuenta=ic.id_cuenta and"
                    . " sub.id_subcuenta=ic.id_subcuenta and ic.id_subcuenta = '$id_cuenta'";


            $conexion = new Conexion();
            $instancias1 = $conexion->devolverResultados($sql1);
            ///arreglar esto
            return [$instancias1[0][0], 0];
        } else if ($tipo == "subcuenta2") {
            $sql2 = "SELECT sum(valor) FROM instanciacuenta ic,subcuentas sub,cuentas c "
                    . "WHERE ic.id_clienteFecha='$id_clienteFecha' and"
                    . " c.id_cuenta=ic.id_cuenta and"
                    . " sub.id_subcuenta=ic.id_subcuenta and ic.contrapartida = c.id_cuenta and "
                    . "ic.id_subcuenta = '$id_cuenta'";
            $conexion = new Conexion();
            $instancias1 = $conexion->devolverResultados($sql2);
            ///arreglar esto
            return [0, $instancias1[0][0]];
        }
    }

    function EsSubcuentaDe($id_cuenta, $id_subcuenta) {
        //verifica que sea una subcuenta de una cuenta determinada
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

        if ($instancia == false) {
            $sql = "SELECT nombre_subcuenta FROM subcuentas  "
                    . "WHERE id_subcuenta='$id_cuenta'";

            $instancia = $conexion->devolverResultados($sql);
        }


        return $instancia;
    }

    private function obtenerNaturalezaCuenta($id_cuenta) {
        $sql = "SELECT naturaleza FROM cuentas  "
                . "WHERE id_cuenta='$id_cuenta'";
        $conexion = new Conexion();
        $instancia = $conexion->devolverResultados($sql);
        if ($instancia == false) {
            $sql = "SELECT naturaleza FROM subcuentas  "
                    . "WHERE id_subcuenta='$id_cuenta'";

            $instancia = $conexion->devolverResultados($sql);
        }
        return $instancia;
    }

    function esTrimestral($id_clienteFecha) {
        $sql = "Select id_mes "
                . "from clientefecha cf"
                . " where cf.id_clienteFecha='$id_clienteFecha'";
        $conexion = new Conexion();

        $instancia = $conexion->devolverResultados($sql);
        if ($instancia[0]['id_mes'] == 3 || $instancia[0]['id_mes'] == 6 || $instancia[0]['id_mes'] == 9 || $instancia[0]['id_mes'] == 12) {
            return true;
        } else {
            return false;
        }
    }

    function crearInstanciaMayor($id_cuenta, $id_clienteFecha, $saldo_final, $id_subcuenta, $tipo) {
        $conexion = new Conexion();

        $sql = "SELECT saldo_final FROM instanciamayor  "
                . "WHERE id_cuenta='$id_cuenta' and "
                . "id_clienteFecha='$id_clienteFecha' "
                . "and id_subcuenta='$id_subcuenta' ";
        $instancia = $conexion->devolverResultados($sql);
        if ($instancia == false) {
            $sql = "INSERT INTO instanciamayor (id_cuenta,id_clienteFecha,saldo_final,id_subcuenta)"
                    . " VALUES ('$id_cuenta','$id_clienteFecha','$saldo_final','$id_subcuenta')";

            $conexion->ejecutarConsulta($sql);
        } else {

            if ($saldo_final != $instancia[0][0]) {

                $sql = "UPDATE instanciamayor SET saldo_final='$saldo_final' "
                        . "WHERE id_cuenta='$id_cuenta' and id_subcuenta='$id_subcuenta' and id_clienteFecha='$id_clienteFecha' ";
                $conexion->ejecutarConsulta($sql);
            }
        }
        $conexion->CerrarConexion();
    }

    function obtenerSaldoAnterior($id_cuenta, $id_clienteFecha, $id_subcuenta) {

        $conexion = new Conexion();
        $sql = "SELECT saldo_final FROM instanciamayor  "
                . "WHERE id_cuenta='$id_cuenta' and "
                . "id_clienteFecha='$id_clienteFecha' "
                . "and id_subcuenta='$id_subcuenta' ";

        $instancia = $conexion->devolverResultados($sql);
        if ($instancia != false) {
            return $instancia[0][0];
        } else {
            return $instancia;
        }
    }

    function depuradorMayor($libroMayor, $id_clienteFecha) {

        $sql = "SELECT saldo_final,id_cuenta,id_subcuenta FROM instanciamayor  "
                . "WHERE id_clienteFecha='$id_clienteFecha' ";
        $conexion = new Conexion();
        $instancias_anterior = $conexion->devolverResultados($sql);
        if ($instancias_anterior != false) {
            foreach ($instancias_anterior as $vIa) {

                if (is_numeric(array_search($vIa["id_cuenta"], array_column($libroMayor, 'id_cuenta')))) {


                    if ($vIa["id_subcuenta"] != 0) {

                        $pos = array_search($vIa["id_cuenta"], array_column($libroMayor, 'id_cuenta'));
                        if (!is_numeric(array_search($vIa["id_subcuenta"], array_column($libroMayor[$pos]['subcuentas'], 'id_subcuenta')))) {

                            if ($this->EsSubcuentaDe($vIa["id_cuenta"], $vIa["id_subcuenta"])) {
                                array_push($libroMayor[$pos]['subcuentas'], [
                                    'id_subcuenta' => $vIa["id_subcuenta"],
                                    'valor' => 0,
                                    'nombre_subcuenta' => $this->obtenerNombreCuenta($vIa["id_subcuenta"])[0][0],
                                    'saldoAnterior' => $vIa["saldo_final"]
                                        ]
                                );

                                $libroMayor[$pos]["saldoAnterior"] += $vIa["saldo_final"];
                            }
                        } else {
                            if ($vIa["id_cuenta"] == "800") {
                                $pos2 = array_search($vIa["id_subcuenta"], array_column($libroMayor[$pos]['subcuentas'], 'id_subcuenta'));
                                $libroMayor[$pos]['subcuentas'][$pos2]['saldoAnterior'] = $vIa['saldo_final'];
                                $libroMayor[$pos]["saldoAnterior"] += $vIa["saldo_final"];
                            }
                        }
                    } else {
                        $pos = array_search($vIa["id_cuenta"], array_column($libroMayor, 'id_cuenta'));
                        $libroMayor[$pos]['saldoAnterior'] = $vIa['saldo_final'];
                    }
                } else {


                    if ($vIa["id_subcuenta"] != 0) {


                        array_push($libroMayor, [
                            'id_cuenta' => $vIa["id_cuenta"],
                            'nombre' => $this->obtenerNombreCuenta($vIa["id_cuenta"])[0][0],
                            'debe' => 0,
                            'haber' => 0,
                            'clasificacion' => $this->obtenerNaturalezaCuenta($vIa["id_cuenta"])[0][0],
                            'saldoAnterior' => $vIa["saldo_final"],
                            'subcuentas' => [
                                ['id_subcuenta' => $vIa["id_subcuenta"],
                                    'valor' => 0,
                                    'nombre_subcuenta' => $this->obtenerNombreCuenta($vIa["id_subcuenta"])[0][0],
                                    'saldoAnterior' => $vIa["saldo_final"]
                                ]
                            ]
                        ]);
                    } else {
                        array_push($libroMayor, [
                            'id_cuenta' => $vIa["id_cuenta"],
                            'nombre' => $this->obtenerNombreCuenta($vIa["id_cuenta"])[0][0],
                            'debe' => 0,
                            'haber' => 0,
                            'clasificacion' => $this->obtenerNaturalezaCuenta($vIa["id_cuenta"])[0][0],
                            'saldoAnterior' => $vIa["saldo_final"],
                            'subcuentas' => 0]);
                    }
                }
            }
        }
        return $libroMayor;
    }

    function obtenerInstanciasMayor($id_clienteFecha, $id_clienteFecha2, $tipo) {

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

                    $saldoAnterior = ($this->obtenerSaldoAnterior($instancias1[$k]['id_cuenta'], $id_clienteFecha2, '0'));
                    $saldoAnteriorSubcuenta = $this->obtenerSaldoAnterior($instancias1[$k]['id_cuenta'], $id_clienteFecha2, $instancias1[$k]['id_subcuenta']);


                    if ($saldoAnteriorSubcuenta == false) {
                        $saldoAnteriorSubcuenta = $this->encontrarUltimaInstanciaCuenta($id_clienteFecha, $instancias1[$k]['id_cuenta'], $instancias1[$k]['id_subcuenta']);

                        if ($saldoAnteriorSubcuenta == false) {
                            $saldoAnteriorSubcuenta = 0;
                        }
                    }
                } else {
                    $saldoAnterior = 0;
                    $saldoAnteriorSubcuenta = 0;
                }


                if ($this->EsSubcuentaDe($instancias1[$k]['id_cuenta'], $instancias1[$k]['id_subcuenta'])) {

                    array_push($entrega, [
                        'id_cuenta' => $instancias1[$k]['id_cuenta'],
                        'nombre' => $instancias1[$k]['nombre_cuenta'],
                        'debe' => $instancias1[$k]['valor'],
                        'haber' => 0,
                        'clasificacion' => $instancias1[$k]['naturaleza'],
                        'saldoAnterior' => $saldoAnterior,
                        'subcuentas' => [
                            ['id_subcuenta' => $instancias1[$k]['id_subcuenta'],
                                'valor' => $instancias1[$k]['valor'],
                                'nombre_subcuenta' => $instancias1[$k]['nombre_subcuenta'],
                                'saldoAnterior' => $saldoAnteriorSubcuenta,]]]);
                } else {
                    array_push($entrega, [
                        'id_cuenta' => $instancias1[$k]['id_cuenta'],
                        'nombre' => $instancias1[$k]['nombre_cuenta'],
                        'debe' => $instancias1[$k]['valor'],
                        'haber' => 0,
                        'clasificacion' => $instancias1[$k]['naturaleza'],
                        'saldoAnterior' => $saldoAnterior,
                        'subcuentas' => 0]);
                }
            } else {
                $pos1 = $this->existeCuenta($entrega, $instancias1[$k]['id_cuenta']);
                $entrega[$pos1]['debe'] += $instancias1[$k]['valor'];
                if ($this->EsSubcuentaDe($instancias1[$k]['id_cuenta'], $instancias1[$k]['id_subcuenta'])) {
                    if ($id_clienteFecha2 != 0) {

                        $saldoAnteriorSubcuenta = $this->obtenerSaldoAnterior($instancias1[$k]['id_cuenta'], $id_clienteFecha2, $instancias1[$k]['id_subcuenta']);
                        if ($saldoAnteriorSubcuenta != false) {
                            $saldoAnteriorSubcuenta = $saldoAnteriorSubcuenta[0];
                        } else {
                            $saldoAnteriorSubcuenta = $this->encontrarUltimaInstanciaCuenta($id_clienteFecha, $instancias1[$k]['id_cuenta'], $instancias1[$k]['id_subcuenta']);
                            if (!$saldoAnteriorSubcuenta != false) {
                                $saldoAnteriorSubcuenta = 0;
                            } else {
                                $entrega[$pos1]['saldoAnterior'] += $saldoAnteriorSubcuenta;
                            }
                        }
                    } else {
                        $saldoAnteriorSubcuenta = 0;
                    }

                    array_push($entrega[$pos1]['subcuentas'],
                            [
                                'id_subcuenta' => $instancias1[$k]['id_subcuenta'],
                                'valor' => $instancias1[$k]['valor'],
                                'nombre_subcuenta' => $instancias1[$k]['nombre_subcuenta'],
                                'saldoAnterior' => $saldoAnteriorSubcuenta]);
                }
            }

            if ($this->existeCuenta($entrega, $instancias1[$k]['contrapartida']) == -1) {
                if ($id_clienteFecha2 != 0) {

                    $saldoAnterior2 = $this->obtenerSaldoAnterior($instancias1[$k]['contrapartida'], $id_clienteFecha2, $instancias1[$k]['id_subcuenta']);
                    $saldoAnteriorSubcuenta2 = $this->obtenerSaldoAnterior($instancias1[$k]['contrapartida'], $id_clienteFecha2, $instancias1[$k]['id_subcuenta']);
                    if ($saldoAnterior2 == false) {
                        $saldoAnterior2 = $this->encontrarUltimaInstanciaCuenta($id_clienteFecha, $instancias1[$k]['contrapartida'], $instancias1[$k]['id_subcuenta']);
                    }

                    if ($saldoAnteriorSubcuenta2 == false) {
                        $saldoAnteriorSubcuenta2 = $this->encontrarUltimaInstanciaCuenta($id_clienteFecha, $instancias1[$k]['contrapartida'], $instancias1[$k]['id_subcuenta']);
                        if ($saldoAnteriorSubcuenta2 == false) {
                            $saldoAnteriorSubcuenta2 = 0;
                        }
                    }
                } else {
                    $saldoAnterior2 = 0;
                    $saldoAnteriorSubcuenta2 = 0;
                }

                $nombre = $this->obtenerNombreCuenta($instancias1[$k]['contrapartida'])[0][0];
                if ($this->EsSubcuentaDe($instancias1[$k]['contrapartida'], $instancias1[$k]['id_subcuenta'])) {
                    $debe = 0;
                    if ($instancias1[$k]['contrapartida'] == '600') {
                        if ($tipo == "Micro") {
                            $debe = 750;
                        } else {
                            $debe = 1200;
                        }

                        if ($this->esTrimestral($id_clienteFecha)) {
                            $debe += 262.5;
                        }
                    }


                    array_push($entrega, [
                        'id_cuenta' => $instancias1[$k]['contrapartida'],
                        'nombre' => $nombre,
                        'debe' => $debe,
                        'haber' => $instancias1[$k]['valor'],
                        'clasificacion' => $instancias1[$k]['naturaleza'],
                        'saldoAnterior' => $saldoAnterior2,
                        'subcuentas' => [
                            ['id_subcuenta' => $instancias1[$k]['id_subcuenta'],
                                'valor' => $instancias1[$k]['valor'],
                                'nombre_subcuenta' => $instancias1[$k]['nombre_subcuenta'],
                                'saldoAnterior' => $saldoAnteriorSubcuenta2]]]);
                } else {
                    array_push($entrega, [
                        'id_cuenta' => $instancias1[$k]['contrapartida'],
                        'nombre' => $nombre,
                        'debe' => 0,
                        'haber' => $instancias1[$k]['valor'],
                        'clasificacion' => $instancias1[$k]['naturaleza'],
                        'saldoAnterior' => $saldoAnterior2,
                        'subcuentas' => 0]);
                }
            } else {

                $pos2 = $this->existeCuenta($entrega, $instancias1[$k]['contrapartida']);
                $entrega[$pos2]['haber'] += $instancias1[$k]['valor'];
                if ($this->EsSubcuentaDe($instancias1[$k]['contrapartida'], $instancias1[$k]['id_subcuenta'])) {

                    if ($id_clienteFecha2 != 0) {


                        $saldoAnteriorSubcuenta2 = $this->obtenerSaldoAnterior($instancias1[$k]['id_subcuenta'], $id_clienteFecha2, 'subcuenta2')[0];
                    } else {

                        $saldoAnteriorSubcuenta2 = 0;
                    }

                    array_push($entrega[$pos2]['subcuentas'],
                            ['id_subcuenta' => $instancias1[$k]['id_subcuenta'],
                                'valor' => $instancias1[$k]['valor'],
                                'nombre_subcuenta' => $instancias1[$k]['nombre_subcuenta'],
                                'saldoAnterior' => $saldoAnteriorSubcuenta2]);
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

                        if (!is_numeric(array_search($instancia['contrapartida'], array_column($array_pos, 'contrapartida'))) && !is_numeric(array_search('800', array_column($array_pos, 'id')))) {

                            array_push($aux, ['id_cuenta' => $instancia['id_cuenta'],
                                'nombre_cuenta' => $instancia['nombre_cuenta'],
                                'contrapartida' => $instancia['contrapartida'],
                                'valor' => $instancia['valor'],
                                'subcuentas' => [['id_subcuenta' => $instancia['id_subcuenta'],
                                'nombre_subcuenta' => $instancia['nombre_subcuenta'],
                                'valor' => $instancia['valor'],
                                'alcance' => $instancia['alcance']]]]);
                            array_push($array_pos, ['id' => $instancia['id_cuenta'],
                                'pos' => $pos, 'contrapartida' => $instancia['contrapartida']]);
                            $pos++;
                        } else {


                            for ($k = 0; $k < count($array_pos); $k++) {
                                if ($array_pos[$k]['id'] == $instancia['id_cuenta']) {
                                    $valor = $array_pos[$k]['pos'];
                                } else {
                                    $valor = -1;
                                }
                            }

                            //agregarle una subcuenta
                            if ($valor != -1) {
                                if ($aux[$valor]['contrapartida'] == $instancia['contrapartida']) {
                                    array_push($aux[$valor]['subcuentas'],
                                            ['id_subcuenta' => $instancia['id_subcuenta'],
                                                'nombre_subcuenta' => $instancia['nombre_subcuenta'],
                                                'valor' => $instancia['valor'],
                                                'alcance' => $instancia['alcance']]);
                                    //sumarle el valor

                                    $aux[$valor]['valor'] += $instancia['valor'];
                                } else {
                                    array_push($aux, ['id_cuenta' => $instancia['id_cuenta'],
                                        'nombre_cuenta' => $instancia['nombre_cuenta'],
                                        'contrapartida' => $instancia['contrapartida'],
                                        'valor' => $instancia['valor'],
                                        'subcuentas' => [['id_subcuenta' => $instancia['id_subcuenta'],
                                        'nombre_subcuenta' => $instancia['nombre_subcuenta'],
                                        'valor' => $instancia['valor'],
                                        'alcance' => $instancia['alcance']]]]);
                                    array_push($array_pos, ['id' => $instancia['id_cuenta'],
                                        'pos' => $pos, 'contrapartida' => $instancia['contrapartida']]);
                                    $pos++;
                                }
                            } else {
                                array_push($aux, ['id_cuenta' => $instancia['id_cuenta'],
                                    'nombre_cuenta' => $instancia['nombre_cuenta'],
                                    'contrapartida' => $instancia['contrapartida'],
                                    'valor' => $instancia['valor'],
                                    'subcuentas' => [['id_subcuenta' => $instancia['id_subcuenta'],
                                    'nombre_subcuenta' => $instancia['nombre_subcuenta'],
                                    'valor' => $instancia['valor'],
                                    'alcance' => $instancia['alcance']]]]);
                                array_push($array_pos, ['id' => $instancia['id_cuenta'],
                                    'pos' => $pos, 'contrapartida' => $instancia['contrapartida']]);
                                $pos++;
                            }
                        }
                    }
                }
            }
        }




        $conexion->CerrarConexion();
        return $aux;
    }

    function ExisteComprobante($id_clienteFecha, $grupoComprobante) {
        $sql = "select * from comprobantes where id_clienteFecha = '$id_clienteFecha' and grupo_comprobante = '$grupoComprobante'";
        $conexion = new Conexion();
        $instancias = $conexion->devolverResultados($sql);
        $conexion->CerrarConexion();
        return $instancias;
    }

    function ExisteInstancia($id_clienteFecha, $cuenta) {
        $sql = "select * from instanciacuenta "
                . "where id_clienteFecha = '$id_clienteFecha' and id_subcuenta = '$cuenta'";
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

    function crearComprobante($id_clienteFecha, $grupoComprobante) {
        if (!$this->ExisteComprobante($id_clienteFecha, $grupoComprobante) != false) {
            $sql = "Insert into comprobantes (descripcion,id_clienteFecha,grupo_comprobante)"
                    . " values('','$id_clienteFecha','$grupoComprobante')";
            $conexion = new Conexion();
            $conexion->ejecutarConsulta($sql);
            $conexion->CerrarConexion();
        }
    }

    function crearInstanciaCuenta($id_cuenta, $id_subcuenta, $valor, $id_clienteFecha, $id_contrapartida, $grupoComprobante, $estado, $alcance) {

        $sql = "Insert into instanciacuenta (id_cuenta,id_subcuenta,valor,id_clienteFecha,contrapartida,grupoComprobante,estado,alcance)"
                . " values('$id_cuenta','$id_subcuenta','$valor','$id_clienteFecha','$id_contrapartida','$grupoComprobante','$estado','$alcance')";

        $conexion = new Conexion();
        $instancias = $conexion->ejecutarConsulta($sql);

//Crear comprobante 
        $this->crearComprobante($id_clienteFecha, $grupoComprobante);

        if (!$this->ExisteInstancia($id_clienteFecha, '90') != false) {
            $sql = "SELECT salariotrabajador FROM clientefecha  where"
                    . " id_clienteFecha='$id_clienteFecha' ";
            $valor = $conexion->devolverResultados($sql);
            $valor = $valor[0][0];

            $sql2 = "Insert into instanciacuenta (id_cuenta,id_subcuenta,valor,id_clienteFecha,contrapartida,grupoComprobante,estado,alcance)"
                    . " values('800','90','$valor','$id_clienteFecha','100','1','1','subcuenta1')";
            $conexion->ejecutarConsulta($sql2);
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

    function crearBonificacion($id_clienteFecha, $tipo_impuesto, $valor) {
        $sql = "select * from bonificacion "
                . "where id_clienteFecha = '$id_clienteFecha' and impuesto = '$tipo_impuesto'";
        $conexion = new Conexion();
        $resultado = $conexion->devolverResultados($sql);
        if ($resultado != false) {
            $sql = "update  bonificacion "
                    . "set valor='$valor'"
                    . "where id_clienteFecha='$id_clienteFecha'and valor<>$valor and impuesto = '$tipo_impuesto'";
            $resultado = $conexion->ejecutarConsulta($sql);
        } else {
            $sql = "Insert into bonificacion (id_clienteFecha,impuesto,valor)"
                    . " values('$id_clienteFecha','$tipo_impuesto','$valor')";
            $conexion->ejecutarConsulta($sql);
        }
        $conexion->CerrarConexion();
    }

    function obtenerBonificaciones($id_clienteFecha) {
        $sql = "select * from bonificacion "
                . "where id_clienteFecha = '$id_clienteFecha'";
        $conexion = new Conexion();
        $resultado = $conexion->devolverResultados($sql);
        $conexion->CerrarConexion();
        return $resultado;
    }

}
