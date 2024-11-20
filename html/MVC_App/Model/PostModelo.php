<?php
date_default_timezone_set('America/Montevideo');

require_once(__DIR__ . '/../Controler/config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'upvote') {
    $PostController = new PostController(new PostModelo());
    $PostController->upvotePost();
}

class PostModelo extends DatabaseConnection {

    private $conexion;

    public function __construct() {
        $host = 'localhost';
        $usuario = 'root'; 
        $password = 'root'; 
        $base_de_datos = 'social_network'; 

        $this->conexion = new mysqli($host, $usuario, $password, $base_de_datos);

        if ($this->conexion->connect_error) {
            die("Error en la conexión: " . $this->conexion->connect_error);
        }
    }

    public function obtener_posts() {
        $stmt = $this->conexion->prepare("SELECT p.*, u.nombre_usuario 
                                          FROM post p 
                                          LEFT JOIN usuario u ON p.id_usuario = u.id_usuario");
        if (!$stmt->execute()) {
            echo "Error en la consulta: " . $stmt->error;
            return [];
        }

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function insert_Post($contenido, $id_usuario, $fecha_post, $ruta_imagen = null) {
        $stmt = $this->conexion->prepare("INSERT INTO post (contenido, id_usuario, fecha_post, ruta_imagen) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('siss', $contenido, $id_usuario, $fecha_post, $ruta_imagen);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function get_last_insert_id() {
        return $this->conexion->insert_id;
    }


    public function delete_Post($id_post) {     
        if ($this->conexion->query("DELETE FROM post WHERE id_post = $id_post")) {
            $resultado = "HTTP/1.1 201 se borraron datos";
        } else {
            $resultado = 'HTTP/1.1 404 no se borraron datos';
        }
        return $resultado;
    }

    public function update_Post($id_post, $contenido, $id_usuario) {     
        if ($this->conexion->query("UPDATE post SET contenido = '$contenido', id_usuario = '$id_usuario' WHERE id_post = $id_post")) {
            $resultado = "HTTP/1.1 201 se modificaron datos";
        } else {
            $resultado = 'HTTP/1.1 404 no se modificaron datos';
        }
        return $resultado;
    }

    public function insert_comment($id_post, $id_usuario, $comentario, $fecha_comentario) {
        $sql = "INSERT INTO comentario (id_post, id_usuario, comentario, fecha_comentario) VALUES (?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id_post, $id_usuario, $comentario, $fecha_comentario]);
    }
    
    public function verificarUpvote($id_usuario, $id_post) {
        $query = "SELECT COUNT(*) FROM upvotes WHERE id_usuario = ? AND id_post = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param('ii', $id_usuario, $id_post);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        return $count > 0;
    }

    public function upvotePost($id_usuario, $id_post) {
        return $this->conexion->addUpvote($id_usuario, $id_post);
    }

    public function addUpvote($id_usuario, $id_post) {
        try {
            $sql = "INSERT INTO upvote (id_usuario, id_post) VALUES (:id_usuario, :id_post)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Si ocurre una violación de la clave única (intento de duplicado), la manejamos
            if ($e->getCode() == 23000) {
                return false; // Ya existe un upvote
            }
            throw $e;
        }
    }
}

?>
