<?php 
require_once 'controller/entorno.php';
require_once 'models/productos.php';
require_once 'models/proveedores.php';

function renderizarPrincipal(){
    $entorno = Entorno::getInstancia();
    $variables = [];

    echo $entorno->renderizar("principal.html",$variables);
}

function renderizarGestionAlmacenes(){
    $entorno = Entorno::getInstancia();
    $variables = [];
    
    echo $entorno->renderizar("gestion_almacen.html.twig",$variables);
}

function renderizarGestionProductos(){
    $entorno = Entorno::getInstancia();
    $productos = obtenerProductos();

    $variables = [
        "productos" => $productos,
    ];

    echo $entorno->renderizar("gestion_productos.html.twig",$variables);
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
    $variables = [];
    
    echo $entorno->renderizar("gestion_pedidos.html.twig",$variables);
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
    
    echo $entorno->renderizar("hacerpedido.html.twig",$variables);
}