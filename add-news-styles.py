#!/usr/bin/env python3
import os
import re

# The styles to add
styles = '''    <style>
        /* News Article Styles */
        .news-article {
            padding-top: 100px;
            min-height: 100vh;
            background: #f5f5f5;
        }

        .news-article .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
        }

        .back-to-news {
            margin-bottom: 2rem;
        }

        .back-link {
            color: #2e7d32;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #1b5e20;
        }

        .article-header {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid #e0e0e0;
        }

        .category-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1rem;
            text-transform: uppercase;
        }

        .category-badge.tournament-news {
            background-color: #2e7d32;
            color: white;
        }

        .category-badge.tennessee-news {
            background-color: #1565c0;
            color: white;
        }

        .category-badge.equipment-news {
            background-color: #f57c00;
            color: white;
        }

        .article-title {
            font-size: 2.5rem;
            color: #1a1a1a;
            line-height: 1.2;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .article-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            color: #666;
            font-size: 0.95rem;
        }

        .meta-left {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .author {
            font-weight: 500;
            color: #333;
        }

        .separator {
            color: #999;
        }

        .read-time {
            color: #666;
        }

        .featured-image {
            margin-bottom: 2rem;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .featured-image img {
            width: 100%;
            height: auto;
            display: block;
        }

        .article-content {
            font-size: 1.125rem;
            line-height: 1.7;
            color: #333;
        }

        .article-content p {
            margin-bottom: 1.5rem;
        }

        .article-content .lead {
            font-size: 1.25rem;
            line-height: 1.6;
            font-weight: 500;
            color: #1a1a1a;
            margin-bottom: 2rem;
        }

        .article-content h2 {
            font-size: 1.875rem;
            color: #1a1a1a;
            margin-top: 2.5rem;
            margin-bottom: 1.25rem;
            font-weight: 600;
        }

        .article-content h3 {
            font-size: 1.5rem;
            color: #333;
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .article-content blockquote {
            border-left: 4px solid #2e7d32;
            padding-left: 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            color: #555;
        }

        .scoreboard {
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 2rem 0;
        }

        .match-result {
            margin-bottom: 1.5rem;
        }

        .match-result:last-child {
            margin-bottom: 0;
        }

        .match-result h4 {
            color: #2e7d32;
            font-size: 1.125rem;
            margin-bottom: 0.75rem;
            font-weight: 600;
        }

        .results-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .results-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #e0e0e0;
            color: #555;
        }

        .results-list li:last-child {
            border-bottom: none;
        }

        .article-footer {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #e0e0e0;
        }

        .disclaimer {
            font-size: 0.875rem;
            color: #666;
            font-style: italic;
        }

        .share-buttons {
            margin-top: 3rem;
            padding: 2rem;
            background: #f9f9f9;
            border-radius: 10px;
            text-align: center;
        }

        .share-buttons h3 {
            font-size: 1.25rem;
            margin-bottom: 1rem;
            color: #333;
        }

        .social-share {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .social-share a {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .share-twitter {
            background: #1DA1F2;
            color: white;
        }

        .share-twitter:hover {
            background: #0d8bd9;
            transform: translateY(-2px);
        }

        .share-facebook {
            background: #1877F2;
            color: white;
        }

        .share-facebook:hover {
            background: #0e63d9;
            transform: translateY(-2px);
        }

        .share-linkedin {
            background: #0077B5;
            color: white;
        }

        .share-linkedin:hover {
            background: #005885;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .news-article .container {
                padding: 1rem;
            }

            .article-title {
                font-size: 1.875rem;
            }

            .article-content {
                font-size: 1rem;
            }

            .article-content .lead {
                font-size: 1.125rem;
            }

            .social-share {
                flex-direction: column;
            }

            .social-share a {
                width: 100%;
            }
        }
    </style>
'''

news_dir = "/mnt/c/Users/ddhoward/TGC LLC/tennessee-golf-courses/news"

for filename in os.listdir(news_dir):
    if filename.endswith('.php') and filename != 'test-fresh.php':
        filepath = os.path.join(news_dir, filename)

        # Skip if already processed
        if filename == 'liv-golf-michigan-2025-semifinals-thriller.php':
            print(f"Skipping {filename} - already processed")
            continue

        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()

        # Check if styles already exist
        if "News Article Styles" in content:
            print(f"Skipping {filename} - styles already exist")
            continue

        # Find the position after the Google Analytics script
        pattern = r"(gtag\('config', 'G-7VPNPCDTBP'\);\s*</script>)"

        if re.search(pattern, content):
            # Add styles after Google Analytics
            new_content = re.sub(pattern, r"\1\n" + styles, content)

            with open(filepath, 'w', encoding='utf-8') as f:
                f.write(new_content)

            print(f"Updated {filename}")
        else:
            print(f"Warning: Could not find insertion point in {filename}")

print("All news articles have been updated!")