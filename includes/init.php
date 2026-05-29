<?php
require_once __DIR__ . '/performance.php';
Performance::start();
Performance::enableCompression();

require_once __DIR__ . '/database-cache.php';
require_once __DIR__ . '/../config/database.php';

$dbCache = new DatabaseCache($pdo);

if (!headers_sent()) {
    Performance::sendPerformanceHeaders();
}
?>
