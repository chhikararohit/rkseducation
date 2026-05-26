<?php
require 'header.php';

// Handle Add Teacher
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_teacher'])) {
    $name = $_POST['name'];
    $role = $_POST['role'];
    $image_path = 'assets/images/default-avatar.png'; // default fallback

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $filename = uniqid() . '.' . $ext;
            $destination = '../uploads/teachers/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $image_path = 'uploads/teachers/' . $filename;
            }
        }
    }

    $stmt = $pdo->prepare("INSERT INTO teachers (name, role, image_path, display_order) VALUES (?, ?, ?, 1)");
    $stmt->execute([$name, $role, $image_path]);
    echo "<div style='color:green; margin-bottom:1rem;'>Tutor added successfully!</div>";
}

// Handle Set Main
if (isset($_GET['main'])) {
    $id = $_GET['main'];
    $pdo->query("UPDATE teachers SET display_order = 1");
    $stmt = $pdo->prepare("UPDATE teachers SET display_order = 0 WHERE id = ?");
    $stmt->execute([$id]);
    echo "<div style='color:green; margin-bottom:1rem;'>Main Tutor updated successfully!</div>";
}

// Handle Delete Teacher
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("SELECT image_path FROM teachers WHERE id = ?");
    $stmt->execute([$id]);
    $tutor = $stmt->fetch();
    if ($tutor && strpos($tutor['image_path'], 'uploads/teachers/') === 0) {
        if(file_exists('../' . $tutor['image_path'])) {
            unlink('../' . $tutor['image_path']);
        }
    }
    $pdo->prepare("DELETE FROM teachers WHERE id = ?")->execute([$id]);
    echo "<div style='color:green; margin-bottom:1rem;'>Tutor deleted!</div>";
}

// Handle Edit Teacher
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_teacher'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $role = $_POST['role'];
    $display_order = $_POST['display_order']; // 0 = main, 1 = normal
    
    // If setting to Main, unset others
    if ($display_order == 0) {
        $pdo->query("UPDATE teachers SET display_order = 1");
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $filename = uniqid() . '.' . $ext;
            $destination = '../uploads/teachers/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $image_path = 'uploads/teachers/' . $filename;
                
                // Delete old image
                $stmt = $pdo->prepare("SELECT image_path FROM teachers WHERE id = ?");
                $stmt->execute([$id]);
                $old_tutor = $stmt->fetch();
                if ($old_tutor && strpos($old_tutor['image_path'], 'uploads/teachers/') === 0 && file_exists('../' . $old_tutor['image_path'])) {
                    unlink('../' . $old_tutor['image_path']);
                }

                $stmt = $pdo->prepare("UPDATE teachers SET name = ?, role = ?, image_path = ?, display_order = ? WHERE id = ?");
                $stmt->execute([$name, $role, $image_path, $display_order, $id]);
            }
        }
    } else {
        $stmt = $pdo->prepare("UPDATE teachers SET name = ?, role = ?, display_order = ? WHERE id = ?");
        $stmt->execute([$name, $role, $display_order, $id]);
    }
    echo "<div style='color:green; margin-bottom:1rem;'>Tutor updated successfully!</div>";
}

// Check for Edit mode to prefill form
$edit_tutor = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM teachers WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_tutor = $stmt->fetch();
}

$teachers = $pdo->query("SELECT * FROM teachers ORDER BY display_order ASC, id DESC")->fetchAll();
?>

<div class="flex-between">
    <h2>Manage Tutors</h2>
</div>

<div class="grid grid-2 gap-4" style="display:grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
    <!-- Add/Edit Form -->
    <div class="card" style="align-self: start;">
        <?php if($edit_tutor): ?>
            <h3>Edit Tutor</h3>
            <form method="POST" enctype="multipart/form-data" style="margin-top: 1rem;">
                <input type="hidden" name="id" value="<?= $edit_tutor['id'] ?>">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" required value="<?= htmlspecialchars($edit_tutor['name']) ?>">
                </div>
                <div class="form-group">
                    <label>Role/Qualification</label>
                    <input type="text" name="role" required value="<?= htmlspecialchars($edit_tutor['role']) ?>">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="display_order" class="w-100" style="padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 8px;">
                        <option value="1" <?= $edit_tutor['display_order'] == 1 ? 'selected' : '' ?>>Normal</option>
                        <option value="0" <?= $edit_tutor['display_order'] == 0 ? 'selected' : '' ?>>Main Tutor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Profile Image (Leave blank to keep current)</label>
                    <input type="file" name="image" accept="image/jpeg, image/png, image/webp">
                    <div style="margin-top: 10px;">
                        <img src="../<?= htmlspecialchars($edit_tutor['image_path']) ?>" alt="Current Image" style="width: 60px; height: 60px; border-radius: 5px; object-fit: cover;">
                    </div>
                </div>
                <button type="submit" name="edit_teacher" class="btn btn-warning w-100" style="margin-bottom: 0.5rem; width: 100%;">Update Tutor</button>
                <a href="teachers.php" class="btn btn-secondary w-100" style="display: block; text-align: center;">Cancel Edit</a>
            </form>
        <?php else: ?>
            <h3>Add New Tutor</h3>
            <form method="POST" enctype="multipart/form-data" style="margin-top: 1rem;">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" required placeholder="e.g. Mr. Ankit Kaushik">
                </div>
                <div class="form-group">
                    <label>Role/Qualification</label>
                    <input type="text" name="role" required placeholder="e.g. Director & Lead Educator">
                </div>
                <div class="form-group">
                    <label>Profile Image</label>
                    <input type="file" name="image" accept="image/jpeg, image/png, image/webp" required>
                </div>
                <button type="submit" name="add_teacher" class="btn btn-warning w-100" style="width: 100%;">Add Tutor</button>
            </form>
        <?php endif; ?>
    </div>

    <!-- List -->
    <div class="card">
        <h3>Current Tutors</h3>
        <table style="margin-top: 1rem; width: 100%;">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($teachers as $t): ?>
                <tr>
                    <td><img src="../<?= htmlspecialchars($t['image_path']) ?>" alt="Tutor" style="width:50px; height:50px; object-fit:cover; border-radius:50%;"></td>
                    <td><?= htmlspecialchars($t['name']) ?></td>
                    <td><?= htmlspecialchars($t['role']) ?></td>
                    <td>
                        <?php if($t['display_order'] == 0): ?>
                            <span style="color: #eab308; font-weight: bold;"><i class="fas fa-star"></i> Main Tutor</span>
                        <?php else: ?>
                            <a href="?main=<?= $t['id'] ?>" class="btn btn-outline btn-sm" style="padding: 0.3rem 0.5rem; font-size: 0.8rem;">Make Main</a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="?edit=<?= $t['id'] ?>" class="btn btn-primary btn-sm" style="margin-right: 5px;">Edit</a>
                        <a href="?delete=<?= $t['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this tutor?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($teachers)): ?>
                <tr><td colspan="5" style="text-align:center;">No tutors added yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</div>
</body>
</html>
