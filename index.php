<?php 
// muestra todos los errores generados por PHP en el navegador
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'controller/principal.php';
require_once 'models/productos.php';

// Recibe la URI de htaccess en formato "limpio"
$uri = $_SERVER['REQUEST_URI'];

if($uri == "/"){
    $array_uri = array(
        0    => "",
        1  => "principal"
    );
}
else{
    // Separar URI utilizando como delimitador "/" y guardar cada string en un array
    $array_uri = explode("/",$uri);
}

switch($_SERVER['REQUEST_METHOD']){
    //------------------------------------  GET  ------------------------------------------
    case 'GET':
        switch($array_uri[1]){
            case 'principal':
                renderizarPrincipal();
            break;

            case 'add_producto':
                renderizarAddProducto();
            break;

            case 'gestion_productos':
                renderizarGestionProductos();
                break;

            case 'gestion_pedidos':
                renderizarGestionPedidos();
                break;

            case 'gestion_productos_almacen':
                renderizarGestionProductosAlmacen();
                break;
    
            case 'proveedores_pedidos':
                renderizarProveedoresPedidos();
                break;
            
            case 'produccion_almacenaje':
                renderizarProduccionAlmacenaje();
                break;


            case 'hacer_pedido':
                renderizarHacerPedido();
                break;

            case 'consultar_pedido':
                $id_pedido = $array_uri[2];
                pedirConsultarPedido($id_pedido);
                break;

            case "favicon.ico":
                echo "imgs/favicon.png";
                break;
            default:
                http_response_code(404);
                break;
        }
    //------------------------------------------------------------------------------------
    
    //------------------------------------  POST  ----------------------------------------
    case 'POST':
        switch($array_uri[1]){
            case 'pedido':
                pedirHacerPedido();
                break;
            
            case 'producto':
                pedirAddProducto();
            break;
        }
        break;
    //------------------------------------------------------------------------------------

    //------------------------------------  PUT   ----------------------------------------
    case 'PUT':
        switch($array_uri[1]){
            case 'registrar':
                $id_pedido = $array_uri[2];
                pedirRegistrarPedido($id_pedido);
                break;

            case 'almacen':
                $seccion = $array_uri[2];
                $estanteria = $array_uri[3];
                $cantidad = $array_uri[4];
                $id_producto = $array_uri[5];
                pedirRetirarAlmacen($seccion,$estanteria,$cantidad,$id_producto);
            break;
        }
        break;
    //------------------------------------------------------------------------------------

    //------------------------------------  DELETE  --------------------------------------
    case 'DELETE':
        switch($array_uri[1]){
            case 'pedido':
                $id_pedido = $array_uri[2];
                pedirAnularPedido($id_pedido);
            break;

            case 'producto':
                $id_producto = $array_uri[2];
                pedirEliminarProducto($id_producto);
            break;
        }
        break;
    //------------------------------------------------------------------------------------
} // END switch($_SERVER['REQUEST_METHOD'])