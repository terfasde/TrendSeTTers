<?php
// Escribir la URL en el cliente API (Insomnia, Postman, etc.)
// localhost/api/Controles/perfilController.php
require('../Model/PerfilModelo.php');

$PerfilObjeto = new PerfilModelo();

function convertirImagenABase64($archivo) {
    // Obtener el contenido del archivo
    $imagenContenido = file_get_contents($archivo["tmp_name"]);
    // Codificar el contenido en base64
    $imagenBase64 = base64_encode($imagenContenido);
    return $imagenBase64;
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $respuesta = $PerfilObjeto->obtenerPerfil($id_usuario);
        echo json_encode($respuesta);
        break;

    case 'POST':
        if (isset($_FILES['foto_perfil'])) {
            $foto_perfil = convertirImagenABase64($_FILES['foto_perfil']);
        } else {
            $foto_perfil = null;  // Si no hay imagen
        }
    
            // Guardar el perfil con la imagen codificada en base64
        $respuesta = $PerfilObjeto->insert_Perfil($_POST['id_usuario'], $_POST['nombre_completo'], $_POST['descripcion'], $foto_perfil);
        echo json_encode($respuesta);
        break;
        

    case 'PUT':
        if (isset($_FILES['foto_perfil'])) {
            $foto_perfil = convertirImagenABase64($_FILES['foto_perfil']);
        } else {
            $foto_perfil = null;  // Si no hay imagen
        }
        
                // Actualizar el perfil con la nueva imagen codificada en base64
        $respuesta = $PerfilObjeto->update_Perfil($_PUT['id_perfil'], $_PUT['id_usuario'], $_PUT['nombre_completo'], $_PUT['descripcion'], $foto_perfil);
        echo json_encode($respuesta);
        break;

    case 'DELETE':     
        $_DELETE = json_decode(file_get_contents('php://input'), true);
        $respuesta = $PerfilObjeto->delete_Perfil($_DELETE['id_perfil']);
        echo json_encode($respuesta);
        break;
}
?>
