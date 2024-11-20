<?php
require_once '../Controller/PostController.php';
require_once '../Model/PostModelo.php';
require_once '../config/db.php'; // Verifica que la ruta sea correcta

$conexion = (new DatabaseConnection())->getConexion();
$PostModelo = new PostModelo($conexion);
$PostController = new PostController($PostModelo);

// Obtener datos de la solicitud AJAX
$data = json_decode(file_get_contents('php://input'), true);
$id_post = $data['id_post'] ?? null;
$id_usuario = $data['id_usuario'] ?? null;

if ($id_post && $id_usuario) {
    $result = $PostController->upvotePost($id_usuario, $id_post);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Upvote registered']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to register upvote']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
}
?>
