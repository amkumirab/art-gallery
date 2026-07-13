<?php
/**
 * Review.php - Review class (user ratings + comments)
 * Chapter 13 (OOP) + Chapter 12 ($_SESSION for user id).
 */

class Review
{
    /** @var PDO */
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all reviews for an artwork (with reviewer username).
     * @return array
     */
    public function getByArtwork($artworkId)
    {
        $sql = 'SELECT r.*, u.username
                FROM reviews r
                JOIN users u ON r.user_id = u.id
                WHERE r.artwork_id = :a
                ORDER BY r.created_at DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':a' => (int)$artworkId]);
        return $stmt->fetchAll();
    }

    /**
     * Get all reviews (for admin moderation), with artwork title + username.
     * @return array
     */
    public function getAll()
    {
        $sql = 'SELECT r.*, u.username, aw.title AS artwork_title
                FROM reviews r
                JOIN users u ON r.user_id = u.id
                JOIN artworks aw ON r.artwork_id = aw.id
                ORDER BY r.created_at DESC';
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Add a new review.
     * @return bool
     */
    public function add($userId, $artworkId, $rating, $comment)
    {
        $rating = max(1, min(5, (int)$rating)); // clamp 1-5
        $stmt = $this->db->prepare(
            'INSERT INTO reviews (user_id, artwork_id, rating, comment)
             VALUES (:u, :a, :rating, :comment)'
        );
        return $stmt->execute([
            ':u'       => (int)$userId,
            ':a'       => (int)$artworkId,
            ':rating'  => $rating,
            ':comment' => trim($comment ?? ''),
        ]);
    }

    /**
     * Delete a review (admin moderation).
     * @return bool
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM reviews WHERE id = :id');
        return $stmt->execute([':id' => (int)$id]);
    }

    /**
     * Average rating for an artwork.
     * @return float|null
     */
    public function getAverageRating($artworkId)
    {
        $stmt = $this->db->prepare(
            'SELECT AVG(rating) FROM reviews WHERE artwork_id = :a'
        );
        $stmt->execute([':a' => (int)$artworkId]);
        $avg = $stmt->fetchColumn();
        return $avg !== null ? round((float)$avg, 1) : null;
    }

    /** @return int */
    public function count()
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM reviews')->fetchColumn();
    }
}
