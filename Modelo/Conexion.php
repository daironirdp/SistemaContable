<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Conexion
 *
 * @author Dairon
 */
header("Access-Control-Allow-Origin:*");
header("Content_type:Aplication/json");
class Conexion {

    //put your code here
    private $conexion;

    function __construct() {
        try {
            $this->conexion = new PDO("mysql:host=localhost;dbname=sistemacontable", "root", "");
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conexion->exec("SET CHARACTER SET utf8");

            return $this->conexion;
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }


        //  return FALSE;
    }

    public function ejecutarConsulta($sql) {

        return $this->conexion->query($sql);
        
    }

    public function devolverResultados($sql) {
        $objeto = $this->conexion->query($sql);

        while ($registros = $objeto->fetch()) {

            $personas[] = $registros;
        }
        if (isset($personas)) {
            return $personas;
       } else {
           return $registros;
       }
    }

    public function CerrarConexion() {
        if (isset($this->objeto))
            $this->objeto->closeCursor();
    }

}
