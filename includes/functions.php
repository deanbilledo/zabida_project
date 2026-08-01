<?php
/**
 * ZABIDA — Shared helper functions
 * Posts are read from and saved to MySQL when available (see config/database.php),
 * and fall back to the flat-file JSON store in /database/posts.json
 * so the site remains functional even without an active database connection.
 */

// MUST BE HERE: Load database config so get_db() is available
require_once __DIR__ . '/../config/database.php';

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
    $pdo = get_db();
    if ($pdo) {
        try {
            $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = :id LIMIT 1');
            $stmt->execute([':id' => $id]);
            $post = $stmt->fetch();
            if ($post) {
                return $post;
            }
        } catch (PDOException $e) {
            error_log('ZABIDA get_post_by_id DB error: ' . $e->getMessage());
        }
    }

    foreach (read_posts_store() as $post) {
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
    $pdo = get_db();

    // 1. Primary: Save to MySQL database
    if ($pdo) {
        try {
            $stmt = $pdo->prepare('
                INSERT INTO posts (title, excerpt, body, image, source, published_at, facebook_post_id)
                VALUES (:title, :excerpt, :body, :image, :source, :published_at, :facebook_post_id)
            ');

            $stmt->execute([
                ':title'            => $fields['title'],
                ':excerpt'          => $fields['excerpt'],
                ':body'             => $fields['body'] ?? $fields['excerpt'],
                ':image'            => $fields['image'] ?? 'assets/images/zabida_logo.png',
                ':source'           => $fields['source'] ?? 'manual',
                ':published_at'     => $fields['published_at'] ?? date('Y-m-d'),
                ':facebook_post_id' => $fields['facebook_post_id'] ?? null,
            ]);

            return [
                'id'               => (int)$pdo->lastInsertId(),
                'title'            => $fields['title'],
                'excerpt'          => $fields['excerpt'],
                'body'             => $fields['body'] ?? $fields['excerpt'],
                'image'            => $fields['image'] ?? 'assets/images/zabida_logo.png',
                'source'           => $fields['source'] ?? 'manual',
                'published_at'     => $fields['published_at'] ?? date('Y-m-d'),
                'facebook_post_id' => $fields['facebook_post_id'] ?? null,
            ];
        } catch (PDOException $e) {
            error_log('ZABIDA create_post_record DB error: ' . $e->getMessage());
        }
    }

    // 2. Fallback: Save to JSON store
    $posts = read_posts_store();
    $post = [
        'id'               => next_post_id($posts),
        'title'            => $fields['title'],
        'excerpt'          => $fields['excerpt'],
        'body'             => $fields['body'] ?? $fields['excerpt'],
        'image'            => $fields['image'] ?? 'assets/images/zabida_logo.png',
        'source'           => $fields['source'] ?? 'manual',
        'published_at'     => $fields['published_at'] ?? date('Y-m-d'),
        'facebook_post_id' => $fields['facebook_post_id'] ?? null,
    ];
    $posts[] = $post;
    write_posts_store($posts);
    return $post;
}

function update_post_record(int $id, array $fields): bool
{
    $pdo = get_db();
    if ($pdo) {
        try {
            $stmt = $pdo->prepare('
                UPDATE posts 
                SET title = :title, excerpt = :excerpt, body = :body, image = :image, published_at = :published_at 
                WHERE id = :id
            ');
            return $stmt->execute([
                ':id'           => $id,
                ':title'        => $fields['title'],
                ':excerpt'      => $fields['excerpt'],
                ':body'         => $fields['body'] ?? $fields['excerpt'],
                ':image'        => $fields['image'] ?? 'assets/images/zabida_logo.png',
                ':published_at' => $fields['published_at'] ?? date('Y-m-d'),
            ]);
        } catch (PDOException $e) {
            error_log('ZABIDA update_post_record DB error: ' . $e->getMessage());
        }
    }

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
    $pdo = get_db();
    if ($pdo) {
        try {
            $stmt = $pdo->prepare('DELETE FROM posts WHERE id = :id');
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log('ZABIDA delete_post_record DB error: ' . $e->getMessage());
        }
    }

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

/**
 * Returns true if a post with this facebook_post_id already exists,
 * checking MySQL if available, otherwise the JSON store.
 */
function post_exists_by_facebook_id(string $fbPostId): bool
{
    $pdo = get_db();
    if ($pdo) {
        try {
            $stmt = $pdo->prepare('SELECT id FROM posts WHERE facebook_post_id = :fbid LIMIT 1');
            $stmt->execute([':fbid' => $fbPostId]);
            if ($stmt->fetch()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log('ZABIDA post_exists_by_facebook_id DB error: ' . $e->getMessage());
        }
    }

    foreach (read_posts_store() as $post) {
        if (($post['facebook_post_id'] ?? null) === $fbPostId) {
            return true;
        }
    }
    return false;
}