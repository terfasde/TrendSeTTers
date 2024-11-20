<?php
require('../Controler/config/db.php');
class EventoModelo extends DatabaseConnection  {
    public $eventos = [];

    public function delete_Evento($id_evento) {     
        if ($this->conexion->query("DELETE FROM evento WHERE id_evento = $id_evento")) {
            $resultado = "HTTP/1.1 201 se borraron datos";
        } else {
            $resultado = 'HTTP/1.1 404 no se borraron datos';
        }
        return $resultado;
    }

    public function update_Evento($id_evento, $titulo_evento, $fecha_evento, $descripcion_evento) {     
        if ($this->conexion->query("UPDATE evento SET titulo_evento = '$titulo_evento', fecha_evento = '$fecha_evento', descripcion_evento = '$descripcion_evento' WHERE id_evento = $id_evento")) {
            $resultado = "HTTP/1.1 201 se modificaron datos";
        } else {
            $resultado = 'HTTP/1.1 404 no se modificaron datos';
        }
        return $resultado;
    }

    public function insert_Evento($titulo_evento, $fecha_evento, $descripcion_evento, $imagen_evento) {
        if ($this->conexion->query("INSERT INTO evento (titulo_evento, fecha_evento, descripcion_evento, imagenes_evento) VALUES ('$titulo_evento', '$fecha_evento', '$descripcion_evento', '$imagen_evento')")) {
            $resultado = "HTTP/1.1 201 se guardaron datos";
        } else {
            $resultado = 'HTTP/1.1 404 no se guardaron datos';
        }
        return $resultado;
    }
    
    public function obtener_Eventos() {
        $consulta = $this->conexion->query("SELECT * FROM evento;");
        $filas = $consulta->fetch_all(MYSQLI_ASSOC);
        foreach ($filas as $evento) {
            $this->eventos[] = $evento;
        }
        return $this->eventos;
    }

    public function unirse_Evento($id_evento, $id_usuario) {
        // Aquí puedes crear o modificar una tabla que registre usuarios y eventos
        $query = "INSERT INTO usuarios_eventos (id_evento, id_usuario) VALUES ($id_evento, $id_usuario)";
        
        if ($this->conexion->query($query)) {
            return "HTTP/1.1 201 Usuario unido al evento";
        } else {
            return "HTTP/1.1 404 Error al unirse al evento";
        }
    }
    
}
?>
