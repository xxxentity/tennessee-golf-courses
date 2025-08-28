#!/bin/bash

# Force server to match GitHub repository exactly
# This will overwrite any local server changes

echo "=== FORCE SERVER UPDATE ==="
echo "This will overwrite all local changes on the server"
echo ""

# Backup current state
echo "1. Creating backup..."
cp -r . ../TGC-LLC-backup-$(date +%Y%m%d-%H%M%S)

# Reset everything
echo "2. Resetting repository state..."
git fetch origin
git clean -fd
git reset --hard origin/main

# Verify success
echo "3. Verifying update..."
git status

echo ""
echo "=== UPDATE COMPLETE ==="
echo "Server should now have threaded comments on all news articles"
echo "Test at: your-domain.com/news/tennessee-herrington-historic-run-125th-us-amateur-runner-up"