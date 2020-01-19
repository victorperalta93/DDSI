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

// Registrar llegada  de un pedido realizado
function registrarLlegada($id_pedido_que_ha_llegado){
    $db = Database::getInstancia();
    $mysqli = $db->getConexion();

    $peticion = $mysqli->query("SELECT id_producto, cantidad FROM incluye WHERE id_pedido='$id_pedido_que_ha_llegado';");
    $producto = array();
    $almacen = array();
    $z=0;
    $i=0;
    $j=0;
    while($fila = $peticion->fetch_assoc()){
        $producto[$i] = $fila;
        $i++;
    }

    while(j<i){
        $peticion2 = $mysqli->query("SELECT seccion,estanteria FROM almacen WHERE capacidad>='$producto[$j].cantidad';");
        while($fila2 = $peticion2->fetch_assoc()){
            $almacen[$z] = $fila2;
            $sentencia = $mysqli->prepare("INSERT INTO almacena (seccion, estanteria, id_producto, cantidad)
             VALUES (seccion_elegida, estanteria_elegida, id_producto_fabricado, cantidad_producto_llegado); VALUES(?,?,?,?)");
            $sentencia->bind_param("iiii",$almacen[$z].seccion,$almacen[$z].estanteria,$producto[$j].id_producto,$producto[$j].cantidad);
            $sentencia->execute();
            $z++;
        }
        $j++;
    }

    $sentencia = $mysqli->prepare("INSERT INTO almacena (seccion, estanteria, id_producto, cantidad)
     VALUES (seccion_elegida, estanteria_elegida, id_producto_fabricado, cantidad_producto_llegado); VALUES(?,?,?,?)");
    $sentencia->bind_param("iiii",$producto[0].seccion,$producto[0].estanteria,$id_producto,$cantidad_producida);
    $sentencia->execute();
}

function hacerPedido($nif_proveedor,$coste,$fecha_entrega,$id_producto,$cantidad){
    $db = Database::getInstancia();
    $mysqli = $db->getConexion();

    $sentencia = $mysqli->prepare("INSERT INTO PedidoRealizadoA (id_pedido,NIF,coste,fechaEntrega) VALUES(?,?,?,?)");
    $sentencia->bind_param("isis",NULL,$nif_proveedor,$coste,$fecha_entrega);
    $sentencia->execute();

    $peticion = $mysqli->query("SELECT max(id_pedido) FROM PedidoRealizadoA;");
    $producto = array();
    while($fila = $peticion->fetch_assoc()){
        $producto[$i] = $fila;
        $i++;
    }
    $sentencia2 = $mysqli->prepare("INSERT INTO incluye (id_pedido,id_producto,cantidad) VALUES(?,?,?)");
    $sentencia2->bind_param("iii",producto[0].id_pedudi,$id_producto,$cantidad);
    $sentencia2->execute();
}