<?php
// Simple test to debug the issue
echo "Newsletter test page works!<br>";
echo "Request method: " . $_SERVER['REQUEST_METHOD'] . "<br>";
echo "POST data: ";
print_r($_POST);
echo "<br>GET data: ";
print_r($_GET);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<br>✅ POST request received successfully!";
} else {
    echo "<br>❌ This was not a POST request";
}
?>