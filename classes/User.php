<?php

class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $name;
    public $email;
    public $password;
    public $role;
    public $points;
    public $badges;
    public $level;
    public $avatar_url;
    public $total_co2_saved;
    public $bio;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register() {
        $query = "INSERT INTO " . $this->table_name . " (name, email, password_hash, role, points, level) VALUES (:name, :email, :password, :role, 0, 1)";
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $this->role = $this->role ?? 'user';

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":role", $this->role);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";
        $stmt = $this->conn->prepare($query);

        $email = htmlspecialchars(strip_tags($email));
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password_hash'])) {
                $this->id = $row['id'];
                $this->name = $row['name'];
                $this->email = $row['email'];
                $this->role = $row['role'];
                $this->points = $row['points'];
                $this->level = $row['level'];
                $this->avatar_url = $row['avatar_url'];
                return true;
            }
        }
        return false;
    }

    public function emailExists() {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->email]);
        return $stmt->rowCount() > 0;
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfile() {
        $query = "UPDATE " . $this->table_name . " SET name = :name, bio = :bio, avatar_url = :avatar_url WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->bio = htmlspecialchars(strip_tags($this->bio));
        $this->avatar_url = htmlspecialchars(strip_tags($this->avatar_url));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":bio", $this->bio);
        $stmt->bindParam(":avatar_url", $this->avatar_url);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    public function addPoints($points) {
        $query = "UPDATE " . $this->table_name . " SET points = points + :points WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":points", $points);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    public function getLeaderboard($limit = 10) {
        $query = "SELECT id, name, points, level, avatar_url FROM " . $this->table_name . " ORDER BY points DESC LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
