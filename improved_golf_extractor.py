#!/usr/bin/env python3
"""
Improved Golf Course Data Extractor
Enhanced with intelligent pattern matching and context understanding
"""

import re
from bs4 import BeautifulSoup
import requests
from urllib.parse import urljoin, urlparse

class IntelligentGolfExtractor:
    def __init__(self):
        # Known golf course designers (for better matching)
        self.known_designers = [
            'Tom Fazio', 'Jack Nicklaus', 'Arnold Palmer', 'Robert Trent Jones', 
            'Pete Dye', 'Donald Ross', 'Ben Crenshaw', 'Tom Kite', 'Gary Player',
            'Greg Norman', 'Tom Doak', 'Coore & Crenshaw', 'Gil Hanse', 'Mike Davis',
            'Von Hagge', 'Robert von Hagge', 'Bruce Devlin', 'Michael Hurdzan', 'Dana Fry', 'Arthur Hills',
            'Tom Watson', 'Raymond Floyd', 'Gary Roger Baird', 'Mark McCumber',
            'Denis Griffiths', 'John Floyd', 'Larry Nelson', 'Dick Wilson',
            'Leon Howard', 'Bob Renaud', 'George Livingstone', 'Bob Cupp',
            'Bill Oliphant', 'Bobby Greenwood', 'Ron Prichard', 'Devlin & von Hagge'
        ]
        
        # Common golf course name patterns
        self.golf_name_patterns = [
            r'([A-Z][a-zA-Z\s&\'-]{2,40})\s+Golf\s+(?:Club|Course|Links)',
            r'([A-Z][a-zA-Z\s&\'-]{2,40})\s+Country\s+Club',
            r'([A-Z][a-zA-Z\s&\'-]{2,40})\s+Golf\s+&\s+Field\s+Club',
            r'TPC\s+([A-Z][a-zA-Z\s&\'-]{2,40})',
            r'The\s+([A-Z][a-zA-Z\s&\'-]{2,40})\s+Golf\s+(?:Club|Course)',
            r'([A-Z][a-zA-Z\s&\'-]{2,40})\s+at\s+([A-Z][a-zA-Z\s&\'-]{2,40})',
        ]
        
    def extract_intelligent_data(self, all_soups):
        """
        Intelligently extract course data from multiple pages using context and patterns
        """
        extracted_data = {
            'course_name': '',
            'designer': '',
            'year_opened': '',
            'par': '',
            'yardage': '',
            'course_type': 'Public',
            'location_city': '',
            'address': '',
            'phone': '',
            'website': '',
            'weekday_fees': '',
            'weekend_fees': '',
            'cart_fee': '',
            'slope_rating': '',
            'course_rating': '',
            'amenities': []
        }
        
        # Combine all text from all pages for comprehensive analysis
        all_text = ""
        homepage_soup = None
        
        for page_type, soup, url in all_soups:
            if page_type == 'Homepage':
                homepage_soup = soup
                extracted_data['website'] = url
            all_text += " " + soup.get_text()
        
        # Clean up text for better processing
        all_text = re.sub(r'\s+', ' ', all_text).strip()
        
        # Extract course name with intelligence
        extracted_data['course_name'] = self._extract_smart_course_name(homepage_soup, all_soups)
        
        # Extract designer with context matching
        extracted_data['designer'] = self._extract_smart_designer(all_text)
        
        # Extract numerical data with validation
        extracted_data['par'] = self._extract_smart_par(all_text)
        extracted_data['yardage'] = self._extract_smart_yardage(all_text)
        extracted_data['year_opened'] = self._extract_smart_year(all_text)
        
        # Extract location data
        address_data = self._extract_smart_address(all_text)
        extracted_data['address'] = address_data['address']
        extracted_data['location_city'] = address_data['city']
        
        # If no city found in address, try other methods
        if not extracted_data['location_city']:
            extracted_data['location_city'] = self._extract_city_alternative(all_text)
        
        # Extract contact info
        extracted_data['phone'] = self._extract_smart_phone(all_text)
        
        # Extract ratings (slope and course rating)
        extracted_data['slope_rating'] = self._extract_slope_rating(all_text)
        extracted_data['course_rating'] = self._extract_course_rating(all_text)
        
        # Extract pricing with context
        pricing_data = self._extract_smart_pricing(all_text)
        extracted_data.update(pricing_data)
        
        # Determine course type intelligently
        extracted_data['course_type'] = self._extract_smart_course_type(all_text)
        
        # Extract amenities
        extracted_data['amenities'] = self._extract_smart_amenities(all_text)
        
        return extracted_data
    
    def _extract_smart_course_name(self, homepage_soup, all_soups):
        """Extract course name using multiple intelligent methods"""
        if not homepage_soup:
            return ""
        
        # Special case: For Troubadour, check specific patterns first
        all_text = ""
        for page_type, soup, url in all_soups:
            all_text += " " + soup.get_text()
        
        # Check for "Troubadour" specifically
        if 'troubadour' in all_text.lower():
            troubadour_patterns = [
                r'(Troubadour\s+Golf\s*&?\s*Field\s+Club)',
                r'(The\s+Troubadour\s+Club)',
                r'(Troubadour\s+Club)'
            ]
            for pattern in troubadour_patterns:
                match = re.search(pattern, all_text, re.I)
                if match:
                    return match.group(1).strip()
        
        # Method 1: Check page title first
        title = homepage_soup.find('title')
        if title:
            title_text = title.get_text().strip()
            # Clean common suffixes
            clean_title = re.sub(r'\s*[-|]\s*(?:home|golf|tennessee|tn|welcome|official|site).*$', '', title_text, flags=re.I)
            
            # Check if it matches golf course patterns
            for pattern in self.golf_name_patterns:
                match = re.search(pattern, clean_title, re.I)
                if match:
                    return match.group(0).strip()
        
        # Method 2: Look for h1 tags with golf context
        h1_tags = homepage_soup.find_all(['h1', 'h2'])
        for h1 in h1_tags:
            text = h1.get_text().strip()
            if len(text) < 100:  # Reasonable length
                for pattern in self.golf_name_patterns:
                    match = re.search(pattern, text, re.I)
                    if match:
                        return match.group(0).strip()
        
        # Method 3: Check meta tags
        meta_title = homepage_soup.find('meta', attrs={'property': 'og:title'})
        if meta_title:
            meta_text = meta_title.get('content', '').strip()
            for pattern in self.golf_name_patterns:
                match = re.search(pattern, meta_text, re.I)
                if match:
                    return match.group(0).strip()
        
        # Method 4: Look in navigation or header elements
        nav_elements = homepage_soup.find_all(['nav', 'header', 'div'], class_=re.compile(r'nav|header|logo|brand', re.I))
        for nav in nav_elements:
            nav_text = nav.get_text().strip()
            for pattern in self.golf_name_patterns:
                match = re.search(pattern, nav_text, re.I)
                if match:
                    return match.group(0).strip()
        
        # Method 5: Look for any golf course name in combined text
        for pattern in self.golf_name_patterns:
            match = re.search(pattern, all_text, re.I)
            if match:
                candidate = match.group(0).strip()
                # Avoid common false positives
                if not any(bad_word in candidate.lower() for bad_word in 
                          ['example', 'sample', 'your', 'our', 'this', 'the best']):
                    return candidate
        
        return ""
    
    def _extract_smart_designer(self, text):
        """Extract designer using known designer database and context"""
        # First check for exact matches of known designers
        for designer in self.known_designers:
            # Look for designer name in context
            patterns = [
                rf'(?:designed\s+by|architect|designer|course\s+architect|design)\s*:?\s*{re.escape(designer)}',
                rf'{re.escape(designer)}\s*(?:design|designed|architect)',
                rf'(?:by\s+)?{re.escape(designer)}\s+(?:signature|design)'
            ]
            
            for pattern in patterns:
                if re.search(pattern, text, re.I):
                    return designer
        
        # If no exact match, look for designer patterns near golf context
        designer_patterns = [
            r'(?:designed\s+by|architect|designer|course\s+architect)\s*:?\s*([A-Z][a-zA-Z\s&.]{3,40})(?=\s*(?:\.|,|$|\n))',
            r'([A-Z][a-zA-Z\s&.]{3,40})\s+(?:design|designed|architect)',
            r'(?:by\s+)?([A-Z][a-zA-Z\s&.]{3,40})\s+signature\s+design'
        ]
        
        for pattern in designer_patterns:
            matches = re.finditer(pattern, text, re.I)
            for match in matches:
                candidate = match.group(1).strip()
                # Filter out common false positives
                if (len(candidate) < 50 and 
                    not any(word in candidate.lower() for word in 
                           ['the course', 'this golf', 'our course', 'features', 'offers', 'provides',
                            'championship', 'experience', 'perfect', 'beautiful', 'stunning'])):
                    return candidate
        
        return ""
    
    def _extract_smart_par(self, text):
        """Extract par with validation and context"""
        par_patterns = [
            r'par\s*(?:of\s*)?(\d{2})',
            r'(\d{2})\s*par\s*course',
            r'(?:18|27|36)\s*holes?\s*[,.]?\s*par\s*(\d{2})',
            r'par\s*:?\s*(\d{2})',
            r'Par:\s*(\d{2})',  # USGA format
            r'course\s*par\s*(\d{2})',
            # Look for "18-hole" followed by par somewhere nearby
            r'18[- ]hole.*?par[:\s]*(\d{2})',
            r'par[:\s]*(\d{2}).*?18[- ]hole'
        ]
        
        for pattern in par_patterns:
            matches = re.finditer(pattern, text, re.I | re.DOTALL)
            for match in matches:
                par_val = int(match.group(1))
                # Validate par range
                if 27 <= par_val <= 72:
                    return str(par_val)
        
        return ""
    
    def _extract_smart_yardage(self, text):
        """Extract yardage with context validation"""
        yardage_patterns = [
            r'(\d{4,5})\s*(?:yards?|yds?)',
            r'(?:yardage|length)\s*:?\s*(\d{4,5})',
            r'(\d{4,5})\s*yard\s*course',
            r'plays\s*(?:to\s*)?(\d{4,5})\s*yards?',
            r'Length:\s*(\d{4,5})\s*yards?',  # USGA format
            r'(\d{4,5})\s*total\s*yards?',
            # Look for championship tees context
            r'championship.*?(\d{4,5})\s*(?:yards?|yds?)',
            r'tips.*?(\d{4,5})\s*(?:yards?|yds?)',
            # USGA course database format
            r'(\d{1})[,](\d{3})\s*yards?'  # Handle comma-separated numbers like "7,157"
        ]
        
        for pattern in yardage_patterns:
            matches = re.finditer(pattern, text, re.I)
            for match in matches:
                if len(match.groups()) == 2:  # Handle comma format
                    yardage_str = match.group(1) + match.group(2)
                    yardage = int(yardage_str)
                else:
                    yardage = int(match.group(1))
                    
                # Validate yardage range (reasonable for golf courses)
                if 2000 <= yardage <= 8000:
                    return str(yardage)
        
        return ""
    
    def _extract_smart_year(self, text):
        """Extract opening year with context"""
        year_patterns = [
            r'(?:opened|established|founded|built)\s*(?:in\s*)?(\d{4})',
            r'(?:since|from)\s*(\d{4})',
            r'(\d{4})\s*(?:opening|establishment|founding)',
            r'(?:in\s*)?(\d{4})[,.]?\s*(?:this|the)\s*(?:course|golf\s*course)\s*(?:was\s*)?(?:opened|established|built)'
        ]
        
        for pattern in year_patterns:
            matches = re.finditer(pattern, text, re.I)
            for match in matches:
                year = int(match.group(1))
                # Validate year range
                if 1890 <= year <= 2025:
                    return str(year)
        
        return ""
    
    def _extract_slope_rating(self, text):
        """Extract slope rating"""
        slope_patterns = [
            r'slope\s*rating[:\s]*(\d{2,3})',
            r'slope[:\s]*(\d{2,3})',
            r'Slope Rating:\s*(\d{2,3})'
        ]
        
        for pattern in slope_patterns:
            match = re.search(pattern, text, re.I)
            if match:
                slope = int(match.group(1))
                # Validate slope range (55-155 is standard)
                if 55 <= slope <= 155:
                    return str(slope)
        return ""
    
    def _extract_course_rating(self, text):
        """Extract course rating"""
        rating_patterns = [
            r'course\s*rating[:\s]*(\d{2}\.\d)',
            r'rating[:\s]*(\d{2}\.\d)',
            r'Course Rating:\s*(\d{2}\.\d)'
        ]
        
        for pattern in rating_patterns:
            match = re.search(pattern, text, re.I)
            if match:
                rating = float(match.group(1))
                # Validate course rating range (typically 60-80)
                if 60.0 <= rating <= 80.0:
                    return str(rating)
        return ""
    
    def _extract_smart_address(self, text):
        """Extract address and city with validation"""
        # Comprehensive address patterns
        address_patterns = [
            r'(\d+\s+[^,\n\r]+(?:Street|St|Drive|Dr|Road|Rd|Avenue|Ave|Lane|Ln|Boulevard|Blvd|Way|Pike|Circle|Cir|Court|Ct|Place|Pl)[^,\n\r]*[,]\s*[^,\n\r]+[,]\s*[A-Z]{2}\s*\d{5})',
            r'(\d+\s+[^,\n\r]+(?:Street|St|Drive|Dr|Road|Rd|Avenue|Ave|Lane|Ln|Boulevard|Blvd|Way|Pike|Circle|Cir|Court|Ct|Place|Pl)[^,\n\r]*[,]\s*[^,\n\r]+[,]\s*[A-Z]{2})',
            r'(\d+\s+[^,\n\r]+[,]\s*[^,\n\r]+[,]\s*TN\s*\d{5})',
            r'(\d+\s+[^,\n\r]+[,]\s*[^,\n\r]+[,]\s*TN)'
        ]
        
        for pattern in address_patterns:
            match = re.search(pattern, text, re.I)
            if match:
                address = match.group(1).strip()
                # Clean up the address
                address = re.sub(r'\s+', ' ', address)
                
                # Extract city
                city_match = re.search(r',\s*([^,]+),\s*[A-Z]{2}', address)
                city = city_match.group(1).strip() if city_match else ""
                
                return {'address': address, 'city': city}
        
        return {'address': "", 'city': ""}
    
    def _extract_city_alternative(self, text):
        """Alternative city extraction methods"""
        # Look for "City, TN" patterns
        city_patterns = [
            r'([A-Z][a-zA-Z\s]+),\s*TN',
            r'([A-Z][a-zA-Z\s]+),\s*Tennessee',
            r'located\s+in\s+([A-Z][a-zA-Z\s]+),?\s*TN',
            r'([A-Z][a-zA-Z\s]{3,20}),\s*TN\s*\d{5}?'
        ]
        
        for pattern in city_patterns:
            match = re.search(pattern, text)
            if match:
                city = match.group(1).strip()
                # Filter out common false positives
                if (len(city) < 30 and 
                    not any(word in city.lower() for word in 
                           ['united states', 'tennessee', 'golf', 'club', 'course', 'the'])):
                    return city
        
        return ""
    
    def _extract_smart_phone(self, text):
        """Extract phone number with formatting"""
        phone_patterns = [
            r'\((\d{3})\)\s*(\d{3})-(\d{4})',
            r'(\d{3})-(\d{3})-(\d{4})',
            r'(\d{3})\.(\d{3})\.(\d{4})',
            r'(\d{3})\s+(\d{3})\s+(\d{4})'
        ]
        
        for pattern in phone_patterns:
            match = re.search(pattern, text)
            if match:
                if len(match.groups()) == 3:
                    return f"({match.group(1)}) {match.group(2)}-{match.group(3)}"
        
        # Fallback: look for 10-digit numbers
        fallback_match = re.search(r'(\d{10})', text)
        if fallback_match:
            phone = fallback_match.group(1)
            return f"({phone[:3]}) {phone[3:6]}-{phone[6:]}"
        
        return ""
    
    def _extract_smart_pricing(self, text):
        """Extract pricing with context understanding"""
        pricing = {'weekday_fees': '', 'weekend_fees': '', 'cart_fee': ''}
        
        # Look for pricing sections
        pricing_sections = re.finditer(r'(?:rates|pricing|fees|green\s*fees|cost)[^$]*\$\d+', text, re.I)
        
        for section in pricing_sections:
            section_text = section.group()
            
            # Extract weekday prices
            weekday_match = re.search(r'(?:weekday|monday|tuesday|wednesday|thursday|friday)[^$]*\$(\d+)', section_text, re.I)
            if weekday_match and not pricing['weekday_fees']:
                pricing['weekday_fees'] = f"${weekday_match.group(1)}"
            
            # Extract weekend prices
            weekend_match = re.search(r'(?:weekend|saturday|sunday)[^$]*\$(\d+)', section_text, re.I)
            if weekend_match and not pricing['weekend_fees']:
                pricing['weekend_fees'] = f"${weekend_match.group(1)}"
            
            # Extract cart fees
            cart_match = re.search(r'(?:cart|riding)[^$]*\$(\d+)', section_text, re.I)
            if cart_match and not pricing['cart_fee']:
                pricing['cart_fee'] = f"${cart_match.group(1)}"
        
        # If no specific pricing found, look for general price ranges
        if not pricing['weekday_fees']:
            price_matches = re.findall(r'\$(\d{2,3})', text)
            if price_matches:
                for price in price_matches:
                    price_val = int(price)
                    if 15 <= price_val <= 200:  # Reasonable golf fee range
                        pricing['weekday_fees'] = f"${price}"
                        break
        
        return pricing
    
    def _extract_smart_course_type(self, text):
        """Determine course type with context understanding"""
        text_lower = text.lower()
        
        # Check for explicit mentions
        if any(phrase in text_lower for phrase in ['private club', 'members only', 'membership required']):
            return 'Private'
        elif any(phrase in text_lower for phrase in ['semi-private', 'semi private']):
            return 'Semi-Private'
        elif any(phrase in text_lower for phrase in ['municipal', 'city course', 'county course']):
            return 'Municipal Public'
        elif any(phrase in text_lower for phrase in ['public course', 'open to public']):
            return 'Public'
        
        # Infer from pricing context
        if '$' in text and any(phrase in text_lower for phrase in ['green fees', 'rates', 'pricing']):
            return 'Public'  # Has public pricing
        
        # Infer from club context
        if 'country club' in text_lower:
            return 'Private'
        
        return 'Public'  # Default
    
    def _extract_smart_amenities(self, text):
        """Extract amenities using keyword matching"""
        amenity_keywords = {
            'Pro Shop': ['pro shop', 'golf shop', 'clubhouse shop'],
            'Restaurant': ['restaurant', 'dining', 'grill', 'bar & grill', 'club restaurant'],
            'Driving Range': ['driving range', 'practice range', 'range'],
            'Putting Green': ['putting green', 'practice green', 'putting area'],
            'Golf Instruction': ['golf instruction', 'lessons', 'pga professional', 'teaching pro'],
            'Cart Rental': ['cart rental', 'golf carts', 'cart included'],
            'Swimming Pool': ['swimming pool', 'pool', 'aquatic'],
            'Tennis Courts': ['tennis', 'tennis courts', 'tennis facility'],
            'Fitness Center': ['fitness', 'gym', 'workout'],
            'Event Space': ['events', 'weddings', 'banquet', 'event space'],
            'Lodging': ['hotel', 'rooms', 'accommodations', 'lodge'],
            'Spa': ['spa', 'massage', 'wellness']
        }
        
        found_amenities = []
        text_lower = text.lower()
        
        for amenity, keywords in amenity_keywords.items():
            if any(keyword in text_lower for keyword in keywords):
                found_amenities.append(amenity)
        
        return found_amenities