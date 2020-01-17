<?php 
require_once 'entorno.php';

function renderizarPrincipal(){
    $entorno = Entorno::getInstancia();
    $variables = [];

    echo $entorno->renderizar("principal.html.twig",$variables);
}

function renderizarGestionAlmacenes(){
    $entorno = Entorno::getInstancia();
    $variables = [];
    
    echo $entorno->renderizar("gestion_almacen.html",$variables);
}

function renderizarGestionProductos(){
    $entorno = Entorno::getInstancia();
    $productos = array();

    $productos[0] = [
        "id" => 1,
        "nombre" => "tablero de madera de pino",
        "cantidad" => 7
    ];

    $productos[1] = [
        "id" => 2,
        "nombre" => "tablero de madera de roble",
        "cantidad" => 14
    ];

    $productos[2] = [
        "id" => 3,
        "nombre" => "placa de aluminio",
        "cantidad" => 23
    ];

    $productos[3] = [
        "id" => 4,
        "nombre" => "plancha de PVC",
        "cantidad" => 165
    ];

    $productos[4] = [
        "id" => 5,
        "nombre" => "placa de acero",
        "cantidad" => 65
    ];

    $variables = [
        "productos" => $productos,
    ];

    echo $entorno->renderizar("gestion_productos.html.twig",$variables);
}