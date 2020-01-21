<?php 
// muestra todos los errores generados por PHP en el navegador
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'controller/entorno.php';
require_once 'models/productos.php';
require_once 'models/proveedores.php';
require_once 'models/pedidos.php';

function renderizarPrincipal(){
    $entorno = Entorno::getInstancia();
    $variables = [];

    echo $entorno->renderizar("principal.html",$variables);
}

function renderizarGestionAlmacenes(){
    $entorno = Entorno::getInstancia();
    $variables = [];
    
    echo $entorno->renderizar("gestion_almacen.html",$variables);
}

function renderizarGestionProductos(){
    $entorno = Entorno::getInstancia();
    $productos = obtenerProductosFabricados();

    $variables = [
        "productos" => $productos,
    ];

    echo $entorno->renderizar("gestion_productos.html",$variables);
}

function renderizarGestionProductosAlmacen(){
    $entorno = Entorno::getInstancia();
    $productos = obtenerProductos();

    $variables = [
        "productos" => $productos,
    ];

    echo $entorno->renderizar("gestion_productos_almacen.html",$variables);
}


function renderizarGestionPedidos(){
    $entorno = Entorno::getInstancia();
    $pedidos = obtenerPedidosRealizados();
    $variables = [
        "pedidos" => $pedidos
    ];

    echo $entorno->renderizar("gestion_pedidos.html",$variables);
}


function renderizarProveedoresPedidos(){
    $entorno = Entorno::getInstancia();
    $variables = [];
    
    echo $entorno->renderizar("proveedores_pedidos.html",$variables);
}

function renderizarProduccionAlmacenaje(){
    $entorno = Entorno::getInstancia();
    $variables = [];
    
    echo $entorno->renderizar("produccion_almacenaje.html",$variables);
}

function renderizarHacerPedido(){
    $entorno = Entorno::getInstancia();
    $productos = obtenerProductos();
    $proveedores = obtenerProveedores();

    $variables = [
        "productos" => $productos,
        "proveedores" => $proveedores
    ];
    
    echo $entorno->renderizar("hacerpedido.html",$variables);
}

function renderizarAddProducto(){
    $entorno = Entorno::getInstancia();
    $productos = obtenerProductos();

    $variables = [
        "productos" => $productos
    ];
    
    echo $entorno->renderizar("addproducto.html",$variables);
}

function pedirHacerPedido(){
    $datos = file_get_contents('php://input');
    $valores = json_decode($datos);

    hacerPedido($valores);
}

function pedirAnularPedido($id_pedido){
    anularPedido((int)$id_pedido);
}

function pedirRegistrarPedido($id_pedido){
    registrarLlegada($id_pedido);
}

function pedirConsultarPedido($id_pedido){
    $datos = consultarPedido($id_pedido);

    $entorno = Entorno::getInstancia();
    $variables = [
        "productos" => $datos
    ];

    echo $entorno->renderizarBloque("gestion_pedidos.html","consultar",$variables);
}

function pedirAddProducto(){
    $datos = file_get_contents('php://input');
    $valores = json_decode($datos);

    addProducto($valores->nombre, $valores->productos);
}

function pedirEliminarProducto($id_producto){
    eliminarProducto($id_producto);
}