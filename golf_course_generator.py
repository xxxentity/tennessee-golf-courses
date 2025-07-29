#!/usr/bin/env python3
"""
Golf Course Page Generator
A tool to scrape golf course websites and generate standardized PHP pages
using the Bear Trace template as a base.
"""

import tkinter as tk
from tkinter import ttk, messagebox, filedialog, scrolledtext
import requests
from bs4 import BeautifulSoup
import re
from urllib.parse import urljoin, urlparse
import threading
import time
import os

class GolfCourseGenerator:
    def __init__(self, root):
        self.root = root
        self.root.title("Golf Course Page Generator")
        self.root.geometry("900x700")
        
        # Course data storage
        self.course_data = {
            'course_name': '',
            'course_slug': '',
            'designer': '',
            'year_opened': '',
            'par': '',
            'yardage': '',
            'course_type': '',  # Public, Private, Semi-Private
            'location_city': '',
            'location_state': 'TN',
            'address': '',
            'phone': '',
            'website': '',
            'description': '',
            'weekday_fees': '',
            'weekend_fees': '',
            'cart_fee': '',
            'additional_fees': {},
            'amenities': [],
            'signature_holes': [],
            'special_features': '',
            'slope_rating': '',
            'course_rating': '',
            'greens_type': ''
        }
        
        self.create_widgets()
        
    def create_widgets(self):
        # Create notebook for tabs
        notebook = ttk.Notebook(self.root)
        notebook.pack(fill=tk.BOTH, expand=True, padx=10, pady=10)
        
        # Tab 1: URL Input and Scraping
        self.scrape_frame = ttk.Frame(notebook)
        notebook.add(self.scrape_frame, text="1. Scrape Data")
        self.create_scrape_tab()
        
        # Tab 2: Data Review and Editing
        self.edit_frame = ttk.Frame(notebook)
        notebook.add(self.edit_frame, text="2. Review & Edit")
        self.create_edit_tab()
        
        # Tab 3: Description and Final Details
        self.desc_frame = ttk.Frame(notebook)
        notebook.add(self.desc_frame, text="3. Description")
        self.create_description_tab()
        
        # Tab 4: Generate PHP File
        self.generate_frame = ttk.Frame(notebook)
        notebook.add(self.generate_frame, text="4. Generate")
        self.create_generate_tab()
        
    def create_scrape_tab(self):
        # URL Input section
        url_frame = ttk.LabelFrame(self.scrape_frame, text="Website to Scrape", padding=10)
        url_frame.pack(fill=tk.X, padx=10, pady=5)
        
        # Homepage URL
        ttk.Label(url_frame, text="Golf Course Website URL:").pack(anchor='w')
        self.url_entry = ttk.Entry(url_frame, width=80)
        self.url_entry.pack(fill=tk.X, pady=2)
        
        # Scraping options
        options_frame = ttk.Frame(url_frame)
        options_frame.pack(fill=tk.X, pady=(10,0))
        
        self.auto_discover = tk.BooleanVar(value=True)
        ttk.Checkbutton(options_frame, text="Auto-discover and scrape all relevant pages", 
                       variable=self.auto_discover).pack(anchor='w')
        
        self.max_pages = tk.IntVar(value=10)
        pages_frame = ttk.Frame(options_frame)
        pages_frame.pack(anchor='w', pady=2)
        ttk.Label(pages_frame, text="Max pages to scrape:").pack(side=tk.LEFT)
        ttk.Spinbox(pages_frame, from_=5, to=20, width=5, textvariable=self.max_pages).pack(side=tk.LEFT, padx=5)
        
        # Manual URLs section (for override)
        manual_frame = ttk.LabelFrame(url_frame, text="Manual URLs (optional override)", padding=5)
        manual_frame.pack(fill=tk.X, pady=(10,0))
        
        ttk.Label(manual_frame, text="Additional specific URLs to scrape:").pack(anchor='w')
        self.manual_urls_text = tk.Text(manual_frame, height=3, width=80)
        self.manual_urls_text.pack(fill=tk.X, pady=2)
        
        # Helper text
        help_text = tk.Text(url_frame, height=4, wrap=tk.WORD, bg='#f0f0f0', relief=tk.FLAT)
        help_text.pack(fill=tk.X, pady=(10,0))
        help_text.insert(1.0, "Auto-discovery will find pages like: About, Rates/Pricing, Course Info, Contact, History, etc.\n\nManual URLs: Enter one URL per line if you want to force scraping of specific pages in addition to auto-discovery.")
        help_text.config(state=tk.DISABLED)
        
        # Scrape button
        self.scrape_btn = ttk.Button(self.scrape_frame, text="Start Comprehensive Scraping", 
                                    command=self.scrape_website)
        self.scrape_btn.pack(pady=10)
        
        # Progress bar
        self.progress = ttk.Progressbar(self.scrape_frame, mode='indeterminate')
        self.progress.pack(pady=5, fill=tk.X, padx=20)
        
        # Status text area
        ttk.Label(self.scrape_frame, text="Scraping Status:").pack(pady=(20,5))
        self.status_text = scrolledtext.ScrolledText(self.scrape_frame, height=15, width=100)
        self.status_text.pack(pady=5, fill=tk.BOTH, expand=True)
        
    def create_edit_tab(self):
        # Create scrollable frame
        canvas = tk.Canvas(self.edit_frame)
        scrollbar = ttk.Scrollbar(self.edit_frame, orient="vertical", command=canvas.yview)
        scrollable_frame = ttk.Frame(canvas)
        
        scrollable_frame.bind(
            "<Configure>",
            lambda e: canvas.configure(scrollregion=canvas.bbox("all"))
        )
        
        canvas.create_window((0, 0), window=scrollable_frame, anchor="nw")
        canvas.configure(yscrollcommand=scrollbar.set)
        
        # Basic Info
        ttk.Label(scrollable_frame, text="BASIC COURSE INFORMATION", font=('Arial', 12, 'bold')).grid(row=0, column=0, columnspan=2, pady=10)
        
        # Course fields
        self.course_fields = {}
        fields = [
            ('Course Name:', 'course_name'),
            ('Designer:', 'designer'),
            ('Year Opened:', 'year_opened'),
            ('Par:', 'par'),
            ('Yardage:', 'yardage'),
            ('Course Type:', 'course_type'),
            ('City:', 'location_city'),
            ('Address:', 'address'),
            ('Phone:', 'phone'),
            ('Website:', 'website'),
            ('Slope Rating:', 'slope_rating'),
            ('Course Rating:', 'course_rating'),
            ('Greens Type:', 'greens_type')
        ]
        
        for i, (label, key) in enumerate(fields, 1):
            ttk.Label(scrollable_frame, text=label).grid(row=i, column=0, sticky='w', padx=5, pady=2)
            entry = ttk.Entry(scrollable_frame, width=50)
            entry.grid(row=i, column=1, padx=5, pady=2)
            self.course_fields[key] = entry
            
        # Pricing section
        ttk.Label(scrollable_frame, text="PRICING INFORMATION", font=('Arial', 12, 'bold')).grid(row=len(fields)+2, column=0, columnspan=2, pady=10)
        
        pricing_fields = [
            ('Weekday Fees:', 'weekday_fees'),
            ('Weekend Fees:', 'weekend_fees'),
            ('Cart Fee:', 'cart_fee')
        ]
        
        for i, (label, key) in enumerate(pricing_fields, len(fields)+3):
            ttk.Label(scrollable_frame, text=label).grid(row=i, column=0, sticky='w', padx=5, pady=2)
            entry = ttk.Entry(scrollable_frame, width=50)
            entry.grid(row=i, column=1, padx=5, pady=2)
            self.course_fields[key] = entry
            
        # Google Maps section
        maps_row = len(fields) + len(pricing_fields) + 4
        ttk.Label(scrollable_frame, text="GOOGLE MAPS", font=('Arial', 12, 'bold')).grid(row=maps_row, column=0, columnspan=2, pady=10)
        
        ttk.Label(scrollable_frame, text="Google Maps Search Query:").grid(row=maps_row+1, column=0, sticky='w', padx=5, pady=2)
        self.maps_entry = ttk.Entry(scrollable_frame, width=50)
        self.maps_entry.grid(row=maps_row+1, column=1, padx=5, pady=2)
        
        ttk.Button(scrollable_frame, text="Auto-Generate Maps Query", 
                  command=self.auto_generate_maps).grid(row=maps_row+2, column=1, sticky='w', padx=5, pady=2)
            
        canvas.pack(side="left", fill="both", expand=True)
        scrollbar.pack(side="right", fill="y")
        
    def create_description_tab(self):
        ttk.Label(self.desc_frame, text="Course Description (will be used in the generated page):").pack(pady=5)
        self.description_text = scrolledtext.ScrolledText(self.desc_frame, height=15, width=100)
        self.description_text.pack(pady=5, fill=tk.BOTH, expand=True)
        
        ttk.Label(self.desc_frame, text="Special Features/Notes:").pack(pady=5)
        self.features_text = scrolledtext.ScrolledText(self.desc_frame, height=5, width=100)
        self.features_text.pack(pady=5, fill=tk.BOTH, expand=True)
        
    def create_generate_tab(self):
        ttk.Label(self.generate_frame, text="Ready to Generate PHP File").pack(pady=20)
        
        # Output directory selection
        ttk.Label(self.generate_frame, text="Output Directory:").pack(pady=5)
        self.output_dir = tk.StringVar(value="/Users/entity./TGC LLC/courses/")
        ttk.Entry(self.generate_frame, textvariable=self.output_dir, width=80).pack(pady=5)
        
        ttk.Button(self.generate_frame, text="Browse", 
                  command=self.browse_output_dir).pack(pady=5)
        
        # Generate button
        self.generate_btn = ttk.Button(self.generate_frame, text="Generate PHP File", 
                                      command=self.generate_php_file)
        self.generate_btn.pack(pady=20)
        
        # Output preview
        ttk.Label(self.generate_frame, text="Generated File Preview:").pack(pady=5)
        self.preview_text = scrolledtext.ScrolledText(self.generate_frame, height=15, width=100)
        self.preview_text.pack(pady=5, fill=tk.BOTH, expand=True)
        
    def browse_output_dir(self):
        directory = filedialog.askdirectory()
        if directory:
            self.output_dir.set(directory)
            
    def scrape_website(self):
        homepage_url = self.url_entry.get().strip()
        if not homepage_url:
            messagebox.showerror("Error", "Please enter a homepage URL")
            return
            
        # Get manual URLs if provided
        manual_urls = []
        manual_text = self.manual_urls_text.get(1.0, tk.END).strip()
        if manual_text:
            manual_urls = [url.strip() for url in manual_text.split('\n') if url.strip()]
            
        # Start scraping in separate thread
        threading.Thread(target=self._scrape_worker, args=(homepage_url, manual_urls), daemon=True).start()
        
    def _scrape_worker(self, homepage_url, manual_urls):
        self.progress.start()
        self.scrape_btn.config(state='disabled')
        
        try:
            self.log_status(f"Starting comprehensive scraping of: {homepage_url}")
            
            headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'}
            all_soups = []
            scraped_urls = set()
            
            # Step 1: Load homepage and discover links
            self.log_status("Loading homepage...")
            try:
                response = requests.get(homepage_url, headers=headers, timeout=10)
                response.raise_for_status()
                homepage_soup = BeautifulSoup(response.content, 'html.parser')
                all_soups.append(('Homepage', homepage_soup, homepage_url))
                scraped_urls.add(homepage_url)
                self.log_status("✓ Homepage loaded successfully")
            except Exception as e:
                raise Exception(f"Failed to load homepage: {str(e)}")
            
            # Step 2: Auto-discover relevant links if enabled
            urls_to_scrape = []
            if self.auto_discover.get():
                self.log_status("Auto-discovering relevant pages...")
                discovered_urls = self._discover_relevant_links(homepage_soup, homepage_url)
                urls_to_scrape.extend(discovered_urls)
                self.log_status(f"Discovered {len(discovered_urls)} relevant pages")
            
            # Step 3: Add manual URLs
            for url in manual_urls:
                if url not in scraped_urls:
                    urls_to_scrape.append(('Manual', url))
            
            # Step 4: Scrape discovered and manual URLs
            max_pages = self.max_pages.get()
            urls_to_scrape = urls_to_scrape[:max_pages-1]  # -1 for homepage already scraped
            
            for page_type, url in urls_to_scrape:
                if url in scraped_urls:
                    continue
                    
                try:
                    self.log_status(f"Scraping {page_type}: {url}")
                    response = requests.get(url, headers=headers, timeout=8)
                    response.raise_for_status()
                    
                    soup = BeautifulSoup(response.content, 'html.parser')
                    all_soups.append((page_type, soup, url))
                    scraped_urls.add(url)
                    self.log_status(f"✓ Successfully loaded {page_type}")
                    
                except Exception as e:
                    self.log_status(f"✗ Error loading {page_type}: {str(e)}")
                    continue
            
            self.log_status(f"Scraping complete! Processed {len(all_soups)} pages total.")
            
            # Extract course data from all pages
            self._extract_course_data_multi(all_soups)
            
            # Populate fields
            self._populate_fields()
            
            self.log_status("Data extraction complete! Please review the data in the next tab.")
            
        except Exception as e:
            self.log_status(f"Error during scraping: {str(e)}")
            messagebox.showerror("Scraping Error", f"Could not complete scraping: {str(e)}")
            
        finally:
            self.progress.stop()
            self.scrape_btn.config(state='normal')
    
    def _discover_relevant_links(self, homepage_soup, base_url):
        """Discover relevant links from homepage navigation and content"""
        from urllib.parse import urljoin, urlparse
        
        relevant_urls = []
        base_domain = urlparse(base_url).netloc
        
        # Keywords that indicate relevant pages
        relevant_keywords = [
            # About/Info pages
            ('about', 'About'),
            ('history', 'History'), 
            ('course-info', 'Course Info'),
            ('course-information', 'Course Info'),
            ('the-course', 'Course Info'),
            ('golf-course', 'Course Info'),
            
            # Pricing pages
            ('rates', 'Rates'),
            ('pricing', 'Pricing'),
            ('fees', 'Fees'),
            ('green-fees', 'Green Fees'),
            ('membership', 'Membership'),
            
            # Contact pages
            ('contact', 'Contact'),
            ('location', 'Location'),
            ('directions', 'Directions'),
            ('find-us', 'Location'),
            
            # Additional info
            ('amenities', 'Amenities'),
            ('facilities', 'Facilities'),
            ('pro-shop', 'Pro Shop'),
            ('restaurant', 'Restaurant'),
            ('events', 'Events'),
        ]
        
        # Find all links
        links = homepage_soup.find_all('a', href=True)
        
        for link in links:
            href = link['href'].lower()
            link_text = link.get_text().lower().strip()
            
            # Convert relative URLs to absolute
            full_url = urljoin(base_url, link['href'])
            
            # Only process links from same domain
            if urlparse(full_url).netloc != base_domain:
                continue
                
            # Check if link matches relevant keywords
            for keyword, page_type in relevant_keywords:
                if (keyword in href or keyword in link_text) and len(link_text) < 50:
                    # Avoid duplicate page types
                    if not any(page_type == existing_type for existing_type, _ in relevant_urls):
                        relevant_urls.append((page_type, full_url))
                        break
        
        # Also look for navigation menus and common page patterns
        nav_elements = homepage_soup.find_all(['nav', 'ul', 'div'], class_=re.compile(r'nav|menu|header', re.I))
        for nav in nav_elements:
            nav_links = nav.find_all('a', href=True)
            for link in nav_links:
                href = link['href'].lower()
                link_text = link.get_text().lower().strip()
                full_url = urljoin(base_url, link['href'])
                
                if urlparse(full_url).netloc != base_domain:
                    continue
                
                for keyword, page_type in relevant_keywords:
                    if (keyword in href or keyword in link_text) and len(link_text) < 50:
                        if not any(page_type == existing_type for existing_type, _ in relevant_urls):
                            relevant_urls.append((page_type, full_url))
                            break
        
        return relevant_urls
            
    def _extract_course_data_multi(self, all_soups):
        """Extract course data from multiple pages"""
        
        # Reset course data
        for key in self.course_data:
            if key not in ['location_state']:  # Keep TN as default
                self.course_data[key] = ''
        self.course_data['location_state'] = 'TN'
        
        # Extract data from each page, with page type priority
        for page_type, soup, url in all_soups:
            self.log_status(f"Extracting data from {page_type}...")
            
            page_text = soup.get_text()
            
            # Course name - prioritize homepage
            if not self.course_data['course_name'] and page_type == 'Homepage':
                course_name = self._extract_course_name(soup)
                if course_name:
                    self.course_data['course_name'] = course_name
                    self.log_status(f"Found course name: {course_name}")
                    
                    # Generate slug
                    slug = re.sub(r'[^a-zA-Z0-9\s]', '', course_name.lower())
                    slug = re.sub(r'\s+', '-', slug.strip())
                    self.course_data['course_slug'] = slug
            
            # Phone number - any page
            if not self.course_data['phone']:
                phone = self._extract_phone(page_text)
                if phone:
                    self.course_data['phone'] = phone
                    self.log_status(f"Found phone: {phone}")
            
            # Address - prioritize contact page, then homepage
            if not self.course_data['address'] or page_type in ['Contact/Location', 'Homepage']:
                address = self._extract_address(page_text)
                if address:
                    self.course_data['address'] = address
                    self.log_status(f"Found address: {address}")
                    
                    # Extract city from address
                    city_match = re.search(r',\s*([^,]+),\s*[A-Z]{2}', address)
                    if city_match and not self.course_data['location_city']:
                        self.course_data['location_city'] = city_match.group(1).strip()
                        self.log_status(f"Found city: {city_match.group(1).strip()}")
            
            # Pricing - prioritize rates page
            if page_type == 'Rates/Pricing' or not self.course_data['weekday_fees']:
                pricing = self._extract_pricing(page_text)
                if pricing['weekday']:
                    self.course_data['weekday_fees'] = pricing['weekday']
                    self.log_status(f"Found weekday fees: {pricing['weekday']}")
                if pricing['weekend']:
                    self.course_data['weekend_fees'] = pricing['weekend']
                    self.log_status(f"Found weekend fees: {pricing['weekend']}")
                if pricing['cart']:
                    self.course_data['cart_fee'] = pricing['cart']
                    self.log_status(f"Found cart fee: {pricing['cart']}")
            
            # Course specs - any page
            if not self.course_data['yardage']:
                yardage = self._extract_yardage(page_text)
                if yardage:
                    self.course_data['yardage'] = yardage
                    self.log_status(f"Found yardage: {yardage}")
                    
            if not self.course_data['par']:
                par = self._extract_par(page_text)
                if par:
                    self.course_data['par'] = par
                    self.log_status(f"Found par: {par}")
            
            # Designer and year - prioritize about page
            if page_type == 'About/Course Info' or not self.course_data['designer']:
                designer = self._extract_designer(page_text)
                if designer:
                    self.course_data['designer'] = designer
                    self.log_status(f"Found designer: {designer}")
                    
            if page_type == 'About/Course Info' or not self.course_data['year_opened']:
                year = self._extract_year(page_text)
                if year:
                    self.course_data['year_opened'] = year
                    self.log_status(f"Found year opened: {year}")
            
            # Set website to homepage URL
            if page_type == 'Homepage':
                self.course_data['website'] = url
            
            # Course type
            if not self.course_data['course_type'] or self.course_data['course_type'] == 'Public':
                course_type = self._extract_course_type(page_text)
                self.course_data['course_type'] = course_type
                self.log_status(f"Set course type: {course_type}")
    
    def _extract_course_data(self, soup, url):
        """Extract golf course data from the webpage"""
        
        # Get course name - try multiple methods
        course_name = ""
        
        # Method 1: Look for h1 tags first
        h1_tags = soup.find_all('h1')
        for h1 in h1_tags:
            text = h1.get_text().strip()
            if 'golf' in text.lower() and len(text) < 100:
                course_name = text
                break
                
        # Method 2: Try title tag if h1 didn't work
        if not course_name:
            title = soup.find('title')
            if title:
                title_text = title.get_text().strip()
                # Clean up title - remove common suffixes
                title_text = re.sub(r'\s*[-|]\s*(golf|home|welcome).*$', '', title_text, flags=re.I)
                course_name = title_text
                
        # Method 3: Look in meta tags
        if not course_name:
            meta_title = soup.find('meta', attrs={'property': 'og:title'})
            if meta_title:
                course_name = meta_title.get('content', '').strip()
                
        if course_name:
            self.course_data['course_name'] = course_name
            self.log_status(f"Found course name: {course_name}")
            
            # Generate slug from course name
            slug = re.sub(r'[^a-zA-Z0-9\s]', '', course_name.lower())
            slug = re.sub(r'\s+', '-', slug.strip())
            self.course_data['course_slug'] = slug
            
        # Extract phone numbers - more comprehensive search
        phone_pattern = r'\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}'
        page_text = soup.get_text()
        phones = re.findall(phone_pattern, page_text)
        if phones:
            # Clean up phone format
            phone = re.sub(r'[^\d]', '', phones[0])
            formatted_phone = f"({phone[:3]}) {phone[3:6]}-{phone[6:]}"
            self.course_data['phone'] = formatted_phone
            self.log_status(f"Found phone: {formatted_phone}")
            
        # Extract address - improved patterns
        # Look for full addresses with zip codes
        address_patterns = [
            r'\d+\s+[^,\n]+,\s*[^,\n]+,\s*[A-Z]{2}\s*\d{5}',  # Full address with zip
            r'\d+\s+[^,\n]+,\s*[^,\n]+,\s*[A-Z]{2}',  # Address without zip
            r'\d+\s+[A-Za-z\s]+(?:Street|St|Drive|Dr|Road|Rd|Avenue|Ave|Lane|Ln|Boulevard|Blvd|Way|Pike|Circle|Cir)[^,\n]*,\s*[^,\n]+,\s*[A-Z]{2}'
        ]
        
        for pattern in address_patterns:
            address_match = re.search(pattern, page_text)
            if address_match:
                address = address_match.group().strip()
                self.course_data['address'] = address
                self.log_status(f"Found address: {address}")
                
                # Extract city from address
                city_match = re.search(r',\s*([^,]+),\s*[A-Z]{2}', address)
                if city_match:
                    self.course_data['location_city'] = city_match.group(1).strip()
                    self.log_status(f"Found city: {city_match.group(1).strip()}")
                break
                
        # Extract pricing information
        pricing_keywords = ['green fee', 'rates', 'pricing', 'cost', 'fee']
        for keyword in pricing_keywords:
            # Look for pricing sections
            elements = soup.find_all(text=re.compile(keyword, re.I))
            for element in elements:
                parent = element.parent
                if parent:
                    # Look for dollar amounts near pricing keywords
                    price_text = parent.get_text()
                    # Find weekday/weekend prices
                    weekday_match = re.search(r'weekday[^$]*\$(\d+)', price_text, re.I)
                    weekend_match = re.search(r'weekend[^$]*\$(\d+)', price_text, re.I)
                    
                    if weekday_match:
                        self.course_data['weekday_fees'] = f"${weekday_match.group(1)}"
                        self.log_status(f"Found weekday fees: ${weekday_match.group(1)}")
                    if weekend_match:
                        self.course_data['weekend_fees'] = f"${weekend_match.group(1)}"
                        self.log_status(f"Found weekend fees: ${weekend_match.group(1)}")
                        
        # Look for general price patterns if specific ones not found
        if not self.course_data['weekday_fees']:
            price_matches = re.findall(r'\$(\d{2,3})', page_text)
            if price_matches:
                # Use first reasonable price found
                for price in price_matches:
                    if 15 <= int(price) <= 150:  # Reasonable golf fee range
                        self.course_data['weekday_fees'] = f"${price}"
                        self.log_status(f"Found general fees: ${price}")
                        break
                        
        # Extract yardage and par - improved patterns
        yardage_patterns = [
            r'(\d{4,5})\s*yards?',
            r'yardage[:\s]*(\d{4,5})',
            r'(\d{4,5})\s*yds?'
        ]
        
        for pattern in yardage_patterns:
            yardage_match = re.search(pattern, page_text, re.I)
            if yardage_match:
                self.course_data['yardage'] = yardage_match.group(1)
                self.log_status(f"Found yardage: {yardage_match.group(1)}")
                break
                
        # Par extraction - more flexible
        par_patterns = [
            r'par\s*(\d{2})',
            r'par[:\s]*(\d{2})',
            r'(\d{2})\s*par'
        ]
        
        for pattern in par_patterns:
            par_match = re.search(pattern, page_text, re.I)
            if par_match:
                par_val = int(par_match.group(1))
                if 27 <= par_val <= 72:  # Reasonable par range
                    self.course_data['par'] = str(par_val)
                    self.log_status(f"Found par: {par_val}")
                    break
                    
        # Extract designer
        designer_keywords = ['designed by', 'architect', 'designer', 'designed', 'course architect']
        for keyword in designer_keywords:
            designer_match = re.search(rf'{keyword}\s*:?\s*([A-Za-z\s&.]+?)(?:\.|,|\n|$)', page_text, re.I)
            if designer_match:
                designer = designer_match.group(1).strip()
                if len(designer) < 50 and not any(word in designer.lower() for word in ['the', 'this', 'our', 'features']):
                    self.course_data['designer'] = designer
                    self.log_status(f"Found designer: {designer}")
                    break
                    
        # Extract year opened
        year_patterns = [
            r'(?:opened|built|established|founded)\s*(?:in\s*)?(\d{4})',
            r'(\d{4})\s*(?:opening|establishment)',
            r'since\s*(\d{4})'
        ]
        
        for pattern in year_patterns:
            year_match = re.search(pattern, page_text, re.I)
            if year_match:
                year = int(year_match.group(1))
                if 1890 <= year <= 2024:  # Reasonable year range
                    self.course_data['year_opened'] = str(year)
                    self.log_status(f"Found year opened: {year}")
                    break
                    
        # Set website
        self.course_data['website'] = url
        
        # Try to determine course type
        text_lower = page_text.lower()
        if 'private' in text_lower and 'club' in text_lower:
            self.course_data['course_type'] = 'Private'
        elif 'municipal' in text_lower:
            self.course_data['course_type'] = 'Municipal Public'
        elif 'semi-private' in text_lower or 'semi private' in text_lower:
            self.course_data['course_type'] = 'Semi-Private'
        else:
            self.course_data['course_type'] = 'Public'  # Default
            
        self.log_status(f"Set course type: {self.course_data['course_type']}")
    
    # Helper extraction functions for multi-page scraping
    def _extract_course_name(self, soup):
        """Extract course name from soup"""
        # Method 1: Look for h1 tags first - be more selective
        h1_tags = soup.find_all('h1')
        for h1 in h1_tags:
            text = h1.get_text().strip()
            # Must contain "golf" and not contain unwanted words
            if ('golf' in text.lower() and len(text) < 100 and 
                not any(word in text.lower() for word in ['event', 'charity', 'tournament', 'lesson', 'tip'])):
                return text
                
        # Method 2: Look for specific golf course name patterns
        # Try to find text that matches "Something Golf Club" pattern
        page_text = soup.get_text()
        golf_patterns = [
            r'([A-Z][a-zA-Z\s]+Golf\s+Club)',
            r'([A-Z][a-zA-Z\s]+Golf\s+Course)', 
            r'(Cheekwood\s+Golf\s+Club)',  # Specific for Cheekwood
            r'([A-Z][a-zA-Z\s]{3,30})\s+Golf\s+Club',
            r'([A-Z][a-zA-Z\s]{3,30})\s+Golf\s+Course',
        ]
        
        for pattern in golf_patterns:
            matches = re.findall(pattern, page_text, re.IGNORECASE)
            for match in matches:
                if (len(match) < 50 and len(match) > 5 and
                    not any(word in match.lower() for word in ['event', 'charity', 'tournament', 'lesson'])):
                    return match.strip()
                    
        # Method 3: Try title tag - clean it better
        title = soup.find('title')
        if title:
            title_text = title.get_text().strip()
            # Remove everything after dash and common suffixes
            title_text = re.sub(r'\s*[-|]\s*.*$', '', title_text)
            title_text = re.sub(r'\s*(home|welcome|golf).*$', '', title_text, flags=re.I)
            if len(title_text) > 5 and len(title_text) < 50:
                return title_text
            
        # Method 4: Meta tags
        meta_title = soup.find('meta', attrs={'property': 'og:title'})
        if meta_title:
            content = meta_title.get('content', '').strip()
            if 'golf' in content.lower() and len(content) < 50:
                return content
        
        return None
    
    def _extract_phone(self, page_text):
        """Extract phone number from page text"""
        phone_pattern = r'\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}'
        phones = re.findall(phone_pattern, page_text)
        if phones:
            phone = re.sub(r'[^\d]', '', phones[0])
            return f"({phone[:3]}) {phone[3:6]}-{phone[6:]}"
        return None
    
    def _extract_address(self, page_text):
        """Extract address from page text"""
        # Multiple address patterns to handle different formats
        address_patterns = [
            # Standard: 123 Main St, City, ST 12345
            r'\b\d+\s+[A-Za-z\s]+(?:Street|St|Drive|Dr|Road|Rd|Avenue|Ave|Lane|Ln|Boulevard|Blvd|Way|Pike|Circle|Cir|Trail|Tr)\b[^,\n]*,\s*[A-Za-z\s]+,\s*[A-Z]{2}\s+\d{5}',
            
            # Cheekwood format: 285 Spencer Creek Rd Franklin TN, 37069  
            r'\b\d+\s+[A-Za-z\s]+(?:Street|St|Drive|Dr|Road|Rd|Avenue|Ave|Lane|Ln|Boulevard|Blvd|Way|Pike|Circle|Cir|Trail|Tr)\s+[A-Za-z\s]+\s+[A-Z]{2},?\s*\d{5}',
            
            # Without zip: 123 Main St, City, ST  
            r'\b\d+\s+[A-Za-z\s]+(?:Street|St|Drive|Dr|Road|Rd|Avenue|Ave|Lane|Ln|Boulevard|Blvd|Way|Pike|Circle|Cir|Trail|Tr)\b[^,\n]*,\s*[A-Za-z\s]+,\s*[A-Z]{2}\b',
            
            # Alternative format: 123 Main St City ST
            r'\b\d+\s+[A-Za-z\s]+(?:Street|St|Drive|Dr|Road|Rd|Avenue|Ave|Lane|Ln|Boulevard|Blvd|Way|Pike|Circle|Cir|Trail|Tr)\s+[A-Za-z\s]+\s+[A-Z]{2}\b',
            
            # Simpler: number + words + city, ST
            r'\b\d+\s+[A-Za-z\s]+,\s*[A-Za-z\s]+,\s*[A-Z]{2}\b'
        ]
        
        for pattern in address_patterns:
            matches = re.findall(pattern, page_text)
            for match in matches:
                address = match.strip()
                # Validate it looks like a real address
                if (len(address) > 15 and len(address) < 100 and
                    not any(word in address.lower() for word in ['par', 'golf', 'hole', 'yard', 'green', 'course', 'features'])):
                    # Clean up the address format
                    # Convert "285 Spencer Creek Rd Franklin TN, 37069" to standard format
                    address = re.sub(r'\s+', ' ', address)  # normalize spaces
                    return address
        
        return None
    
    def _extract_pricing(self, page_text):
        """Extract pricing information from page text"""
        pricing = {'weekday': None, 'weekend': None, 'cart': None}
        
        # Look for specific weekday/weekend prices
        weekday_match = re.search(r'weekday[^$]*\$(\d+)', page_text, re.I)
        weekend_match = re.search(r'weekend[^$]*\$(\d+)', page_text, re.I)
        cart_match = re.search(r'cart[^$]*\$(\d+)', page_text, re.I)
        
        if weekday_match:
            pricing['weekday'] = f"${weekday_match.group(1)}"
        if weekend_match:
            pricing['weekend'] = f"${weekend_match.group(1)}"
        if cart_match:
            pricing['cart'] = f"${cart_match.group(1)}"
            
        # If no specific prices found, look for general pricing
        if not pricing['weekday']:
            price_matches = re.findall(r'\$(\d{2,3})', page_text)
            for price in price_matches:
                if 15 <= int(price) <= 150:
                    pricing['weekday'] = f"${price}"
                    break
                    
        return pricing
    
    def _extract_yardage(self, page_text):
        """Extract yardage from page text"""
        yardage_patterns = [
            r'(\d{4,5})\s*yards?',
            r'yardage[:\s]*(\d{4,5})',
            r'(\d{4,5})\s*yds?'
        ]
        
        for pattern in yardage_patterns:
            match = re.search(pattern, page_text, re.I)
            if match:
                return match.group(1)
        return None
    
    def _extract_par(self, page_text):
        """Extract par from page text"""
        par_patterns = [
            r'par\s*(\d{2})',
            r'par[:\s]*(\d{2})',
            r'(\d{2})\s*par'
        ]
        
        for pattern in par_patterns:
            match = re.search(pattern, page_text, re.I)
            if match:
                par_val = int(match.group(1))
                if 27 <= par_val <= 72:
                    return str(par_val)
        return None
    
    def _extract_designer(self, page_text):
        """Extract designer from page text"""
        designer_keywords = ['designed by', 'architect', 'designer', 'designed', 'course architect']
        for keyword in designer_keywords:
            match = re.search(rf'{keyword}\s*:?\s*([A-Za-z\s&.]+?)(?:\.|,|\n|$)', page_text, re.I)
            if match:
                designer = match.group(1).strip()
                if len(designer) < 50 and not any(word in designer.lower() for word in ['the', 'this', 'our', 'features']):
                    return designer
        return None
    
    def _extract_year(self, page_text):
        """Extract year opened from page text"""
        year_patterns = [
            r'(?:opened|built|established|founded)\s*(?:in\s*)?(\d{4})',
            r'(\d{4})\s*(?:opening|establishment)',
            r'since\s*(\d{4})'
        ]
        
        for pattern in year_patterns:
            match = re.search(pattern, page_text, re.I)
            if match:
                year = int(match.group(1))
                if 1890 <= year <= 2024:
                    return str(year)
        return None
    
    def _extract_course_type(self, page_text):
        """Extract course type from page text"""
        text_lower = page_text.lower()
        if 'private' in text_lower and 'club' in text_lower:
            return 'Private'
        elif 'municipal' in text_lower:
            return 'Municipal Public'
        elif 'semi-private' in text_lower or 'semi private' in text_lower:
            return 'Semi-Private'
        else:
            return 'Public'
        
    def _populate_fields(self):
        """Populate the form fields with scraped data"""
        for key, entry in self.course_fields.items():
            if key in self.course_data and self.course_data[key]:
                entry.delete(0, tk.END)
                entry.insert(0, str(self.course_data[key]))
                
    def auto_generate_maps(self):
        """Auto-generate Google Maps search query from course name and city"""
        course_name = self.course_fields['course_name'].get().strip()
        city = self.course_fields['location_city'].get().strip()
        
        if course_name and city:
            maps_query = f"{course_name} {city} TN"
            self.maps_entry.delete(0, tk.END)
            self.maps_entry.insert(0, maps_query)
        elif course_name:
            maps_query = f"{course_name} TN"
            self.maps_entry.delete(0, tk.END)
            self.maps_entry.insert(0, maps_query)
        else:
            messagebox.showwarning("Warning", "Please fill in Course Name first")
    
    def log_status(self, message):
        """Add message to status log"""
        timestamp = time.strftime("%H:%M:%S")
        self.status_text.insert(tk.END, f"[{timestamp}] {message}\n")
        self.status_text.see(tk.END)
        self.root.update_idletasks()
        
    def generate_php_file(self):
        """Generate the PHP file using Bear Trace template"""
        try:
            # Collect all data from form
            self._collect_form_data()
            
            # Generate PHP content
            php_content = self._generate_php_content()
            
            # Save file
            filename = f"{self.course_data['course_slug']}.php"
            filepath = os.path.join(self.output_dir.get(), filename)
            
            with open(filepath, 'w', encoding='utf-8') as f:
                f.write(php_content)
                
            # Show preview
            self.preview_text.delete(1.0, tk.END)
            self.preview_text.insert(1.0, php_content[:2000] + "..." if len(php_content) > 2000 else php_content)
            
            messagebox.showinfo("Success", f"PHP file generated successfully!\nSaved to: {filepath}")
            
        except Exception as e:
            messagebox.showerror("Error", f"Failed to generate PHP file: {str(e)}")
            
    def _collect_form_data(self):
        """Collect data from all form fields"""
        for key, entry in self.course_fields.items():
            self.course_data[key] = entry.get().strip()
            
        self.course_data['description'] = self.description_text.get(1.0, tk.END).strip()
        self.course_data['special_features'] = self.features_text.get(1.0, tk.END).strip()
        self.course_data['maps_query'] = self.maps_entry.get().strip()
        
        # Generate slug if not set
        if not self.course_data['course_slug'] and self.course_data['course_name']:
            slug = re.sub(r'[^a-zA-Z0-9\s]', '', self.course_data['course_name'].lower())
            slug = re.sub(r'\s+', '-', slug.strip())
            self.course_data['course_slug'] = slug
            
    def _generate_php_content(self):
        """Generate PHP file content using Bear Trace template structure"""
        
        # Read Bear Trace template
        template_path = "/Users/entity./TGC LLC/courses/bear-trace-harrison-bay.php"
        try:
            with open(template_path, 'r', encoding='utf-8') as f:
                template_content = f.read()
        except FileNotFoundError:
            raise Exception(f"Template file not found: {template_path}")
            
        # Get form data
        course_name = self.course_data['course_name'] or 'Golf Course'
        course_slug = self.course_data['course_slug'] or 'golf-course'
        designer = self.course_data['designer'] or 'Unknown'
        year_opened = self.course_data['year_opened'] or 'Unknown'
        par = self.course_data['par'] or '72'
        yardage = self.course_data['yardage'] or '6,500'
        course_type = self.course_data['course_type'] or 'Public'
        city = self.course_data['location_city'] or 'Tennessee'
        address = self.course_data['address'] or ''
        phone = self.course_data['phone'] or ''
        website = self.course_data['website'] or ''
        description = self.course_data['description'] or f'{course_name} offers a challenging golf experience.'
        
        # Comprehensive replacements
        replacements = {
            # Basic identifiers
            'bear-trace-harrison-bay': course_slug,
            'Bear Trace at Harrison Bay': course_name,
            'Harrison, TN': f"{city}, TN",
            'Harrison': city,
            
            # Course specifications  
            'Jack Nicklaus': designer,
            '1999': year_opened,
            '72': par,
            '7,313': yardage.replace(',', ''),
            
            # Contact info
            '(423) 326-0885': phone,
            'https://tnstateparks.com/golf/course/bear-trace-at-harrison-bay/': website,
            
            # Course type and descriptions
            'Public': course_type,
            'Jack Nicklaus Signature Design': f'{designer} Design' if designer != 'Unknown' else 'Championship Course',
            
            # Address
            '2650 Bear Trace Blvd, Harrison, TN 37341': address,
            
            # Google Maps query
            'Bear+Trace+at+Harrison+Bay+Harrison+TN': self.course_data.get('maps_query', f'{course_name}+{city}+TN').replace(' ', '+'),
        }
        
        content = template_content
        for old, new in replacements.items():
            if new:  # Only replace if we have a value
                content = content.replace(old, str(new))
            
        # Replace course description sections
        if description:
            # Find and replace the main course description paragraph
            desc_pattern = r'<p>Bear Trace.*?championship course\.</p>'
            new_desc = f'<p>{description}</p>'
            content = re.sub(desc_pattern, new_desc, content, flags=re.DOTALL)
            
        # Add pricing information if available
        if self.course_data['weekday_fees']:
            # Replace pricing sections with actual data
            content = content.replace('$45-$75', self.course_data['weekday_fees'])
            
        return content

def main():
    root = tk.Tk()
    app = GolfCourseGenerator(root)
    root.mainloop()

if __name__ == "__main__":
    main()