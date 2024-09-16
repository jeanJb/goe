<?php
class Conexion{
    public static function getConexion(){
        $conn= null;
        try {
            $conn = new PDO("mysql:host=localhost;dbname=agenda", "root", "");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            echo 'Error'. $ex->getMessage();
        }
        return $conn;
    }
}

$con = Conexion::getConexion();
