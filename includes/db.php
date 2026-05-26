<?php
$host = 'localhost';
$dbname = 'rkseducation'; 
$username = 'root'; 
$password = ''; 

// Load Hostinger/production database credentials if config.local.php exists
if (file_exists(__DIR__ . '/config.local.php')) {
    include __DIR__ . '/config.local.php';
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Database Connection failed: " . $e->getMessage());
}
?>
