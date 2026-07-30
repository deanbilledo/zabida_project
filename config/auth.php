<?php
/**
 * ZABIDA — Admin authentication
 * Single shared admin account for now. Swap ADMIN_PASS_HASH for a real
 * hash (password_hash()) and move both values to environment variables
 * before deploying.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('ADMIN_USERNAME', getenv('ZABIDA_ADMIN_USER') ?: 'admin');
// Default password is "changeme" — replace before going live.
define('ADMIN_PASS_HASH', getenv('ZABIDA_ADMIN_HASH') ?: '$2y$10$Hr5WnRUVHb9Tytaxx4ea7eElUrvPEUiqoPYJdzCmJBEULFydvb6e2');

function is_logged_in(): bool
{
    return !empty($_SESSION['zabida_admin']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: /admin/login.php');
        exit;
    }
}

function attempt_login(string $username, string $password): bool
{
    if ($username === ADMIN_USERNAME && password_verify($password, ADMIN_PASS_HASH)) {
        $_SESSION['zabida_admin'] = $username;
        session_regenerate_id(true);
        return true;
    }
    return false;
}

function logout(): void
{
    $_SESSION = [];
    session_destroy();
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_check(string $token): bool
{
    return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
