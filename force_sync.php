<?php
/**
 * Force Git Sync Script - Emergency server sync
 * This will force the server to match GitHub exactly
 */

echo "Starting emergency git sync...\n";

// Force fetch from origin
exec('git fetch origin 2>&1', $output1, $return1);
echo "Fetch result:\n" . implode("\n", $output1) . "\n\n";

// Hard reset to match origin/main exactly
exec('git reset --hard origin/main 2>&1', $output2, $return2);
echo "Reset result:\n" . implode("\n", $output2) . "\n\n";

// Clean any untracked files
exec('git clean -fd 2>&1', $output3, $return3);
echo "Clean result:\n" . implode("\n", $output3) . "\n\n";

if ($return2 === 0) {
    echo "✅ SUCCESS: Server now matches GitHub exactly\n";
    echo "The belmont article should now work properly.\n";
} else {
    echo "❌ ERROR: Git reset failed\n";
}

// Show current status
exec('git status --porcelain 2>&1', $output4, $return4);
if (empty($output4)) {
    echo "✅ Working directory is clean\n";
} else {
    echo "⚠️ Working directory status:\n" . implode("\n", $output4) . "\n";
}
?>