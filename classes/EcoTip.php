<?php

class EcoTip {
    private $conn;
    private $table_name = "eco_tips";

    public $id;
    public $title;
    public $content;
    public $category;
    public $likes_count;
    public $author_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT t.*, u.name as author_name FROM " . $this->table_name . " t 
                  JOIN users u ON t.author_id = u.id 
                  ORDER BY t.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (title, content, category, author_id) 
                  VALUES (:title, :content, :category, :author_id)";
        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->author_id = (int)$this->author_id;

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":content", $this->content);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":author_id", $this->author_id);

        return $stmt->execute();
    }

    public function like($id) {
        $query = "UPDATE " . $this->table_name . " SET likes_count = likes_count + 1 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>
