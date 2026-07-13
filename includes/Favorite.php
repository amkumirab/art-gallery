<?php
/**
 * Favorite.php - Favorite class (user <-> artwork)
 * Chapter 13 (OOP) + Chapter 12 ($_SESSION for user id).
 */

class Favorite
{
    /** @var PDO */
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Toggle a favorite on/off for a user (AJAX endpoint uses this).
     * @return string 'added' or 'removed'
     */
    public function toggle($userId, $artworkId)
    {
        if ($this->exists($userId, $artworkId)) {
            $this->remove($userId, $artworkId);
            return 'removed';
        }
        $this->add($userId, $artworkId);
        return 'added';
    }

    /** @return bool */
    public function exists($userId, $artworkId)
    {
        $stmt = $this->db->prepare(
            'SELECT id FROM favorites WHERE user_id = :u AND artwork_id = :a LIMIT 1'
        );
        $stmt->execute([':u' => (int)$userId, ':a' => (int)$artworkId]);
        return (bool)$stmt->fetch();
    }

    /** @return bool */
    public function add($userId, $artworkId)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO favorites (user_id, artwork_id) VALUES (:u, :a)'
        );
        return $stmt->execute([':u' => (int)$userId, ':a' => (int)$artworkId]);
    }

    /** @return bool */
    public function remove($userId, $artworkId)
    {
        $stmt = $this->db->prepare(
            'DELETE FROM favorites WHERE user_id = :u AND artwork_id = :a'
        );
        return $stmt->execute([':u' => (int)$userId, ':a' => (int)$artworkId]);
    }

    /**
     * Get all favorite artworks for a user (with artist names).
     * @return array
     */
    public function getByUser($userId)
    {
        $sql = 'SELECT aw.*, a.last_name AS artist_last, a.first_name AS artist_first
                FROM favorites f
                JOIN artworks aw ON f.artwork_id = aw.id
                LEFT JOIN artists a ON aw.artist_id = a.id
                WHERE f.user_id = :u
                ORDER BY aw.title';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':u' => (int)$userId]);
        return $stmt->fetchAll();
    }
}
