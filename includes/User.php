<?php
/**
 * User.php - User class for registration & authentication
 *
 * Demonstrates Chapter 13 (OOP): class, constructor, public methods,
 * encapsulation (private $db), and Chapter 12 ($_SESSION).
 */

class User
{
    /** @var PDO */
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Register a new user account.
     * @param array $data  keys: username, email, password
     * @return array  ['success' => bool, 'errors' => string[]]
     */
    public function register($data)
    {
        $errors   = [];
        $username = trim($data['username'] ?? '');
        $email    = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        // --- Validation ---
        if ($username === '') {
            $errors[] = 'Username is required.';
        } elseif (strlen($username) < 3) {
            $errors[] = 'Username must be at least 3 characters.';
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email is required.';
        }
        if (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters.';
        }

        // --- Uniqueness checks ---
        if ($username !== '' && $this->findByUsername($username)) {
            $errors[] = 'That username is already taken.';
        }
        if ($email !== '' && $this->findByEmail($email)) {
            $errors[] = 'That email is already registered.';
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // --- Insert (password_hash for security) ---
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare(
            'INSERT INTO users (username, email, password, role)
             VALUES (:username, :email, :password, "user")'
        );
        $stmt->execute([
            ':username' => $username,
            ':email'    => $email,
            ':password' => $hash,
        ]);

        return ['success' => true, 'id' => (int)$this->db->lastInsertId()];
    }

    /**
     * Attempt to log in a user by username + password.
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function login($username, $password)
    {
        $user = $this->findByUsername($username);
        if (!$user) {
            return false;
        }
        if (password_verify($password, $user['password'])) {
            // Persist identity in the session (Chapter 12 - $_SESSION)
            $_SESSION['user_id']  = (int)$user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];
            return true;
        }
        return false;
    }

    /**
     * Log out the current user.
     */
    public function logout()
    {
        session_unset();
        session_destroy();
    }

    /**
     * Look up a user by username.
     * @return array|false
     */
    public function findByUsername($username)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :u LIMIT 1');
        $stmt->execute([':u' => $username]);
        return $stmt->fetch();
    }

    /**
     * Look up a user by email.
     * @return array|false
     */
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :e LIMIT 1');
        $stmt->execute([':e' => $email]);
        return $stmt->fetch();
    }

    /**
     * Get a user by id.
     * @return array|false
     */
    public function getById($id)
    {
        $stmt = $this->db->prepare('SELECT id, username, email, role, created_at FROM users WHERE id = :id');
        $stmt->execute([':id' => (int)$id]);
        return $stmt->fetch();
    }

    /**
     * Get all users (for the admin panel).
     * @return array
     */
    public function getAll()
    {
        return $this->db->query(
            'SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC'
        )->fetchAll();
    }

    /**
     * Toggle a user's role between 'user' and 'admin'.
     */
    public function toggleRole($id)
    {
        $user = $this->getById($id);
        if (!$user) {
            return false;
        }
        $newRole = ($user['role'] === 'admin') ? 'user' : 'admin';
        $stmt = $this->db->prepare('UPDATE users SET role = :role WHERE id = :id');
        return $stmt->execute([':role' => $newRole, ':id' => (int)$id]);
    }

    /**
     * Delete a user account.
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
        return $stmt->execute([':id' => (int)$id]);
    }

    /**
     * Count total users (for the dashboard stats).
     * @return int
     */
    public function count()
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }
}
