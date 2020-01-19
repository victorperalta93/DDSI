<?php
// muestra todos los errores generados por PHP en el navegador
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function anularPedido($id_pedido_indicado){
    $db = Database::getInstancia();
    $mysqli = $db->getConexion();

    $sentencia = $mysqli->prepare("DELETE FROM pedidoRealizadoA WHERE id_pedido=?");
    $sentencia->bind_param("i",$id_pedido_indicado);
    $sentencia->execute();
}

function obtenerInfomacionPedidosRealizados(){
    $db = Database::getInstancia();
    $mysqli = $db->getConexion();

    $peticion = $mysqli->query("SELECT * FROM Proveedor NATURAL JOIN PedidoRealizadoA NATURAL JOIN incluye NATURAL JOIN recursoDeFabricacion");
    $var = array();
    $i=0;
    while($fila = $peticion->fetch_assoc()){
        $var[$i] = $fila;
        $i++;
    }

    return $var;
}

function obtenerPedidosRealizados(){
    $db = Database::getInstancia();
    $mysqli = $db->getConexion();

    $peticion = $mysqli->query("SELECT * FROM PedidoRealizadoA;");
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

function hacerPedido($valores){
    $db = Database::getInstancia();
    $mysqli = $db->getConexion();

    // obtener NIF del proveedor
    $peticion = $mysqli->query("SELECT NIF FROM Proveedor WHERE nombre='$valores->proveedor';");
    $proveedor = array();
    $i = 0;
    while($fila = $peticion->fetch_assoc()){
        $proveedor[$i] = $fila;
        $i++;
    }

    $nif_proveedor = $proveedor[0]['NIF'];
    $coste = 100;
    $input_date=$valores->fecha;
    $date=date("Y-m-d",strtotime($input_date));

    // añadir pedido a tabla de pedidos realizados
    $peticion = $mysqli->query("INSERT INTO PedidoRealizadoA (NIF,coste,fechaEntrega) VALUES($nif_proveedor,$coste,STR_TO_DATE('2020-01-24','%Y-%m-%d'));");

    // obtener el id del pedido añadido
    $peticion = $mysqli->query("SELECT max(id_pedido) FROM PedidoRealizadoA;");
    $producto = array();
    $i = 0;
    while($fila = $peticion->fetch_assoc()){
        $producto[$i] = $fila;
        $i++;
    }

    // por cada producto en el pedido, insertar datos en incluye
    foreach ($valores->productos as $clave => $valor) {
        $sentencia2 = $mysqli->prepare("INSERT INTO incluye (id_pedido,id_producto,cantidad) VALUES(?,?,?)");
        $sentencia2->bind_param("iii",$producto[0]['max(id_pedido)'],$clave,$valor);
        $sentencia2->execute();
    }
}