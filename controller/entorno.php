<?php
require_once 'vendor/autoload.php';

class Entorno{
    private $twig;
    private $loader;
    private static $instancia;

    // constructor
    private function __construct(){
        $this->loader = new \Twig\Loader\FilesystemLoader('templates');
        $this->twig   = new \Twig\Environment($this->loader,[
            'debug' => 'true'
        ]);
        $this->twig->addExtension(new \Twig\Extension\DebugExtension());            
    }

    // no permitir la copia del objeto singleton de la clase
    private function __clone(){}

    // devuelve la instancia
    // si no existe, la crea primero
    public static function getInstancia(){
        if(!self::$instancia)
            self::$instancia = new self();

        return self::$instancia;
    }

    // renderiza un html por completo
    public function renderizar($html,$variables){
        return $this->twig->render($html,$variables);
    }

    // renderiza una sección de un html
    public function renderizarBloque($html,$bloque,$variables){
        $this->template = $this->twig->load($html);
        return $this->template->renderBlock($bloque,$variables);
    }
}
?>
