<?php
require 'header.php';

// Handle Add Course
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_course'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $icon = $_POST['icon_class']; // Default: fas fa-book

    $stmt = $pdo->prepare("INSERT INTO courses (title, description, icon_class) VALUES (?, ?, ?)");
    $stmt->execute([$title, $description, $icon]);
    echo "<div style='color:green; margin-bottom:1rem;'>Program added successfully!</div>";
}

// Handle Delete Course
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $pdo->prepare("DELETE FROM courses WHERE id = ?")->execute([$id]);
    echo "<div style='color:green; margin-bottom:1rem;'>Program deleted!</div>";
}

$courses = $pdo->query("SELECT * FROM courses ORDER BY id ASC")->fetchAll();
?>

<div class="flex-between">
    <h2>Manage Programs</h2>
</div>

<div class="grid grid-2 gap-4" style="display:grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
    <!-- Add Form -->
    <div class="card" style="align-self: start;">
        <h3>Add New Program</h3>
        <form method="POST" style="margin-top: 1rem;">
            <div class="form-group">
                <label>Program Title</label>
                <input type="text" name="title" required placeholder="e.g. School Coaching (VIII - XII)">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" required rows="3" placeholder="Brief description of the course..."></textarea>
            </div>
            <div class="form-group">
                <label>FontAwesome Icon Class</label>
                <input type="text" name="icon_class" required placeholder="e.g. fas fa-graduation-cap" value="fas fa-book">
            </div>
            <button type="submit" name="add_course" class="btn btn-warning w-100" style="width: 100%;">Add Program</button>
        </form>
    </div>

    <!-- List -->
    <div class="card">
        <h3>Current Programs</h3>
        <table style="margin-top: 1rem;">
            <thead>
                <tr>
                    <th>Icon</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($courses as $c): ?>
                <tr>
                    <td><i class="<?= htmlspecialchars($c['icon_class']) ?>" style="font-size:1.5rem; color:#1e3a8a;"></i></td>
                    <td><strong><?= htmlspecialchars($c['title']) ?></strong></td>
                    <td style="max-width: 250px; font-size:0.9rem;"><?= htmlspecialchars($c['description']) ?></td>
                    <td>
                        <a href="?delete=<?= $c['id'] ?>" class="btn btn-danger" onclick="return confirm('Delete this program?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($courses)): ?>
                <tr><td colspan="4" style="text-align:center;">No programs added yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</div>
</body>
</html>
