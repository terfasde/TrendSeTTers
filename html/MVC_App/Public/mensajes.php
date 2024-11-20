<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('../Controler/MessageController.php');

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php'); // Redirigir al login si no está autenticado
    exit();
}

$idUsuario = $_SESSION['id_usuario'];
$idDestinatario = isset($_GET['id_destinatario']) ? $_GET['id_destinatario'] : 0;

// Obtener la lista de usuarios y los mensajes
$usuarios = obtenerUsuarios($idUsuario);
$mensajes = obtenerMensajesEntreUsuarios($idUsuario, $idDestinatario);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amigos</title>
    <link rel="stylesheet" href="../Visual/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="icon" href="../Visual/icon.png">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Danfo&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Varela+Round&display=swap" rel="stylesheet">
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
                <a href="perfil.php">
                    <i class="fa fa-user fa-lg"></i>
                    <span class="nav-text">Perfil</span>
                </a>
            </li>
            <li>
                <a href="#">
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
            <a href="#">
                <i class="fa fa-envelope fa-lg"></i> <!-- Icono de carta -->
                <span class="nav-text">Mensajes</span>
            </a>
            </li>
        </ul>
    </div>

    <div id="mensaje-container">
    <h2>Enviar Mensaje</h2>
    <form action="../Controler/MessageController.php" method="post" class="mensaje">
        <label for="destinatario">Seleccionar Usuario:</label>
        <select name="id_destinatario" id="destinatario" required>
            <option value="">Seleccione un usuario</option>
            <?php foreach ($usuarios as $usuario): ?>
                <option value="<?php echo $usuario['id_usuario']; ?>" <?php echo ($usuario['id_usuario'] == $idDestinatario) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($usuario['nombre_usuario']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="mensaje">Mensaje:</label>
        <textarea name="contenido_mensaje" id="mensaje" rows="5" required></textarea>
        <br>
        <button type="submit" name="enviar_mensaje">Enviar Mensaje</button>
    </form>

    <!-- Mostrar los mensajes intercambiados entre los usuarios -->
    <h2>Mensajes Intercambiados</h2>
    <div class="intercambiados-messages">
        <?php if (empty($mensajes)): ?>
            <p>No hay mensajes para mostrar.</p>
        <?php else: ?>
            <?php foreach ($mensajes as $mensaje): ?>
                <div class="mensaje">
                    <p><strong><?php echo htmlspecialchars($mensaje['nombre_remitente']); ?>:</strong></p>
                    <p><?php echo htmlspecialchars($mensaje['contenido_mensaje']); ?></p>
                    <p><small><?php echo date('H:i - d/m/Y', strtotime($mensaje['fecha_mensaje'])); ?></small></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#mensaje-form').on('submit', function(e) {
        e.preventDefault(); // Evita que el formulario se envíe de forma tradicional

        $.ajax({
            url: '../Controler/MessageController.php',
            type: 'POST',
            data: $(this).serialize(), // Envía todos los datos del formulario
            success: function(response) {
                // Actualiza la sección de mensajes recibidos
                $('#received-messages').append(response); // Añade el nuevo mensaje al contenedor
                $('#mensaje-form')[0].reset(); // Limpia el formulario
            },
            error: function() {
                alert('Error al enviar el mensaje.'); // Muestra un error si falla
            }
        });
    });
});
</script>


</body>
</html>