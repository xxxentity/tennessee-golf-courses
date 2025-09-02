<?php
/**
 * Golf Courses API Endpoint
 * Public endpoint for retrieving golf course information
 */

require_once '../../config/database.php';
require_once '../../includes/api-security.php';
require_once '../../includes/output-security.php';

// Initialize API middleware
$middleware = APISecurity::createAPIMiddleware($pdo, [
    'require_auth' => false,  // Public endpoint
    'rate_limit' => true,
    'allowed_methods' => ['GET'],
    'cors_enabled' => true
]);

// Run middleware
$auth = $middleware();

// Get request parameters
$courseId = $_GET['id'] ?? null;
$limit = min(50, max(1, intval($_GET['limit'] ?? 20))); // Max 50, default 20
$offset = max(0, intval($_GET['offset'] ?? 0));
$search = $_GET['search'] ?? '';
$city = $_GET['city'] ?? '';
$featured = $_GET['featured'] ?? '';

try {
    if ($courseId) {
        // Get specific course
        $stmt = $pdo->prepare("
            SELECT id, name, description, address, city, state, zip_code, phone, website, 
                   email, holes, par, yardage, rating, slope, price_range, amenities, 
                   featured, image_url, created_at, updated_at
            FROM golf_courses 
            WHERE id = ? AND status = 'active'
        ");
        $stmt->execute([$courseId]);
        $course = $stmt->fetch();
        
        if (!$course) {
            APISecurity::sendAPIError('Course not found', APISecurity::HTTP_NOT_FOUND);
        }
        
        // Safely output course data
        $course['name'] = esc($course['name']);
        $course['description'] = esc($course['description']);
        $course['address'] = esc($course['address']);
        $course['city'] = esc($course['city']);
        $course['amenities'] = $course['amenities'] ? json_decode($course['amenities'], true) : [];
        
        APISecurity::sendAPIResponse([
            'course' => $course
        ]);
        
    } else {
        // Get list of courses with filters
        $whereConditions = ["status = 'active'"];
        $params = [];
        
        if (!empty($search)) {
            $whereConditions[] = "(name LIKE ? OR city LIKE ? OR description LIKE ?)";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if (!empty($city)) {
            $whereConditions[] = "city = ?";
            $params[] = $city;
        }
        
        if ($featured === 'true') {
            $whereConditions[] = "featured = 1";
        }
        
        $whereClause = implode(' AND ', $whereConditions);
        
        // Get total count
        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM golf_courses WHERE $whereClause");
        $countStmt->execute($params);
        $totalCount = $countStmt->fetchColumn();
        
        // Get courses with pagination
        $stmt = $pdo->prepare("
            SELECT id, name, description, address, city, state, zip_code, phone, website, 
                   email, holes, par, yardage, rating, slope, price_range, amenities, 
                   featured, image_url, created_at, updated_at
            FROM golf_courses 
            WHERE $whereClause
            ORDER BY featured DESC, name ASC
            LIMIT ? OFFSET ?
        ");
        
        $params[] = $limit;
        $params[] = $offset;
        $stmt->execute($params);
        $courses = $stmt->fetchAll();
        
        // Safely output course data
        foreach ($courses as &$course) {
            $course['name'] = esc($course['name']);
            $course['description'] = esc($course['description']);
            $course['address'] = esc($course['address']);
            $course['city'] = esc($course['city']);
            $course['amenities'] = $course['amenities'] ? json_decode($course['amenities'], true) : [];
        }
        
        // Get available cities for filtering
        $citiesStmt = $pdo->query("SELECT DISTINCT city FROM golf_courses WHERE status = 'active' ORDER BY city");
        $availableCities = $citiesStmt->fetchAll(PDO::FETCH_COLUMN);
        
        APISecurity::sendAPIResponse([
            'courses' => $courses,
            'pagination' => [
                'total' => $totalCount,
                'limit' => $limit,
                'offset' => $offset,
                'has_more' => ($offset + $limit) < $totalCount
            ],
            'filters' => [
                'search' => $search,
                'city' => $city,
                'featured' => $featured,
                'available_cities' => $availableCities
            ]
        ]);
    }
    
} catch (PDOException $e) {
    error_log("Golf courses API error: " . $e->getMessage());
    
    APISecurity::logSecurityEvent($pdo, 'api_database_error', [
        'endpoint' => '/api/v1/courses',
        'error' => $e->getMessage(),
        'user_type' => $auth['user_type']
    ], 'error');
    
    APISecurity::sendAPIError(
        'Unable to retrieve courses at this time',
        APISecurity::HTTP_INTERNAL_ERROR
    );
}
?>