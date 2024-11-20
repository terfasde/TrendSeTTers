<?php
// Escribir la URL en el cliente API (Insomnia, Postman, etc.)
// localhost/api/Controles/amigoController.php
require('../Model/AmigoModelo.php');
$AmigoObjeto = new AmigoModelo();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $respuesta = $AmigoObjeto->obtener_Amigos();
        echo json_encode($respuesta);
        break;

    case 'POST':
        $_POST = json_decode(file_get_contents('php://input'), true);
        $respuesta = $AmigoObjeto->insert_Amigo($_POST['usuario_1'], $_POST['usuario_2']);
        echo json_encode($respuesta);
        break;

    case 'PUT':
        $respuesta = 'HTTP/1.1 405 Método no permitido';
        echo json_encode($respuesta);
        break;

    case 'DELETE':     
        $_DELETE = json_decode(file_get_contents('php://input'), true);
        $respuesta = $AmigoObjeto->delete_Amigo($_DELETE['usuario_1'], $_DELETE['usuario_2']);
        echo json_encode($respuesta);
        break;
}
?>
