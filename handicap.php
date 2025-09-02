<?php
require_once 'includes/init.php';
require_once 'includes/seo.php';

// Set up SEO for handicap page
SEO::setupHandicapPage();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php echo SEO::generateMetaTags(); ?>
    <?php echo SEO::generateNewsKeywords(['golf handicap', 'USGA', 'calculator', 'Tennessee', 'golf scoring', 'handicap index', 'golf rules']); ?>
    <link rel="stylesheet" href="/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <?php include 'includes/favicon.php'; ?>
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7VPNPCDTBP"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-7VPNPCDTBP');
    </script>
    
    <style>
        .handicap-page {
            padding-top: 0px;
            min-height: 100vh;
            background: var(--bg-light);
        }
        
        .handicap-hero {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 80px 0;
            text-align: center;
            margin-bottom: 80px;
        }
        
        .handicap-hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .handicap-hero p {
            font-size: 1.4rem;
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
        }
        
        .content-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            margin-bottom: 4rem;
        }
        
        .explanation-section {
            background: var(--bg-white);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
        }
        
        .explanation-section h2 {
            color: var(--primary-color);
            font-size: 2rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .explanation-section h3 {
            color: var(--primary-color);
            font-size: 1.4rem;
            margin: 2rem 0 1rem 0;
        }
        
        .explanation-section p {
            color: var(--text-gray);
            line-height: 1.7;
            margin-bottom: 1.5rem;
        }
        
        .explanation-section ul {
            color: var(--text-gray);
            line-height: 1.7;
            margin: 1rem 0;
            padding-left: 1.5rem;
        }
        
        .explanation-section li {
            margin-bottom: 0.5rem;
        }
        
        .calculator-section {
            background: var(--bg-white);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
            position: sticky;
            top: 120px;
        }
        
        .calculator-section h2 {
            color: var(--primary-color);
            font-size: 2rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .score-input-group {
            margin-bottom: 2rem;
        }
        
        .score-input-group label {
            display: block;
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .score-inputs {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .score-input {
            padding: 0.75rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            background: var(--bg-light);
        }
        
        .score-input:focus {
            border-color: var(--primary-color);
            outline: none;
            background: white;
        }
        
        .course-inputs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .course-input {
            padding: 0.75rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-weight: 500;
        }
        
        .course-input:focus {
            border-color: var(--primary-color);
            outline: none;
        }
        
        .calculate-btn {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 2rem;
        }
        
        .calculate-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }
        
        .result-display {
            background: var(--bg-light);
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            border-left: 4px solid var(--primary-color);
        }
        
        .result-display.show {
            display: block;
        }
        
        .handicap-result {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .result-label {
            color: var(--text-gray);
            font-weight: 500;
            font-size: 1.1rem;
        }
        
        .highlight-box {
            background: linear-gradient(135deg, rgba(6, 78, 59, 0.05), rgba(234, 88, 12, 0.05));
            padding: 2rem;
            border-radius: 15px;
            border-left: 5px solid var(--primary-color);
            margin: 2rem 0;
        }
        
        .info-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 4rem;
        }
        
        .info-card {
            background: var(--bg-white);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow-light);
            text-align: center;
            border-top: 4px solid var(--primary-color);
        }
        
        .info-card .icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .info-card h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .info-card p {
            color: var(--text-gray);
            line-height: 1.6;
        }
        
        @media (max-width: 768px) {
            .handicap-hero h1 {
                font-size: 2.5rem;
            }
            
            .content-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .calculator-section {
                position: static;
            }
            
            .course-inputs {
                grid-template-columns: 1fr;
            }
            
            .score-inputs {
                grid-template-columns: repeat(auto-fit, minmax(60px, 1fr));
            }
        }
    </style>
    
    <?php echo SEO::generateStructuredData(); ?>
</head>

<body>
    <div class="handicap-page">
        <!-- Dynamic Navigation -->
        <?php include 'includes/navigation.php'; ?>
        
        <!-- Handicap Hero Section -->
        <div class="handicap-hero">
            <h1>Golf Handicap System</h1>
            <p>Understand, calculate, and track your golf handicap index</p>
        </div>
        
        <div class="content-container">
            <div class="content-grid">
                <!-- Explanation Section -->
                <div class="explanation-section">
                    <h2><i class="fas fa-book"></i> What is a Golf Handicap?</h2>
                    
                    <p>A golf handicap is a numerical measure of a golfer's playing ability based on their past performance. It allows golfers of different skill levels to compete on an equal basis by adjusting scores relative to the difficulty of the course being played.</p>
                    
                    <h3>Why Do We Need Handicaps?</h3>
                    <p>Handicaps serve several important purposes:</p>
                    <ul>
                        <li><strong>Fair Competition:</strong> Allows golfers of different abilities to compete against each other</li>
                        <li><strong>Progress Tracking:</strong> Measures improvement over time</li>
                        <li><strong>Course Difficulty:</strong> Accounts for varying course conditions and layouts</li>
                        <li><strong>Tournament Play:</strong> Essential for most organized golf competitions</li>
                    </ul>
                    
                    <div class="highlight-box">
                        <p><strong>Did You Know?</strong> The USGA Handicap System is used worldwide and is based on your best 8 scores out of your most recent 20 rounds, ensuring your handicap reflects your current potential rather than just your average.</p>
                    </div>
                    
                    <h3>How is it Calculated?</h3>
                    <p>The modern USGA Handicap System uses a complex formula that considers:</p>
                    <ul>
                        <li><strong>Course Rating:</strong> The expected score for a scratch golfer</li>
                        <li><strong>Slope Rating:</strong> The relative difficulty for bogey golfers vs scratch golfers</li>
                        <li><strong>Adjusted Gross Score:</strong> Your score with Equitable Stroke Control applied</li>
                        <li><strong>Playing Conditions:</strong> Daily adjustments for weather and course conditions</li>
                    </ul>
                    
                    <p>The basic formula is: <em>(Adjusted Gross Score - Course Rating) × 113 ÷ Slope Rating</em></p>
                    
                    <h3>Handicap Ranges</h3>
                    <p>Handicap indexes typically range from:</p>
                    <ul>
                        <li><strong>+5 to 0:</strong> Professional/Expert level</li>
                        <li><strong>0 to 10:</strong> Very good amateur golfers</li>
                        <li><strong>10 to 20:</strong> Average club golfers</li>
                        <li><strong>20 to 36:</strong> Recreational/beginner golfers</li>
                    </ul>
                    
                    <h3>Tennessee Golf Association</h3>
                    <p>Many Tennessee golfers maintain their official handicap through the Tennessee Golf Association (TGA), which follows USGA guidelines and provides sanctioned handicap services for tournaments and competitions across the state.</p>
                </div>
                
                <!-- Calculator Section -->
                <div class="calculator-section">
                    <h2><i class="fas fa-calculator"></i> Handicap Calculator</h2>
                    
                    <div class="score-input-group">
                        <label>Enter Your Last 10 Scores:</label>
                        <div class="score-inputs" id="score-inputs">
                            <input type="number" class="score-input" placeholder="Score" min="50" max="150">
                            <input type="number" class="score-input" placeholder="Score" min="50" max="150">
                            <input type="number" class="score-input" placeholder="Score" min="50" max="150">
                            <input type="number" class="score-input" placeholder="Score" min="50" max="150">
                            <input type="number" class="score-input" placeholder="Score" min="50" max="150">
                            <input type="number" class="score-input" placeholder="Score" min="50" max="150">
                            <input type="number" class="score-input" placeholder="Score" min="50" max="150">
                            <input type="number" class="score-input" placeholder="Score" min="50" max="150">
                            <input type="number" class="score-input" placeholder="Score" min="50" max="150">
                            <input type="number" class="score-input" placeholder="Score" min="50" max="150">
                        </div>
                    </div>
                    
                    <div class="course-inputs">
                        <div>
                            <label for="course-rating">Course Rating:</label>
                            <input type="number" id="course-rating" class="course-input" placeholder="72.0" step="0.1" min="60" max="80" value="72.0">
                        </div>
                        <div>
                            <label for="slope-rating">Slope Rating:</label>
                            <input type="number" id="slope-rating" class="course-input" placeholder="113" min="55" max="155" value="113">
                        </div>
                    </div>
                    
                    <button class="calculate-btn" onclick="calculateHandicap()">
                        <i class="fas fa-calculator"></i> Calculate My Handicap
                    </button>
                    
                    <div class="result-display" id="result-display" style="display: none;">
                        <div class="handicap-result" id="handicap-result">0.0</div>
                        <div class="result-label">Estimated Handicap Index</div>
                        <p style="font-size: 0.9rem; color: var(--text-gray); margin-top: 1rem;">
                            <em>This is a simplified calculation. For official handicaps, join a golf association or club.</em>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Additional Info Cards -->
            <div class="info-cards">
                <div class="info-card">
                    <div class="icon">
                        <i class="fas fa-golf-ball"></i>
                    </div>
                    <h3>Course Rating</h3>
                    <p>The expected score for a scratch golfer on a given course under normal conditions. Most courses range from 67-75.</p>
                </div>
                
                <div class="info-card">
                    <div class="icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Slope Rating</h3>
                    <p>Measures course difficulty for bogey golfers relative to scratch golfers. Standard is 113, ranging from 55-155.</p>
                </div>
                
                <div class="info-card">
                    <div class="icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3>Official Handicaps</h3>
                    <p>For tournament play and official competitions, you'll need an official handicap through a recognized golf association.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Dynamic Footer -->
    <?php include 'includes/footer.php'; ?>
    
    <script>
        function calculateHandicap() {
            const scoreInputs = document.querySelectorAll('.score-input');
            const courseRating = parseFloat(document.getElementById('course-rating').value) || 72.0;
            const slopeRating = parseInt(document.getElementById('slope-rating').value) || 113;
            
            let scores = [];
            scoreInputs.forEach(input => {
                if (input.value && !isNaN(input.value)) {
                    scores.push(parseInt(input.value));
                }
            });
            
            if (scores.length < 3) {
                alert('Please enter at least 3 scores to calculate your handicap.');
                return;
            }
            
            // Calculate differentials
            let differentials = scores.map(score => {
                return (score - courseRating) * 113 / slopeRating;
            });
            
            // Sort differentials and take best ones based on number of scores
            differentials.sort((a, b) => a - b);
            
            let numberOfDifferentials;
            if (scores.length >= 20) {
                numberOfDifferentials = 8; // Best 8 of 20+
            } else if (scores.length >= 10) {
                numberOfDifferentials = Math.floor(scores.length * 0.4); // Best 40%
            } else if (scores.length >= 5) {
                numberOfDifferentials = Math.floor(scores.length * 0.3); // Best 30%
            } else {
                numberOfDifferentials = 1; // Best 1 for 3-4 scores
            }
            
            let bestDifferentials = differentials.slice(0, numberOfDifferentials);
            let averageDifferential = bestDifferentials.reduce((sum, diff) => sum + diff, 0) / bestDifferentials.length;
            
            let handicapIndex = Math.round(averageDifferential * 0.96 * 10) / 10;
            
            // Display result
            document.getElementById('handicap-result').textContent = handicapIndex.toFixed(1);
            document.getElementById('result-display').style.display = 'block';
            document.getElementById('result-display').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        // Add enter key support for score inputs
        document.querySelectorAll('.score-input').forEach((input, index) => {
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const nextInput = document.querySelectorAll('.score-input')[index + 1];
                    if (nextInput) {
                        nextInput.focus();
                    } else {
                        calculateHandicap();
                    }
                }
            });
        });
    </script>
</body>
</html>