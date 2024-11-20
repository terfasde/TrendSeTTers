<?php
require('../Controler/config/db.php');
class AmigoModelo extends DatabaseConnection  {
    public $amigos = [];

    public function delete_Amigo($usuario_1, $usuario_2) {     
        if ($this->conexion->query("DELETE FROM amigo WHERE usuario_1 = $usuario_1 AND usuario_2 = $usuario_2")) {
            $resultado = "HTTP/1.1 201 se borraron datos";
        } else {
            $resultado = 'HTTP/1.1 404 no se borraron datos';
        }
        return $resultado;
    }

    public function update_Amigo($usuario_1, $usuario_2) {     
        // No se suele actualizar una relación de amistad, por lo que este método podría estar vacío o no ser necesario.
        return 'HTTP/1.1 405 Método no permitido';
    }

    public function insert_Amigo($usuario_1, $usuario_2) {     
        if ($this->conexion->query("INSERT INTO amigo (usuario_1, usuario_2) VALUES ('$usuario_1', '$usuario_2')")) {
            $resultado = "HTTP/1.1 201 se guardaron datos";
        } else {
            $resultado = 'HTTP/1.1 404 no se guardaron datos';
        }
        return $resultado;
    }
    
    public function obtener_Amigos() {
        $consulta = $this->conexion->query("SELECT * FROM amigo;");
        $filas = $consulta->fetch_all(MYSQLI_ASSOC);
        foreach ($filas as $amigo) {
            $this->amigos[] = $amigo;
        }
        return $this->amigos;
    }
}
?>
