<?php
/**
 * Artist.php - Artist class (admin CRUD + public listing)
 * Chapter 13 (OOP): class, constructor, PDO prepared statements.
 */

class Artist
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
        return $this->db->query(
            'SELECT a.*, COUNT(aw.id) AS artwork_count
             FROM artists a
             LEFT JOIN artworks aw ON aw.artist_id = a.id
             GROUP BY a.id
             ORDER BY a.last_name'
        )->fetchAll();
    }

    /** @return array|false */
    public function getById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM artists WHERE id = :id');
        $stmt->execute([':id' => (int)$id]);
        return $stmt->fetch();
    }

    /** @return array */
    public function getByIdWithArtworks($id)
    {
        $artist = $this->getById($id);
        if (!$artist) {
            return false;
        }
        $stmt = $this->db->prepare('SELECT * FROM artworks WHERE artist_id = :id ORDER BY year');
        $stmt->execute([':id' => (int)$id]);
        $artist['artworks'] = $stmt->fetchAll();
        return $artist;
    }

    /** @return bool */
    public function create($data)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO artists (first_name, last_name, birth_year, death_year, nationality, biography)
             VALUES (:first, :last, :birth, :death, :nat, :bio)'
        );
        return $stmt->execute([
            ':first' => trim($data['first_name'] ?? ''),
            ':last'  => trim($data['last_name'] ?? ''),
            ':birth' => $data['birth_year'] !== '' ? (int)$data['birth_year'] : null,
            ':death' => $data['death_year'] !== '' ? (int)$data['death_year'] : null,
            ':nat'   => trim($data['nationality'] ?? ''),
            ':bio'   => trim($data['biography'] ?? ''),
        ]);
    }

    /** @return bool */
    public function update($id, $data)
    {
        $stmt = $this->db->prepare(
            'UPDATE artists
             SET first_name = :first, last_name = :last, birth_year = :birth,
                 death_year = :death, nationality = :nat, biography = :bio
             WHERE id = :id'
        );
        return $stmt->execute([
            ':first' => trim($data['first_name'] ?? ''),
            ':last'  => trim($data['last_name'] ?? ''),
            ':birth' => $data['birth_year'] !== '' ? (int)$data['birth_year'] : null,
            ':death' => $data['death_year'] !== '' ? (int)$data['death_year'] : null,
            ':nat'   => trim($data['nationality'] ?? ''),
            ':bio'   => trim($data['biography'] ?? ''),
            ':id'    => (int)$id,
        ]);
    }

    /** @return bool */
    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM artists WHERE id = :id');
        return $stmt->execute([':id' => (int)$id]);
    }

    /** @return int */
    public function count()
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM artists')->fetchColumn();
    }
}
