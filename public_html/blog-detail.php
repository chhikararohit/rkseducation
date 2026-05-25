<?php
require_once 'includes/db.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM blogs WHERE id = ?");
$stmt->execute([$id]);
$blog = $stmt->fetch();

if (!$blog) {
    header("Location: blogs.php");
    exit;
}
?>
<?php
$page_title = $blog['title'] . ' | RKS Temple Of Education';
$meta_description = substr(strip_tags($blog['content']), 0, 150);
$active_page = 'blogs';
include 'includes/header.php';
?>

    <main style="padding-top: 100px; min-height: calc(100vh - 200px);">
        <article class="section-padding">
            <div class="container" style="max-width: 800px; background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);">
                <img src="<?= htmlspecialchars($blog['image_path']) ?>" alt="<?= htmlspecialchars($blog['title']) ?>" style="width: 100%; height: auto; max-height: 450px; object-fit: cover; border-radius: 8px; margin-bottom: 2rem;" onerror="this.src='https://placehold.co/800x450/e2e8f0/1e293b?text=Blog+Image'">
                <h1 style="font-size: 2.25rem; color: #1e3a8a; margin-bottom: 1rem; line-height: 1.3; font-weight: 700;"><?= htmlspecialchars($blog['title']) ?></h1>
                
                <div style="display: flex; gap: 1.5rem; color: #64748b; font-size: 0.95rem; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid #e2e8f0;">
                    <span><i class="fas fa-user-edit" style="color: #eab308;"></i> <?= htmlspecialchars($blog['author']) ?></span>
                    <span><i class="fas fa-calendar-alt" style="color: #eab308;"></i> <?= date('F d, Y', strtotime($blog['created_at'])) ?></span>
                </div>
                
                <div class="blog-content" style="line-height: 1.8; color: #334155; font-size: 1.05rem;">
                    <?= $blog['content'] ?>
                </div>
                
                <!-- Demo Class Form -->
                <div style="margin-top: 3rem; padding: 2rem; background-color: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                    <h3 style="color: var(--primary); margin-bottom: 1.5rem; text-align: center; font-size: 1.5rem;">Book Free Demo Class</h3>
                    <form class="contact-form" onsubmit="sendBlogDemoToWhatsApp(event)">
                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label for="blog_demo_name" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Full Name</label>
                            <input type="text" id="blog_demo_name" required placeholder="Enter your full name" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: var(--border-radius);">
                        </div>
                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label for="blog_demo_phone" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Phone Number</label>
                            <input type="tel" id="blog_demo_phone" required placeholder="Enter your phone number" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: var(--border-radius);">
                        </div>
                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label for="blog_demo_course" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Interested Course</label>
                            <select id="blog_demo_course" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: var(--border-radius);">
                                <option value="">Select a Course</option>
                                <option value="school">School Coaching (VIII - XII)</option>
                                <option value="competitive">Competitive Exams (JEE/NEET)</option>
                                <option value="foundation">Foundation Courses</option>
                            </select>
                        </div>
                        <button type="submit" class="btn w-100" style="background-color: #25D366; color: white; width: 100%; padding: 0.75rem; border-radius: var(--border-radius); font-weight: 600; cursor: pointer; border: none; font-size: 1.1rem;">Book Demo via WhatsApp <i class="fab fa-whatsapp" style="margin-left: 5px;"></i></button>
                    </form>
                </div>
                
                <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #e2e8f0; text-align: center;">
                    <a href="blogs.php" class="btn btn-outline" style="border-radius: 50px;"><i class="fas fa-arrow-left"></i> Back to Blogs</a>
                </div>
            </div>
        </article>
    </main>

    <script>
        function sendBlogDemoToWhatsApp(event) {
            event.preventDefault();
            const name = document.getElementById('blog_demo_name').value;
            const phone = document.getElementById('blog_demo_phone').value;
            const courseSelect = document.getElementById('blog_demo_course');
            const course = courseSelect.options[courseSelect.selectedIndex].text;
            const courseVal = courseSelect.value;

            const whatsappNumber = "919729615438";

            let whatsappMessage = `*Free Demo Class Request (From Blog)*\n\n`;
            whatsappMessage += `*Name:* ${name}\n`;
            whatsappMessage += `*Phone:* ${phone}\n`;
            if (courseVal) whatsappMessage += `*Course:* ${course}\n`;

            window.open(`https://wa.me/${whatsappNumber}?text=${encodeURIComponent(whatsappMessage)}`, '_blank');
        }
    </script>
<?php include 'includes/footer.php'; ?>
