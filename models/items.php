<?php
class Item {
    private $conn;
    private $table = "items";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getItemsByStore($store_id) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE store_id = :store_id");
        $stmt->execute(['store_id' => $store_id]);
        return $stmt;
    }

    public function createItem($store_id, $name, $quantity) {
        $stmt = $this->conn->prepare(
            "INSERT INTO $this->table (store_id, name, quantity) VALUES (:store_id, :name, :quantity)"
        );
        return $stmt->execute([
            'store_id' => $store_id,
            'name' => $name,
            'quantity' => $quantity
        ]);
    }

    public function deleteItem($id) {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function updateItem($id, $checked, $name, $quantity) {
        $stmt = $this->conn->prepare(
            "UPDATE $this->table 
             SET checked = :checked, name = :name, quantity = :quantity
             WHERE id = :id"
        );
        return $stmt->execute([
            'checked' => $checked,
            'name' => $name,
            'quantity' => $quantity,
            'id' => $id
        ]);
    }
}