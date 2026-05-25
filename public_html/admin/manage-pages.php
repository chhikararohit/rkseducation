<?php
require 'header.php';

$action = $_GET['action'] ?? 'list';
$error = '';
$success = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM pages WHERE id = ?");
        $stmt->execute([$id]);
        $success = "Page deleted successfully.";
    } catch (Exception $e) {
        $error = "Error deleting page: " . $e->getMessage();
    }
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $slug = trim($_POST['slug'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $class_name = trim($_POST['class_name'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $meta_title = trim($_POST['meta_title'] ?? '');
    $meta_description = trim($_POST['meta_description'] ?? '');
    $h1 = trim($_POST['h1'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $status = trim($_POST['status'] ?? 'published');
    $featured = isset($_POST['featured']) ? 1 : 0;
    $is_index = isset($_POST['is_index']) ? 1 : 0;
    $canonical_url = trim($_POST['canonical_url'] ?? '');
    $seo_schema = trim($_POST['seo_schema'] ?? '');
    $meta_keywords = trim($_POST['meta_keywords'] ?? '');

    // Process FAQs
    $faqQuestions = $_POST['faq_question'] ?? [];
    $faqAnswers = $_POST['faq_answer'] ?? [];
    $faqs = [];
    for ($i = 0; $i < count($faqQuestions); $i++) {
        if (!empty(trim($faqQuestions[$i])) && !empty(trim($faqAnswers[$i]))) {
            $faqs[] = [
                'question' => trim($faqQuestions[$i]),
                'answer' => trim($faqAnswers[$i])
            ];
        }
    }
    $faq_json = json_encode($faqs);

    if (empty($slug) || empty($title)) {
        $error = "Slug and Title are required.";
    } else {
        try {
            if (isset($_POST['id']) && !empty($_POST['id'])) {
                // Update
                $id = (int)$_POST['id'];
                $stmt = $pdo->prepare("UPDATE pages SET slug=?, category=?, class_name=?, subject=?, title=?, meta_title=?, meta_description=?, h1=?, content=?, faq=?, status=?, featured=?, is_index=?, canonical_url=?, seo_schema=?, meta_keywords=? WHERE id=?");
                // Check if the old DB structure throws error due to missing columns, we use try-catch and fallback
                try {
                    $stmt->execute([$slug, $category, $class_name, $subject, $title, $meta_title, $meta_description, $h1, $content, $faq_json, $status, $featured, $is_index, $canonical_url, $seo_schema, $meta_keywords, $id]);
                    $success = "Page updated successfully.";
                    $action = 'list';
                } catch (PDOException $ex) {
                    // Fallback for missing columns if DB upgrade script was not run
                    $stmt2 = $pdo->prepare("UPDATE pages SET slug=?, category=?, class_name=?, subject=?, title=?, meta_title=?, meta_description=?, h1=?, content=?, faq=? WHERE id=?");
                    $stmt2->execute([$slug, $category, $class_name, $subject, $title, $meta_title, $meta_description, $h1, $content, $faq_json, $id]);
                    $success = "Page updated successfully. (Warning: Advanced SEO fields ignored because database is not upgraded. Please run database_updates.sql)";
                    $action = 'list';
                }
            } else {
                // Insert
                try {
                    $stmt = $pdo->prepare("INSERT INTO pages (slug, category, class_name, subject, title, meta_title, meta_description, h1, content, faq, status, featured, is_index, canonical_url, seo_schema, meta_keywords) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$slug, $category, $class_name, $subject, $title, $meta_title, $meta_description, $h1, $content, $faq_json, $status, $featured, $is_index, $canonical_url, $seo_schema, $meta_keywords]);
                    $success = "Page created successfully.";
                    $action = 'list';
                } catch (PDOException $ex) {
                    $stmt2 = $pdo->prepare("INSERT INTO pages (slug, category, class_name, subject, title, meta_title, meta_description, h1, content, faq) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt2->execute([$slug, $category, $class_name, $subject, $title, $meta_title, $meta_description, $h1, $content, $faq_json]);
                    $success = "Page created successfully. (Warning: Advanced SEO fields ignored. Please run database_updates.sql)";
                    $action = 'list';
                }
            }
        } catch (Exception $e) {
            $error = "Database Error: " . $e->getMessage();
            $action = isset($_POST['id']) && !empty($_POST['id']) ? 'edit' : 'add';
        }
    }
}

if ($action === 'list') {
    // List Pages
    try {
        $stmt = $pdo->query("SELECT * FROM pages ORDER BY id DESC");
        $pages = $stmt->fetchAll();
    } catch (Exception $e) {
        $pages = [];
        $error = "Could not fetch pages. Make sure database is imported.";
    }
?>
    <div class="flex-between">
        <h2>Manage Dynamic Pages</h2>
        <a href="manage-pages.php?action=add" class="btn">Add New Page</a>
    </div>

    <?php if ($success): ?><div style="background: #10b981; color: white; padding: 1rem; border-radius: 4px; margin-bottom: 1.5rem;"><?= htmlspecialchars($success) ?></div><?php endif; ?>
    <?php if ($error): ?><div style="background: #ef4444; color: white; padding: 1rem; border-radius: 4px; margin-bottom: 1.5rem;"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="card" style="padding: 0;">
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Category</th>
                        <th>Class / Subject</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pages as $p): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($p['title']) ?></strong></td>
                            <td style="color: #64748b; font-size: 0.9em;"><?= htmlspecialchars($p['slug']) ?></td>
                            <td><span style="background: #f1f5f9; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.85em;"><?= htmlspecialchars($p['category']) ?></span></td>
                            <td><?= htmlspecialchars($p['class_name']) ?> <br> <span style="font-size: 0.8em; color: #64748b;"><?= htmlspecialchars($p['subject']) ?></span></td>
                            <td>
                                <?php if (isset($p['status']) && $p['status'] === 'draft'): ?>
                                    <span style="background: #fef08a; color: #854d0e; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.85em; font-weight: 500;">Draft</span>
                                <?php else: ?>
                                    <span style="background: #dcfce3; color: #166534; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.85em; font-weight: 500;">Published</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="../<?= htmlspecialchars($p['slug']) ?>" target="_blank" class="btn" style="padding: 0.3rem 0.6rem; font-size: 0.85rem; background: #64748b;">Preview</a>
                                <a href="manage-pages.php?action=edit&id=<?= $p['id'] ?>" class="btn btn-warning" style="padding: 0.3rem 0.6rem; font-size: 0.85rem;">Edit</a>
                                <a href="manage-pages.php?delete=<?= $p['id'] ?>" onclick="return confirm('Are you sure you want to delete this page?');" class="btn btn-danger" style="padding: 0.3rem 0.6rem; font-size: 0.85rem;">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($pages)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem;">No dynamic pages found. Create one!</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php
} elseif ($action === 'add' || $action === 'edit') {
    // Add or Edit Page
    $page = [
        'id' => '', 'slug' => '', 'category' => '', 'class_name' => '', 'subject' => '', 
        'title' => '', 'meta_title' => '', 'meta_description' => '', 'h1' => '', 'content' => '', 
        'faq' => '[]', 'status' => 'published', 'featured' => 0, 'is_index' => 1, 
        'canonical_url' => '', 'seo_schema' => '', 'meta_keywords' => ''
    ];
    $isEdit = false;

    if ($action === 'edit' && isset($_GET['id'])) {
        $isEdit = true;
        try {
            $stmt = $pdo->prepare("SELECT * FROM pages WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $fetched = $stmt->fetch();
            if ($fetched) {
                $page = array_merge($page, $fetched); // Merge so missing columns don't cause errors
            }
        } catch (Exception $e) {}
    }

    $faqs = json_decode($page['faq'], true) ?: [];
?>
    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        selector: '#content',
        height: 500,
        plugins: 'advlist autolink lists link image charmap preview anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking table emoticons template',
        toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media | forecolor backcolor emoticons',
        menubar: 'file edit view insert format tools table help',
        content_style: 'body { font-family:Poppins,sans-serif; font-size:16px; color:#334155; }'
      });
    </script>
    <style>
        .tabs { display: flex; border-bottom: 2px solid #e2e8f0; margin-bottom: 2rem; }
        .tab { padding: 0.75rem 1.5rem; cursor: pointer; font-weight: 500; color: #64748b; border-bottom: 2px solid transparent; margin-bottom: -2px; }
        .tab.active { color: #1e3a8a; border-bottom-color: #1e3a8a; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .faq-item-ui { background: #f8fafc; border: 1px solid #e2e8f0; padding: 1rem; border-radius: 4px; margin-bottom: 1rem; position: relative; }
        .remove-faq { position: absolute; top: 10px; right: 10px; background: #ef4444; color: white; border: none; padding: 0.25rem 0.5rem; border-radius: 4px; cursor: pointer; }
    </style>

    <div class="flex-between">
        <h2><?= $isEdit ? 'Edit Page' : 'Add New Page' ?></h2>
        <a href="manage-pages.php" class="btn btn-warning">Back to List</a>
    </div>

    <?php if ($error): ?><div style="background: #ef4444; color: white; padding: 1rem; border-radius: 4px; margin-bottom: 1.5rem;"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="POST" action="manage-pages.php" class="card">
        <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= htmlspecialchars($page['id']) ?>"><?php endif; ?>
        
        <div class="tabs">
            <div class="tab active" onclick="switchTab(event, 'tab-content')">Content</div>
            <div class="tab" onclick="switchTab(event, 'tab-structure')">Structure</div>
            <div class="tab" onclick="switchTab(event, 'tab-seo')">SEO</div>
            <div class="tab" onclick="switchTab(event, 'tab-faq')">FAQ</div>
            <div class="tab" onclick="switchTab(event, 'tab-settings')">Settings</div>
        </div>

        <!-- TAB: Content -->
        <div id="tab-content" class="tab-content active">
            <div class="form-group">
                <label>Page Title (Internal/Heading)</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($page['title']) ?>" required>
            </div>
            <div class="form-group">
                <label>H1 Heading</label>
                <input type="text" id="h1" name="h1" value="<?= htmlspecialchars($page['h1']) ?>" required>
            </div>
            <div class="form-group">
                <label>Main Content</label>
                <textarea id="content" name="content"><?= htmlspecialchars($page['content']) ?></textarea>
            </div>
        </div>

        <!-- TAB: Structure -->
        <div id="tab-structure" class="tab-content">
            <p style="color: #64748b; margin-bottom: 1.5rem;">Changing these values will auto-generate SEO fields.</p>
            <div class="grid-2">
                <div class="form-group">
                    <label>Category</label>
                    <select id="category" name="category" required onchange="autoGenerateSEO()">
                        <option value="">Select Category</option>
                        <option value="Foundation Program" <?= $page['category'] === 'Foundation Program' ? 'selected' : '' ?>>Foundation Program (Class 1-8)</option>
                        <option value="Science Coaching" <?= $page['category'] === 'Science Coaching' ? 'selected' : '' ?>>Science Coaching (Class 9-12)</option>
                        <option value="Other" <?= $page['category'] === 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Class Name</label>
                    <select id="class_name" name="class_name" required onchange="autoGenerateSEO()">
                        <option value="">Select Class</option>
                        <?php for($i=1; $i<=12; $i++): ?>
                            <option value="Class <?= $i ?>" <?= $page['class_name'] === "Class $i" ? 'selected' : '' ?>>Class <?= $i ?></option>
                        <?php endfor; ?>
                        <option value="General" <?= $page['class_name'] === 'General' ? 'selected' : '' ?>>General</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Subject (Optional for Foundation)</label>
                    <input type="text" id="subject" name="subject" value="<?= htmlspecialchars($page['subject']) ?>" placeholder="e.g. Physics, Math" oninput="autoGenerateSEO()">
                </div>
                <div class="form-group">
                    <label>City/Location (For pSEO)</label>
                    <input type="text" id="city" value="Ganaur" disabled title="Currently hardcoded for local SEO">
                </div>
            </div>
        </div>

        <!-- TAB: SEO -->
        <div id="tab-seo" class="tab-content">
            <div class="form-group">
                <label>URL Slug (e.g., class-10-physics-coaching-in-ganaur)</label>
                <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($page['slug']) ?>" required>
            </div>
            <div class="form-group">
                <label>Meta Title (SEO Title Tag)</label>
                <input type="text" id="meta_title" name="meta_title" value="<?= htmlspecialchars($page['meta_title']) ?>" required maxlength="60">
                <small style="color:#64748b;">Recommended length: 50-60 characters</small>
            </div>
            <div class="form-group">
                <label>Meta Description</label>
                <textarea name="meta_description" rows="3" maxlength="160"><?= htmlspecialchars($page['meta_description']) ?></textarea>
                <small style="color:#64748b;">Recommended length: 150-160 characters</small>
            </div>
            <div class="form-group">
                <label>Meta Keywords</label>
                <input type="text" name="meta_keywords" value="<?= htmlspecialchars($page['meta_keywords'] ?? '') ?>" placeholder="physics coaching, class 10 tuition, ganaur">
            </div>
            <div class="form-group">
                <label>Canonical URL (Optional: Overrides auto-generated canonical)</label>
                <input type="url" name="canonical_url" value="<?= htmlspecialchars($page['canonical_url'] ?? '') ?>" placeholder="https://www.rkseducation.com/...">
            </div>
            <div class="form-group">
                <label>Custom JSON-LD SEO Schema (Optional)</label>
                <textarea name="seo_schema" rows="4" placeholder='{"@context": "https://schema.org", "@type": "Course", ...}'><?= htmlspecialchars($page['seo_schema'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- TAB: FAQ -->
        <div id="tab-faq" class="tab-content">
            <div id="faq-container">
                <?php foreach ($faqs as $f): ?>
                    <div class="faq-item-ui">
                        <button type="button" class="remove-faq" onclick="this.parentElement.remove()">X</button>
                        <div class="form-group" style="margin-bottom:0.5rem;">
                            <label>Question</label>
                            <input type="text" name="faq_question[]" value="<?= htmlspecialchars($f['question']) ?>">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label>Answer</label>
                            <textarea name="faq_answer[]" rows="2"><?= htmlspecialchars($f['answer']) ?></textarea>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="btn" style="background:#eab308; color:#1e3a8a; font-weight:600;" onclick="addFaq()">+ Add FAQ Item</button>
        </div>

        <!-- TAB: Settings -->
        <div id="tab-settings" class="tab-content">
            <div class="grid-2">
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="published" <?= (isset($page['status']) && $page['status'] === 'published') ? 'selected' : '' ?>>Published</option>
                        <option value="draft" <?= (isset($page['status']) && $page['status'] === 'draft') ? 'selected' : '' ?>>Draft</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Search Engine Indexing</label>
                    <select name="is_index">
                        <option value="1" <?= (isset($page['is_index']) && $page['is_index'] == 1) ? 'selected' : '' ?>>Index (Allow Search Engines)</option>
                        <option value="0" <?= (isset($page['is_index']) && $page['is_index'] == 0) ? 'selected' : '' ?>>NoIndex (Hide from Search Engines)</option>
                    </select>
                </div>
                <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem; margin-top: 1.5rem;">
                    <input type="checkbox" id="featured" name="featured" value="1" <?= (isset($page['featured']) && $page['featured'] == 1) ? 'checked' : '' ?> style="width: auto;">
                    <label for="featured" style="margin: 0;">Featured Page (Show on homepage/widgets)</label>
                </div>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 2rem 0;">
        <button type="submit" class="btn" style="font-size: 1.1rem; padding: 0.75rem 2rem;">Save Dynamic Page</button>
    </form>

    <script>
        function switchTab(evt, tabId) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            evt.currentTarget.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        }

        function addFaq() {
            const container = document.getElementById('faq-container');
            const html = `
                <div class="faq-item-ui">
                    <button type="button" class="remove-faq" onclick="this.parentElement.remove()">X</button>
                    <div class="form-group" style="margin-bottom:0.5rem;">
                        <label>Question</label>
                        <input type="text" name="faq_question[]">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label>Answer</label>
                        <textarea name="faq_answer[]" rows="2"></textarea>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        }

        // Auto SEO Generation Logic
        let isEditMode = <?= $isEdit ? 'true' : 'false' ?>;
        
        function autoGenerateSEO() {
            // Only auto-generate if we are creating a new page, to avoid overwriting custom tweaks on edit
            if (isEditMode) return;

            const category = document.getElementById('category').value;
            const className = document.getElementById('class_name').value;
            const subject = document.getElementById('subject').value.trim();
            const city = 'Ganaur';

            if (!className) return;

            // Generate Strings
            let classString = className; // e.g. "Class 10"
            let subjectString = subject ? ` ${subject} ` : ' ';
            let typeString = category === 'Foundation Program' ? 'Foundation Program' : 'Coaching';

            // Base Title: Class 10 Physics Coaching in Ganaur
            let baseTitle = `${classString}${subjectString}${typeString} in ${city}`.replace(/\s+/g, ' ').trim();
            
            // Slug: class-10-physics-coaching-in-ganaur
            let slug = baseTitle.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');

            // Meta Title: Best Class 10 Physics Coaching in Ganaur | RKS
            let metaTitle = `Best ${baseTitle}`;
            if(metaTitle.length < 50) metaTitle += ` | RKS Institute`;

            // H1
            let h1 = baseTitle;

            // Update UI
            document.getElementById('title').value = baseTitle;
            document.getElementById('h1').value = h1;
            document.getElementById('slug').value = slug;
            document.getElementById('meta_title').value = metaTitle;
        }
    </script>
<?php
}
?>
</div>
</body>
</html>
