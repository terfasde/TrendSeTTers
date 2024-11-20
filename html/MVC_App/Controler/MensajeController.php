<?php
// Escribir la URL en el cliente API (Insomnia, Postman, etc.)
// localhost/api/Controles/mensajeController.php
require('../Modelo/MensajeModelo.php');
$MensajeObjeto = new MensajeModelo();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['usuario'])) {
            $id_usuario = $_GET['usuario'];
            $respuesta = $MensajeObjeto->obtener_Mensajes_Usuario($id_usuario);
        } else {
            $respuesta = $MensajeObjeto->obtener_Mensajes();
        }
        echo json_encode($respuesta);
        break;

    case 'POST':
        $_POST = json_decode(file_get_contents('php://input'), true);
        $respuesta = $MensajeObjeto->insert_Mensaje($_POST['id_usuario'], $_POST['contenido_mensaje'], $_POST['fecha_mensaje']);
        echo json_encode($respuesta);
        break;

    case 'PUT':
        $_PUT = json_decode(file_get_contents('php://input'), true);
        $respuesta = $MensajeObjeto->update_Mensaje($_PUT['id_mensaje'], $_PUT['id_usuario'], $_PUT['contenido_mensaje'], $_PUT['fecha_mensaje']);
        echo json_encode($respuesta);
        break;

    case 'DELETE':     
        $_DELETE = json_decode(file_get_contents('php://input'), true);
        $respuesta = $MensajeObjeto->delete_Mensaje($_DELETE['id_mensaje']);
        echo json_encode($respuesta);
        break;

    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['nombre_usuario'])) {
        $id_usuario = $_GET['nombre_usuario'];
        $respuesta = $MensajeObjeto->obtener_Mensajes_Usuario($id_usuario);
        echo json_encode($respuesta);
    }
}
?>
