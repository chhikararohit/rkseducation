<?php
require 'header.php';

$id = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
$blog = null;

if ($id > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM blogs WHERE id = ?");
        $stmt->execute([$id]);
        $blog = $stmt->fetch();
    } catch (Exception $e) {}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $author = $_POST['author'];
    $content = $_POST['content'];
    
    // Image handling
    $image_path = $blog ? $blog['image_path'] : 'assets/images/default-blog.jpg';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $filename = uniqid() . '.' . $ext;
            if (!is_dir('../uploads/blogs')) mkdir('../uploads/blogs', 0777, true);
            $destination = '../uploads/blogs/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $image_path = 'uploads/blogs/' . $filename;
            }
        }
    }

    try {
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE blogs SET title=?, slug=?, author=?, content=?, image_path=? WHERE id=?");
            $stmt->execute([$title, $slug, $author, $content, $image_path, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO blogs (title, slug, author, content, image_path) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $slug, $author, $content, $image_path]);
        }
        echo "<script>window.location.href='blogs.php?success=1';</script>";
        exit;
    } catch(PDOException $e) {
        $error = "Database Error: " . $e->getMessage();
        if (strpos($e->getMessage(), "Base table or view not found") !== false) {
             $error = "You need to create the 'blogs' table in the database first!";
        }
    }
}
?>

<div class="flex-between">
    <h2><?= $id > 0 ? "Edit Blog" : "Write a New Blog" ?></h2>
    <a href="blogs.php" class="btn btn-secondary" style="background:#64748b; color:white;">&larr; Back to Blogs</a>
</div>

<?php if (isset($error)): ?>
    <div style="background: #fee2e2; color: #ef4444; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<div class="card" style="max-width: 1100px; margin: 0 auto;">
    <form method="POST" enctype="multipart/form-data">
        <div class="grid grid-2 gap-4" style="display:grid; grid-template-columns: 2.5fr 1fr; gap: 2rem;">
            
            <!-- Left Side: Editor -->
            <div>
                <div class="form-group">
                    <label>Blog Title</label>
                    <input type="text" name="title" required placeholder="Enter an engaging title..." value="<?= $blog ? htmlspecialchars($blog['title']) : '' ?>" style="font-size: 1.25rem; font-weight: 500; padding: 1rem;">
                </div>
                <div class="form-group">
                    <label>Content</label>
                    <!-- Give the textarea a specific ID for TinyMCE -->
                    <textarea name="content" id="blogContent" rows="15" placeholder="Start writing your article..."><?= $blog ? htmlspecialchars($blog['content']) : '' ?></textarea>
                </div>
            </div>
            
            <!-- Right Side: Meta -->
            <div style="background: #f8fafc; padding: 1.5rem; border-radius: 8px; border: 1px solid #e2e8f0; height: fit-content;">
                <h3 style="margin-top: 0; font-size: 1.1rem; border-bottom: 1px solid #cbd5e1; padding-bottom: 0.5rem; margin-bottom: 1rem;">Publishing Details</h3>
                
                <div class="form-group">
                    <label>Author Name</label>
                    <input type="text" name="author" required value="<?= $blog ? htmlspecialchars($blog['author']) : 'Admin' ?>">
                </div>
                
                <div class="form-group">
                    <label>Featured Image</label>
                    <?php if ($blog && file_exists('../' . $blog['image_path'])): ?>
                        <div style="margin-bottom: 0.5rem;">
                            <img src="../<?= htmlspecialchars($blog['image_path']) ?>" alt="Current Image" style="width: 100%; height: auto; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        </div>
                        <small style="display:block; margin-bottom:0.5rem; color:#64748b;">Current Image. Upload new to replace.</small>
                    <?php endif; ?>
                    
                    <input type="file" name="image" accept="image/jpeg, image/png, image/webp" <?= !$blog ? 'required' : '' ?> style="background:white;">
                </div>
                
                <button type="submit" class="btn btn-warning w-100" style="font-size: 1.1rem; padding: 0.75rem; background:#1e3a8a; color:white;">
                    <?= $id > 0 ? "Save Updates" : "Publish Blog" ?>
                </button>
            </div>
            
        </div>
    </form>
</div>

<!-- TinyMCE Script integration for fully-featured editing -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#blogContent',
        plugins: 'advlist autolink lists link image charmap preview anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking table emoticons template help',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | forecolor backcolor | removeformat | help',
        height: 650,
        menubar: 'file edit view insert format tools table help',
        branding: false,
        promotion: false,
        image_advtab: true,
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif; font-size: 16px; line-height: 1.8; color: #334155; } p { margin-bottom: 1rem; } h1, h2, h3 { color: #1e3a8a; }'
    });
</script>
</body>
</html>
