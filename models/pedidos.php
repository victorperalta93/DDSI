<?php

function anularPedido($id_pedido_indicado){
    $db = Database::getInstancia();
    $mysqli = $db->getConexion();

    $sentencia = $mysqli->prepare("DELETE FROM pedidoRealizadoA WHERE id_pedido=?");
    $sentencia->bind_param("i",$id_pedido_indicado);
    $sentencia->execute();
}

function eliminarPedido($id_introducido){
    $db = Database::getInstancia();
    $mysqli = $db->getConexion();

    $sentencia = $mysqli->prepare("DELETE FROM producto WHERE id_producto=?");
    $sentencia->bind_param("i",$id_introducido);
    $sentencia->execute();
}

function obtenerInfomacionPedidoRealizado(){
    $db = Database::getInstancia();
    $mysqli = $db->getConexion();

    $peticion = $mysqli->query("SELECT * FROM Proveedor JOIN PedidoRealizadoA JOIN incluye JOIN recursoDeFabricacion");
    $var = array();
    $i=0;
    while($fila = $peticion->fetch_assoc()){
        $var[$i] = $fila;
        $i++;
    }

    return $var;
}