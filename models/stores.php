<?php
class Store {
    private $conn;
    private $table = "stores";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getStores() {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt;
    }

    public function createStore($name) {
        $stmt = $this->conn->prepare("INSERT INTO $this->table (name) VALUES (:name)");
        return $stmt->execute(['name' => $name]);
    }

    public function deleteStore($id) {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}