<?php
// Simple Community Events System
$current_month = date('n');
$current_year = date('Y');

// Seasonal message
function getSeasonalMessage($month) {
    switch($month) {
        case 12: case 1: case 2:
            return "Winter golf season - perfect time to plan your spring rounds!";
        case 3: case 4: case 5:
            return "Spring golf season is here! Courses are opening up and tournament season begins.";
        case 6: case 7: case 8:
            return "Peak golf season in Tennessee - tournaments, perfect weather, and courses in prime condition.";
        case 9: case 10: case 11:
            return "Beautiful fall golf season - ideal weather and stunning course scenery.";
        default:
            return "Great time to explore Tennessee's amazing golf courses!";
    }
}

$seasonal_message = getSeasonalMessage($current_month);
?>

<div class="events-system" style="padding: 2rem 0;">
    <!-- Seasonal Banner -->
    <div style="background: linear-gradient(135deg, #2c5530, #8b4513); color: white; padding: 2rem; border-radius: 15px; text-align: center; margin-bottom: 3rem;">
        <div style="display: flex; align-items: center; justify-content: center; gap: 1rem; flex-wrap: wrap;">
            <i class="fas fa-calendar-alt" style="font-size: 1.5rem;"></i>
            <p style="margin: 0; font-size: 1.1rem;"><?php echo $seasonal_message; ?></p>
        </div>
    </div>

    <!-- Upcoming Events -->
    <div style="margin-bottom: 3rem;">
        <h3 style="color: #2c5530; font-size: 1.8rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-trophy"></i> Upcoming Events & Tournaments
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
            <div style="background: white; padding: 1.5rem; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-left: 4px solid #2c5530;">
                <div style="background: #2c5530; color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; display: inline-block; margin-bottom: 1rem;">
                    June <?php echo $current_year; ?>
                </div>
                <h5 style="color: #2c5530; font-size: 1.2rem; margin-bottom: 0.5rem;">Tennessee State Amateur Championship</h5>
                <p style="color: #666; margin-bottom: 0.8rem;">The premier amateur golf championship in Tennessee</p>
                <p style="color: #666; font-size: 0.9rem; margin-bottom: 1rem;"><i class="fas fa-map-marker-alt" style="color: #2c5530;"></i> Various Tennessee courses</p>
                <a href="https://www.tngolf.org/" target="_blank" style="background: #2c5530; color: white; padding: 0.5rem 1rem; border-radius: 20px; text-decoration: none; font-size: 0.9rem;">Learn More</a>
            </div>
            
            <div style="background: white; padding: 1.5rem; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-left: 4px solid #2c5530;">
                <div style="background: #2c5530; color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; display: inline-block; margin-bottom: 1rem;">
                    August <?php echo $current_year; ?>
                </div>
                <h5 style="color: #2c5530; font-size: 1.2rem; margin-bottom: 0.5rem;">FedEx St. Jude Championship</h5>
                <p style="color: #666; margin-bottom: 0.8rem;">PGA Tour event at TPC Southwind in Memphis</p>
                <p style="color: #666; font-size: 0.9rem; margin-bottom: 1rem;"><i class="fas fa-map-marker-alt" style="color: #2c5530;"></i> TPC Southwind, Memphis</p>
                <a href="https://www.pgatour.com/" target="_blank" style="background: #2c5530; color: white; padding: 0.5rem 1rem; border-radius: 20px; text-decoration: none; font-size: 0.9rem;">Learn More</a>
            </div>
        </div>
    </div>


    <!-- Community Stats -->
    <div>
        <h3 style="color: #2c5530; font-size: 1.8rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-chart-line"></i> Community Activity
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center; border-top: 4px solid #2c5530;">
                <div style="font-size: 2.5rem; font-weight: 700; color: #2c5530; margin-bottom: 0.5rem;">97+</div>
                <div style="color: #666; font-weight: 500;">Tennessee Courses Featured</div>
            </div>
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center; border-top: 4px solid #2c5530;">
                <div style="font-size: 2.5rem; font-weight: 700; color: #2c5530; margin-bottom: 0.5rem;"><?php echo $current_year; ?></div>
                <div style="color: #666; font-weight: 500;">Events & Tournaments This Year</div>
            </div>
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center; border-top: 4px solid #2c5530;">
                <div style="font-size: 2.5rem; font-weight: 700; color: #2c5530; margin-bottom: 0.5rem;">Active</div>
                <div style="color: #666; font-weight: 500;">Community Growing Daily</div>
            </div>
        </div>
    </div>
</div>