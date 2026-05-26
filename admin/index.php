<?php 
require 'header.php'; 

// Fetch stats safely
try {
    $teachers = $pdo->query("SELECT COUNT(*) FROM teachers")->fetchColumn();
    $courses = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn();
    $gallery = $pdo->query("SELECT COUNT(*) FROM gallery")->fetchColumn();
    $notes = $pdo->query("SELECT COUNT(*) FROM notes")->fetchColumn();
    $leads = $pdo->query("SELECT COUNT(*) FROM leads")->fetchColumn();
} catch (Exception $e) {
    echo "<div style='color:red;'>Please import the database.sql setup file first!</div>";
    die();
}
?>
<h2>Welcome to Admin Dashboard</h2>
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 2rem;">
    <div class="card" style="text-align:center; border-top: 4px solid #1e3a8a; margin-bottom: 0;">
        <h3 style="font-size: 2.5rem; margin: 0; color: #1e3a8a;"><?= $teachers ?></h3><p style="color: #64748b; margin-top: 0.5rem;">Tutors</p>
    </div>
    <div class="card" style="text-align:center; border-top: 4px solid #eab308; margin-bottom: 0;">
        <h3 style="font-size: 2.5rem; margin: 0; color: #eab308;"><?= $courses ?></h3><p style="color: #64748b; margin-top: 0.5rem;">Programs</p>
    </div>
    <div class="card" style="text-align:center; border-top: 4px solid #10b981; margin-bottom: 0;">
        <h3 style="font-size: 2.5rem; margin: 0; color: #10b981;"><?= $gallery ?></h3><p style="color: #64748b; margin-top: 0.5rem;">Gallery Images</p>
    </div>
    <div class="card" style="text-align:center; border-top: 4px solid #8b5cf6; margin-bottom: 0;">
        <h3 style="font-size: 2.5rem; margin: 0; color: #8b5cf6;"><?= $notes ?></h3><p style="color: #64748b; margin-top: 0.5rem;">Notes PDFs</p>
    </div>
    <div class="card" style="text-align:center; border-top: 4px solid #ef4444; margin-bottom: 0;">
        <h3 style="font-size: 2.5rem; margin: 0; color: #ef4444;"><?= $leads ?></h3><p style="color: #64748b; margin-top: 0.5rem;">Total Downloads (Leads)</p>
    </div>
</div>
</div>
</body>
</html>
