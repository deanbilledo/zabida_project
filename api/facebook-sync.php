<?php
/**
 * ZABIDA — Facebook → Journal sync logic
 * Requires config/facebook.php and config/database.php to already be loaded
 * by the caller (admin page or CLI scheduler both do this).
 */

function fb_graph_get(string $url): array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);

    $body    = curl_exec($ch);
    $errno   = curl_errno($ch);
    $error   = curl_error($ch);
    $status  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($errno) {
        return ['ok' => false, 'error' => "cURL error ({$errno}): {$error}"];
    }

    $data = json_decode($body, true);

    if ($status !== 200) {
        $msg = $data['error']['message'] ?? "HTTP {$status} with no error body";
        return ['ok' => false, 'error' => $msg];
    }

    return ['ok' => true, 'data' => $data];
}

function run_facebook_sync(): array
{
    if (!facebook_sync_ready()) {
        return ['ok' => false, 'message' => 'No Facebook Page Access Token configured.'];
    }

    $url = FB_GRAPH_BASE . '/' . FB_PAGE_ID . '/posts?' . http_build_query([
        'fields'       => 'id,message,created_time,full_picture,permalink_url',
        'access_token' => FB_PAGE_ACCESS_TOKEN,
        'limit'        => 25,
    ]);

    $response = fb_graph_get($url);
    if (!$response['ok']) {
        return ['ok' => false, 'message' => 'Facebook API error: ' . $response['error']];
    }

    $data = $response['data'];

    $imported = 0;
    $skipped  = 0;
    $pdo      = get_db(); // adjust to however database.php exposes the connection

    foreach ($data['data'] ?? [] as $fbPost) {
        if (empty($fbPost['message'])) {
            continue;
        }

        $stmt = $pdo->prepare('SELECT id FROM posts WHERE facebook_post_id = ?');
        $stmt->execute([$fbPost['id']]);
        if ($stmt->fetch()) {
            $skipped++;
            continue;
        }

        $title   = mb_substr(strtok($fbPost['message'], "\n"), 0, 120);
        $excerpt = mb_substr($fbPost['message'], 0, 200);
        $body    = $fbPost['message'];
        $image   = null;

        if (!empty($fbPost['full_picture'])) {
            $image = download_facebook_image($fbPost['full_picture'], $fbPost['id']);
        }

        $insert = $pdo->prepare(
            'INSERT INTO posts (title, excerpt, body, image, published_at, facebook_post_id, source)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $insert->execute([
            $title,
            $excerpt,
            $body,
            $image,
            date('Y-m-d H:i:s', strtotime($fbPost['created_time'])),
            $fbPost['id'],
            'facebook',
        ]);

        $imported++;
    }

    return [
        'ok'      => true,
        'message' => "Synced: {$imported} new post(s) imported, {$skipped} already existed.",
    ];
}

function download_facebook_image(string $remoteUrl, string $fbPostId): ?string
{
    $ch = curl_init($remoteUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
    ]);
    $bytes = curl_exec($ch);
    curl_close($ch);

    if ($bytes === false) {
        return null;
    }

    $dir = __DIR__ . '/../assets/images/journal';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $filename = 'fb_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $fbPostId) . '.jpg';
    $path     = $dir . '/' . $filename;
    file_put_contents($path, $bytes);

    return 'assets/images/journal/' . $filename;
}