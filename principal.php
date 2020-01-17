<?php 
require_once 'entorno.php';

function renderizarPrincipal(){
    $entorno = Entorno::getInstancia();
    $variables = [];

    echo $entorno->renderizar("principal.html.twig",$variables);
}