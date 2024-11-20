<?php
require('../Controler/config/db.php');
class GrupoModelo extends DatabaseConnection  {
    public $grupos = [];

    public function delete_Grupo($id_grupo) {     
        if ($this->conexion->query("DELETE FROM grupo WHERE id_grupo = $id_grupo")) {
            $resultado = "HTTP/1.1 201 se borraron datos";
        } else {
            $resultado = 'HTTP/1.1 404 no se borraron datos';
        }
        return $resultado;
    }

    public function update_Grupo($id_grupo, $titulo_grupo, $descripcion_grupo) {     
        if ($this->conexion->query("UPDATE grupo SET titulo_grupo = '$titulo_grupo', descripcion_grupo = '$descripcion_grupo' WHERE id_grupo = $id_grupo")) {
            $resultado = "HTTP/1.1 201 se modificaron datos";
        } else {
            $resultado = 'HTTP/1.1 404 no se modificaron datos';
        }
        return $resultado;
    }

    public function insert_Grupo($titulo_grupo, $descripcion_grupo) {     
        if ($this->conexion->query("INSERT INTO grupo (titulo_grupo, descripcion_grupo) VALUES ('$titulo_grupo', '$descripcion_grupo')")) {
            $resultado = "HTTP/1.1 201 se guardaron datos";
        } else {
            $resultado = 'HTTP/1.1 404 no se guardaron datos';
        }
        return $resultado;
    }
    
    public function obtener_Grupos() {
        $consulta = $this->conexion->query("SELECT * FROM grupo;");
        $filas = $consulta->fetch_all(MYSQLI_ASSOC);
        foreach ($filas as $grupo) {
            $this->grupos[] = $grupo;
        }
        return $this->grupos;
    }
}
?>
