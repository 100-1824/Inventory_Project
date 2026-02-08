<?php
/**
 * Export SQLite database to MySQL-compatible SQL format
 */

$sqliteFile = 'database/database.sqlite';
$outputFile = 'database_export.sql';

if (!file_exists($sqliteFile)) {
    echo "ERROR: $sqliteFile not found!\n";
    echo "Make sure you have data in your SQLite database before exporting.\n";
    exit(1);
}

try {
    echo "Connecting to SQLite database...\n";
    $db = new PDO('sqlite:' . $sqliteFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Reading database schema...\n";
    
    // Get all tables
    $tables = $db->query(
        "SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'"
    )->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "No tables found in database!\n";
        exit(1);
    }
    
    echo "Found " . count($tables) . " tables\n";
    echo "Exporting to $outputFile...\n\n";
    
    // Start SQL export
    $sql = "-- SQLite to MySQL Export\n";
    $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
    $sql .= "SET FOREIGN_KEY_CHECKS=0;\n";
    $sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
    $sql .= "SET time_zone = \"+00:00\";\n\n";
    
    foreach ($tables as $table) {
        echo "  - Exporting table: $table\n";
        
        $sql .= "-- --------------------------------------------------------\n";
        $sql .= "-- Table structure for table `$table`\n";
        $sql .= "-- --------------------------------------------------------\n\n";
        
        // Get CREATE TABLE statement
        $createStmt = $db->query("SELECT sql FROM sqlite_master WHERE name='$table'")->fetchColumn();
        
        // Convert SQLite syntax to MySQL
        // Replace double quotes with backticks for identifiers
        $createStmt = preg_replace('/"([^"]+)"/', '`$1`', $createStmt);
        
        // Remove CHECK constraints (not compatible with older MySQL/MariaDB)
        $createStmt = preg_replace('/,?\s*check\s*\([^)]+\)/i', '', $createStmt);
        $createStmt = preg_replace('/,?\s*CONSTRAINT\s+`\w+`\s+CHECK\s*\([^)]+\)/i', '', $createStmt);
        
        // Convert AUTOINCREMENT to AUTO_INCREMENT (both cases)
        $createStmt = str_ireplace('AUTOINCREMENT', 'AUTO_INCREMENT', $createStmt);
        
        // Convert INTEGER PRIMARY KEY to INT AUTO_INCREMENT PRIMARY KEY
        $createStmt = preg_replace('/`(\w+)`\s+integer\s+primary\s+key\s+AUTO_INCREMENT/i', '`$1` INT AUTO_INCREMENT PRIMARY KEY', $createStmt);
        $createStmt = preg_replace('/`(\w+)`\s+integer\s+primary\s+key/i', '`$1` INT AUTO_INCREMENT PRIMARY KEY', $createStmt);
        
        // Convert other INTEGER types to INT
        $createStmt = preg_replace('/\s+integer(\s+|,|\))/i', ' INT$1', $createStmt);
        
        // Convert varchar without length to VARCHAR(255)
        $createStmt = preg_replace('/\s+varchar(\s+|\)|,)/i', ' VARCHAR(255)$1', $createStmt);
        
        // Convert datetime to DATETIME
        $createStmt = preg_replace('/\s+datetime(\s+|\)|,)/i', ' DATETIME$1', $createStmt);
        
        // Convert text to TEXT  
        $createStmt = preg_replace('/\s+text(\s+|\)|,)/i', ' TEXT$1', $createStmt);
        
        // Handle NOT NULL
        $createStmt = preg_replace('/\s+not\s+null/i', ' NOT NULL', $createStmt);
        
        // Clean up multiple commas and spaces
        $createStmt = preg_replace('/,\s*,/', ',', $createStmt);
        $createStmt = preg_replace('/,\s*\)/', ')', $createStmt);
        
        $sql .= "DROP TABLE IF EXISTS `$table`;\n";
        $sql .= $createStmt . ";\n\n";
        
        // Get table data
        $rows = $db->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($rows)) {
            $sql .= "-- Dumping data for table `$table`\n\n";
            
            foreach ($rows as $row) {
                $columns = array_keys($row);
                $values = array_map(function($v) {
                    if (is_null($v)) {
                        return 'NULL';
                    }
                    return "'" . addslashes($v) . "'";
                }, $row);
                
                $sql .= "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $values) . ");\n";
            }
            
            $sql .= "\n";
        }
    }
    
    $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
    
    // Write to file
    file_put_contents($outputFile, $sql);
    
    $fileSize = filesize($outputFile);
    $fileSizeKB = round($fileSize / 1024, 2);
    
    echo "\n";
    echo "============================================\n";
    echo "Export Successful!\n";
    echo "============================================\n";
    echo "\n";
    echo "Output file: $outputFile\n";
    echo "File size: $fileSizeKB KB\n";
    echo "\n";
    echo "This file is ready to import into InfinityFree via phpMyAdmin.\n";
    echo "\n";
    echo "NOTE: You may need to manually adjust the SQL file if there are compatibility issues.\n";
    echo "Common adjustments needed:\n";
    echo "  * Verify data types are MySQL compatible\n";
    echo "  * Check for any SQLite-specific syntax\n";
    echo "  * Ensure foreign key constraints are correct\n";
    echo "\n";
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
