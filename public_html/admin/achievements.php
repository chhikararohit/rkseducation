<?php
require 'header.php';

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_achievement'])) {
        $name = $_POST['name'];
        $exam = $_POST['exam'];
        $score = $_POST['score'];
        $order = $_POST['display_order'];
        $image_path = 'assets/images/default-avatar.png'; // Fallback

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed)) {
                $filename = uniqid() . '.' . $ext;
                if (!is_dir('../uploads/achievements')) mkdir('../uploads/achievements', 0777, true);
                $destination = '../uploads/achievements/' . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                    $image_path = 'uploads/achievements/' . $filename;
                }
            }
        }

        $stmt = $pdo->prepare("INSERT INTO achievements (name, exam_details, score, image_path, display_order) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $exam, $score, $image_path, $order]);
        echo "<div style='color:green; margin-bottom:1rem; background:#dcfce7; padding:1rem; border-radius:4px;'>Achievement added successfully!</div>";
    } elseif (isset($_POST['edit_achievement'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $exam = $_POST['exam'];
        $score = $_POST['score'];
        $order = $_POST['display_order'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $filename = uniqid() . '.' . $ext;
            if (!is_dir('../uploads/achievements')) mkdir('../uploads/achievements', 0777, true);
            $destination = '../uploads/achievements/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $image_path = 'uploads/achievements/' . $filename;
                $stmt = $pdo->prepare("UPDATE achievements SET name=?, exam_details=?, score=?, image_path=?, display_order=? WHERE id=?");
                $stmt->execute([$name, $exam, $score, $image_path, $order, $id]);
            }
        } else {
            $stmt = $pdo->prepare("UPDATE achievements SET name=?, exam_details=?, score=?, display_order=? WHERE id=?");
            $stmt->execute([$name, $exam, $score, $order, $id]);
        }
        echo "<div style='color:green; margin-bottom:1rem; background:#dcfce7; padding:1rem; border-radius:4px;'>Achievement updated successfully!</div>";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("SELECT image_path FROM achievements WHERE id = ?");
    $stmt->execute([$id]);
    $rec = $stmt->fetch();
    if ($rec && strpos($rec['image_path'], 'uploads/achievements/') === 0 && file_exists('../' . $rec['image_path'])) {
        unlink('../' . $rec['image_path']);
    }
    $pdo->prepare("DELETE FROM achievements WHERE id = ?")->execute([$id]);
    echo "<div style='color:green; margin-bottom:1rem; background:#dcfce7; padding:1rem; border-radius:4px;'>Achievement deleted!</div>";
}

$edit_ach = null;
try {
    if (isset($_GET['edit'])) {
        $stmt = $pdo->prepare("SELECT * FROM achievements WHERE id = ?");
        $stmt->execute([$_GET['edit']]);
        $edit_ach = $stmt->fetch();
    }
    $achievements = $pdo->query("SELECT * FROM achievements ORDER BY display_order ASC, id DESC")->fetchAll();
} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Base table or view not found") !== false) {
        die("<div style='padding: 2rem; color: #ef4444; font-family: sans-serif; text-align: center;'><h2>Database Table Missing!</h2><p>Please execute the `CREATE TABLE achievements` query in your database.</p></div>");
    }
}
?>

<div class="flex-between">
    <h2>Manage Student Achievements</h2>
</div>

<div class="grid grid-2 gap-4" style="display:grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
    <!-- Form -->
    <div class="card" style="align-self: start;">
        <?php if($edit_ach): ?>
            <h3>Edit Achievement</h3>
            <form method="POST" enctype="multipart/form-data" style="margin-top: 1rem;">
                <input type="hidden" name="id" value="<?= $edit_ach['id'] ?>">
                <div class="form-group">
                    <label>Student Name</label>
                    <input type="text" name="name" required value="<?= htmlspecialchars($edit_ach['name']) ?>">
                </div>
                <div class="form-group">
                    <label>Exam / Subject Details</label>
                    <input type="text" name="exam" required placeholder="e.g. CBSE 12th Board" value="<?= htmlspecialchars($edit_ach['exam_details']) ?>">
                </div>
                <div class="form-group">
                    <label>Rank / Marks</label>
                    <input type="text" name="score" required placeholder="e.g. 98.5% or AIR 1" value="<?= htmlspecialchars($edit_ach['score']) ?>">
                </div>
                <div class="form-group">
                    <label>Display Order (0 is first)</label>
                    <input type="number" name="display_order" value="<?= $edit_ach['display_order'] ?>">
                </div>
                <div class="form-group">
                    <label>Photo (Leave blank to keep current)</label>
                    <input type="file" name="image" accept="image/jpeg, image/png, image/webp">
                </div>
                <button type="submit" name="edit_achievement" class="btn btn-warning w-100" style="margin-bottom:0.5rem;">Update Achievement</button>
                <a href="achievements.php" class="btn btn-secondary w-100" style="display:block; text-align:center;">Cancel</a>
            </form>
        <?php else: ?>
            <h3>Add Achievement</h3>
            <form method="POST" enctype="multipart/form-data" style="margin-top: 1rem;">
                <div class="form-group">
                    <label>Student Name</label>
                    <input type="text" name="name" required placeholder="e.g. Rohan Sharma">
                </div>
                <div class="form-group">
                    <label>Exam / Subject Details</label>
                    <input type="text" name="exam" required placeholder="e.g. CBSE 12th Board">
                </div>
                <div class="form-group">
                    <label>Rank / Marks</label>
                    <input type="text" name="score" required placeholder="e.g. 98.5% or AIR 1">
                </div>
                <div class="form-group">
                    <label>Display Order</label>
                    <input type="number" name="display_order" value="0">
                </div>
                <div class="form-group">
                    <label>Student Photo</label>
                    <input type="file" name="image" accept="image/jpeg, image/png, image/webp">
                    <small style="color:#64748b;">Optional. A default avatar will be used if skipped.</small>
                </div>
                <button type="submit" name="add_achievement" class="btn btn-primary w-100">Add Achievement</button>
            </form>
        <?php endif; ?>
    </div>

    <!-- List -->
    <div class="card">
        <h3>Current Achievements</h3>
        <table style="width: 100%; margin-top: 1rem;">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Exam & Score</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($achievements as $a): ?>
                <tr>
                    <td><img src="../<?= htmlspecialchars($a['image_path']) ?>" alt="img" style="width:50px; height:50px; object-fit:cover; border-radius:50%;" onerror="this.src='https://placehold.co/100x100/e2e8f0/1e293b?text=Student'"></td>
                    <td style="font-weight: 500;"><?= htmlspecialchars($a['name']) ?></td>
                    <td>
                        <div style="font-size: 0.9rem; color: #64748b;"><?= htmlspecialchars($a['exam_details']) ?></div>
                        <div style="font-size: 1rem; color: #10b981; font-weight: 600;"><?= htmlspecialchars($a['score']) ?></div>
                    </td>
                    <td>
                        <a href="?edit=<?= $a['id'] ?>" class="btn btn-primary btn-sm" style="margin-right: 5px;">Edit</a>
                        <a href="?delete=<?= $a['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this achievement?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($achievements)): ?>
                <tr><td colspan="4" style="text-align:center; padding: 2rem;">No achievements recorded yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</div>
</body>
</html>
