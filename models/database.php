<?php
/*
    Esta clase representa la conexión a la base de datos.
    Se utiliza el patrón singleton en su implementación.
    Utilizar el método de clase getInstancia() para obtener 
    el objeto de la clase.
*/
class Database{
    private $conexion;
    private static $instancia;

    // constructor
    // genera la conexión a la base de datos, solo se llama a través de getInstancia()
    private function __construct(){
        $config = parse_ini_file('config.ini');
        $this->conexion = new mysqli($config['servidor'],$config['usuario'],$config['password'],$config['nombre_bd']);
        $this->conexion->set_charset("utf8"); // para mostrar los caracteres latinos correctamente

        if(mysqli_connect_error())
			trigger_error("Falló la conexion a la base de datos de MySQL: " . mysql_connect_error(),E_USER_ERROR);
    }

    // no permitir la copia del objeto singleton de la clase
    private function __clone(){}

    // devuelve la instancia de la bd
    // si no existe, la crea primero
    public static function getInstancia(){
        if(!self::$instancia)
            self::$instancia = new self();

        return self::$instancia;
    }

    // devuelve la variable que guarda la conexión a la base de datos
    public function getConexion(){
        return $this->conexion;
    }
}

?>
