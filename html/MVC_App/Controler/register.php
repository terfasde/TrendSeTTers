<?php
// Mostrar errores para depuración
session_start();
date_default_timezone_set('America/Montevideo');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ajusta las rutas de los includes según tu estructura de archivos
include(__DIR__ . '/../Controler/config/functions.php');


if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = generate_csrf_token();
}

// Generar CSRF token si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_usuario = isset($_POST['nombre_usuario']) ? sanitize_input($_POST['nombre_usuario']) : '';
    $contrasena = isset($_POST['contrasena']) ? password_hash(sanitize_input($_POST['contrasena']), PASSWORD_BCRYPT) : '';
    $correo = isset($_POST['correo']) ? sanitize_input($_POST['correo']) : '';
    $fecha_registro = date('Y-m-d');

    if (validate_csrf_token($_POST['csrf_token'])) {
        // Datos a enviar a la API
        $data = array(
            'nombre_usuario' => $nombre_usuario,
            'correo' => $correo,
            'contrasena' => $contrasena,
            'fecha_registro' => $fecha_registro
        );

        // Inicializamos cURL
        var_dump($data);

        $ch = curl_init('http://localhost/Controler/UsuarioController.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $response = curl_exec($ch);
        if ($response === false) {
            echo "cURL Error: " . curl_error($ch);
        } else {
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($http_code == 201) {
                echo "Registrado exitosamente. Redirigiendo a login.php...";
                header("Location: login.php");
                exit();
            } else {
                echo "Error al registrar: Código de respuesta HTTP: " . $http_code;
            }
        }
        curl_close($ch);

    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<link href="https://fonts.googleapis.com/css2?family=Danfo&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Varela+Round&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="icon" href="../Visual/icon.png">
    <link rel="stylesheet" href="../Visual/log.css">
</head>

<style>
    body {
        background-image:url(../Visual/fondo.gif);
    }
    .ring {
        animation-duration: 1.8s;
    }
    .login {
        animation-duration: 2.2s;
    }
    .inputBx {
        animation-duration: 2.2s;
    }
    
    .links {
        animation-duration: 2.2s;
    }
    .login h2 {
    color: #F0EDCF;
    }
    p {
        text-align: center;
    }
</style>

<body>

<audio id="audio" src="../Visual/sable.mp3"></audio>

<div class="ring animate__animated animate__rotateIn">
            <i style="--clr:#40A2D8;"></i>
            <i style="--clr:#0B60B0;"></i>
            <i style="--clr:#0d4e8a;"></i>
        <div class="login animate__animated animate__jackInTheBox">
            <h2>Registro</h2>
            <form method="post" action="">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <div class="inputBx animate__animated animate__jackInTheBox">
            <label for="username"><b>Usuario:</b></label>
            <input type="text" id="espacio" name="nombre_usuario" id="nombre_usuario" required>
        </div>
        <div class="inputBx animate__animated animate__jackInTheBox">
            <label for="password"><b>Contraseña:</b></label>
            <input type="password" id="espacio" name="contrasena" id="contrasena" required>
        </div>
        <div class="inputBx animate__animated animate__jackInTheBox">
            <label for="email"><b>Correo:</b></label>
            <input type="email" id="espacio" name="correo" id="correo" required>
        </div>
        <br>
        <div class="inputBx animate__animated animate__jackInTheBox">
            <input type="submit" value="Registrarse">
        </div>
    </form>
    <div class="links animate__animated animate__jackInTheBox">
    <a href="login.php">¿Ya tienes una cuenta?</a>
    </div>
</body>
<script>
        // Reproducir el sonido automáticamente cuando la página se carga
        window.onload = function() {
            var audio = document.getElementById("audio");
            audio.play();
        };
    </script>
</html>
