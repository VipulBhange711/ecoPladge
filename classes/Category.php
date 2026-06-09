<?php

class Category {
    private $conn;
    private $table_name = "categories";

    public $id;
    public $name;
    public $icon;
    public $description;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (name, icon, description) VALUES (:name, :icon, :description)";
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->icon = htmlspecialchars(strip_tags($this->icon));
        $this->description = htmlspecialchars(strip_tags($this->description));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":icon", $this->icon);
        $stmt->bindParam(":description", $this->description);

        return $stmt->execute();
    }
}
?>
