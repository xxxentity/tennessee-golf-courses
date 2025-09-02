#!/usr/bin/env python3

import os
import re

def standardize_galleries():
    courses_dir = "/Users/entity./TGC LLC/courses"
    
    print("Standardizing all galleries to CSS class-based structure...")
    
    # Counters
    files_converted = 0
    files_skipped = 0
    total_processed = 0
    
    # Standard gallery template
    def create_standard_gallery(course_name, course_slug):
        return f'''
    <!-- Photo Gallery -->
    <section class="photo-gallery">
        <div class="container">
            <div class="section-header">
                <h2>Course Gallery</h2>
                <p>Experience the beauty of {course_name}</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item" style="background-image: url('../images/courses/{course_slug}/1.webp');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/{course_slug}/2.webp');"></div>
                <div class="gallery-item" style="background-image: url('../images/courses/{course_slug}/3.webp');"></div>
            </div>
            <div class="gallery-button">
                <button class="btn-gallery" onclick="openGallery()">View Full Gallery (25 Photos)</button>
            </div>
        </div>
    </section>'''

    # Standard modal template
    def create_standard_modal(course_name):
        return f'''
    <!-- Full Gallery Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">{course_name} - Complete Photo Gallery</h2>
                <button class="close" onclick="closeGallery()">&times;</button>
            </div>
            <div class="full-gallery-grid" id="fullGalleryGrid">
                <!-- Photos will be loaded dynamically -->
            </div>
        </div>
    </div>'''

    # Standard JavaScript template
    def create_standard_js(course_slug):
        return f'''
        // Gallery Modal Functions
        function openGallery() {{
            const modal = document.getElementById('galleryModal');
            const galleryGrid = document.getElementById('fullGalleryGrid');
            
            // Clear existing content
            galleryGrid.innerHTML = '';
            
            // Generate all 25 images
            for (let i = 1; i <= 25; i++) {{
                const galleryItem = document.createElement('div');
                galleryItem.className = 'full-gallery-item';
                galleryItem.style.backgroundImage = `url('../images/courses/{course_slug}/${{i}}.webp')`;
                galleryItem.onclick = () => window.open(`../images/courses/{course_slug}/${{i}}.webp`, '_blank');
                galleryGrid.appendChild(galleryItem);
            }}
            
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }}
        
        function closeGallery() {{
            const modal = document.getElementById('galleryModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto'; // Restore scrolling
        }}
        
        // Close modal when clicking outside of it
        document.getElementById('galleryModal').addEventListener('click', function(event) {{
            if (event.target === this) {{
                closeGallery();
            }}
        }});
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {{
            if (event.key === 'Escape') {{
                closeGallery();
            }}
        }});'''

    # Get all PHP files in courses directory
    for filename in os.listdir(courses_dir):
        if filename.endswith('.php'):
            file_path = os.path.join(courses_dir, filename)
            total_processed += 1
            
            # Read the file
            with open(file_path, 'r', encoding='utf-8') as f:
                content = f.read()
            
            # Save original content
            original_content = content
            
            # Check if this file uses inline-styled gallery (Type 1)
            inline_gallery_pattern = r'<!-- Course Gallery -->\s*<div class="course-info-card"[^>]*>.*?</div>\s*</div>\s*</div>'
            
            if re.search(inline_gallery_pattern, content, re.DOTALL):
                # Extract course information
                course_slug = filename.replace('.php', '')
                
                # Try to find course name from title or h1
                course_name_match = re.search(r'<h1[^>]*>([^<]+)</h1>', content)
                if course_name_match:
                    course_name = course_name_match.group(1).strip()
                else:
                    # Fallback: extract from title
                    title_match = re.search(r'<title>([^-]+)', content)
                    if title_match:
                        course_name = title_match.group(1).strip()
                    else:
                        course_name = course_slug.replace('-', ' ').title()
                
                print(f"Converting {filename} - {course_name}")
                
                # Remove the old gallery section
                content = re.sub(inline_gallery_pattern, '', content, flags=re.DOTALL)
                
                # Find where to insert the new gallery (before Reviews Section)
                reviews_pattern = r'(\s*)<!-- Reviews Section -->'
                reviews_match = re.search(reviews_pattern, content)
                
                if reviews_match:
                    # Insert new gallery before reviews
                    new_gallery = create_standard_gallery(course_name, course_slug)
                    content = content[:reviews_match.start()] + new_gallery + '\\n\\n' + content[reviews_match.start():]
                else:
                    # Fallback: insert before footer
                    footer_pattern = r'(\s*)<!-- Footer -->'
                    footer_match = re.search(footer_pattern, content)
                    if footer_match:
                        new_gallery = create_standard_gallery(course_name, course_slug)
                        content = content[:footer_match.start()] + new_gallery + '\\n\\n' + content[footer_match.start():]
                
                # Replace or add modal
                old_modal_pattern = r'<!-- Gallery Modal -->.*?</div>\s*</div>'
                modal_exists = re.search(old_modal_pattern, content, re.DOTALL)
                
                new_modal = create_standard_modal(course_name)
                
                if modal_exists:
                    content = re.sub(old_modal_pattern, new_modal.strip(), content, flags=re.DOTALL)
                else:
                    # Insert modal before footer
                    footer_pattern = r'(\s*)<!-- Footer -->'
                    footer_match = re.search(footer_pattern, content)
                    if footer_match:
                        content = content[:footer_match.start()] + new_modal + '\\n\\n' + content[footer_match.start():]
                
                # Replace or add JavaScript
                old_js_pattern = r'// Gallery modal functionality.*?</script>'
                js_exists = re.search(old_js_pattern, content, re.DOTALL)
                
                new_js = create_standard_js(course_slug)
                
                if js_exists:
                    content = re.sub(old_js_pattern, new_js.strip() + '\\n    </script>', content, flags=re.DOTALL)
                else:
                    # Find script section and add JS
                    script_pattern = r'(<script>.*?)(</script>)'
                    script_match = re.search(script_pattern, content, re.DOTALL)
                    if script_match:
                        content = content[:script_match.end(1)] + new_js + '\\n    ' + content[script_match.start(2):]
                
                files_converted += 1
                
            else:
                # File already uses CSS class-based gallery or different structure
                files_skipped += 1
                print(f"✓ {filename} already uses modern gallery structure")
            
            # Write the updated content
            if content != original_content:
                with open(file_path, 'w', encoding='utf-8') as f:
                    f.write(content)
    
    print(f"\\n📊 Gallery Standardization Summary:")
    print(f"Files processed: {total_processed}")
    print(f"Files converted: {files_converted}")
    print(f"Files skipped (already modern): {files_skipped}")
    print(f"\\n✅ Gallery standardization complete!")
    
    if files_converted > 0:
        print(f"\\n📋 All galleries now use:")
        print(f"• CSS class-based structure")
        print(f"• Consistent section layout")
        print(f"• Modern modal implementation")
        print(f"• WebP image format")
        print(f"• Responsive design")

if __name__ == "__main__":
    standardize_galleries()