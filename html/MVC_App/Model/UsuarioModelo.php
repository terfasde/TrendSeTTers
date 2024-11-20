<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../Controler/config/db.php');

class UsuarioModelo extends DatabaseConnection  {
    
    private $conexion;

    // Constructor para establecer la conexión a la base de datos
    public function __construct() {
        // Configura tus parámetros de conexión (ajusta según tu entorno)
        $host = 'db-master';
        $usuario = 'francisco'; 
        $password = 12345; 
        $base_de_datos = 'social_network'; 

        // Crear la conexión
        $this->conexion = new mysqli($host, $usuario, $password, $base_de_datos);

        // Verificar la conexión
        if ($this->conexion->connect_error) {
            die("Error en la conexión: " . $this->conexion->connect_error);
        }
    }
    
    public $usuarios = [];

    public function delete_Usuario($id_usuario) {     
        if ($this->conexion->query("DELETE FROM usuario WHERE id_usuario = $id_usuario")) {
            $resultado = "HTTP/1.1 201 se borraron datos";
        } else {
            $resultado = 'HTTP/1.1 404 no se borraron datos';
        }
        return $resultado;
    }

    public function update_Usuario($id_usuario, $nombre_usuario, $correo, $contrasena, $fecha_registro) {     
        if ($this->conexion->query("UPDATE usuario SET nombre_usuario = '$nombre_usuario', correo = '$correo', contrasena = '$contrasena', fecha_registro = '$fecha_registro' WHERE id_usuario = $id_usuario")) {
            $resultado = "HTTP/1.1 201 se modificaron datos";
        } else {
            $resultado = 'HTTP/1.1 404 no se modificaron datos';
        }
        return $resultado;
    }

    public function insert_Usuario($nombre_usuario, $correo, $contrasena, $fecha_registro) {
        try {
                    // Establecer el modo de error de la conexión para usar excepciones
                    $this->conexion->set_charset("utf8mb4");
                    $this->conexion->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
                    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            
                    // Preparamos la consulta SQL
                    $stmt = $this->conexion->prepare("INSERT INTO usuario (nombre_usuario, correo, contrasena, fecha_registro) VALUES (?, ?, ?, ?)");
            
                    // Vinculamos los parámetros de la consulta
                    $stmt->bind_param('ssss', $nombre_usuario, $correo, $contrasena, $fecha_registro);
            
                    // Ejecutamos la consulta
                    $stmt->execute();
            
                    return true; // El registro fue exitoso
            
                } catch (mysqli_sql_exception $e) {
                    if ($e->getCode() == 1062) { // Código de error para duplicados
                        echo "Error: El nombre de usuario ya está registrado.";
                    } else {
                        echo "Error en la base de datos: " . $e->getMessage();
                    }
                    return false;
                }
            }

    public function __destruct() {
        $this->conexion->close();
    }
    
    public function obtener_Usuario() {
        $consulta = $this->conexion->query("SELECT * FROM usuario;");
        $filas = $consulta->fetch_all(MYSQLI_ASSOC);
        foreach ($filas as $usuario) {
            $this->usuarios[] = $usuario;
        }
        return $this->usuarios;
    }
    
    public function obtener_UsuarioPorNombre($nombre_usuario) {
        // Utilizar la conexión existente en lugar de crear una nueva
        $stmt = $this->conexion->prepare("SELECT * FROM usuario WHERE nombre_usuario = ?");
        $stmt->bind_param('s', $nombre_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc() ?: null; // Devuelve el usuario o null si no existe
    }
    
}
?>
