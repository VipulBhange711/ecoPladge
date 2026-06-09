<?php

class Product {
    private $conn;
    private $table_name = "products";

    public $id;
    public $name;
    public $description;
    public $price;
    public $carbon_saved_kg;
    public $image_url;
    public $points_cost;
    public $stock_quantity;
    public $category_id;
    public $is_featured;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT p.*, c.name as category_name FROM " . $this->table_name . " p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getFeatured() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE is_featured = 1 LIMIT 4";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (name, description, price, carbon_saved_kg, image_url, points_cost, stock_quantity, category_id, is_featured) 
                  VALUES (:name, :description, :price, :carbon_saved_kg, :image_url, :points_cost, :stock_quantity, :category_id, :is_featured)";
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->carbon_saved_kg = htmlspecialchars(strip_tags($this->carbon_saved_kg));
        $this->image_url = htmlspecialchars(strip_tags($this->image_url));
        $this->points_cost = (int)$this->points_cost;
        $this->stock_quantity = (int)$this->stock_quantity;
        $this->category_id = $this->category_id ? (int)$this->category_id : null;
        $this->is_featured = $this->is_featured ? 1 : 0;

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":carbon_saved_kg", $this->carbon_saved_kg);
        $stmt->bindParam(":image_url", $this->image_url);
        $stmt->bindParam(":points_cost", $this->points_cost);
        $stmt->bindParam(":stock_quantity", $this->stock_quantity);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":is_featured", $this->is_featured);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET name = :name, description = :description, price = :price, 
                      carbon_saved_kg = :carbon_saved_kg, image_url = :image_url, 
                      points_cost = :points_cost, stock_quantity = :stock_quantity, 
                      category_id = :category_id, is_featured = :is_featured 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->carbon_saved_kg = htmlspecialchars(strip_tags($this->carbon_saved_kg));
        $this->image_url = htmlspecialchars(strip_tags($this->image_url));
        $this->points_cost = (int)$this->points_cost;
        $this->stock_quantity = (int)$this->stock_quantity;
        $this->category_id = $this->category_id ? (int)$this->category_id : null;
        $this->is_featured = $this->is_featured ? 1 : 0;
        $this->id = (int)$this->id;

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":carbon_saved_kg", $this->carbon_saved_kg);
        $stmt->bindParam(":image_url", $this->image_url);
        $stmt->bindParam(":points_cost", $this->points_cost);
        $stmt->bindParam(":stock_quantity", $this->stock_quantity);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":is_featured", $this->is_featured);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>
