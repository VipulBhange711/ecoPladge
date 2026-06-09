<?php

class Challenge {
    private $conn;
    private $table_name = "challenges";

    public $id;
    public $title;
    public $description;
    public $target_value;
    public $points_reward;
    public $start_date;
    public $end_date;
    public $is_active;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllActive() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE is_active = 1 AND (end_date >= CURDATE() OR end_date IS NULL) ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (title, description, target_value, points_reward, start_date, end_date) 
                  VALUES (:title, :description, :target_value, :points_reward, :start_date, :end_date)";
        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->target_value = (float)$this->target_value;
        $this->points_reward = (int)$this->points_reward;

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":target_value", $this->target_value);
        $stmt->bindParam(":points_reward", $this->points_reward);
        $stmt->bindParam(":start_date", $this->start_date);
        $stmt->bindParam(":end_date", $this->end_date);

        return $stmt->execute();
    }

    public function join($user_id, $challenge_id) {
        $query = "INSERT INTO user_challenges (user_id, challenge_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$user_id, $challenge_id]);
    }

    public function getUserChallenges($user_id) {
        $query = "SELECT uc.*, c.title, c.description, c.target_value, c.points_reward 
                  FROM user_challenges uc 
                  JOIN challenges c ON uc.challenge_id = c.id 
                  WHERE uc.user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt;
    }
}
?>
