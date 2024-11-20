<?php

session_start();  
require('../Model/MessageModel.php');

if (!isset($_SESSION['id_usuario'])) {
    echo "Error: Usuario no autenticado.";
    exit();
}

$idActual = $_SESSION['id_usuario'];
$usuarios = obtenerUsuarios($idActual);

// Obtener mensajes intercambiados
if (isset($_GET['id_destinatario'])) {
    $idDestinatario = $_GET['id_destinatario'];
    $mensajes = obtenerMensajesIntercambiados($idActual, $idDestinatario);
}

// Función para obtener la lista de usuarios excepto el propio usuario
function obtenerUsuarios($idActual) {
    $conexion = DatabaseConnection::getInstance();
    $query = "SELECT id_usuario, nombre_usuario FROM usuario WHERE id_usuario != ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $idActual);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);  // Devuelve todos los usuarios excepto el actual
}

// Función para obtener los mensajes entre dos usuarios
function obtenerMensajesEntreUsuarios($idUsuario1, $idUsuario2) {
    $conexion = DatabaseConnection::getInstance();
    $sql = "SELECT m.contenido_mensaje, m.fecha_mensaje, u.nombre_usuario AS nombre_remitente
            FROM mensaje m
            JOIN usuario u ON m.id_usuario = u.id_usuario
            WHERE (m.id_usuario = ? AND m.id_destinatario = ?)
            OR (m.id_usuario = ? AND m.id_destinatario = ?)
            ORDER BY m.fecha_mensaje ASC";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("iiii", $idUsuario1, $idUsuario2, $idUsuario2, $idUsuario1);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Lógica para enviar el mensaje
if (isset($_POST['enviar_mensaje'])) {
    $idRemitente = $_SESSION['id_usuario'];  // Verifica que la sesión tiene el ID de usuario
    $idDestinatario = $_POST['id_destinatario'];
    $contenidoMensaje = $_POST['contenido_mensaje'];

    if ($idRemitente && $idDestinatario && $contenidoMensaje) {
        enviarMensaje($idRemitente, $idDestinatario, $contenidoMensaje);  // Llama al modelo para guardar el mensaje
        header("Location: ../Views/mensajes.php?id_destinatario=$idDestinatario");  // Redirige a la vista mensajes
        exit();
    } else {
        echo "Error: Todos los campos son obligatorios.";
        echo "ID Remitente: $idRemitente<br>";
echo "ID Destinatario: $idDestinatario<br>";
echo "Mensaje: $contenidoMensaje<br>";

    }
}