<?php 
class Database {

    protected $conn;

    public function connect()
    {
        try {
            $this->conn = new PDO("mysql:host=localhost;dbname=vakantie", "root", "");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}

?>