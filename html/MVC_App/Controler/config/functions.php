<?php
include_once(__DIR__ . '/db.php');


function sanitize_input($data) {
    return isset($data) ? trim(htmlspecialchars($data)) : '';
}

function validate_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token;
}

function generate_csrf_token() {
    return bin2hex(random_bytes(32));
}


header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Strict-Transport-Security: max-age=31536000; includeSubDomains');

$db_path = __DIR__ . '/db.php';
if (file_exists($db_path)) {
    include_once($db_path);
} else {
    die("Error: No se encontró el archivo de base de datos en la ruta especificada.");
}

function insertar_evento($titulo, $descripcion, $fecha, $imagen) {
    global $conexion;
    $sql = "INSERT INTO evento (titulo_evento, descripcion_evento, fecha_evento, imagenes_evento) VALUES ('$titulo', '$descripcion', '$fecha', '$imagen')";
    return mysqli_query($conexion, $sql);
}
?>
 
