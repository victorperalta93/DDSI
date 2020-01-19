<?php
require_once 'models/database.php';

function localizarProducto($id_introducido){
    $db = Database::getInstancia();
    $mysqli = $db->getConexion();

    $peticion = $mysqli->query("SELECT seccion, estanteria, cantidad FROM almacena WHERE id_producto='$id_introducido';");
    $producto = array();
    $i=0;
    while($fila = $peticion->fetch_assoc()){
        $producto[$i] = $fila;
        $i++;
    }

    return $producto;
}

function obtenerProductos(){
    $db = Database::getInstancia();
    $mysqli = $db->getConexion();

    $peticion = $mysqli->query("SELECT * FROM producto;");
    $productos = array();
    $i=0;
    while($fila = $peticion->fetch_assoc()){
        $productos[$i] = $fila;
        $i++;
    }

    return $productos;
}

function retirarProducto($id_producto,$cantidad){
    $db = Database::getInstancia();
    $mysqli = $db->getConexion();
    $peticion = $mysqli->query("SELECT seccion,estanteria FROM almacena WHERE id_producto='$id_producto' AND cantidad>='$cantidad';");
    $producto = array();
    $i=0;
    $producto2 = array();
    $j=0;
    while($fila = $peticion->fetch_assoc()){
        $producto[$i] = $fila;
        $i++;
    }
    if($fila!=NULL){
        $sentencia = $mysqli->prepare("UPDATE almacena SET cantidad=? WHERE seccion='$producto[0].seccion' AND seccion='$producto[0].estanteria'");
        $sentencia->bind_param("i",$cantidad);
        $sentencia->execute();
    }else {
        $peticion2 = $mysqli->query("SELECT sum(cantidad) FROM almacena WHERE id_producto='$id_producto'';");
        $producto2 = array();
        $j=0;
        while($fila2 = $peticion2->fetch_assoc()){
            $producto2[$j] = $fila2;
            $j++;
        }
        $peticion3 = $mysqli->query("SELECT seccion,estanteria, cantidad FROM almacena WHERE id_producto='$id_producto';");
        $producto3 = array();
        $k=0;
        while($fila3 = $peticion3->fetch_assoc()){
            $producto2[$k] = $fila3;
            $k++;
        }
        $sentencia2 = $mysqli->prepare("UPDATE almacena SET cantidad=? WHERE seccion='$producto2[0].seccion' AND seccion='$producto2[0].estanteria'AND id_producto='$id_producto'");
        $sentencia2->bind_param("i",$cantidad-$producto2[0].cantidad);
        $sentencia2->execute();
    }
}