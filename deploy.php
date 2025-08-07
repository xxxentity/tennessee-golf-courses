<?php
// Simple deployment script to force update
if ($_GET['key'] !== 'deploy123') {
    die('Unauthorized');
}

$commands = [
    'cd ' . __DIR__,
    'git reset --hard HEAD',
    'git clean -fd',
    'git pull origin main'
];

echo "<pre>";
foreach ($commands as $cmd) {
    echo "Running: $cmd\n";
    $output = shell_exec($cmd . ' 2>&1');
    echo $output . "\n";
}
echo "</pre>";
?>