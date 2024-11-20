<?php
require('../Controler/config/db.php');
class MensajeModelo extends DatabaseConnection  {
    public $mensajes = [];

    public function delete_Mensaje($id_mensaje) {     
        if ($this->conexion->query("DELETE FROM Mensaje WHERE id_mensaje = $id_mensaje")) {
            $resultado = "HTTP/1.1 201 se borraron datos";
        } else {
            $resultado = 'HTTP/1.1 404 no se borraron datos';
        }
        return $resultado;
    }

    public function update_Mensaje($id_mensaje, $id_usuario, $contenido_mensaje, $fecha_mensaje) {     
        if ($this->conexion->query("UPDATE Mensaje SET id_usuario = '$id_usuario', contenido_mensaje = '$contenido_mensaje', fecha_mensaje = '$fecha_mensaje' WHERE id_mensaje = $id_mensaje")) {
            $resultado = "HTTP/1.1 201 se modificaron datos";
        } else {
            $resultado = 'HTTP/1.1 404 no se modificaron datos';
        }
        return $resultado;
    }

    public function insert_Mensaje($id_usuario, $contenido_mensaje, $fecha_mensaje) {     
        if ($this->conexion->query("INSERT INTO Mensaje (id_usuario, contenido_mensaje, fecha_mensaje) VALUES ('$id_usuario', '$contenido_mensaje', '$fecha_mensaje')")) {
            $resultado = "HTTP/1.1 201 se guardaron datos";
        } else {
            $resultado = 'HTTP/1.1 404 no se guardaron datos';
        }
        return $resultado;
    }
    
    public function obtener_Mensajes() {
        $consulta = $this->conexion->query("SELECT * FROM Mensaje;");
        $filas = $consulta->fetch_all(MYSQLI_ASSOC);
        foreach ($filas as $mensaje) {
            $this->mensajes[] = $mensaje;
        }
        return $this->mensajes;
    }

    public function obtener_Mensajes_Usuario($id_usuario) {
        $consulta = $this->conexion->query("SELECT * FROM Mensaje WHERE id_usuario = $id_usuario ORDER BY fecha_mensaje ASC");
        $filas = $consulta->fetch_all(MYSQLI_ASSOC);
        
        return $filas;
    }
}
?>
