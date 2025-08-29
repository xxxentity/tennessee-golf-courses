<?php
// Force no caching
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cache Test</title>
</head>
<body>
    <h1>CACHE BYPASS TEST - <?php echo date('Y-m-d H:i:s'); ?></h1>
    <p>Current time: <?php echo time(); ?></p>
    <p>If the time changes when you refresh, caching is working correctly.</p>
    <p>If it doesn't change, there's aggressive caching happening.</p>
</body>
</html>