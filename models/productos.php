<?php
require_once 'models/database.php';

function addProducto($nombre,$otro){
    $db = Database::getInstancia();
    $mysqli = $db->getConexion();

    $sentencia = $mysqli->prepare("INSERT INTO producto (nombre) VALUES(?)");
    $sentencia->bind_param("s",$nombre);
    $sentencia->execute();

    $peticion = $mysqli->query("SELECT max(id_producto) FROM producto;");
    $idproducto = array();
    $i = 0;
    while($fila = $peticion->fetch_assoc()){
        $idproducto[$i] = $fila;
        $i++;
    }

    //Calcular coste
    $coste = random_int(100,400);

    $sentencia2 = $mysqli->prepare("INSERT INTO productoFabricado (id_producto,precio) VALUES(?,?)");
    $sentencia2->bind_param("is",$idproducto[0]['max(id_producto)'],$coste);
    $sentencia2->execute();

    echo $idproducto[0]['max(id_producto)'];
    

    // por cada producto en el pedido, insertar datos en incluye
    foreach ($otro as $clave => $valor) {
        echo $clave;
        echo $valor;
        $sentencia3 = $mysqli->prepare("INSERT INTO forma (id_producto_utilizado,id_producto_formado,cantidad) VALUES(?,?,?)");
        $sentencia3->bind_param("iii",$clave,$idproducto[0]['max(id_producto)'],$valor);
        $sentencia3->execute();
    }
}

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

function eliminarProducto($id_introducido){
    $db = Database::getInstancia();
    $mysqli = $db->getConexion();

    $sentencia = $mysqli->prepare("DELETE FROM producto WHERE id_producto=?");
    $sentencia->bind_param("i",$id_introducido);
    $sentencia->execute();
}

function obtenerProductos(){
    $db = Database::getInstancia();
    $mysqli = $db->getConexion();

    $peticion = $mysqli->query("SELECT producto.id_producto,nombre FROM producto INNER JOIN recursoDeFabricacion ON producto.id_producto = recursoDeFabricacion.id_producto;");
    $productos = array();
    $i=0;
    while($fila = $peticion->fetch_assoc()){
        $productos[$i] = $fila;
        $i++;
    }

    return $productos;
}

function obtenerProductosFabricados(){
    $db = Database::getInstancia();
    $mysqli = $db->getConexion();

    $peticion = $mysqli->query("SELECT producto.id_producto,nombre,productoFabricado.precio FROM producto 
                                INNER JOIN productoFabricado ON producto.id_producto = productoFabricado.id_producto;");
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