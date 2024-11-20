<?php
require('../Controler/config/db.php');

class ComentarioModelo {
    private $conexion;

    public function __construct() {
        $this->conexion = new mysqli('localhost', 'usuario', 'password', 'basedatos');
    }

    public function delete_Comentario($id_comentario) {     
        if ($this->conexion->query("DELETE FROM comentario WHERE id_comentario = $id_comentario")) {
            $resultado = "HTTP/1.1 201 se borraron datos";
        } else {
            $resultado = 'HTTP/1.1 404 no se borraron datos';
        }
        return $resultado;
    }

    public function update_Comentario($id_comentario, $contenido_comentario, $fecha_comentario, $id_post) {
        $stmt = $this->conexion->prepare("UPDATE comentario SET contenido_comentario = ?, fecha_comentario = ?, id_post = ? WHERE id_comentario = ?");
        $stmt->bind_param("ssii", $contenido_comentario, $fecha_comentario, $id_post, $id_comentario);
        if ($stmt->execute()) {
            return "HTTP/1.1 201 se modificaron datos";
        } else {
            return 'HTTP/1.1 404 no se modificaron datos';
        }
    }

    public function guardarComentario($id_post, $id_usuario, $contenido, $fecha) {
        $stmt = $this->conexion->prepare("INSERT INTO comentario (id_post, id_usuario, comentario, fecha_comentario) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $id_post, $id_usuario, $contenido, $fecha);
        return $stmt->execute();
    }
    
    public function obtenerComentariosPorPost($id_post) {
        $stmt = $this->conexion->prepare("SELECT * FROM comentario WHERE id_post = ?");
        $stmt->bind_param("i", $id_post);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
}
?>
