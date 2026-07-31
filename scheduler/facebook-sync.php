<?php
/**
 * ZABIDA — Scheduled Facebook sync (CLI)
 * Intended to run via cron, e.g. daily at 6am:
 *   0 6 * * *  php /path/to/zabida-project/scheduler/facebook-sync.php >> /var/log/zabida-sync.log 2>&1
 */

if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit('This script is intended for CLI/cron use only.');
}

require __DIR__ . '/../config/facebook.php';
require __DIR__ . '/../config/database.php';
require __DIR__ . '/../includes/functions.php';
require __DIR__ . '/../api/facebook-sync.php'; // defines run_facebook_sync()

$result = run_facebook_sync();

printf(
    "[%s] %s\n",
    date('Y-m-d H:i:s'),
    $result['message']
);

exit($result['ok'] ? 0 : 1);