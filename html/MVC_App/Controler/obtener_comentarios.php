<?php
// obtener_comentarios.php

require_once (__DIR__ . '/config/db.php'); // Incluye tu archivo de conexión a la base de datos

// Crear una conexión con la base de datos
$conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$id_post = $_GET['post_id'] ?? null;

if ($id_post) {
    // Obtener los comentarios para el post correspondiente
    $stmt = $conexion->prepare("SELECT comentario, fecha_comentario FROM comentario WHERE id_post = ? ORDER BY fecha_comentario ASC");
    $stmt->bind_param('i', $id_post);
    $stmt->execute();
    $result = $stmt->get_result();
    $comentarios = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($comentarios);
    $stmt->close();
}

$conexion->close();
?>
