<?php
/**
 * ZABIDA — Facebook page sync configuration
 * Used to mirror posts from facebook.com/zabidadotorg into the Journal
 * section. Requires a Page Access Token with pages_read_engagement.
 * https://developers.facebook.com/docs/graph-api/
 */

define('FB_PAGE_ID', getenv('ZABIDA_FB_PAGE_ID') ?: 'zabidadotorg');
define('FB_PAGE_ACCESS_TOKEN', getenv('ZABIDA_FB_PAGE_TOKEN') ?: '');
define('FB_GRAPH_VERSION', 'v19.0');
define('FB_GRAPH_BASE', 'https://graph.facebook.com/' . FB_GRAPH_VERSION);

/**
 * Whether sync can actually run — false until a real token is configured.
 */
function facebook_sync_ready(): bool
{
    return FB_PAGE_ACCESS_TOKEN !== '';
}
