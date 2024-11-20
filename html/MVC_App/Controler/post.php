<?php
include(__DIR__ . '/../Controler/config/db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $content = sanitize_input($_POST['content']);
    $image_path = '';

    // Manejar la carga de la imagen
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../uploads/";
        $image_name = basename($_FILES['image']['name']);
        $target_file = $target_dir . $image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        $image_path = $target_file;
    }

    // Insertar el post en la base de datos
    $stmt = $conn->prepare("INSERT INTO Posts (user_id, content, image_path) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $content, $image_path);

    if ($stmt->execute()) {
        echo "Publicación exitosa.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>