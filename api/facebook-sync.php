<?php
/**
 * ZABIDA — Facebook page sync
 * Defines run_facebook_sync(), used by admin/sync-facebook.php and
 * scheduler/facebook-sync.php. When this file is hit directly as an
 * HTTP endpoint it also runs the sync and returns JSON.
 */

require_once __DIR__ . '/../config/facebook.php';
require_once __DIR__ . '/../includes/functions.php';

function run_facebook_sync(): array
{
    if (!facebook_sync_ready()) {
        return ['ok' => false, 'message' => 'No Facebook Page Access Token configured — set ZABIDA_FB_PAGE_TOKEN.', 'imported' => 0];
    }

    $url = FB_GRAPH_BASE . '/' . FB_PAGE_ID . '/posts'
         . '?fields=id,message,created_time,full_picture'
         . '&access_token=' . urlencode(FB_PAGE_ACCESS_TOKEN);

    $response = @file_get_contents($url);
    if ($response === false) {
        return ['ok' => false, 'message' => 'Could not reach the Facebook Graph API.', 'imported' => 0];
    }

    $data = json_decode($response, true);
    if (empty($data['data'])) {
        return ['ok' => false, 'message' => 'No posts returned from Facebook.', 'imported' => 0];
    }

    $existing = read_posts_store();
    $existingFbIds = array_column($existing, 'source_id');
    $imported = 0;

    foreach ($data['data'] as $fbPost) {
        if (empty($fbPost['message']) || in_array($fbPost['id'], $existingFbIds, true)) {
            continue;
        }
        create_post_record([
            'title'        => mb_substr($fbPost['message'], 0, 80),
            'excerpt'      => mb_substr($fbPost['message'], 0, 240),
            'body'         => $fbPost['message'],
            'image'        => $fbPost['full_picture'] ?? 'assets/images/zabida_logo.png',
            'published_at' => date('Y-m-d', strtotime($fbPost['created_time'])),
            'source'       => 'facebook',
        ]);
        $imported++;
    }

    return ['ok' => true, 'message' => "Sync complete — imported {$imported} new post(s).", 'imported' => $imported];
}

// If hit directly as an HTTP endpoint (not included by another script), run it.
if (basename($_SERVER['SCRIPT_FILENAME'] ?? '') === basename(__FILE__)) {
    header('Content-Type: application/json');
    echo json_encode(run_facebook_sync());
}
