<?php

class DailyLog {
    private $conn;
    private $table_name = "daily_logs";

    public $id;
    public $user_id;
    public $action_type;
    public $co2_saved_kg;
    public $logged_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (user_id, action_type, co2_saved_kg) 
                  VALUES (:user_id, :action_type, :co2_saved_kg)";
        $stmt = $this->conn->prepare($query);

        $this->user_id = (int)$this->user_id;
        $this->action_type = htmlspecialchars(strip_tags($this->action_type));
        $this->co2_saved_kg = (float)$this->co2_saved_kg;

        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":action_type", $this->action_type);
        $stmt->bindParam(":co2_saved_kg", $this->co2_saved_kg);

        return $stmt->execute();
    }

    public function getUserLogs($user_id, $limit = 30) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = :user_id ORDER BY logged_at DESC LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function getStats($user_id) {
        $query = "SELECT SUM(co2_saved_kg) as total_saved, COUNT(*) as total_actions 
                  FROM " . $this->table_name . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
