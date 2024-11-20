<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include(__DIR__ . '/../Controler/config/functions.php');
require_once(__DIR__ . '/config/db.php');





// Si el usuario ya está autenticado, redirige a la página principal
if (isset($_SESSION['id_usuario'])) {
    header("Location: ../Public/index.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_usuario = isset($_POST['username']) ? sanitize_input($_POST['username']) : '';
    $contrasena = isset($_POST['password']) ? sanitize_input($_POST['password']) : '';

    // Configuramos la URL del API con el nombre de usuario
    $url = 'http://localhost/Controler/UsuarioController.php?nombre_usuario=' . urlencode($nombre_usuario);

    if ($id_usuario) {
        // Generar un token único para la sesión
        $token = bin2hex(random_bytes(32));

        // Guardar el token en la base de datos
        $conexion = new mysqli('localhost', 'root', 'root', 'social_network');
        $stmt = $conexion->prepare("INSERT INTO sesiones (id_usuario, token) VALUES (?, ?)");
        $stmt->bind_param('is', $id_usuario, $token);
        $stmt->execute();

        // Guardar el token en una cookie para el cliente
        setcookie("session_token", $token, time() + (86400 * 30), "/"); // Expira en 30 días

        // Redirigir al perfil
        header("Location: perfil.php");
        exit();
    } else {
        echo "Credenciales incorrectas.";
    }
    
    // Inicializamos cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPGET, true);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false || json_last_error() !== JSON_ERROR_NONE) {
        echo 'Error al conectar con la API o al decodificar la respuesta JSON.';
        exit;
    }

    // Decodificamos la respuesta JSON
    $response_data = json_decode($response, true);

    if ($http_code == 200 && isset($response_data['id_usuario'])) {
        // Verificamos la contraseña usando password_verify
        if (password_verify($contrasena, $response_data['contrasena'])) {
            // Almacenar la sesión si el login es correcto
            $_SESSION['id_usuario'] = $response_data['id_usuario'];
            $_SESSION['nombre_usuario'] = $response_data['nombre_usuario'];
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            // Redirigir al usuario a la página principal
            header("Location: ../Public/index.php");
            exit();
        } else {
            echo "Usuario o contraseña incorrecta.";
        }
    } else {
        echo "Usuario o contraseña incorrecta.";
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
    <title>Inicio de Sesion</title>
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
                <h2><b>Inicio de Sesion</b></h2>
                <form method="post" action="">
            <div class="inputBx animate__animated animate__jackInTheBox">
                <label for="username"><b>Usuario:</b></label>
                <input type="text" id="espacio" name="username" id="username" required>
            </div>
            <div class="inputBx animate__animated animate__jackInTheBox">
                <label for="password"><b>Contraseña:</b></label>
                <input type="password" id="espacio" name="password" id="password" required>
            </div>
            <br>
            <div class="inputBx animate__animated animate__jackInTheBox">
                <input type="submit" value="Acceder">
            </div>
            </form>
            <div class="links animate__animated animate__jackInTheBox">
                <a href="register.php">Registrarse</a>
            </div>
        </div>
</div>
<script>
        // Reproducir el sonido automáticamente cuando la página se carga
        window.onload = function() {
            var audio = document.getElementById("audio");
            audio.play();
        };
    </script>
</html>
