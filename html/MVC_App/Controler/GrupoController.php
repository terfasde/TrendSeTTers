<?php
// Escribir la URL en el cliente API (Insomnia, Postman, etc.)
// localhost/api/Controles/grupoController.php
require('../Model/GrupoModelo.php');
$GrupoObjeto = new GrupoModelo();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $respuesta = $GrupoObjeto->obtener_Grupos();
        echo json_encode($respuesta);
        break;

    case 'POST':
        $_POST = json_decode(file_get_contents('php://input'), true);
        $respuesta = $GrupoObjeto->insert_Grupo($_POST['nombre_grupo'], $_POST['descripcion_grupo']);
        echo json_encode($respuesta);
        break;

    case 'PUT':
        $_PUT = json_decode(file_get_contents('php://input'), true);
        $respuesta = $GrupoObjeto->update_Grupo($_PUT['id_grupo'], $_PUT['nombre_grupo'], $_PUT['descripcion_grupo']);
        echo json_encode($respuesta);
        break;

    case 'DELETE':     
        $_DELETE = json_decode(file_get_contents('php://input'), true);
        $respuesta = $GrupoObjeto->delete_Grupo($_DELETE['id_grupo']);
        echo json_encode($respuesta);
        break;
}
?>
