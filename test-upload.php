<?php
// Simple upload test script
echo "<h2>Photo Upload Diagnostic</h2>";

// Test directory permissions
$upload_dir = 'uploads/contest_photos/';
echo "<h3>Directory Check:</h3>";
echo "Upload directory: " . realpath($upload_dir) . "<br>";
echo "Directory exists: " . (is_dir($upload_dir) ? 'YES' : 'NO') . "<br>";
echo "Directory writable: " . (is_writable($upload_dir) ? 'YES' : 'NO') . "<br>";
echo "Directory permissions: " . substr(sprintf('%o', fileperms($upload_dir)), -4) . "<br>";

// Test PHP upload settings
echo "<h3>PHP Upload Settings:</h3>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";
echo "max_file_uploads: " . ini_get('max_file_uploads') . "<br>";
echo "file_uploads enabled: " . (ini_get('file_uploads') ? 'YES' : 'NO') . "<br>";
echo "upload_tmp_dir: " . (ini_get('upload_tmp_dir') ?: 'default') . "<br>";

// Test if we can create a file
echo "<h3>Write Test:</h3>";
$test_file = $upload_dir . 'test_write.txt';
$write_result = file_put_contents($test_file, 'test');
if ($write_result) {
    echo "Write test: SUCCESS<br>";
    unlink($test_file); // clean up
} else {
    echo "Write test: FAILED<br>";
}

// Show current working directory
echo "<h3>Path Info:</h3>";
echo "Current working directory: " . getcwd() . "<br>";
echo "Script directory: " . __DIR__ . "<br>";
echo "Full upload path: " . realpath($upload_dir ?: '.') . "<br>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_photo'])) {
    echo "<h3>Upload Test Results:</h3>";
    echo "File uploaded: " . (!empty($_FILES['test_photo']['name']) ? 'YES' : 'NO') . "<br>";
    echo "Error code: " . $_FILES['test_photo']['error'] . "<br>";
    echo "File size: " . $_FILES['test_photo']['size'] . " bytes<br>";
    echo "File type: " . $_FILES['test_photo']['type'] . "<br>";
    echo "Temp file: " . $_FILES['test_photo']['tmp_name'] . "<br>";
    echo "Temp file exists: " . (file_exists($_FILES['test_photo']['tmp_name']) ? 'YES' : 'NO') . "<br>";
    
    if ($_FILES['test_photo']['error'] === UPLOAD_ERR_OK) {
        $filename = 'test_' . uniqid() . '.jpg';
        $destination = $upload_dir . $filename;
        
        echo "Attempting to move to: $destination<br>";
        
        if (move_uploaded_file($_FILES['test_photo']['tmp_name'], $destination)) {
            echo "<strong>SUCCESS: File uploaded successfully!</strong><br>";
            echo "File saved to: $destination<br>";
            echo "File size on disk: " . filesize($destination) . " bytes<br>";
        } else {
            echo "<strong>FAILED: move_uploaded_file() returned false</strong><br>";
            $error = error_get_last();
            if ($error) {
                echo "Last error: " . $error['message'] . "<br>";
            }
        }
    } else {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'Upload stopped by extension'
        ];
        echo "Upload error: " . ($errors[$_FILES['test_photo']['error']] ?? 'Unknown error') . "<br>";
    }
}
?>

<form method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
    <h3>Test Photo Upload:</h3>
    <input type="file" name="test_photo" accept="image/*" required>
    <button type="submit">Test Upload</button>
</form>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2, h3 { color: #333; }
</style>