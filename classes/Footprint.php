<?php

class Footprint {
    private $conn;
    private $table_name = "footprints";

    public $id;
    public $user_id;
    public $transport_kg;
    public $energy_kg;
    public $waste_kg;
    public $total_kg;
    public $calculated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (user_id, transport_kg, energy_kg, waste_kg, total_kg) VALUES (:user_id, :transport_kg, :energy_kg, :waste_kg, :total_kg)";
        $stmt = $this->conn->prepare($query);

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->transport_kg = htmlspecialchars(strip_tags($this->transport_kg));
        $this->energy_kg = htmlspecialchars(strip_tags($this->energy_kg));
        $this->waste_kg = htmlspecialchars(strip_tags($this->waste_kg));
        $this->total_kg = htmlspecialchars(strip_tags($this->total_kg));

        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":transport_kg", $this->transport_kg);
        $stmt->bindParam(":energy_kg", $this->energy_kg);
        $stmt->bindParam(":waste_kg", $this->waste_kg);
        $stmt->bindParam(":total_kg", $this->total_kg);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getByUserId($user_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = ? ORDER BY calculated_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt;
    }

    public function getAll() {
        $query = "SELECT f.*, u.name as user_name FROM " . $this->table_name . " f JOIN users u ON f.user_id = u.id ORDER BY f.calculated_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
