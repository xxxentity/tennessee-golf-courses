<?php
/**
 * SQL Injection Vulnerability Scanner
 * Scans PHP files for potential SQL injection vulnerabilities
 */

class SQLInjectionScanner {
    
    private $vulnerabilities = [];
    private $safePatterns = [];
    private $filesScanned = 0;
    private $queriesFound = 0;
    
    // Patterns that indicate potential SQL injection vulnerabilities
    private $dangerousPatterns = [
        // Direct variable concatenation in queries
        '/\$(?:pdo|db|conn|mysqli)->(?:query|exec|prepare)\s*\(\s*["\'].*?\$(?!.*\?)/i',
        
        // String concatenation with user input
        '/(?:SELECT|INSERT|UPDATE|DELETE|WHERE).*?"\s*\.\s*\$_(?:GET|POST|REQUEST|COOKIE)/i',
        '/(?:SELECT|INSERT|UPDATE|DELETE|WHERE).*?\'\s*\.\s*\$_(?:GET|POST|REQUEST|COOKIE)/i',
        
        // Direct interpolation of variables in queries
        '/(?:SELECT|INSERT|UPDATE|DELETE).*?(?:WHERE|SET|VALUES).*?["\'].*?\{?\$\w+\}?.*?["\']/i',
        
        // mysql_query with concatenation (old style)
        '/mysql_query\s*\(["\'].*?\$.*?["\']/i',
        
        // exec() or query() with direct input
        '/->(?:exec|query)\s*\(\s*["\'].*?\$(?!.*\?)/i',
        
        // ORDER BY with direct input (common vulnerability)
        '/ORDER\s+BY\s+["\']?\s*\.\s*\$/i',
        
        // LIMIT with direct input
        '/LIMIT\s+["\']?\s*\.\s*\$/i'
    ];
    
    // Patterns that indicate safe practices
    private $safeIndicators = [
        // Prepared statements with placeholders
        '/\$(?:stmt|statement|pdo|db)->prepare\s*\(["\'][^"\']*\?[^"\']*["\']\s*\)/i',
        
        // Named parameters
        '/\$(?:stmt|statement|pdo|db)->prepare\s*\(["\'][^"\']*:[a-zA-Z_]\w*[^"\']*["\']\s*\)/i',
        
        // Binding parameters
        '/->bind(?:Param|Value)\s*\(/i',
        
        // Execute with array
        '/->execute\s*\(\s*\[.*?\]\s*\)/i'
    ];
    
    /**
     * Scan a directory for SQL injection vulnerabilities
     */
    public function scanDirectory($directory) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $this->scanFile($file->getPathname());
            }
        }
        
        return $this->generateReport();
    }
    
    /**
     * Scan a single file for vulnerabilities
     */
    private function scanFile($filePath) {
        $this->filesScanned++;
        $content = file_get_contents($filePath);
        $lines = explode("\n", $content);
        
        // Skip files without database operations
        if (!preg_match('/\$(?:pdo|db|conn|mysqli|stmt)|->(?:query|exec|prepare)|(?:SELECT|INSERT|UPDATE|DELETE)/i', $content)) {
            return;
        }
        
        // Check for dangerous patterns
        foreach ($this->dangerousPatterns as $pattern) {
            if (preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
                foreach ($matches[0] as $match) {
                    $lineNumber = $this->getLineNumber($content, $match[1]);
                    $this->addVulnerability($filePath, $lineNumber, $match[0], 'potential_injection');
                }
            }
        }
        
        // Look for queries without prepared statements
        $this->checkUnpreparedQueries($filePath, $content, $lines);
        
        // Check for safe practices
        $this->checkSafePractices($filePath, $content);
    }
    
    /**
     * Check for queries that don't use prepared statements
     */
    private function checkUnpreparedQueries($filePath, $content, $lines) {
        // Pattern to find SQL queries
        $queryPattern = '/\$\w+\s*=\s*["\'](?:SELECT|INSERT|UPDATE|DELETE)[^"\']+["\']/i';
        
        if (preg_match_all($queryPattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
            foreach ($matches[0] as $match) {
                $this->queriesFound++;
                $lineNumber = $this->getLineNumber($content, $match[1]);
                $queryLine = trim($lines[$lineNumber - 1]);
                
                // Check if this query uses concatenation
                if (preg_match('/\.\s*\$|\$\w+\s*\./', $queryLine)) {
                    $this->addVulnerability($filePath, $lineNumber, $queryLine, 'concatenation');
                }
                
                // Check if query contains variables
                if (preg_match('/\$\w+/', $match[0]) && !preg_match('/\?|:\w+/', $match[0])) {
                    $this->addVulnerability($filePath, $lineNumber, $queryLine, 'embedded_variable');
                }
            }
        }
    }
    
    /**
     * Check for safe practices
     */
    private function checkSafePractices($filePath, $content) {
        $hasPreparedStatements = false;
        $hasParameterBinding = false;
        
        foreach ($this->safeIndicators as $pattern) {
            if (preg_match($pattern, $content)) {
                if (strpos($pattern, 'prepare') !== false) {
                    $hasPreparedStatements = true;
                }
                if (strpos($pattern, 'bind') !== false) {
                    $hasParameterBinding = true;
                }
            }
        }
        
        if ($hasPreparedStatements || $hasParameterBinding) {
            $this->safePatterns[$filePath] = [
                'prepared_statements' => $hasPreparedStatements,
                'parameter_binding' => $hasParameterBinding
            ];
        }
    }
    
    /**
     * Get line number from offset
     */
    private function getLineNumber($content, $offset) {
        return substr_count(substr($content, 0, $offset), "\n") + 1;
    }
    
    /**
     * Add vulnerability to the list
     */
    private function addVulnerability($file, $line, $code, $type) {
        $this->vulnerabilities[] = [
            'file' => $file,
            'line' => $line,
            'type' => $type,
            'code' => substr($code, 0, 100) . (strlen($code) > 100 ? '...' : '')
        ];
    }
    
    /**
     * Generate report
     */
    private function generateReport() {
        $report = [
            'summary' => [
                'files_scanned' => $this->filesScanned,
                'queries_found' => $this->queriesFound,
                'vulnerabilities_found' => count($this->vulnerabilities),
                'files_with_safe_practices' => count($this->safePatterns)
            ],
            'vulnerabilities' => $this->vulnerabilities,
            'safe_files' => array_keys($this->safePatterns)
        ];
        
        return $report;
    }
    
    /**
     * Print report in readable format
     */
    public function printReport($report) {
        echo "\n=== SQL Injection Security Audit Report ===\n\n";
        
        echo "Summary:\n";
        echo "- Files scanned: " . $report['summary']['files_scanned'] . "\n";
        echo "- Database queries found: " . $report['summary']['queries_found'] . "\n";
        echo "- Potential vulnerabilities: " . $report['summary']['vulnerabilities_found'] . "\n";
        echo "- Files using safe practices: " . $report['summary']['files_with_safe_practices'] . "\n\n";
        
        if (count($report['vulnerabilities']) > 0) {
            echo "⚠️  Potential Vulnerabilities Found:\n\n";
            
            $groupedVulns = [];
            foreach ($report['vulnerabilities'] as $vuln) {
                $groupedVulns[$vuln['file']][] = $vuln;
            }
            
            foreach ($groupedVulns as $file => $vulns) {
                echo "File: " . str_replace('/Users/entity./TGC LLC/', '', $file) . "\n";
                foreach ($vulns as $vuln) {
                    echo "  Line " . $vuln['line'] . " - " . $this->getTypeDescription($vuln['type']) . "\n";
                    echo "  Code: " . $vuln['code'] . "\n\n";
                }
            }
        } else {
            echo "✅ No SQL injection vulnerabilities detected!\n\n";
        }
        
        if (count($report['safe_files']) > 0) {
            echo "✅ Files using safe practices:\n";
            foreach ($report['safe_files'] as $file) {
                echo "  - " . str_replace('/Users/entity./TGC LLC/', '', $file) . "\n";
            }
        }
    }
    
    /**
     * Get human-readable type description
     */
    private function getTypeDescription($type) {
        $descriptions = [
            'potential_injection' => 'Potential SQL injection vulnerability',
            'concatenation' => 'Query uses string concatenation',
            'embedded_variable' => 'Query contains embedded variables',
            'unprepared_query' => 'Query does not use prepared statements'
        ];
        
        return $descriptions[$type] ?? $type;
    }
}

// Run the scanner if executed directly
if (php_sapi_name() === 'cli' && basename(__FILE__) === basename($argv[0])) {
    $scanner = new SQLInjectionScanner();
    $directory = isset($argv[1]) ? $argv[1] : dirname(__DIR__);
    
    echo "Scanning directory: $directory\n";
    $report = $scanner->scanDirectory($directory);
    $scanner->printReport($report);
    
    // Save detailed report
    $jsonReport = json_encode($report, JSON_PRETTY_PRINT);
    file_put_contents(__DIR__ . '/sql-injection-report.json', $jsonReport);
    echo "\nDetailed report saved to: security-audit/sql-injection-report.json\n";
}
?>