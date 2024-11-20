<?php
date_default_timezone_set('America/Montevideo');

require_once(__DIR__ . '/../Model/PostModelo.php');

$PostObjeto = new PostModelo();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $respuesta = $PostObjeto->obtener_posts();
        echo json_encode($respuesta);
        break;

    case 'POST':
        // Subida de imagen y contenido del post
        $contenido = $_POST['content'];
        $id_usuario = $_SESSION['id_usuario'];
        $fecha_post = date('Y-m-d H:i:s');

        // Verificar si se subió una imagen
        $rutaImagen = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . '/../uploads/';  // Directorio donde se guardarán las imágenes
        
            // Verificar si el directorio existe y tiene permisos correctos
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);  // Crear el directorio si no existe
            }
            
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $nuevoNombre = uniqid() . '.' . $extension;  // Generar un nombre único para la imagen
            $ruta = $upload_dir . $nuevoNombre;
            
            // Mover la imagen al directorio de subida
            if (move_uploaded_file($_FILES['image']['tmp_name'], $ruta)) {
                $rutaImagen = 'uploads/' . $nuevoNombre;  // Guardar la ruta relativa para la base de datos
            } else {
                echo json_encode(["error" => "Error al mover el archivo al directorio de uploads."]);
                exit;
            }
        } else {
            $error_message = $_FILES['image']['error'] !== UPLOAD_ERR_OK ? $_FILES['image']['error'] : "No se subió ninguna imagen.";
            echo json_encode(["error" => "Error al subir la imagen: " . $error_message]);
            exit;
        }

        // Insertar el post en la base de datos
        $respuesta = $PostObjeto->insert_Post($contenido, $id_usuario, $fecha_post, $rutaImagen);
        if (!$respuesta) {
            echo json_encode(["error" => "Error al insertar el post."]);
            exit;
        }

        $id_post = $PostObjeto->get_last_insert_id();

        // Insertar la ruta de la imagen en la base de datos
        if ($rutaImagen) {
            $respuestaImagen = $PostObjeto->insert_image($id_post, $rutaImagen);
            if (!$respuestaImagen) {
                echo json_encode(["error" => "Error al insertar la imagen en la base de datos."]);
                exit;
            }
        }

        // Redirigir de nuevo al index después de postear
        header("Location: ../Public/index.php");
        exit;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario'])) {
            $id_post = $_POST['id_post'];
            $id_usuario = $_SESSION['id_usuario'];
            $comentario = $_POST['comentario'];
            $fecha_comentario = date('Y-m-d H:i:s');
            
            $PostObjeto->insert_comment($id_post, $id_usuario, $comentario, $fecha_comentario);
            echo json_encode(['status' => 'success']);
            exit();
        }
        

    case 'PUT':
        $_PUT = json_decode(file_get_contents('php://input'), true);
        $respuesta = $PostObjeto->update_Post($_PUT['id_post'], $_PUT['contenido'], $_PUT['id_usuario']);
        echo json_encode($respuesta);
        break;
    
    case 'DELETE':
        $_DELETE = json_decode(file_get_contents('php://input'), true);
        $respuesta = $PostObjeto->delete_Post($_DELETE['id_post']);
        echo json_encode($respuesta);
        break;
}
?>