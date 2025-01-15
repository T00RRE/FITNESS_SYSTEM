<?php
class Database {
    private $host = "DESKTOP-C3B60RT";
    private $db_name = "ProjektPhp";
    private $username = "sa";
    private $password = "haslo123"; // Tu wstaw swoje hasło

    public $conn;

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "sqlsrv:Server=" . $this->host . ";Database=" . $this->db_name,
                $this->username,
                $this->password
            );
            
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch(PDOException $e) {
            echo "Błąd połączenia: " . $e->getMessage();
        }

        return $this->conn;
    }
}
?>