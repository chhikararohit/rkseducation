<?php
$host = 'localhost';
$dbname = 'rkseducation'; // Change this on Hostinger
$username = 'root'; // Change this on Hostinger
$password = ''; // Change this on Hostinger

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Database Connection failed: " . $e->getMessage());
}
?>
