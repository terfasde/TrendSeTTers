<?php 

ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once(__DIR__ . '/../Controler/config/db.php');
include(__DIR__ . '/../Controler/config/functions.php');

// Verificar si se ha enviado el formulario
if (isset($_POST['crear_evento'])) {
    // Redirigir al controlador que procesa el evento y la imagen
    include(__DIR__ . '/../Controler/ImageEventoController.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        href="https://fonts.googleapis.com/css2?family=Danfo&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Varela+Round&display=swap"
        rel="stylesheet">
    <title>Eventos</title>
    <link rel="stylesheet" href="../Visual/eventos.css">
    <link rel="icon" href="../Visual/icon.png">
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
            <a href="amigos.php">
                <i class="fa fa-heart fa-lg"></i>
                <span class="nav-text">Amigos</span>
            </a>
        </li>
        <li>
            <a href="#">
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

<div style="text-align:center; margin-top: 20px;">
    <button id="abrir-form-evento">Crear Evento</button>
</div>



    <?php
    // Mostrar los eventos como antes
    $conexion = new mysqli('localhost', 'root', 'root', 'social_network');

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    $resultado = $conexion->query("
        SELECT 
            e.titulo_evento, 
            e.image, 
            u.nombre_usuario, 
            e.fecha_evento, 
            e.id_evento, 
            e.descripcion_evento, 
            e.contador_usuarios 
        FROM 
            evento e
        INNER JOIN 
            usuario u ON e.id_usuario = u.id_usuario
        ORDER BY 
            e.id_evento DESC 
        LIMIT 10
    ");

    $eventos = [];
    while ($fila = $resultado->fetch_assoc()) {
        $eventos[] = $fila;
    }

    $conexion->close();
    ?>

<div id="evento-form-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <form id="evento-form" method="POST" enctype="multipart/form-data">
            <label for="titulo_evento">Título del Evento:</label>
            <input type="text" name="titulo_evento" id="titulo_evento" required>

            <label for="descripcion_evento">Descripción del Evento:</label>
            <textarea name="descripcion_evento" id="descripcion_evento" required></textarea>

            <label for="fecha_evento">Fecha del Evento:</label>
            <input type="datetime-local" name="fecha_evento" id="fecha_evento" required>

            <label for="image">Selecciona una imagen:</label>
            <input type="file" name="image" id="image" accept="image/*" required>

            <button type="submit" name="crear_evento">Crear Evento</button>
        </form>
    </div>
</div>

<!-- Lista de eventos -->
<div id="event-list">
    <h3>Eventos Actuales</h3>
    <?php foreach ($eventos as $evento): ?>
    <div class="evento">
        <h4><?php echo htmlspecialchars($evento['titulo_evento']); ?></h4>
        <p><?php echo htmlspecialchars($evento['descripcion_evento']); ?></p>
        <p>Fecha del Evento: <?php echo date('H:i - d/m/Y', strtotime($evento['fecha_evento'])); ?></p>
        <?php if (!empty($evento['image'])): ?>
            <img src="uploads/<?php echo htmlspecialchars($evento['image']); ?>" alt="Imagen del evento" style="max-width: 300px;">
        <?php endif; ?>
        <p><?php echo htmlspecialchars($evento['nombre_usuario']); ?></p>
        <p>Personas unidas: <?php echo $evento['contador_usuarios']; ?></p>
        <br>

        <button class="unirse-evento" data-id="<?php echo $evento['id_evento']; ?>">Unirse al evento</button>
    </div>
    <?php endforeach; ?>
</div>

    <script>
      // JavaScript para controlar el modal
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('evento-form-modal');
    var openModalBtn = document.getElementById('abrir-form-evento');
    var closeModalBtn = document.querySelector('.close');
    var body = document.body;
    var overlay = document.createElement('div');
    overlay.className = 'overlay-dark'; // Capa semitransparente para sidebar y navbar

    // Mostrar el modal al hacer clic en el botón "Crear Evento"
    openModalBtn.onclick = function () {
        modal.style.display = "block";
        document.querySelector('.main-content').classList.add('blur-background'); // Añadir blur solo al contenido principal
        body.appendChild(overlay); // Agregar la capa oscura sobre sidebar y navbar
    };

    // Cerrar el modal al hacer clic en la "X"
    closeModalBtn.onclick = function () {
        modal.style.display = "none";
        document.querySelector('.main-content').classList.remove('blur-background'); // Quitar blur del contenido principal
        if (body.contains(overlay)) {
            body.removeChild(overlay); // Eliminar la capa oscura
        }
    };

    // Cerrar el modal si se hace clic fuera del contenido del modal
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
            document.querySelector('.main-content').classList.remove('blur-background'); // Quitar blur del contenido principal
            if (body.contains(overlay)) {
                body.removeChild(overlay); // Eliminar la capa oscura
            }
        }
    };
});

    </script>
</body>
</html>
