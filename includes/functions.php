<?php
/**
 * ZABIDA — Shared helper functions
 * Posts are read from MySQL when available (see config/database.php),
 * and fall back to the flat-file JSON store in /database/posts.json
 * so the site is fully functional before a database is provisioned.
 */

define('POSTS_STORE', __DIR__ . '/../database/posts.json');

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function get_all_posts(): array
{
    $pdo = get_db();
    if ($pdo) {
        try {
            $stmt = $pdo->query('SELECT * FROM posts ORDER BY published_at DESC');
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log('ZABIDA get_all_posts DB error: ' . $e->getMessage());
        }
    }
    return read_posts_store();
}

function get_post_by_id(int $id): ?array
{
    foreach (get_all_posts() as $post) {
        if ((int)$post['id'] === $id) {
            return $post;
        }
    }
    return null;
}

function read_posts_store(): array
{
    if (!file_exists(POSTS_STORE)) {
        return [];
    }
    $json = file_get_contents(POSTS_STORE);
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

function write_posts_store(array $posts): bool
{
    return file_put_contents(POSTS_STORE, json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) !== false;
}

function next_post_id(array $posts): int
{
    $max = 0;
    foreach ($posts as $post) {
        $max = max($max, (int)$post['id']);
    }
    return $max + 1;
}

function create_post_record(array $fields): array
{
    $posts = read_posts_store();
    $post = [
        'id'            => next_post_id($posts),
        'title'         => $fields['title'],
        'excerpt'       => $fields['excerpt'],
        'body'          => $fields['body'] ?? $fields['excerpt'],
        'image'         => $fields['image'] ?? 'assets/images/zabida_logo.png',
        'source'        => $fields['source'] ?? 'manual',
        'published_at'  => $fields['published_at'] ?? date('Y-m-d'),
    ];
    $posts[] = $post;
    write_posts_store($posts);
    return $post;
}

function update_post_record(int $id, array $fields): bool
{
    $posts = read_posts_store();
    foreach ($posts as &$post) {
        if ((int)$post['id'] === $id) {
            $post = array_merge($post, $fields);
            write_posts_store($posts);
            return true;
        }
    }
    return false;
}

function delete_post_record(int $id): bool
{
    $posts = read_posts_store();
    $filtered = array_values(array_filter($posts, fn($p) => (int)$p['id'] !== $id));
    if (count($filtered) === count($posts)) {
        return false;
    }
    return write_posts_store($filtered);
}

function format_post_date(string $date): string
{
    $ts = strtotime($date);
    return $ts ? date('M Y', $ts) : $date;
}
