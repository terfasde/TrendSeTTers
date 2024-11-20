<?php
class DatabaseConnection {
    private static $instance = null;
    protected $conn;

    private function __construct() {
        $servername = "localhost";
        $username = "francisco";
        $password = 12345;
        $dbname = "social_network";

        // Establece la conexión
        $this->conn = new mysqli($servername, $username, $password, $dbname);

        // Verifica si hay errores en la conexión
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        // Establece el conjunto de caracteres a utf8mb4
        $this->conn->set_charset("utf8mb4");
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new DatabaseConnection();
        }
        return self::$instance->getConnection();
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>
