<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('../Model/UsuarioModelo.php');
$UsuarioObjeto = new UsuarioModelo();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['nombre_usuario'])) {
            $nombre_usuario = $_GET['nombre_usuario'];
    
            // Buscamos al usuario por nombre de usuario
            $usuario = $UsuarioObjeto->obtener_UsuarioPorNombre($nombre_usuario);
    
            if ($usuario) {
                // Si el usuario existe, retornamos los datos
                echo json_encode([
                    'id_usuario' => $usuario['id_usuario'],
                    'nombre_usuario' => $usuario['nombre_usuario'],
                    'contrasena' => $usuario['contrasena'] // Enviamos el hash de la contraseña
                ]);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Usuario no encontrado']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Falta el nombre de usuario']);
        }
        break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
        
            // Log de depuración
            error_log("Datos recibidos en POST: " . print_r($data, true));
        
            if (!isset($data['nombre_usuario'], $data['correo'], $data['contrasena'], $data['fecha_registro'])) {
                http_response_code(400); // Bad Request
                echo json_encode(['error' => 'Datos incompletos']);
                exit();
            }
        
            // Procesar el registro
            $respuesta = $UsuarioObjeto->insert_Usuario(
                $data['nombre_usuario'], 
                $data['correo'], 
                $data['contrasena'], 
                $data['fecha_registro']
            );
        
            if ($respuesta) {
                http_response_code(201); // Created
                echo json_encode(['success' => 'Usuario registrado correctamente']);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(['error' => 'Error al registrar el usuario']);
            }
            break;
        
   

    case 'PUT':
        $_PUT = json_decode(file_get_contents('php://input'), true);
        $respuesta = $UsuarioObjeto->update_Usuario($_PUT['id_usuario'], $_PUT['nombre_usuario'], $_PUT['correo'], $_PUT['contrasena'], $_PUT['fecha_registro']);
        echo json_encode($respuesta);
        break;


    case 'DELETE':     
        $_DELETE = json_decode(file_get_contents('php://input'), true);
        $respuesta = $UsuarioObjeto->delete_Usuario($_DELETE['id_usuario']);
        echo json_encode($respuesta);
        break;
}
