<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require '../includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - RKS Temple Of Education</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; margin: 0; background: #f8fafc; color: #334155; display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #1e3a8a; color: white; padding: 2rem 1rem; flex-shrink: 0; }
        .sidebar h2 { text-align: center; margin-bottom: 2rem; font-size: 1.2rem; }
        .sidebar a { display: block; color: white; text-decoration: none; padding: 0.75rem 1rem; margin-bottom: 0.5rem; border-radius: 4px; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.1); }
        .content { flex: 1; padding: 2rem; overflow-y: auto; }
        table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; }
        table th, table td { padding: 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; }
        table th { background: #f1f5f9; font-weight: 600; color: #1e3a8a; }
        .btn { padding: 0.5rem 1rem; background: #1e3a8a; color: white; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; display: inline-block; }
        .btn:hover { opacity: 0.9; }
        .btn-danger { background: #ef4444; }
        .btn-warning { background: #eab308; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px; box-sizing: border-box; font-family: inherit; }
        .card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 2rem;}
        .flex-between { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        h2 { color: #1e3a8a; margin-top: 0; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>RKS Admin</h2>
        <a href="index.php">Dashboard & Stats</a>
        <a href="teachers.php">Manage Tutors</a>
        <a href="courses.php">Manage Programs</a>
        <a href="gallery.php">Manage Gallery</a>
        <a href="notes.php">Manage Notes PDFs</a>
        <a href="leads.php">View Note Downloads</a>
        <a href="blogs.php">Manage Blogs</a>
        <a href="manage-pages.php">Manage Dynamic Pages</a>
        <a href="achievements.php">Manage Achievements</a>
        <a href="../index.php" target="_blank" style="margin-top: 1rem; background: #eab308; color: #1e3a8a; font-weight: 600; text-align:center;">View Live Site</a>
        <a href="logout.php" style="margin-top: 1rem; background: #ef4444; text-align:center;">Logout</a>
    </div>
    <div class="content">
