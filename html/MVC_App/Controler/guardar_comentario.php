<?php
// guardar_comentario.php

require_once (__DIR__ . '/config/db.php'); // Incluye tu archivo de conexión a la base de datos

// Crear una conexión con la base de datos
$conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comentario = $_POST['comentario'];
    $id_post = $_POST['post_id'];
    $id_usuario = 1; // Aquí debes usar el ID del usuario autenticado (en este ejemplo es 1)
    $fecha_comentario = date('Y-m-d H:i:s'); // Fecha actual

    // Verificar que el comentario no esté vacío
    if (!empty($comentario) && !empty($id_post)) {
        // Guardar el comentario en la base de datos
        $stmt = $conexion->prepare("INSERT INTO comentario (id_post, id_usuario, comentario, fecha_comentario) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('iiss', $id_post, $id_usuario, $comentario, $fecha_comentario);

        if ($stmt->execute()) {
            // Comentario guardado correctamente
            $response = [
                'success' => true,
                'comentario' => $comentario,
                'fecha_comentario' => $fecha_comentario
            ];
        } else {
            // Error al guardar el comentario
            $response = ['success' => false, 'message' => 'Error al guardar el comentario.'];
        }

        $stmt->close();
    } else {
        $response = ['success' => false, 'message' => 'El comentario no puede estar vacío.'];
    }

    echo json_encode($response);
}

$conexion->close();
?>
