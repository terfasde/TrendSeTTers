<?php
// Escribir la URL en el cliente API (Insomnia, Postman, etc.)
// localhost/api/Controles/comentarioController.php
require('../Model/ComentarioModelo.php');
$ComentarioObjeto = new ComentarioModelo();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Obtener los comentarios de un post
        $id_post = $_GET['id_post'] ?? null;
        if ($id_post) {
            $comentarios = $ComentarioObjeto->obtenerComentariosPorPost($id_post);
            echo json_encode($comentarios);
        } else {
            echo json_encode([]);
        }
        break;

    case 'POST':
         // Obtener los datos de la solicitud
         $data = json_decode(file_get_contents("php://input"), true);

         if (isset($data['comentario']) && isset($data['id_post']) && isset($data['id_usuario'])) {
             $contenido_comentario = $data['comentario'];
             $fecha_comentario = $data['fecha_comentario'];
             $id_post = $data['id_post'];
             $id_usuario = $data['id_usuario'];
 
             // Llamar al método para guardar el comentario
             $resultado = $ComentarioObjeto->guardarComentario($id_post, $id_usuario, $contenido_comentario, $fecha_comentario);
 
             if ($resultado) {
                 // Respuesta exitosa
                 echo json_encode(['success' => true]);
             } else {
                 // Error al guardar el comentario
                 echo json_encode(['success' => false]);
             }
         }
         break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id_comentario']) && isset($data['contenido_comentario']) && isset($data['fecha_comentario']) && isset($data['id_post'])) {
            $respuesta = $ComentarioObjeto->update_Comentario($data['id_comentario'], $data['contenido_comentario'], $data['fecha_comentario'], $data['id_post']);
            echo json_encode($respuesta);
        } else {
            echo json_encode(['error' => 'Faltan datos para actualizar el comentario.']);
        }
        break;

    case 'DELETE':     
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id_comentario'])) {
            $respuesta = $ComentarioObjeto->delete_Comentario($data['id_comentario']);
            echo json_encode($respuesta);
        } else {
            echo json_encode(['error' => 'ID de comentario no proporcionado.']);
        }
        break;
}

?>
