<?php
// session_manager.php

// Verificar si ya existe una sesión antes de iniciar una nueva
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// Si el usuario está logueado, $id_usuario estará disponible
$id_usuario = $_SESSION['id_usuario'];
?>