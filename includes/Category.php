<?php
/**
 * Category.php - Category class (admin CRUD)
 * Chapter 13 (OOP): class, constructor, PDO prepared statements.
 */

class Category
{
    /** @var PDO */
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /** @return array */
    public function getAll()
    {
        return $this->db->query('SELECT * FROM categories ORDER BY name')->fetchAll();
    }

    /** @return array|false */
    public function getById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM categories WHERE id = :id');
        $stmt->execute([':id' => (int)$id]);
        return $stmt->fetch();
    }

    /** @return bool */
    public function create($data)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO categories (name, description) VALUES (:name, :description)'
        );
        return $stmt->execute([
            ':name'        => trim($data['name'] ?? ''),
            ':description' => trim($data['description'] ?? ''),
        ]);
    }

    /** @return bool */
    public function update($id, $data)
    {
        $stmt = $this->db->prepare(
            'UPDATE categories SET name = :name, description = :description WHERE id = :id'
        );
        return $stmt->execute([
            ':name'        => trim($data['name'] ?? ''),
            ':description' => trim($data['description'] ?? ''),
            ':id'          => (int)$id,
        ]);
    }

    /** @return bool */
    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM categories WHERE id = :id');
        return $stmt->execute([':id' => (int)$id]);
    }

    /** @return int */
    public function count()
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM categories')->fetchColumn();
    }
}
