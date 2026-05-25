<?php
require 'header.php';

// Handle Add Gallery
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_gallery'])) {
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $filename = uniqid() . '.' . $ext;
            $destination = '../uploads/gallery/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $image_path = 'uploads/gallery/' . $filename;
                $stmt = $pdo->prepare("INSERT INTO gallery (title, image_path) VALUES (?, ?)");
                $stmt->execute([$_POST['title'], $image_path]);
                echo "<div style='color:green; margin-bottom:1rem;'>Image uploaded to gallery!</div>";
            }
        } else {
             echo "<div style='color:red; margin-bottom:1rem;'>Invalid file format.</div>";
        }
    }
}

// Handle Delete Gallery
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("SELECT image_path FROM gallery WHERE id = ?");
    $stmt->execute([$id]);
    $img = $stmt->fetch();
    if ($img && strpos($img['image_path'], 'uploads/gallery/') === 0) {
        if(file_exists('../' . $img['image_path'])) {
            unlink('../' . $img['image_path']);
        }
    }
    $pdo->prepare("DELETE FROM gallery WHERE id = ?")->execute([$id]);
    echo "<div style='color:green; margin-bottom:1rem;'>Image deleted from gallery!</div>";
}

$gallery = $pdo->query("SELECT * FROM gallery ORDER BY id DESC")->fetchAll();
?>

<div class="flex-between">
    <h2>Manage Gallery</h2>
</div>

<div class="grid grid-2 gap-4" style="display:grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
    <!-- Add Form -->
    <div class="card" style="align-self: start;">
        <h3>Upload Image</h3>
        <form method="POST" enctype="multipart/form-data" style="margin-top: 1rem;">
            <div class="form-group">
                <label>Title / Alt Text (Optional)</label>
                <input type="text" name="title" placeholder="e.g. Classroom Session">
            </div>
            <div class="form-group">
                <label>Select Image</label>
                <input type="file" name="image" accept="image/jpeg, image/png, image/webp" required>
            </div>
            <button type="submit" name="add_gallery" class="btn btn-warning w-100" style="width: 100%;">Upload to Gallery</button>
        </form>
    </div>

    <!-- List -->
    <div class="card">
        <h3>Current Gallery Images</h3>
        <table style="margin-top: 1rem;">
            <thead>
                <tr>
                    <th>Preview</th>
                    <th>Title</th>
                    <th>Date Added</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($gallery as $g): ?>
                <tr>
                    <td><img src="../<?= htmlspecialchars($g['image_path']) ?>" alt="Gallery" style="height:60px; border-radius:4px;"></td>
                    <td><?= htmlspecialchars($g['title']) ?: '<em>None</em>' ?></td>
                    <td><?= date('M d, Y', strtotime($g['upload_date'])) ?></td>
                    <td>
                        <a href="?delete=<?= $g['id'] ?>" class="btn btn-danger" onclick="return confirm('Delete this image?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($gallery)): ?>
                <tr><td colspan="4" style="text-align:center;">No images in gallery yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</div>
</body>
</html>
