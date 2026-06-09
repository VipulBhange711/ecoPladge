<?php

class Team {
    private $conn;
    private $table_name = "teams";

    public $id;
    public $name;
    public $description;
    public $owner_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (name, description, owner_id) VALUES (:name, :description, :owner_id)";
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->owner_id = (int)$this->owner_id;

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":owner_id", $this->owner_id);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            // Automatically join the team as the first member
            $this->join($this->owner_id, $this->id);
            return true;
        }
        return false;
    }

    public function join($user_id, $team_id) {
        $query = "INSERT INTO team_members (team_id, user_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$team_id, $user_id]);
    }

    public function getMembers($team_id) {
        $query = "SELECT u.id, u.name, u.points, u.level, u.avatar_url 
                  FROM users u 
                  JOIN team_members tm ON u.id = tm.user_id 
                  WHERE tm.team_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$team_id]);
        return $stmt;
    }

    public function getTeamStats($team_id) {
        $query = "SELECT SUM(u.points) as total_points, SUM(u.total_co2_saved) as total_co2 
                  FROM users u 
                  JOIN team_members tm ON u.id = tm.user_id 
                  WHERE tm.team_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$team_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
