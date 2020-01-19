<?php
require_once 'models/database.php';

function obtenerProveedores(){
    $db = Database::getInstancia();
    $mysqli = $db->getConexion();

    $peticion = $mysqli->query("SELECT nombre FROM Proveedor;");
    $proveedores = array();
    $i=0;
    while($fila = $peticion->fetch_assoc()){
        $proveedores[$i] = $fila;
        $i++;
    }

    return $proveedores;
}