<?php
/**
 * ZABIDA — Database configuration
 * Fill in real credentials when deploying. Falls back to the flat-file
 * JSON store in /database/posts.json when no DB connection is available,
 * so the site keeps working during local development.
 */

define('DB_HOST', getenv('ZABIDA_DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('ZABIDA_DB_NAME') ?: 'zabida');
define('DB_USER', getenv('ZABIDA_DB_USER') ?: 'root');
define('DB_PASS', getenv('ZABIDA_DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

/**
 * Returns a PDO connection, or null if the database is unreachable.
 * Callers should fall back to get_posts_store() in includes/functions.php.
 */
function get_db(): ?PDO
{
    static $pdo = null;
    static $attempted = false;

    if ($pdo !== null) {
        return $pdo;
    }
    if ($attempted) {
        return null;
    }
    $attempted = true;

    try {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        error_log('ZABIDA DB connection failed: ' . $e->getMessage());
        return null;
    }
}
