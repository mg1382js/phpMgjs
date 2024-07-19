<?php

class Database
{
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $db_name = 'test';
    private $conn;

    public function getConnection()
    {
        $this->conn = '';
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec('set names utf8');
        } catch (PDOException $exeption) {
            echo "connection error:" . $exeption->getMessage();
        }
        return $this->conn;
    }

}
