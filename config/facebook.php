<?php
/**
 * ZABIDA — Facebook page sync configuration
 * Used to mirror posts from facebook.com/zabidadotorg into the Journal
 * section. Requires a Page Access Token with pages_read_engagement.
 * https://developers.facebook.com/docs/graph-api/
 */

if (file_exists(__DIR__ . '/secrets.php')) {
    require __DIR__ . '/secrets.php';
}
require __DIR__ . '/fb-token-store.php';

define('FB_PAGE_ID', getenv('ZABIDA_FB_PAGE_ID') ?: '');
define('FB_APP_ID', getenv('ZABIDA_FB_APP_ID') ?: '');
define('FB_APP_SECRET', getenv('ZABIDA_FB_APP_SECRET') ?: '');
define('FB_GRAPH_VERSION', 'v19.0');
define('FB_GRAPH_BASE', 'https://graph.facebook.com/' . FB_GRAPH_VERSION);

/**
 * Returns the active Page Access Token, preferring the refreshed one in
 * fb-token.json over the initial value in secrets.php/env.
 */
function facebook_get_token(): string
{
    $stored = fb_token_store_read();
    if ($stored && !empty($stored['valid']) && !empty($stored['access_token'])) {
        return $stored['access_token'];
    }
    return getenv('ZABIDA_FB_PAGE_TOKEN') ?: '';
}

/**
 * Whether sync can actually run — false until a real, valid token exists.
 */
function facebook_sync_ready(): bool
{
    return facebook_get_token() !== '';
}

/**
 * True if the last known token was explicitly marked invalid
 * (e.g. after a 190 error), as opposed to simply never configured.
 */
function facebook_token_needs_reauth(): bool
{
    $stored = fb_token_store_read();
    return $stored !== null && empty($stored['valid']);
}