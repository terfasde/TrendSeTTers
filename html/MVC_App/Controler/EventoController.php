<?php
// Escribir la URL en el cliente API (Insomnia, Postman, etc.)
// localhost/api/Controles/eventoController.php
require('../Model/EventoModelo.php');
$EventoObjeto = new EventoModelo();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $respuesta = $EventoObjeto->obtener_Eventos();
        echo json_encode($respuesta);
        break;

        case 'POST':
            $_POST = json_decode(file_get_contents('php://input'), true);
            
            if ($_POST['accion'] == 'unirse') {
                $id_evento = $_POST['id_evento'];
                // Aquí procesa la lógica de agregar al usuario a la lista de asistentes
                // Ejemplo: INSERT en una tabla de usuarios_eventos o actualizar un campo
                $respuesta = $EventoObjeto->unirse_Evento($id_evento, $_SESSION['id_usuario']);
                echo json_encode($respuesta);
            } else {
                $respuesta = $EventoObjeto->insert_Evento($_POST['titulo_evento'], $_POST['fecha_evento'], $_POST['descripcion_evento']);
                if ($respuesta == "HTTP/1.1 201 se guardaron datos") {
                    header('Location: ../Vista/eventos.php');
                } else {
                    echo json_encode($respuesta);
                }
            }
            break;
        
        

    case 'PUT':
        $_PUT = json_decode(file_get_contents('php://input'), true);
        $respuesta = $EventoObjeto->update_Evento($_PUT['id_evento'], $_PUT['titulo_evento'], $_PUT['fecha_evento'], $_PUT['descripcion_evento']);
        echo json_encode($respuesta);
        break;

    case 'DELETE':     
        $_DELETE = json_decode(file_get_contents('php://input'), true);
        $respuesta = $EventoObjeto->delete_Evento($_DELETE['id_evento']);
        echo json_encode($respuesta);
        break;

        case 'JOIN':
            $_POST = json_decode(file_get_contents('php://input'), true);
            $respuesta = $EventoObjeto->unirse_Evento($_POST['id_evento'], $_POST['id_usuario']);
            echo json_encode($respuesta);
            break;
        
}
?>
