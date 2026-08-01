<?php
/**
 * ZABIDA — Facebook token storage
 * Long-lived Page tokens are read from / written to this JSON file so
 * run_facebook_sync() can persist a refreshed token without touching
 * secrets.php or requiring a manual re-paste each time.
 */

define('FB_TOKEN_STORE_PATH', __DIR__ . '/fb-token.json');

/**
 * Reads the currently stored token + metadata, if any.
 * Returns null if no token has been stored yet.
 */
function fb_token_store_read(): ?array
{
    if (!file_exists(FB_TOKEN_STORE_PATH)) {
        return null;
    }
    $raw = file_get_contents(FB_TOKEN_STORE_PATH);
    $data = json_decode($raw, true);
    return is_array($data) ? $data : null;
}

/**
 * Persists a token + metadata to disk.
 */
function fb_token_store_write(string $token, ?int $expiresAt, bool $valid = true): void
{
    file_put_contents(FB_TOKEN_STORE_PATH, json_encode([
        'access_token' => $token,
        'expires_at'   => $expiresAt, // unix timestamp, null = doesn't expire
        'valid'        => $valid,
        'updated_at'   => time(),
    ], JSON_PRETTY_PRINT));
}

/**
 * Marks the currently stored token as invalid (e.g. after a 190 error),
 * without deleting the file, so the admin page can show "needs re-login"
 * instead of "not configured at all".
 */
function fb_token_store_invalidate(): void
{
    $current = fb_token_store_read();
    if ($current) {
        $current['valid'] = false;
        file_put_contents(FB_TOKEN_STORE_PATH, json_encode($current, JSON_PRETTY_PRINT));
    }
}