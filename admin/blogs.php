<?php
require 'header.php';

// Handle Delete Blog
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("SELECT image_path FROM blogs WHERE id = ?");
    $stmt->execute([$id]);
    $blog = $stmt->fetch();
    if ($blog && strpos($blog['image_path'], 'uploads/blogs/') === 0 && file_exists('../' . $blog['image_path'])) {
        unlink('../' . $blog['image_path']);
    }
    $pdo->prepare("DELETE FROM blogs WHERE id = ?")->execute([$id]);
    echo "<div style='color:green; margin-bottom:1rem; background:#dcfce7; padding:1rem; border-radius:4px;'>Blog deleted successfully!</div>";
}

$blogs = [];
try {
    $blogs = $pdo->query("SELECT * FROM blogs ORDER BY id DESC")->fetchAll();
} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Base table or view not found") !== false) {
        die("<div style='padding: 2rem; color: #ef4444; font-family: sans-serif; text-align: center;'><h2>Database Table Missing!</h2><p>You haven't run the SQL query yet! Please go to phpMyAdmin and execute the <code>CREATE TABLE blogs</code> query as instructed. Once done, refresh this page.</p></div>");
    } else {
        die("Database error: " . $e->getMessage());
    }
}
?>

<div class="flex-between">
    <h2>Manage Blogs</h2>
    <a href="write-blog.php" class="btn btn-warning" style="background:#1e3a8a; color:white;"><i class="fas fa-plus"></i> Write New Blog</a>
</div>

<?php if(isset($_GET['success'])): ?>
    <div style="color:green; margin-bottom:1rem; background:#dcfce7; padding:1rem; border-radius:4px;">Action completed successfully!</div>
<?php endif; ?>

<div class="card">
    <table style="width: 100%; margin-top: 1rem;">
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Author</th>
                <th>Date Published</th>
                <th style="text-align:center;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($blogs as $b): ?>
            <tr>
                <td style="width: 80px;">
                    <img src="../<?= htmlspecialchars($b['image_path']) ?>" alt="img" style="width:60px; height:60px; object-fit:cover; border-radius:4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                </td>
                <td style="font-weight: 500; font-size: 1.05rem;"><?= htmlspecialchars($b['title']) ?></td>
                <td><?= htmlspecialchars($b['author']) ?></td>
                <td><span style="background: #f1f5f9; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.85rem;"><?= date('M d, Y', strtotime($b['created_at'])) ?></span></td>
                <td style="text-align:center; white-space: nowrap;">
                    <a href="write-blog.php?edit=<?= $b['id'] ?>" class="btn btn-primary btn-sm" style="margin-right: 5px;">Edit</a>
                    <a href="?delete=<?= $b['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this blog?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
            
            <?php if(empty($blogs)): ?>
            <tr>
                <td colspan="5" style="text-align:center; padding: 3rem; color: #64748b;">
                    <i class="fas fa-newspaper" style="font-size: 3rem; color:#cbd5e1; margin-bottom:1rem; display:block;"></i>
                    <p style="font-size: 1.1rem; margin-bottom: 1rem;">No blogs published yet.</p>
                    <a href="write-blog.php" class="btn btn-outline" style="border: 2px solid #1e3a8a; color: #1e3a8a; font-weight: 500;">Start Writing</a>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</div>
</body>
</html>
