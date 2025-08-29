<?php
// Force no cache with multiple headers
header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0, private');
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CACHE BUSTED - <?php echo time(); ?></title>
</head>
<body>
    <h1>CACHE BUSTED VERSION - <?php echo date('Y-m-d H:i:s'); ?></h1>
    <p>Random number: <?php echo rand(1000, 9999); ?></p>
    <p>Timestamp: <?php echo time(); ?></p>
    <p>If you see this message with a current timestamp, the file is updating correctly.</p>
    <p>If you still see the old content, there's aggressive server-side caching beyond our control.</p>
</body>
</html>