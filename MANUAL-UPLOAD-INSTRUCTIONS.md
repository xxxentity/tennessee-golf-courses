# Manual Upload Instructions for Threaded Comments

## 🚨 IMPORTANT: GitHub Sync Issue
The run-update.php script won't work because there's a sync issue between local changes and GitHub. 

## ✅ SOLUTION: Direct File Upload

### Step 1: Upload the Core System
1. **Create folder** in cPanel File Manager: `includes/` (if it doesn't exist)
2. **Upload this file** to `/includes/`:
   - `threaded-comments.php` (from your local `includes/` folder)

### Step 2: Upload Updated News Articles
Replace these files in your `/news/` folder with the updated versions:

**Upload these 30 files from your local `/news/` folder:**
- 2025-tour-championship-atlanta-predictions.php
- belmont-conner-brown-wins-tennessee-match-play-championship.php
- bmw-championship-2025-complete-tournament-recap-community-impact.php
- etsu-gavin-tiernan-amateur-championship-runner-up.php
- fedex-st-jude-championship-2025-complete-recap-community-impact.php
- fedex-st-jude-first-round-bhatia-leads.php
- fleetwood-maintains-narrow-lead-scheffler-charges.php
- fleetwood-takes-command-weather-halts-play.php
- liv-golf-indianapolis-2025-complete-tournament-recap-entertainment.php
- liv-golf-michigan-2025-championship-legion-xiii-playoff-victory.php
- liv-golf-michigan-2025-semifinals-thriller.php
- liv-golf-michigan-2025-team-championship-complete-tournament-recap.php
- liv-golf-michigan-team-championship-2025-quarterfinals.php
- macintyre-explodes-for-62-leads-bmw-championship.php
- macintyre-extends-commanding-lead-scheffler-pursuit-bmw-championship.php
- macintyre-weathers-moving-day-storm-maintains-bmw-championship-lead.php
- open-championship-2025-round-1-royal-portrush.php
- rose-captures-thrilling-playoff-victory-fleetwood-heartbreak.php
- scheffler-delivers-stunning-comeback-wins-bmw-championship.php
- scheffler-extends-lead-open-championship-round-3.php
- scheffler-seizes-control-open-championship-round-2.php
- scheffler-wins-2025-british-open-final-round.php
- smith-narramore-capture-55th-tennessee-four-ball-championship-morristown.php
- tennessee-four-ball-championship-2025-country-club-morristown.php
- tennessee-herrington-historic-run-125th-us-amateur-runner-up.php
- tour-championship-2025-atlanta-complete-tournament-recap-fleetwood-first-win.php
- tour-championship-2025-final-round-fleetwood-historic-win.php
- tour-championship-2025-first-round-henley-leads.php
- tour-championship-2025-round-2-fleetwood-henley-share-lead.php
- tour-championship-2025-round-3-cantlay-fleetwood-tied.php

### Step 3: Clean Up
Delete these temporary files from your server:
- run-update.php
- force-update-server.sh
- download-updated-files.sh

### Step 4: Test
Visit any news article to test threaded comments:
- Example: your-domain.com/news/tennessee-herrington-historic-run-125th-us-amateur-runner-up

## ✅ What You'll Get
- Threaded reply system with nesting
- Clickable usernames linking to profiles
- Reply buttons for logged-in users
- Consistent styling across all articles

## 🔧 Upload Method
**Option A: Individual Files**
- Upload each file one by one through cPanel File Manager

**Option B: ZIP Upload** 
- I can create a ZIP file with all updated files
- Upload ZIP and extract in cPanel

Which option do you prefer?