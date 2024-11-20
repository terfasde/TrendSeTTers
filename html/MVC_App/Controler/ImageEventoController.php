<?php
date_default_timezone_set('America/Montevideo');

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['titulo_evento']) && !empty($_POST['titulo_evento']) && 
        isset($_POST['descripcion_evento']) && !empty($_POST['descripcion_evento']) && 
        isset($_POST['fecha_evento']) && !empty($_POST['fecha_evento'])) {

        $titulo_evento = htmlspecialchars($_POST['titulo_evento']);
        $descripcion_evento = htmlspecialchars($_POST['descripcion_evento']);
        $fecha_evento = $_POST['fecha_evento'];
        $id_usuario = intval($_SESSION['id_usuario']); 

        // Procesar la imagen si fue seleccionada
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $directorio = __DIR__ . "/../Public/uploads/";
            $nombre_imagen = uniqid() . "-" . basename($_FILES['image']['name']);
            $ruta_imagen = $directorio . $nombre_imagen;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $ruta_imagen)) {
                // Conectar a la base de datos
                $conexion = new mysqli('localhost', 'root', 'root', 'social_network');

                if ($conexion->connect_error) {
                    die("Error de conexión: " . $conexion->connect_error);
                }

                $stmt = $conexion->prepare("INSERT INTO evento (id_usuario, titulo_evento, descripcion_evento, fecha_evento, image) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("issss", $id_usuario, $titulo_evento, $descripcion_evento, $fecha_evento, $nombre_imagen);

                if ($stmt->execute()) {
                    header("Location: ../Public/eventos.php?status=success");
                    exit;
                } else {
                    echo "Error al guardar el evento: " . $stmt->error;
                }

                $stmt->close();
                $conexion->close();
            } else {
                echo "Error al subir la imagen.";
            }
        } else {
            // Si no se selecciona imagen
            $conexion = new mysqli('localhost', 'root', 'root', 'social_network');

            if ($conexion->connect_error) {
                die("Error de conexión: " . $conexion->connect_error);
            }

            $stmt = $conexion->prepare("INSERT INTO evento (id_usuario, titulo_evento, descripcion_evento, fecha_evento) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $id_usuario, $titulo_evento, $descripcion_evento, $fecha_evento);

            if ($stmt->execute()) {
                header("Location: ../Public/eventos.php?status=success");
                exit;
            } else {
                echo "Error al guardar el evento: " . $stmt->error;
            }

            $stmt->close();
            $conexion->close();
        }
    } else {
        echo "Todos los campos del evento deben estar completos.";
    }
}
?>
