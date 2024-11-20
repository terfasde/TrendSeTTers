<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../Controler/config/db.php');
include(__DIR__ . '/../Controler/config/functions.php');
include_once(__DIR__ . '/../Model/PerfilModelo.php');

session_start();

$conexion = new mysqli('localhost', 'root', 'root', 'social_network');
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Verificar si la sesión está activa
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
var_dump($id_usuario); // Agrega esto para verificar el ID

$PerfilObjeto = new PerfilModelo($conexion);
$perfil = $PerfilObjeto->obtenerPerfil($id_usuario);

if (!$perfil) {
    echo "Error: No se encontró el perfil del usuario.";
    exit();
} else {
    // Mostrar el perfil del usuario
    echo "Nombre: " . htmlspecialchars($perfil['nombre_completo']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="../Visual/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="icon" href="../Visual/icon.png">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Danfo&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Varela+Round&display=swap"
        rel="stylesheet">
</head>

<style>
#nova {
    margin-left: 70px;
}
</style>

<body>
    <nav class="navbar">
        <h2 id="nova">NOVA</h2>
        <a href="../Controler/logout.php" id="salir" style="margin-right: 20px;">Salir</a>
    </nav>

    <div class="sidebar">
        <ul>
            <li>
                <a href="index.php">
                    <i class="fa fa-home fa-lg"></i>
                    <span class="nav-text">Inicio</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-user fa-lg"></i>
                    <span class="nav-text">Perfil</span>
                </a>
            </li>
            <li>
                <a href="amigos.php">
                    <i class="fa fa-heart fa-lg"></i>
                    <span class="nav-text">Amigos</span>
                </a>
            </li>
            <li>
                <a href="eventos.php">
                    <i class="fa fa-clock-o fa-lg"></i>
                    <span class="nav-text">Eventos</span>
                </a>
            </li>
            <li>
            <a href="mensajes.php">
                <i class="fa fa-envelope fa-lg"></i> <!-- Icono de carta -->
                <span class="nav-text">Mensajes</span>
            </a>
            </li>
        </ul>
    </div>

    <button id="editar-perfil">Editar</button>

    <div id="form-container" style="display: none;">
        <form method="POST" action="PerfilController.php" enctype="multipart/form-data">
            <label for="nombre_completo">Nombre Completo:</label>
            <input type="text" id="nombre_completo" name="nombre_completo" value="<?= htmlspecialchars($perfil['nombre_completo']) ?>">

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion"><?= htmlspecialchars($perfil['descripcion']) ?></textarea>

            <label for="foto_perfil">Foto de Perfil:</label>
            <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*">

            <button type="submit">Actualizar Perfil</button>
        </form>
    </div>

    <div class="perfil">
        <h3>Perfil de <?= htmlspecialchars($perfil['nombre_completo']); ?></h3>
        <p>Descripción: <?= htmlspecialchars($perfil['descripcion']); ?></p>
        <?php if (!empty($perfil['foto_perfil'])): ?>
            <img src="data:image/jpeg;base64,<?= base64_encode($perfil['foto_perfil']) ?>" alt="Foto de perfil">
        <?php else: ?>
            <img src="../Visual/pfp.jpg" alt="Foto de perfil por defecto">
        <?php endif; ?>
        <h3>Tus Posts</h3>
        <ul>
            <?php
            $posts = obtenerPostsUsuario($id_usuario);
            foreach ($posts as $post) {
                echo "<li>" . htmlspecialchars($post['contenido']) . " - <a href='eliminar_post.php?id=" . $post['id_post'] . "'>Eliminar</a></li>";
            }
            ?>
        </ul>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.getElementById('editar-perfil').addEventListener('click', function() {
            var formContainer = document.getElementById('form-container');
            var overlay = document.getElementById('overlay');

            formContainer.style.display = 'block';
            overlay.style.display = 'block';
        });

        document.getElementById('overlay').addEventListener('click', function() {
            document.getElementById('form-container').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        });
    </script>
</body>
</html>
