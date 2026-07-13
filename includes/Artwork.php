<?php
/**
 * Artwork.php - Artwork class (admin CRUD + public queries)
 *
 * This is the central "product" class the admin manages.
 * Chapter 13 (OOP): class, constructor, public/private, PDO.
 */

class Artwork
{
    /** @var PDO */
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all artworks joined with artist + category names.
     * Supports filtering by category, artist, and a search keyword.
     *
     * @param array $filters  keys: search, category_id, artist_id, sort
     * @return array
     */
    public function getAll($filters = [])
    {
        $sql = 'SELECT aw.*, a.last_name AS artist_last, a.first_name AS artist_first,
                       c.name AS category_name
                FROM artworks aw
                LEFT JOIN artists a   ON aw.artist_id = a.id
                LEFT JOIN categories c ON aw.category_id = c.id';

        $where  = [];
        $params = [];

        if (!empty($filters['search'])) {
            // جدا کردن پارامترها برای سازگاری با حالت native PDO
            $where[] = '(aw.title LIKE :search1 OR a.last_name LIKE :search2 OR a.first_name LIKE :search3)';
            $searchTerm = '%' . $filters['search'] . '%';
            $params[':search1'] = $searchTerm;
            $params[':search2'] = $searchTerm;
            $params[':search3'] = $searchTerm;
        }
        if (!empty($filters['category_id'])) {
            $where[] = 'aw.category_id = :cat';
            $params[':cat'] = (int)$filters['category_id'];
        }
        if (!empty($filters['artist_id'])) {
            $where[] = 'aw.artist_id = :art';
            $params[':art'] = (int)$filters['artist_id'];
        }

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        // Sorting
        $sort = $filters['sort'] ?? 'newest';
        switch ($sort) {
            case 'title':  $sql .= ' ORDER BY aw.title ASC';            break;
            case 'price_low':  $sql .= ' ORDER BY aw.price ASC';        break;
            case 'price_high': $sql .= ' ORDER BY aw.price DESC';       break;
            case 'oldest': $sql .= ' ORDER BY aw.year ASC';             break;
            case 'year_desc':  $sql .= ' ORDER BY aw.year DESC';        break;
            default:       $sql .= ' ORDER BY aw.created_at DESC';      break; // newest
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Get a single artwork by id, with artist + category.
     * @return array|false
     */
    public function getById($id)
    {
        $sql = 'SELECT aw.*, a.last_name AS artist_last, a.first_name AS artist_first,
                       a.biography AS artist_bio, a.birth_year AS artist_birth,
                       a.death_year AS artist_death, a.nationality AS artist_nat,
                       c.name AS category_name
                FROM artworks aw
                LEFT JOIN artists a   ON aw.artist_id = a.id
                LEFT JOIN categories c ON aw.category_id = c.id
                WHERE aw.id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => (int)$id]);
        return $stmt->fetch();
    }

    /**
     * Featured artworks for the home page.
     * @return array
     */
    public function getFeatured($limit = 6)
    {
        $sql = 'SELECT aw.*, a.last_name AS artist_last, a.first_name AS artist_first
                FROM artworks aw
                LEFT JOIN artists a ON aw.artist_id = a.id
                WHERE aw.is_featured = 1
                ORDER BY aw.created_at DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Recent artworks (for admin dashboard).
    /**
     * Recent artworks (for admin dashboard).
     * @return array
     */
    public function getRecent($limit = 5)
    {
        // اضافه شدن نام کوچک هنرمند به کوئری جهت رفع ارور خط ۱۱۹ پنل ادمین
        $sql = 'SELECT aw.*, a.last_name AS artist_last, a.first_name AS artist_first
                FROM artworks aw
                LEFT JOIN artists a ON aw.artist_id = a.id
                ORDER BY aw.created_at DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    /**
     * Create a new artwork (admin).
     * @return int  new artwork id
     */
    public function create($data)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO artworks
                (title, artist_id, category_id, year, medium, dimensions,
                 description, price, image_filename, is_featured)
             VALUES
                (:title, :artist_id, :category_id, :year, :medium, :dimensions,
                 :description, :price, :image_filename, :is_featured)'
        );
        $stmt->execute([
            ':title'          => trim($data['title'] ?? ''),
            ':artist_id'      => !empty($data['artist_id']) ? (int)$data['artist_id'] : null,
            ':category_id'    => !empty($data['category_id']) ? (int)$data['category_id'] : null,
            ':year'           => $data['year'] !== '' ? (int)$data['year'] : null,
            ':medium'         => trim($data['medium'] ?? ''),
            ':dimensions'     => trim($data['dimensions'] ?? ''),
            ':description'    => trim($data['description'] ?? ''),
            ':price'          => $data['price'] !== '' ? (float)$data['price'] : null,
            ':image_filename' => $data['image_filename'] ?? 'placeholder.jpg',
            ':is_featured'    => !empty($data['is_featured']) ? 1 : 0,
        ]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Update an existing artwork (admin).
     * @return bool
     */
    public function update($id, $data)
    {
        $stmt = $this->db->prepare(
            'UPDATE artworks SET
                title          = :title,
                artist_id      = :artist_id,
                category_id    = :category_id,
                year           = :year,
                medium         = :medium,
                dimensions     = :dimensions,
                description    = :description,
                price          = :price,
                image_filename = :image_filename,
                is_featured    = :is_featured
             WHERE id = :id'
        );
        return $stmt->execute([
            ':title'          => trim($data['title'] ?? ''),
            ':artist_id'      => !empty($data['artist_id']) ? (int)$data['artist_id'] : null,
            ':category_id'    => !empty($data['category_id']) ? (int)$data['category_id'] : null,
            ':year'           => $data['year'] !== '' ? (int)$data['year'] : null,
            ':medium'         => trim($data['medium'] ?? ''),
            ':dimensions'     => trim($data['dimensions'] ?? ''),
            ':description'    => trim($data['description'] ?? ''),
            ':price'          => $data['price'] !== '' ? (float)$data['price'] : null,
            ':image_filename' => $data['image_filename'] ?? 'placeholder.jpg',
            ':is_featured'    => !empty($data['is_featured']) ? 1 : 0,
            ':id'             => (int)$id,
        ]);
    }

    /**
     * Delete an artwork (admin).
     * @return bool
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM artworks WHERE id = :id');
        return $stmt->execute([':id' => (int)$id]);
    }

    /** @return int */
    public function count()
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM artworks')->fetchColumn();
    }
}
