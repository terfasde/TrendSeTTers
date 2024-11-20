<?php
require_once(__DIR__ . '/../Controler/config/db.php');

// Función para enviar el mensaje
function enviarMensaje($idRemitente, $idDestinatario, $contenidoMensaje) {
    $conn = DatabaseConnection::getInstance();
    $sql = "INSERT INTO mensaje (id_usuario, id_destinatario, contenido_mensaje, fecha_mensaje) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $idRemitente, $idDestinatario, $contenidoMensaje);

    if ($stmt->execute()) {
        return true;
    } else {
        echo "Error al enviar el mensaje: " . $stmt->error;
        return false;
    }
}


// Función para obtener mensajes intercambiados
function obtenerMensajesIntercambiados($idRemitente, $idDestinatario) {
    $conn = DatabaseConnection::getInstance();
    $sql = "SELECT m.contenido_mensaje, m.fecha_mensaje, 
                   (CASE WHEN m.id_usuario = ? THEN 'Tú' ELSE u.nombre_usuario END) AS nombre_remitente
            FROM mensaje m
            JOIN usuario u ON u.id_usuario = m.id_usuario
            WHERE (m.id_usuario = ? AND m.id_destinatario = ?)
               OR (m.id_usuario = ? AND m.id_destinatario = ?)
            ORDER BY m.fecha_mensaje ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiii", $idRemitente, $idRemitente, $idDestinatario, $idDestinatario, $idRemitente);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Obtener mensajes por destinatario
function obtenerMensajesPorDestinatario($id_destinatario) {
    $conn = DatabaseConnection::getConnection();
    
    $stmt = $conn->prepare("SELECT m.contenido_mensaje, m.fecha_mensaje, u.nombre_usuario as nombre_remitente
                            FROM mensaje m
                            JOIN usuario u ON m.id_usuario = u.id_usuario
                            WHERE m.id_destinatario = ?");
    $stmt->bind_param("i", $id_destinatario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $mensajes = [];
    while ($fila = $result->fetch_assoc()) {
        $mensajes[] = $fila;
    }
    $stmt->close();
    
    return $mensajes;
}

// Obtener todos los usuarios
function obtenerTodosLosUsuarios() {
    $conn = DatabaseConnection::getConnection();
    
    $result = $conn->query("SELECT id_usuario, nombre_usuario FROM usuario");
    
    $usuarios = [];
    while ($fila = $result->fetch_assoc()) {
        $usuarios[] = $fila;
    }
    
    return $usuarios;
}

function obtenerMensajesEnviados($idUsuario) {
    $conn = DatabaseConnection::getInstance();

    $stmt = $conn->prepare("SELECT m.contenido_mensaje, m.fecha_mensaje, u.nombre_usuario as nombre_destinatario
                            FROM mensaje m
                            JOIN usuario u ON m.id_destinatario = u.id_usuario
                            WHERE m.id_usuario = ?");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $mensajes = [];
    while ($fila = $result->fetch_assoc()) {
        $mensajes[] = $fila;
    }
    $stmt->close();
    
    return $mensajes;
}
?>
