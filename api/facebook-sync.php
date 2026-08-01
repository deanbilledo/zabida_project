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
        $msg = facebook_token_needs_reauth()
            ? 'Your Facebook token has expired or been revoked. Please re-authenticate below.'
            : 'No Facebook Page Access Token configured.';
        return ['ok' => false, 'message' => $msg, 'needs_reauth' => facebook_token_needs_reauth()];
    }

    $token = facebook_get_token();

    $url = FB_GRAPH_BASE . '/' . FB_PAGE_ID . '/posts?' . http_build_query([
        'fields'       => 'id,message,created_time,full_picture,permalink_url',
        'access_token' => $token,
        'limit'        => 25,
    ]);

    $response = fb_graph_get($url);

    if (!$response['ok']) {
        if ($response['code'] === 190) {
            fb_token_store_invalidate();
            return [
                'ok'           => false,
                'message'      => 'Facebook access token expired or was revoked. Please re-authenticate below to resume syncing.',
                'needs_reauth' => true,
            ];
        }
        return ['ok' => false, 'message' => 'Facebook API error: ' . $response['error']];
    }

    $data = $response['data'];

    $imported = 0;
    $skipped  = 0;

    foreach ($data['data'] ?? [] as $fbPost) {
        if (empty($fbPost['message'])) {
            continue;
        }

        if (post_exists_by_facebook_id($fbPost['id'])) {
            $skipped++;
            continue;
        }

        $image = null;
        if (!empty($fbPost['full_picture'])) {
            $image = download_facebook_image($fbPost['full_picture'], $fbPost['id']);
        }

        create_post_record([
            'title'            => mb_substr(strtok($fbPost['message'], "\n"), 0, 120),
            'excerpt'          => mb_substr($fbPost['message'], 0, 200),
            'body'             => $fbPost['message'],
            'image'            => $image ?? 'assets/images/zabida_logo.png',
            'source'           => 'facebook',
            'published_at'     => date('Y-m-d', strtotime($fbPost['created_time'])),
            'facebook_post_id' => $fbPost['id'],
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