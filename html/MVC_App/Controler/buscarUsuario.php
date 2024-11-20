<?php
require('config/db.php');
$conexion = new mysqli($servername, $user, $password, $dbname);


// Verifica que el parámetro de nombre está establecido
if (isset($_GET['nombre_usuario'])) {
    $nombre = $_GET['nombre_usuario'];
    
    // Consulta para obtener usuarios que coincidan con el nombre buscado
    $query = "SELECT id_usuario, nombre_usuario FROM usuario WHERE nombre_usuario LIKE '%$nombre%'";
    $resultado = $this->conexion->query($query);
    
    $usuarios = [];
    while ($row = $resultado->fetch_assoc()) {
        $usuarios[] = $row;
    }
    
    // Devolver el resultado en formato JSON
    echo json_encode($usuarios);
}
?>
