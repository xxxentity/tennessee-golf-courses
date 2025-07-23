<?php
echo "This is simple-test.php";
echo "<br>Current URL: " . $_SERVER['REQUEST_URI'];
echo "<br>Script Name: " . $_SERVER['SCRIPT_NAME'];
echo "<br><a href='/index.php'>Go to index.php</a>";
echo "<br><a href='/courses.php'>Go to courses.php</a>";
?>