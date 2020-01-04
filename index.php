<?php 
require_once 'principal.php';
require_once 'eventos.php';
require_once 'contacto.php';
require_once 'inicio_sesion.php';
require_once 'panel_control.php';
require_once 'perfil.php';
require_once 'usuarios.php';
require_once 'modelo/comentarios.php';
require_once 'modelo/eventos.php';
require_once 'subir_imagen.php';

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
                if(array_key_exists(2,$array_uri) && $array_uri[2] == "filtro"){
                    if(array_key_exists(3,$array_uri)){
                        aplicarFiltro($array_uri[3]);
                    }
                    else
                        http_response_code(404);
                }
                else
                    renderizarPrincipal();
                break;

            case 'evento':
                if(array_key_exists(2,$array_uri)){
                    if(array_key_exists(3,$array_uri) && $array_uri[3] == 'imprimir')
                        renderizarEvento((int)$array_uri[2],TRUE);
                    else
                        renderizarEvento((int)$array_uri[2],FALSE);
                }
                else
                    http_response_code(404);
                break;

            case 'contacto':
                renderizarContacto();
                break;
            
            case 'iniciar-sesion':
                renderizarInicioSesion();
                break;

            case 'panel-control':
                renderizarPanelControl();
                break;
            
            case 'perfil':
                renderizarPerfil();
                break;

            case "palabras":
                echo palabras();
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
            case 'subir-imagen':
                $dir = subirImagen($array_uri[2]);
                echo $dir;
                break;
            case 'usuarios':
                if(sizeof($array_uri) == 4){
                    if($array_uri[3] == 'conectar')
                        pedirIniciarSesion();
                    else if($array_uri[3] == 'desconectar')
                        pedirDesconectar();
                    else
                        http_response_code(404);
                }
                else if(sizeof($array_uri) == 2)
                    pedirRegistrarUsuario();
                else
                    http_response_code(404);
                break;

            case 'eventos':
                if(array_key_exists(4,$array_uri) && $array_uri[3] == 'etiquetas'){
                    $id_evento = $array_uri[2];
                    $etiqueta  = $array_uri[4];
                    var_dump($etiqueta);
                    addEtiqueta($id_evento,$etiqueta);
                }
                else if(array_key_exists(3,$array_uri)){
                    if($array_uri[3] == 'comentarios'){
                        $id_evento = $array_uri[2];
                        $datos = file_get_contents('php://input');
                        enviarComentario($id_evento,$datos);
                    }
                    else if($array_uri[3] == 'imagenes'){
                        $id_evento = $array_uri[2];
                        // enviar imagenes
                    }
                }
                else if(sizeof($array_uri) == 2){
                    $datos = file_get_contents('php://input');
                    pedirAddEvento($datos);
                }
                else
                    http_response_code(404);
                break;
            case 'consulta':
                $datos = file_get_contents('php://input');
                $valores = json_decode($datos);
                $respuesta = buscarEventos($valores->consulta,$valores->tipo);
                $eventos = json_encode($respuesta);
                echo $eventos;
                break;
        }
        break;
    //------------------------------------------------------------------------------------

    //------------------------------------  PUT   ----------------------------------------
    case 'PUT':
        switch($array_uri[1]){
            case 'evento':
                $id_evento = $array_uri[2];

                if(sizeof($array_uri) == 5 && $array_uri[3] == 'comentarios'){
                    $id_comentario = $array_uri[4];
                    $datos = file_get_contents('php://input');
                    $valores = json_decode($datos);
                    editarComentario($id_evento,$id_comentario,$valores->mensaje,$valores->moderador);
                }
                else if(array_key_exists(3,$array_uri) && $array_uri[3] == 'publicado'){
                    $datos = file_get_contents('php://input');
                    pedirEditarPublicacion($id_evento,$datos);
                }
                else if(sizeof($array_uri) == 3){
                    $datos = file_get_contents('php://input');
                    $valores = json_decode($datos);
                    $fecha = str_replace('/', '-', $valores->fecha);
                    $fecha = date('Y-m-d', strtotime($fecha));
                    editarEvento($id_evento,$valores->titulo,$valores->organizador,$fecha,$valores->texto);

                    if(isset($valores->dir_img1)){
                        cambiarImagen($id_evento,$valores->id_imagen1,$valores->dir_img1,$valores->titulo_imagen1,$valores->creditos_imagen1);
                    }
                    else
                        cambiarPieImagen($id_evento,$valores->id_imagen1,$valores->titulo_imagen1,$valores->creditos_imagen1);

                    if(isset($valores->dir_img2)){
                        cambiarImagen($id_evento,$valores->id_imagen2,$valores->dir_img2,$valores->titulo_imagen2,$valores->creditos_imagen2);
                    }
                    else
                        cambiarPieImagen($id_evento,$valores->id_imagen2,$valores->titulo_imagen2,$valores->creditos_imagen2);
                }
                else
                    http_response_code(404);
                break;

            case 'usuarios':
                if(sizeof($array_uri) == 4){
                    $id_usuario = $array_uri[2];

                    if($array_uri[3] == 'nombre'){
                        $datos = file_get_contents('php://input');
                        pedirEditarNombre($id_usuario,$datos);
                    }
                    else if($array_uri[3] == 'correo'){
                        $datos = file_get_contents('php://input');
                        pedirEditarEmail($id_usuario,$datos);
                    }
                    else if($array_uri[3] == 'passwd'){
                        $datos = file_get_contents('php://input');
                        pedirEditarPasswd($id_usuario,$datos);
                    }
                    else if($array_uri[3] == 'rol'){
                        $datos = file_get_contents('php://input');
                        pedirEditarRol($id_usuario,$datos);
                    }
                    else
                        http_response_code(404);
                }
                break;
        }
        break;
    //------------------------------------------------------------------------------------

    //------------------------------------  DELETE  --------------------------------------
    case 'DELETE':
        if($array_uri[1] == 'eventos'){
            $id_evento = $array_uri[2];

            if(sizeof($array_uri) == 3){
                eliminarEvento($id_evento);
            }
            else if(sizeof($array_uri) == 5){
                if($array_uri[3] == 'etiquetas'){
                    $etiqueta = $array_uri[4];
                    eliminarEtiqueta($id_evento,$etiqueta);
                }
                else if($array_uri[3] == 'imagenes'){
                    $id_imagen = $array_uri[4];
                    // eliminar imagen
                }
                else if($array_uri[3] == 'comentarios'){
                    echo "eliminar comentario";
                    $id_comentario = $array_uri[4];
                    pedirEliminarComentario($id_evento,$id_comentario);
                }
            }
            else
                http_response_code(404);
        }
        else
            http_response_code(404);
        break;
    //------------------------------------------------------------------------------------
} // END switch($_SERVER['REQUEST_METHOD'])