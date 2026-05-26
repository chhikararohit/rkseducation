<?php
require 'includes/db.php';
try {
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables: " . implode(", ", $tables) . "\n";
    
    if (in_array('pages', $tables)) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM pages");
        echo "Pages count: " . $stmt->fetchColumn() . "\n";
        
        $stmt = $pdo->query("SELECT slug FROM pages LIMIT 5");
        $slugs = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "Sample slugs: " . implode(", ", $slugs) . "\n";
    } else {
        echo "pages table does NOT exist.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
