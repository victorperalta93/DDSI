<?php 

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

// Registrar llegada  de un pedido realizado
function registrarLlegada($id_pedido_que_ha_llegado){
    $db = Database::getInstancia();
    $mysqli = $db->getConexion();

    $peticion = $mysqli->query("SELECT id_producto, cantidad FROM incluye WHERE id_pedido='$id_pedido_que_ha_llegado';");
    $producto = array();
    $almacen = array();
    $z=0
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