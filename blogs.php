<?php
require_once 'includes/db.php';
$blogs = [];
try {
    $blogs = $pdo->query("SELECT * FROM blogs ORDER BY id DESC")->fetchAll();
} catch (Exception $e) { }
?>
<?php
$page_title = 'Blogs | RKS Temple Of Education';
$meta_description = 'Read the latest educational updates, tips, and articles from RKS Temple of Education.';
$active_page = 'blogs';
include 'includes/header.php';
?>

    <main style="padding-top: 100px;">
        <section class="section-padding">
            <div class="container">
                <div class="section-title center">
                    <h2>Our Latest <span class="highlight-text">Blogs</span></h2>
                    <div class="underline center"></div>
                    <p>Stay updated with our latest educational tips, notices, and success stories.</p>
                </div>
                
                <div class="grid grid-3 gap-4">
                    <?php if(!empty($blogs)): foreach($blogs as $b): ?>
                    <div class="course-card" style="padding: 0; overflow: hidden; text-align: left; display: flex; flex-direction: column;">
                        <img src="<?= htmlspecialchars($b['image_path']) ?>" alt="<?= htmlspecialchars($b['title']) ?>" style="width: 100%; height: 200px; object-fit: cover;" onerror="this.src='https://placehold.co/600x400/e2e8f0/1e293b?text=Blog+Image'">
                        <div style="padding: 1.5rem; flex: 1; display:flex; flex-direction:column;">
                            <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?= htmlspecialchars($b['title']) ?></h3>
                            <p class="text-light" style="font-size: 0.85rem; margin-bottom: 1rem; color: #64748b;">
                                <i class="fas fa-user-edit" style="color: var(--secondary);"></i> <?= htmlspecialchars($b['author']) ?> &nbsp;|&nbsp; 
                                <i class="fas fa-calendar-alt" style="color: var(--secondary);"></i> <?= date('M d, Y', strtotime($b['created_at'])) ?>
                            </p>
                            <p style="margin-bottom: 1.5rem; color: #475569; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;"><?= htmlspecialchars(strip_tags($b['content'])) ?></p>
                            <div style="margin-top: auto;">
                                <a href="blog-detail.php?id=<?= $b['id'] ?>" class="btn btn-outline btn-sm">Read Article</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; else: ?>
                    <div style="grid-column: 1 / -1; text-align: center; color: #64748b; padding: 2rem;">
                        <p>No blogs published yet. Check back soon for updates!</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

<?php include 'includes/footer.php'; ?>
