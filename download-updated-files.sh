#!/bin/bash

# Download updated files directly from GitHub
# Use this if Git commands are failing

GITHUB_RAW="https://raw.githubusercontent.com/xxxentity/tennessee-golf-courses/main"

echo "=== DOWNLOADING UPDATED FILES ==="

# Create includes directory if it doesn't exist
mkdir -p includes

# Download the threaded comments system
echo "Downloading threaded-comments.php..."
curl -o includes/threaded-comments.php "$GITHUB_RAW/includes/threaded-comments.php"

# Download updated news articles
echo "Downloading updated news articles..."
curl -o news/2025-tour-championship-atlanta-predictions.php "$GITHUB_RAW/news/2025-tour-championship-atlanta-predictions.php"
curl -o news/belmont-conner-brown-wins-tennessee-match-play-championship.php "$GITHUB_RAW/news/belmont-conner-brown-wins-tennessee-match-play-championship.php"
curl -o news/bmw-championship-2025-complete-tournament-recap-community-impact.php "$GITHUB_RAW/news/bmw-championship-2025-complete-tournament-recap-community-impact.php"
curl -o news/etsu-gavin-tiernan-amateur-championship-runner-up.php "$GITHUB_RAW/news/etsu-gavin-tiernan-amateur-championship-runner-up.php"
curl -o news/fedex-st-jude-championship-2025-complete-recap-community-impact.php "$GITHUB_RAW/news/fedex-st-jude-championship-2025-complete-recap-community-impact.php"
curl -o news/fedex-st-jude-first-round-bhatia-leads.php "$GITHUB_RAW/news/fedex-st-jude-first-round-bhatia-leads.php"
curl -o news/fleetwood-maintains-narrow-lead-scheffler-charges.php "$GITHUB_RAW/news/fleetwood-maintains-narrow-lead-scheffler-charges.php"
curl -o news/fleetwood-takes-command-weather-halts-play.php "$GITHUB_RAW/news/fleetwood-takes-command-weather-halts-play.php"
curl -o news/liv-golf-indianapolis-2025-complete-tournament-recap-entertainment.php "$GITHUB_RAW/news/liv-golf-indianapolis-2025-complete-tournament-recap-entertainment.php"
curl -o news/liv-golf-michigan-2025-championship-legion-xiii-playoff-victory.php "$GITHUB_RAW/news/liv-golf-michigan-2025-championship-legion-xiii-playoff-victory.php"
curl -o news/liv-golf-michigan-2025-semifinals-thriller.php "$GITHUB_RAW/news/liv-golf-michigan-2025-semifinals-thriller.php"
curl -o news/liv-golf-michigan-2025-team-championship-complete-tournament-recap.php "$GITHUB_RAW/news/liv-golf-michigan-2025-team-championship-complete-tournament-recap.php"
curl -o news/liv-golf-michigan-team-championship-2025-quarterfinals.php "$GITHUB_RAW/news/liv-golf-michigan-team-championship-2025-quarterfinals.php"
curl -o news/macintyre-explodes-for-62-leads-bmw-championship.php "$GITHUB_RAW/news/macintyre-explodes-for-62-leads-bmw-championship.php"
curl -o news/macintyre-extends-commanding-lead-scheffler-pursuit-bmw-championship.php "$GITHUB_RAW/news/macintyre-extends-commanding-lead-scheffler-pursuit-bmw-championship.php"
curl -o news/macintyre-weathers-moving-day-storm-maintains-bmw-championship-lead.php "$GITHUB_RAW/news/macintyre-weathers-moving-day-storm-maintains-bmw-championship-lead.php"
curl -o news/open-championship-2025-round-1-royal-portrush.php "$GITHUB_RAW/news/open-championship-2025-round-1-royal-portrush.php"
curl -o news/rose-captures-thrilling-playoff-victory-fleetwood-heartbreak.php "$GITHUB_RAW/news/rose-captures-thrilling-playoff-victory-fleetwood-heartbreak.php"
curl -o news/scheffler-delivers-stunning-comeback-wins-bmw-championship.php "$GITHUB_RAW/news/scheffler-delivers-stunning-comeback-wins-bmw-championship.php"
curl -o news/scheffler-extends-lead-open-championship-round-3.php "$GITHUB_RAW/news/scheffler-extends-lead-open-championship-round-3.php"
curl -o news/scheffler-seizes-control-open-championship-round-2.php "$GITHUB_RAW/news/scheffler-seizes-control-open-championship-round-2.php"
curl -o news/scheffler-wins-2025-british-open-final-round.php "$GITHUB_RAW/news/scheffler-wins-2025-british-open-final-round.php"
curl -o news/smith-narramore-capture-55th-tennessee-four-ball-championship-morristown.php "$GITHUB_RAW/news/smith-narramore-capture-55th-tennessee-four-ball-championship-morristown.php"
curl -o news/tennessee-four-ball-championship-2025-country-club-morristown.php "$GITHUB_RAW/news/tennessee-four-ball-championship-2025-country-club-morristown.php"
curl -o news/tennessee-herrington-historic-run-125th-us-amateur-runner-up.php "$GITHUB_RAW/news/tennessee-herrington-historic-run-125th-us-amateur-runner-up.php"
curl -o news/tour-championship-2025-atlanta-complete-tournament-recap-fleetwood-first-win.php "$GITHUB_RAW/news/tour-championship-2025-atlanta-complete-tournament-recap-fleetwood-first-win.php"
curl -o news/tour-championship-2025-final-round-fleetwood-historic-win.php "$GITHUB_RAW/news/tour-championship-2025-final-round-fleetwood-historic-win.php"
curl -o news/tour-championship-2025-first-round-henley-leads.php "$GITHUB_RAW/news/tour-championship-2025-first-round-henley-leads.php"
curl -o news/tour-championship-2025-round-2-fleetwood-henley-share-lead.php "$GITHUB_RAW/news/tour-championship-2025-round-2-fleetwood-henley-share-lead.php"
curl -o news/tour-championship-2025-round-3-cantlay-fleetwood-tied.php "$GITHUB_RAW/news/tour-championship-2025-round-3-cantlay-fleetwood-tied.php"

echo ""
echo "=== DOWNLOAD COMPLETE ==="
echo "All updated files downloaded with threaded comments"
echo "Test at: your-domain.com/news/tennessee-herrington-historic-run-125th-us-amateur-runner-up"