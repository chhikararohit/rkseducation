<?php
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['pop-name'] ?? '';
    $mobile = $_POST['pop-mobile'] ?? '';
    $class_name = $_POST['pop-class'] ?? '';

    // Log the lead
    $stmt = $pdo->prepare("INSERT INTO leads (name, mobile, class) VALUES (?, ?, ?)");
    $stmt->execute([$name, $mobile, $class_name]);

    // Find the latest notes file for this class
    $stmt = $pdo->prepare("SELECT file_path FROM notes WHERE class_name = ? ORDER BY upload_date DESC LIMIT 1");
    $stmt->execute([$class_name]);
    $note = $stmt->fetch();

    if ($note && file_exists($note['file_path'])) {
        // Return JSON with URL to trigger client-side download/redirect
        echo json_encode(['success' => true, 'url' => $note['file_path']]);
    } else {
        // No notes available for this class yet
        echo json_encode(['success' => false, 'message' => 'Sorry, no notes have been uploaded for this class yet! We will notify you via WhatsApp.']);
    }
}
?>
