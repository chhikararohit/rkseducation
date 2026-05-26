<?php
require_once 'includes/db.php';

// Retrieve the slug from the rewrite rule
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    header("Location: /");
    exit;
}

// Fetch the page content from database
try {
    $stmt = $pdo->prepare("SELECT * FROM pages WHERE slug = ?");
    $stmt->execute([$slug]);
    $page = $stmt->fetch();
} catch (Exception $e) {
    $page = false;
}

// Programmatic SEO Fallback if not found in database
if (!$page) {
    if (preg_match('/^class-([0-9]+)-foundation-program-in-ganaur$/i', $slug, $matches)) {
        $classNum = $matches[1];
        $className = "Class " . $classNum;
        $title = "Class {$classNum} Foundation Program in Ganaur";
        $page = [
            'id' => 9000 + $classNum,
            'slug' => $slug,
            'category' => 'Foundation Program',
            'class_name' => $className,
            'subject' => '',
            'title' => $title,
            'meta_title' => "Best Class {$classNum} Foundation Program in Ganaur | RKS Institute",
            'meta_description' => "Join the best Class {$classNum} foundation program in Ganaur at RKS Temple of Education. Concept-first learning, expert tutors, and small batches.",
            'h1' => $title,
            'content' => "<p>Welcome to our Class {$classNum} Foundation Program. RKS Temple of Education in Ganaur offers the best conceptual training for Class {$classNum} students to build a strong foundation.</p><p>Our program is designed to nurture young minds, improve analytical thinking, and establish regular self-study habits.</p>",
            'faq' => json_encode([
                ['question' => 'What is the fee for this program?', 'answer' => 'Please contact our counselor for detailed fee structures and demo sessions.'],
                ['question' => 'Do you provide study materials?', 'answer' => 'Yes, we provide comprehensive, concept-first study materials and assignments.'],
                ['question' => 'Where is the institute located?', 'answer' => 'We are located at Railway Rd, near court complex, opposite Chirag Garden, Ganaur, Haryana.']
            ]),
            'status' => 'published',
            'featured' => 0,
            'is_index' => 1,
            'canonical_url' => '',
            'seo_schema' => '',
            'meta_keywords' => "class {$classNum} foundation, foundation course ganaur, rks education"
        ];
    } elseif (preg_match('/^class-([0-9]+)-coaching-in-ganaur$/i', $slug, $matches)) {
        $classNum = $matches[1];
        $className = "Class " . $classNum;
        $title = "Class {$classNum} Coaching in Ganaur";
        $page = [
            'id' => 8000 + $classNum,
            'slug' => $slug,
            'category' => 'Science Coaching',
            'class_name' => $className,
            'subject' => '',
            'title' => $title,
            'meta_title' => "Best Class {$classNum} Coaching in Ganaur | RKS Institute",
            'meta_description' => "Top Class {$classNum} coaching and tuition in Ganaur at RKS Temple of Education. Excel in school exams and prepare for competitive exams.",
            'h1' => $title,
            'content' => "<p>Welcome to our Class {$classNum} Coaching Program. RKS Temple of Education in Ganaur offers top-tier coaching for Class {$classNum} students to excel in their academic journey.</p><p>We focus on concept-first learning, board exam blueprints, and competitive exam readiness.</p>",
            'faq' => json_encode([
                ['question' => 'What subjects are covered?', 'answer' => 'We cover Mathematics, Physics, Chemistry, and other core subjects depending on the class level.'],
                ['question' => 'Are there regular tests?', 'answer' => 'Yes, we conduct weekly test series and board pattern assessments.'],
                ['question' => 'Can we book a free demo?', 'answer' => 'Absolutely. Contact us to schedule your free demo session today.']
            ]),
            'status' => 'published',
            'featured' => 0,
            'is_index' => 1,
            'canonical_url' => '',
            'seo_schema' => '',
            'meta_keywords' => "class {$classNum} coaching, class {$classNum} tuition ganaur, rks education"
        ];
    } elseif (preg_match('/^class-([0-9]+)-([a-zA-Z0-9-]+)-(coaching|tuition)-in-ganaur$/i', $slug, $matches)) {
        $classNum = $matches[1];
        $subjectSlug = $matches[2];
        $type = ucfirst($matches[3]); // Coaching or Tuition
        $subject = ucfirst(str_replace('-', ' ', $subjectSlug));
        $className = "Class " . $classNum;
        $title = "Class {$classNum} {$subject} {$type} in Ganaur";
        $page = [
            'id' => 7000 + ($classNum * 10) + strlen($subject),
            'slug' => $slug,
            'category' => 'Science Coaching',
            'class_name' => $className,
            'subject' => $subject,
            'title' => $title,
            'meta_title' => "Best Class {$classNum} {$subject} {$type} in Ganaur | RKS Institute",
            'meta_description' => "Top Class {$classNum} {$subject} {$type} in Ganaur at RKS Temple of Education. Master key concepts with expert educators.",
            'h1' => $title,
            'content' => "<p>Master Class {$classNum} {$subject} with RKS Temple of Education in Ganaur. Our specialized {$type} classes ensure deep understanding of concepts, step-by-step numerical solving, and board exam preparation.</p>",
            'faq' => json_encode([
                ['question' => 'Do you cover CBSE/HBSE syllabus?', 'answer' => 'Yes, we follow the latest CBSE and state board syllabi closely.'],
                ['question' => 'What is the batch size?', 'answer' => 'We keep our batches small to ensure personalized attention for every student.']
            ]),
            'status' => 'published',
            'featured' => 0,
            'is_index' => 1,
            'canonical_url' => '',
            'seo_schema' => '',
            'meta_keywords' => "class {$classNum} {$subject} {$type}, {$subject} tuition ganaur, rks education"
        ];
    }
}

// Block draft pages from public access
if ($page && isset($page['status']) && $page['status'] === 'draft') {
    // Allow admin preview via ?preview=1 if admin session exists
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        $page = false; // Treat as 404
    }
}

// Handle 404 if page not found
if (!$page) {
    header("HTTP/1.1 404 Not Found");
    $page_title = 'Page Not Found | RKS Temple Of Education';
    $meta_description = 'The page you are looking for does not exist or has been moved.';
    $active_page = '404';
    $extra_head = '';
    include 'includes/header.php';
    ?>
    <main style="padding-top: 100px; min-height: 60vh; display: flex; align-items: center; justify-content: center; text-align: center;">
        <div class="container">
            <h1 style="font-size: 4rem; color: var(--primary); margin-bottom: 1rem;">404</h1>
            <h2 style="margin-bottom: 1.5rem;">Oops! Page Not Found</h2>
            <p style="color: var(--text-light); margin-bottom: 2rem;">The page you are looking for does not exist or has been moved.</p>
            <a href="index.php" class="btn btn-primary">Go Back Home</a>
        </div>
    </main>
    <?php
    include 'includes/footer.php';
    exit;
}

$category = $page['category'];
$class_name = $page['class_name'];
$subject = $page['subject'];
$title = $page['title'];
$meta_title = $page['meta_title'];
$meta_description = $page['meta_description'];
$meta_keywords = $page['meta_keywords'] ?? '';
$h1 = $page['h1'];
$content = $page['content'];
$faqs = json_decode($page['faq'], true) ?: [];
$page_status = $page['status'] ?? 'published';
$is_index = isset($page['is_index']) ? (bool)$page['is_index'] : true;
$custom_canonical = $page['canonical_url'] ?? '';
$custom_schema = $page['seo_schema'] ?? '';

// Get URL protocol and domain for Schema structures
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domain = $_SERVER['HTTP_HOST'];
$currentUrl = $protocol . $domain . $_SERVER['REQUEST_URI'];

// Determine canonical URL: use custom if set, otherwise auto-generate
$canonicalUrl = !empty($custom_canonical) ? $custom_canonical : $currentUrl;

// Generate related internal linking pages
$relatedLinks = [];
try {
    if ($category === 'Foundation Program') {
        // Find other foundation programs
        $rStmt = $pdo->prepare("SELECT slug, class_name FROM pages WHERE category = 'Foundation Program' AND id != :id GROUP BY class_name ORDER BY id ASC LIMIT 6");
        $rStmt->execute(['id' => $page['id']]);
        $relatedLinks = $rStmt->fetchAll();
    } else {
        // Science Coaching: find pages of same class, or same subject in other classes
        if (!empty($subject)) {
            $rStmt = $pdo->prepare("SELECT slug, title, class_name, subject FROM pages WHERE category = 'Science Coaching' AND id != :id AND (class_name = :class_name OR subject = :subject) ORDER BY id ASC LIMIT 6");
            $rStmt->execute(['id' => $page['id'], 'class_name' => $class_name, 'subject' => $subject]);
        } else {
            // General class page: find other general class pages
            $rStmt = $pdo->prepare("SELECT slug, title, class_name, subject FROM pages WHERE category = 'Science Coaching' AND id != :id AND subject = '' ORDER BY id ASC LIMIT 4");
            $rStmt->execute(['id' => $page['id']]);
        }
        $relatedLinks = $rStmt->fetchAll();
    }
} catch (Exception $e) { }
?>
<?php
ob_start();
?>
    <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl) ?>">
    <?php if (!empty($meta_keywords)): ?>
    <meta name="keywords" content="<?= htmlspecialchars($meta_keywords) ?>">
    <?php endif; ?>
    <meta name="robots" content="<?= $is_index ? 'index, follow' : 'noindex, nofollow' ?>">

    <!-- JSON-LD Breadcrumb Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Home",
          "item": "<?= $protocol . $domain ?>/index.php"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "<?= htmlspecialchars($category) ?>",
          "item": "<?= $protocol . $domain . '/' . ($category === 'Foundation Program' ? 'class-5-foundation-program-in-ganaur' : 'class-9-coaching-in-ganaur') ?>"
        },
        <?php if (!empty($subject)): ?>
        {
          "@type": "ListItem",
          "position": 3,
          "name": "<?= htmlspecialchars($class_name) ?>",
          "item": "<?= $protocol . $domain . '/' . strtolower(str_replace(' ', '-', $class_name)) ?>-coaching-in-ganaur"
        },
        {
          "@type": "ListItem",
          "position": 4,
          "name": "<?= htmlspecialchars($subject) ?>",
          "item": "<?= htmlspecialchars($currentUrl) ?>"
        }
        <?php else: ?>
        {
          "@type": "ListItem",
          "position": 3,
          "name": "<?= htmlspecialchars($class_name) ?>",
          "item": "<?= htmlspecialchars($currentUrl) ?>"
        }
        <?php endif; ?>
      ]
    }
    </script>

    <!-- JSON-LD FAQ Schema -->
    <?php if (!empty($faqs)): ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [
        <?php 
        $faqSchemas = [];
        foreach ($faqs as $f) {
            $faqSchemas[] = '{
              "@type": "Question",
              "name": ' . json_encode($f['question']) . ',
              "acceptedAnswer": {
                "@type": "Answer",
                "text": ' . json_encode($f['answer']) . '
              }
            }';
        }
        echo implode(",", $faqSchemas);
        ?>
      ]
    }
    </script>
    <?php endif; ?>

    <!-- JSON-LD Local Business Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "EducationalOrganization",
      "name": "RKS Temple Of Education",
      "image": "<?= $protocol . $domain ?>/assets/images/logo.png",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Railway Rd, near court complex, opposite Chirag Garden",
        "addressLocality": "Ganaur",
        "addressRegion": "Haryana",
        "postalCode": "131101",
        "addressCountry": "IN"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": "29.1337",
        "longitude": "76.9538"
      },
      "telephone": "+919629615438",
      "url": "<?= $protocol . $domain ?>",
      "priceRange": "$$"
    }
    </script>
<?php
$extra_head = ob_get_clean();
$page_title = $meta_title;
$active_page = ($category === 'Foundation Program') ? 'foundation' : 'science';
include 'includes/header.php';
?>

    <main>
        <!-- Hero Section -->
        <section class="hero" style="min-height: 50vh; display: flex; align-items: center; text-align: left; padding: 6rem 0 3rem;">
            <div class="hero-overlay"></div>
            <div class="container relative">
                <div class="hero-content" style="margin: 0; padding-top: 50px;">
                    <span class="badge" style="background-color: var(--primary); color: #fff; box-shadow: var(--shadow-md); font-size: 1.1rem; text-transform: none; letter-spacing: normal;">
                        <?= htmlspecialchars($category) ?>
                    </span>
                    <h1 style="color: var(--primary); font-size: clamp(2rem, 5.5vw, 3.5rem); line-height: 1.2; margin-bottom: 1rem; font-weight: 700;">
                        <?= htmlspecialchars($h1) ?>
                    </h1>
                    <p style="color: var(--text-main); font-weight: 600; font-size: 1.15rem; max-width: 700px; margin-bottom: 1.5rem;">
                        Join RKS Temple of Education in Ganaur for expert academic mentorship, concept-first training, and customized exam preparation plans.
                    </p>
                    <div class="hero-buttons" style="justify-content: flex-start;">
                        <a href="#contact" class="btn btn-primary">Book Free Demo Class</a>
                        <a href="tel:+919629615438" class="btn btn-outline" style="border-color: var(--primary); color: var(--primary);">Call Counselor</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Breadcrumbs Bar -->
        <div class="breadcrumbs-bar">
            <div class="container">
                <div class="breadcrumbs">
                    <a href="index.php">Home</a>
                    <span class="breadcrumbs-separator"><i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i></span>
                    <span><?= htmlspecialchars($category) ?></span>
                    <span class="breadcrumbs-separator"><i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i></span>
                    <?php if (!empty($subject)): ?>
                    <a href="class-<?= filter_var($class_name, FILTER_SANITIZE_NUMBER_INT) ?>-coaching-in-ganaur"><?= htmlspecialchars($class_name) ?></a>
                    <span class="breadcrumbs-separator"><i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i></span>
                    <span style="color: var(--primary); font-weight: 600;"><?= htmlspecialchars($subject) ?></span>
                    <?php else: ?>
                    <span style="color: var(--primary); font-weight: 600;"><?= htmlspecialchars($class_name) ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Introduction Section -->
        <section class="about section-padding">
            <div class="container">
                <div class="grid grid-2 items-center gap-4">
                    <div class="about-image">
                        <img src="assets/images/about-us.webp" alt="RKS Temple of Education Ganaur Study Space">
                    </div>
                    <div class="about-content">
                        <div class="section-title left-align" style="margin-bottom: 1.5rem;">
                            <h2>Programs & <span class="highlight-text">Curriculum</span></h2>
                            <div class="underline"></div>
                        </div>
                        <div style="color: var(--text-main); font-size: 1.05rem; line-height: 1.8;">
                            <?= $content ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Benefits Section -->
        <section class="courses section-padding light-bg">
            <div class="container">
                <div class="section-title center">
                    <h2>Program <span class="highlight-text">Benefits</span></h2>
                    <div class="underline center"></div>
                    <p>Unlock success with our structured academic blueprints designed for Ganaur's top achievers.</p>
                </div>
                <div class="grid grid-3 gap-3">
                    <?php if ($category === 'Foundation Program'): ?>
                    <div class="course-card">
                        <div class="card-icon"><i class="fas fa-brain"></i></div>
                        <h3>Concept-First Learning</h3>
                        <p>We move past simple rote-learning, teaching students of <?= htmlspecialchars($class_name) ?> the logical 'why' behind science and math equations.</p>
                    </div>
                    <div class="course-card">
                        <div class="card-icon"><i class="fas fa-pen-alt"></i></div>
                        <h3>Regular Self-Study Habits</h3>
                        <p>Establish structured, stress-free daily routines for reading, revision, homework, and analytical reasoning check-ins.</p>
                    </div>
                    <div class="course-card">
                        <div class="card-icon"><i class="fas fa-users"></i></div>
                        <h3>Small-Batch Supervision</h3>
                        <p>Our small batches ensure that your child receives localized attention and custom doubt-clearing sessions from top educators.</p>
                    </div>
                    <?php else: ?>
                    <div class="course-card">
                        <div class="card-icon"><i class="fas fa-award"></i></div>
                        <h3>CBSE Board Score Booster</h3>
                        <p>Receive solved papers from the past 10 years, board exam assessment blueprints, and answer sheet presentation techniques.</p>
                    </div>
                    <div class="course-card">
                        <div class="card-icon"><i class="fas fa-atom"></i></div>
                        <h3>NEET & JEE Ready Core</h3>
                        <p>Prepare the core mathematical theorems and structural science concepts to smoothly transition to competitive exam tracks.</p>
                    </div>
                    <div class="course-card">
                        <div class="card-icon"><i class="fas fa-calculator"></i></div>
                        <h3>Step-by-Step Numericals</h3>
                        <p>In-depth classes covering equations, derivations, problem-solving speed shortcuts, and weekly assignments.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Why Choose Us Stats Section -->
        <section class="why-us section-padding">
            <div class="container">
                <div class="section-title center">
                    <h2>Why Ganaur Trusts <span class="highlight-text">RKS Institute</span></h2>
                    <div class="underline center"></div>
                </div>
                <div class="grid grid-4 gap-2 text-center stats-grid">
                    <div class="stat-box">
                        <i class="fas fa-user-graduate"></i>
                        <h3>95%</h3>
                        <p>Success Rate</p>
                    </div>
                    <div class="stat-box">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <h3>15+</h3>
                        <p>Expert Tutors</p>
                    </div>
                    <div class="stat-box">
                        <i class="fas fa-book-open"></i>
                        <h3>50+</h3>
                        <p>Courses Offered</p>
                    </div>
                    <div class="stat-box">
                        <i class="fas fa-award"></i>
                        <h3>10k+</h3>
                        <p>Happy Students</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <?php if (!empty($faqs)): ?>
        <section class="faq-section section-padding">
            <div class="container">
                <div class="section-title center">
                    <h2>Frequently Asked <span class="highlight-text">Questions</span></h2>
                    <div class="underline center"></div>
                    <p>Have questions about our <?= htmlspecialchars($class_name) ?> program? Find direct answers here.</p>
                </div>
                <div class="faq-accordion">
                    <?php foreach ($faqs as $index => $f): ?>
                    <div class="faq-item">
                        <div class="faq-question">
                            <span><?= htmlspecialchars($f['question']) ?></span>
                            <i class="fas fa-plus faq-toggle-icon"></i>
                        </div>
                        <div class="faq-answer">
                            <p><?= htmlspecialchars($f['answer']) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Contact / CTA Section -->
        <section id="contact" class="contact section-padding light-bg">
            <div class="container">
                <div class="section-title center">
                    <h2>Book Your Free <span class="highlight-text">Demo Session</span></h2>
                    <div class="underline center"></div>
                    <p>Experience our teaching methodology firsthand. Get direct interaction with top teachers in Ganaur!</p>
                </div>
                <div class="grid grid-2 gap-4 contact-wrapper">
                    <div class="contact-info">
                        <div class="info-card">
                            <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="info-text">
                                <h3>Visit Us</h3>
                                <p><a href="https://share.google/Mcp2ZLX0WmwXWKqLl" target="_blank">Railway Rd, near
                                        court complex,<br>opposite Chirag Garden,<br>Ganaur, Haryana 131101</a></p>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-icon"><i class="fas fa-phone-alt"></i></div>
                            <div class="info-text">
                                <h3>Call Us</h3>
                                <p>
                                    <a href="tel:+919629615438">+91 96296 15438</a><br>
                                    <a href="tel:+917300000132">+91 73000 00132</a><br>
                                    <a href="tel:+917700000553">+91 77000 00553</a>
                                </p>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-icon"><i class="fas fa-envelope"></i></div>
                            <div class="info-text">
                                <h3>Email Us</h3>
                                <p><a href="mailto:info@rkseducation.com">info@rkseducation.com</a></p>
                            </div>
                        </div>

                        <div class="map-container mt-2">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d111586.29959664654!2d76.95383561918341!3d29.1337424699564!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390daa86fe4ec8d1%3A0xeae06c649568973d!2sGanaur%2C%20Haryana!5e0!3m2!1sen!2sin!4v1712818290372!5m2!1sen!2sin"
                                width="100%" height="250" style="border:0; border-radius: 8px;" allowfullscreen=""
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            <a href="https://share.google/Mcp2ZLX0WmwXWKqLl" target="_blank"
                                class="btn btn-outline w-100 mt-2"
                                style="border-radius: 8px; display: block; text-align: center; color: #fff; background-color: transparent;"><i
                                    class="fas fa-map-marked-alt"></i> Open in Google Maps</a>
                        </div>
                    </div>
                    <div class="contact-form-container">
                        <form class="contact-form" onsubmit="sendToWhatsApp(event)">
                            <h3>Book Free Demo</h3>
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" name="name" required placeholder="Enter your full name">
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address (Optional)</label>
                                <input type="email" id="email" name="email" placeholder="Enter your email address">
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" required
                                    placeholder="Enter your phone number">
                            </div>
                            <div class="form-group">
                                <label for="course">Interested Course</label>
                                <select id="course" name="course">
                                    <option value="school" selected><?= htmlspecialchars($class_name) ?> Coaching (<?= htmlspecialchars($category) ?>)</option>
                                    <option value="school">School Coaching (VIII - XII)</option>
                                    <option value="competitive">Competitive Exams (JEE/NEET)</option>
                                    <option value="foundation">Foundation Courses</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="message">Message</label>
                                <textarea id="message" name="message" rows="4"
                                    placeholder="How can we help? I am interested in <?= htmlspecialchars($class_name) ?> <?= htmlspecialchars($subject) ?> coaching classes."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100"
                                style="background-color: #25D366; border-color: #25D366;">Send via WhatsApp <i
                                    class="fab fa-whatsapp" style="font-size: 1.1rem; margin-left: 5px;"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Related Pages Section -->
        <?php if (!empty($relatedLinks)): ?>
        <section class="related-links section-padding">
            <div class="container">
                <div class="section-title center">
                    <h2>Related <span class="highlight-text">Coaching Classes</span></h2>
                    <div class="underline center"></div>
                    <p>Explore other academic coaching courses and subjects offered at RKS Temple of Education in Ganaur.</p>
                </div>
                <div class="related-links-grid">
                    <?php foreach ($relatedLinks as $link): 
                        $linkText = "";
                        if ($category === 'Foundation Program') {
                            $linkText = $link['class_name'] . " Foundation";
                        } else {
                            $linkText = !empty($link['subject']) ? $link['class_name'] . ' ' . $link['subject'] : $link['class_name'] . ' Science';
                        }
                    ?>
                    <a href="<?= htmlspecialchars($link['slug']) ?>" class="related-link-card">
                        <i class="fas fa-graduation-cap" style="margin-right: 5px;"></i> <?= htmlspecialchars($linkText) ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>
    </main>

    <!-- Accordion Script -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const item = question.parentElement;
                const answer = item.querySelector('.faq-answer');
                const isActive = item.classList.contains('active');
                
                // Close all other items
                document.querySelectorAll('.faq-item').forEach(other => {
                    other.classList.remove('active');
                    other.querySelector('.faq-answer').style.maxHeight = null;
                });
                
                if (!isActive) {
                    item.classList.add('active');
                    answer.style.maxHeight = answer.scrollHeight + 'px';
                }
            });
        });
    });

    function sendToWhatsApp(event) {
        event.preventDefault();
        const form = event.target;
        const name = form.querySelector('#name').value;
        const email = form.querySelector('#email').value;
        const phone = form.querySelector('#phone').value;
        const courseSelect = form.querySelector('#course');
        const course = courseSelect.options[courseSelect.selectedIndex].text;
        const message = form.querySelector('#message').value;

        const whatsappNumber = "919729615438";

        let whatsappMessage = `*New Enquiry from Website (pSEO Page)*\n\n`;
        whatsappMessage += `*Name:* ${name}\n`;
        if (email) whatsappMessage += `*Email:* ${email}\n`;
        whatsappMessage += `*Phone:* ${phone}\n`;
        whatsappMessage += `*Course:* ${course}\n`;
        if (message) whatsappMessage += `*Message:* ${message}\n`;

        window.open(`https://wa.me/${whatsappNumber}?text=${encodeURIComponent(whatsappMessage)}`, '_blank');
    }
    </script>
<?php include 'includes/footer.php'; ?>
