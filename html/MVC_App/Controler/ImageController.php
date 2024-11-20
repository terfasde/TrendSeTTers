<?php
date_default_timezone_set('America/Montevideo');

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

    if (isset($_POST['contenido']) && !empty($_POST['contenido'])) {
        $content = htmlspecialchars($_POST['contenido']);
        $id_usuario = isset($_SESSION['id_usuario']) ? intval($_SESSION['id_usuario']) : 0;
        $fecha_post = date('Y-m-d H:i:s');

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $directorio = __DIR__ . "/../Public/uploads/";
            $imagen = $_FILES['image']['name'];
            $archivo_temporal = $_FILES['image']['tmp_name'];

            $nombre_imagen = uniqid() . "-" . basename($imagen);
            $ruta_imagen = $directorio . $nombre_imagen;

            if (move_uploaded_file($archivo_temporal, $ruta_imagen)) {
                $conexion = new mysqli('localhost', 'root', 'root', 'social_network');

                if ($conexion->connect_error) {
                    die("Error de conexión: " . $conexion->connect_error);
                }

                $stmt = $conexion->prepare("INSERT INTO post (id_usuario, contenido, image, fecha_post) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $id_usuario, $content, $nombre_imagen, $fecha_post);

                if ($stmt->execute()) {
                    header("Location: /../Public/index.php?status=success");
                    exit;
                } else {
                    echo "Error al guardar la publicación.";
                }

                $stmt->close();
                $conexion->close();
            } else {
                echo "Error al subir la imagen.";
            }
        } else {
            $conexion = new mysqli('localhost', 'root', 'root', 'social_network');

            if ($conexion->connect_error) {
                die("Error de conexión: " . $conexion->connect_error);
            }

            $stmt = $conexion->prepare("INSERT INTO post (id_usuario, contenido, fecha_post) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $id_usuario, $content, $fecha_post);

            if ($stmt->execute()) {
                header("Location: /../Public/index.php?status=success");
                exit;
            } else {
                echo "Error al guardar la publicación.";
            }

            $stmt->close();
            $conexion->close();
        }
    } else {
        echo "El contenido de la publicación no puede estar vacío.";
    }
} else {
    
}
?>