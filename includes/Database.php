<?php
/**
 * Database.php - Singleton PDO connection wrapper
 *
 * Demonstrates Chapter 13 (OOP): class, private/public visibility,
 * constructor, static property, $this, instantiation with new.
 */

require_once __DIR__ . '/config.php';

class Database
{
    /**
     * Hold the single PDO instance (singleton pattern).
     * @var PDO|null
     */
    private static $instance = null;

    /**
     * Private constructor prevents direct creation with new Database().
     * Use Database::getInstance() instead.
     */
    private function __construct()
    {
        // Build DSN — use DB_PORT if defined, otherwise default to 3306
        $port = defined('DB_PORT') ? DB_PORT : 3307;
        $dsn = 'mysql:host=' . DB_HOST . ';port=' . $port . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
        }
    }

    /**
     * Get the shared PDO connection.
     * @return PDO
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            new Database(); // constructor sets self::$instance
        }
        return self::$instance;
    }
}
