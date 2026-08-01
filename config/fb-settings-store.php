<?php
/**
 * ZABIDA — Facebook app/page settings storage
 * Lets the Page ID, App ID, and App Secret be entered through the admin UI
 * instead of hand-editing config/secrets.php, and persists them to a
 * gitignored JSON file.
 */

define('FB_SETTINGS_STORE_PATH', __DIR__ . '/fb-settings.json');

function fb_settings_store_read(): array
{
    if (!file_exists(FB_SETTINGS_STORE_PATH)) {
        return [];
    }
    $raw  = file_get_contents(FB_SETTINGS_STORE_PATH);
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function fb_settings_store_write(array $settings): void
{
    $current = fb_settings_store_read();
    $merged  = array_merge($current, array_filter($settings, fn($v) => $v !== null && $v !== ''));
    file_put_contents(FB_SETTINGS_STORE_PATH, json_encode($merged, JSON_PRETTY_PRINT));
}

function fb_settings_get(string $key, string $default = ''): string
{
    $stored = fb_settings_store_read();
    return $stored[$key] ?? $default;
}