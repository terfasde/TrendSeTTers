<?php
require_once(__DIR__ . '/../Controler/config/db.php');

class PerfilModelo extends DatabaseConnection  {
    private $conexion;

    // Constructor para establecer la conexión a la base de datos
    public function __construct() {
        // Configura tus parámetros de conexión
        $host = 'localhost';
        $usuario = 'root'; 
        $password = 'root'; 
        $base_de_datos = 'social_network'; 

        // Crear la conexión
        $this->conexion = new mysqli($host, $usuario, $password, $base_de_datos);

        // Verificar la conexión
        if ($this->conexion->connect_error) {
            die("Error en la conexión: " . $this->conexion->connect_error);
        }
    }

    public function delete_Perfil($id_perfil) {     
        if ($this->conexion->query("DELETE FROM perfil WHERE id_perfil = $id_perfil")) {
            $resultado = "HTTP/1.1 201 se borraron datos";
        } else {
            $resultado = 'HTTP/1.1 404 no se borraron datos';
        }
        return $resultado;
    }

    public function update_Perfil($id_perfil, $id_usuario, $nombre_completo, $descripcion, $foto_perfil) {     
        $stmt = $this->conexion->prepare("UPDATE perfil SET nombre_completo = ?, descripcion = ?, foto_perfil = ? WHERE id_perfil = ?");
        $stmt->bind_param("sssi", $nombre_completo, $descripcion, $foto_perfil, $id_perfil);
        if ($stmt->execute()) {
            $resultado = "HTTP/1.1 201 se modificaron datos";
        } else {
            $resultado = 'HTTP/1.1 404 no se modificaron datos';
        }
        return $resultado;
    }

    public function insert_Perfil($id_usuario, $nombre_completo, $descripcion, $foto_perfil) {     
        $stmt = $this->conexion->prepare("INSERT INTO perfil (id_usuario, nombre_completo, descripcion, foto_perfil) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $id_usuario, $nombre_completo, $descripcion, $foto_perfil);
        if ($stmt->execute()) {
            $resultado = "HTTP/1.1 201 se guardaron datos";
        } else {
            $resultado = 'HTTP/1.1 404 no se guardaron datos';
        }
        return $resultado;
    }

    public function obtenerPerfil($id_usuario) {
        $stmt = $this->conexion->prepare("SELECT nombre_completo, descripcion, foto_perfil FROM perfil WHERE id_usuario = ?");
        $stmt->bind_param('i', $id_usuario); // Asegúrate de que el tipo sea correcto
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc(); // Devuelve el primer resultado
        } else {
            return null; // No se encontró el perfil
        }
    }
    
}
?>
