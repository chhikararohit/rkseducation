<?php
require_once 'includes/db.php';
$teachers = [];
$courses = [];
$gallery = [];
$achievements = [];
try {
    $teachers = $pdo->query("SELECT * FROM teachers ORDER BY display_order ASC, id DESC")->fetchAll();
    $courses = $pdo->query("SELECT * FROM courses ORDER BY display_order ASC, id ASC")->fetchAll();
    $gallery = $pdo->query("SELECT * FROM gallery ORDER BY id DESC LIMIT 8")->fetchAll();
    $achievements = $pdo->query("SELECT * FROM achievements ORDER BY display_order ASC, id DESC")->fetchAll();
} catch (Exception $e) { }
?>
<?php 
$active_page = 'home';
include 'includes/header.php'; 
?>

    <main>
        <!-- Hero Section -->
        <section id="hero" class="hero">
            <div class="hero-overlay"></div>
            <div class="container relative min-height-screen flex flex-col justify-center">
                <div class="hero-content">
                    <span class="badge" style="font-size: 1.25rem; text-transform: none; letter-spacing: normal; background-color: var(--primary); color: #fff; box-shadow: var(--shadow-md);">आओ पढ़ाई करे</span>
                    <h1 style="color: var(--primary);">Best Coaching Institute in <span class="highlight-text" style="color: #d97706;">Ganaur</span></h1>
                    <p style="color: var(--primary); font-weight: 600; font-size: 1.25rem;">At RKS Temple Of Education, we provide dedicated coaching, expert guidance, and a foundation for
                        lifelong success in a competitive world.</p>
                    <div class="hero-buttons">
                        <a href="#courses" class="btn btn-primary">Explore Courses</a>
                        <a href="#contact" class="btn btn-secondary">Get Free Demo Class</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="about section-padding">
            <div class="container">
                <div class="grid grid-2 items-center gap-4">
                    <div class="about-image">
                        <img src="assets/images/about-us.webp" alt="RKS Temple of Education Front View">
                    </div>
                    <div class="about-content">
                        <div class="section-title left-align">
                            <h2>About <span class="highlight-text">Us</span></h2>
                            <div class="underline"></div>
                        </div>
                        <p>RKS Temple Of Education is a premier coaching institute established with the mission to
                            impart quality education and personal mentorship. Located in Ganaur, Haryana, we cater to
                            students seeking strong academic foundations and competitive edge.</p>
                        <p>Our experienced faculty believes in holistic development, fostering conceptual clarity, and
                            building the confidence required to tackle any academic challenge.</p>
                        <ul class="features-list">
                            <li><i class="fas fa-check-circle"></i> Experienced & Dedicated Faculty</li>
                            <li><i class="fas fa-check-circle"></i> Comprehensive Study Material</li>
                            <li><i class="fas fa-check-circle"></i> Regular Assessments & Doubt Sessions</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Teachers Section -->
        <section id="teachers" class="teachers section-padding">
            <div class="container">
                <div class="section-title center">
                    <h2>Our <span class="highlight-text">Tutors</span></h2>
                    <div class="underline center"></div>
                    <p>Meet the dedicated academic experts shaping our students' futures.</p>
                </div>
                <div class="grid grid-3 gap-4 tutors-grid" style="justify-content: center;">
                    <?php if(!empty($teachers)): foreach($teachers as $t): ?>
                    <div class="course-card text-center" style="max-width: 400px; margin: 0 auto; width:100%; position: relative;">
                        <img src="<?= htmlspecialchars($t['image_path']) ?>" alt="<?= htmlspecialchars($t['name']) ?>"
                            style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; object-position: top; margin: 0 auto 1.5rem; display: inline-block;"
                            onerror="this.src='https://placehold.co/150x150/1e3a8a/ffffff?text=Tutor'">
                        <h3>
                            <?= htmlspecialchars($t['name']) ?>
                            <?php if ($t['display_order'] == 0): ?>
                                <i class="fas fa-star" title="Main Tutor" style="color: #eab308; font-size: 1rem; margin-left: 5px;"></i>
                            <?php endif; ?>
                        </h3>
                        <p class="text-light"><?= htmlspecialchars($t['role']) ?></p>
                    </div>
                    <?php endforeach; else: ?>
                    <div class="course-card text-center" style="max-width: 400px; margin: 0 auto;">
                        <img src="assets/images/ankit-ashok-kaushik.webp" alt="Mr. Ankit Ashok Kaushik"
                            style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; object-position: top; margin: 0 auto 1.5rem; display: inline-block;">
                        <h3>Mr. Ankit Ashok Kaushik</h3>
                        <p class="text-light">Director & Lead Educator</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Courses Section -->
        <section id="courses" class="courses section-padding light-bg">
            <div class="container">
                <div class="section-title center">
                    <h2>Our <span class="highlight-text">Programs</span></h2>
                    <div class="underline center"></div>
                    <p>Designed to nurture potential and deliver results across all academic levels.</p>
                </div>
                <div class="grid grid-3 gap-3">
                    <?php if(!empty($courses)): foreach($courses as $c): ?>
                    <div class="course-card">
                        <div class="card-icon"><i class="<?= htmlspecialchars($c['icon_class']) ?>"></i></div>
                        <h3><?= htmlspecialchars($c['title']) ?></h3>
                        <p><?= htmlspecialchars($c['description']) ?></p>
                        <a href="#contact" class="btn btn-outline btn-sm">Join Program</a>
                    </div>
                    <?php endforeach; else: ?>
                    <div class="course-card">
                        <div class="card-icon"><i class="fas fa-school"></i></div>
                        <h3>School Coaching (VIII - XII)</h3>
                        <p>Comprehensive subject coaching focusing on board exam excellence.</p>
                        <a href="#contact" class="btn btn-outline btn-sm">Join Program</a>
                    </div>
                    <!-- fallback defaults stripped for brevity since db operates -->
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Why Choose Us Section -->
        <section id="why-us" class="why-us section-padding">
            <div class="container">
                <div class="section-title center">
                    <h2>Why <span class="highlight-text">Choose</span> RKS?</h2>
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

        <!-- Gallery Section -->
        <section id="gallery" class="gallery section-padding light-bg">
            <div class="container">
                <div class="section-title center">
                    <h2>Our <span class="highlight-text">Gallery</span></h2>
                    <div class="underline center"></div>
                </div>
                <!-- Dynamic images grid -->
                <div class="gallery-grid">
                    <?php if(!empty($gallery)): foreach($gallery as $g): ?>
                    <div class="gallery-item">
                        <img src="<?= htmlspecialchars($g['image_path']) ?>" alt="<?= htmlspecialchars($g['title'] ?? 'Gallery') ?>" onerror="this.src='https://placehold.co/400x300/e2e8f0/1e293b?text=Gallery'">
                    </div>
                    <?php endforeach; else: ?>
                    <div class="gallery-item"><img src="assets/images/gallery-1.jpg" alt="Classroom view"></div>
                    <div class="gallery-item"><img src="assets/images/gallery-2.jpg" alt="Award Ceremony"></div>
                    <div class="gallery-item"><img src="assets/images/gallery-3.webp" alt="Study Material"></div>
                    <div class="gallery-item"><img src="assets/images/gallery-4.jpg" alt="Group Photo"></div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Achievements Section -->
        <section id="achievements" class="achievements section-padding">
            <div class="container">
                <div class="section-title center white-title">
                    <h2><span class="highlight-text">Student</span> Achievements</h2>
                    <div class="underline center"></div>
                    <p>Our students consistently deliver top results in board exams and national-level tests.</p>
                </div>
                
                <div class="carousel-wrapper">
                    <button class="carousel-arrow carousel-prev" id="ach-prev" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
                    <div class="achievements-carousel text-center">
                        <?php if(!empty($achievements)): foreach($achievements as $a): ?>
                        <div class="achievement-card">
                            <img src="<?= htmlspecialchars($a['image_path'] ?? 'assets/images/default-avatar.png') ?>" alt="<?= htmlspecialchars($a['name']) ?>" onerror="this.src='https://placehold.co/150x150/ffffff/1e3a8a?text=Student'">
                            <h3><?= htmlspecialchars($a['name']) ?></h3>
                            <p class="exam-details"><?= htmlspecialchars($a['exam_details']) ?></p>
                            <div class="score-badge"><?= htmlspecialchars($a['score']) ?></div>
                        </div>
                        <?php endforeach; else: ?>
                        <div class="achievement-card">
                            <img src="https://placehold.co/150x150/ffffff/1e3a8a?text=Student" alt="Student 1">
                            <h3>Rahul Verma</h3>
                            <p class="exam-details">CBSE Class 12th</p>
                            <div class="score-badge">98.5% Board Topper</div>
                        </div>
                        <div class="achievement-card">
                            <img src="https://placehold.co/150x150/ffffff/1e3a8a?text=Student" alt="Student 2">
                            <h3>Priya Sharma</h3>
                            <p class="exam-details">JEE Mains</p>
                            <div class="score-badge">99.8 Percentile</div>
                        </div>
                        <div class="achievement-card">
                            <img src="https://placehold.co/150x150/ffffff/1e3a8a?text=Student" alt="Student 3">
                            <h3>Amit Kumar</h3>
                            <p class="exam-details">CBSE Class 10th</p>
                            <div class="score-badge">96.4%</div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <button class="carousel-arrow carousel-next" id="ach-next" aria-label="Next"><i class="fas fa-chevron-right"></i></button>
                </div>
                
                <div class="text-center" style="margin-top: 1rem;">
                    <a href="#contact" class="btn btn-primary">Become our next Achiever!</a>
                </div>
            </div>
        </section>

        <!-- Awards Section -->
        <section id="awards" class="awards section-padding">
            <div class="container">
                <div class="section-title center">
                    <h2>Our <span class="highlight-text">Awards</span></h2>
                    <div class="underline center"></div>
                    <p>Recognitions that motivate us to keep delivering excellence in education.</p>
                </div>
                <div class="grid grid-3 gap-3">
                    <div class="gallery-item" style="aspect-ratio: auto; box-shadow: var(--shadow-md);">
                        <img src="assets/images/award-1.webp" alt="Award 1"
                            onerror="this.src='https://placehold.co/400x300/f8fafc/1e3a8a?text=Award+1'">
                    </div>
                    <div class="gallery-item" style="aspect-ratio: auto; box-shadow: var(--shadow-md);">
                        <img src="assets/images/award-2.webp" alt="Award 2"
                            onerror="this.src='https://placehold.co/400x300/f8fafc/1e3a8a?text=Award+2'">
                    </div>
                    <div class="gallery-item" style="aspect-ratio: auto; box-shadow: var(--shadow-md);">
                        <img src="assets/images/award-3.webp" alt="Award 3"
                            onerror="this.src='https://placehold.co/400x300/f8fafc/f97316?text=Award+3'">
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="contact section-padding light-bg">
            <div class="container">
                <div class="section-title center">
                    <h2>Get Free <span class="highlight-text">Demo Class</span></h2>
                    <div class="underline center"></div>
                    <p>Experience our teaching methodology firsthand. Book your free demo session today!</p>
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

                        <!-- Map embedded -->
                        <div class="map-container mt-2">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d111586.29959664654!2d76.95383561918341!3d29.1337424699564!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390daa86fe4ec8d1%3A0xeae06c649568973d!2sGanaur%2C%20Haryana!5e0!3m2!1sen!2sin!4v1712818290372!5m2!1sen!2sin"
                                width="100%" height="250" style="border:0; border-radius: 8px;" allowfullscreen=""
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            <a href="https://share.google/Mcp2ZLX0WmwXWKqLl" target="_blank"
                                class="btn btn-outline w-100 mt-2"
                                style="border-radius: 8px; display: block; text-align: center;"><i
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
                                    <option value="">Select a Course</option>
                                    <option value="school">School Coaching (VIII - XII)</option>
                                    <option value="competitive">Competitive Exams (JEE/NEET)</option>
                                    <option value="foundation">Foundation Courses</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="message">Message</label>
                                <textarea id="message" name="message" rows="4"
                                    placeholder="How can we help?"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100"
                                style="background-color: #25D366; border-color: #25D366;">Send via WhatsApp <i
                                    class="fab fa-whatsapp" style="font-size: 1.1rem; margin-left: 5px;"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

<?php include 'includes/footer.php'; ?>