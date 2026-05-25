<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'RKS Temple Of Education | Premier Coaching Institute') ?></title>
    <link rel="icon" type="image/png" href="assets/images/logo.png">
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'RKS Temple Of Education in Ganaur provides top-tier coaching for School, Competitive Exams, and Foundation Courses. Join us for a bright future.') ?>">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo filemtime('assets/css/style.css'); ?>">
    
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Extra Head Content -->
    <?= $extra_head ?? '' ?>
</head>

<body>

    <!-- Header Overlay Backdrop -->
    <div class="nav-backdrop" id="nav-backdrop"></div>

    <!-- Header -->
    <header class="header" id="header">
        <div class="container">
            <div class="nav-container">
                <a href="index.php" class="logo">
                    <img src="assets/images/logo.png" alt="RKS Temple Of Education Logo">
                    <span>RKS Temple Of Education</span>
                </a>
                
                <!-- Desktop Navigation -->
                <nav class="desktop-nav">
                    <ul>
                        <li><a href="index.php#hero" <?= ($active_page ?? '') === 'home' ? 'class="active"' : '' ?>>Home</a></li>
                        <li>
                            <a href="#" class="desktop-dropdown-trigger <?= ($active_page ?? '') === 'foundation' ? 'active' : '' ?>" data-target="mega-foundation">
                                Foundation Program <i class="fas fa-chevron-down dropdown-arrow"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="desktop-dropdown-trigger <?= ($active_page ?? '') === 'science' ? 'active' : '' ?>" data-target="mega-science">
                                Science Coaching <i class="fas fa-chevron-down dropdown-arrow"></i>
                            </a>
                        </li>
                        <li><a href="index.php#about" <?= ($active_page ?? '') === 'about' ? 'class="active"' : '' ?>>About</a></li>
                        <li><a href="blogs.php" <?= ($active_page ?? '') === 'blogs' ? 'class="active"' : '' ?>>Blogs</a></li>
                        <li><a href="index.php#contact" <?= ($active_page ?? '') === 'contact' ? 'class="active"' : '' ?>>Contact</a></li>
                    </ul>
                </nav>

                <div class="header-actions">
                    <a href="tel:+919876543210" class="btn btn-outline mobile-call">Call Now</a>
                    <button class="hamburger" id="hamburger" aria-label="Toggle Menu">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Desktop Mega Menus (Appears below header) -->
        <div class="mega-menu-panel" id="mega-foundation">
            <div class="container">
                <div class="mega-menu-grid">
                    <a href="class-1-foundation-program-in-ganaur" class="mega-card">
                        <div class="mega-card-icon"><i class="fas fa-book-open"></i></div>
                        <div class="mega-card-content">
                            <div class="mega-card-title">Class 1</div>
                            <div class="mega-card-desc">Strong foundation learning</div>
                        </div>
                    </a>
                    <a href="class-2-foundation-program-in-ganaur" class="mega-card">
                        <div class="mega-card-icon"><i class="fas fa-book-open"></i></div>
                        <div class="mega-card-content">
                            <div class="mega-card-title">Class 2</div>
                            <div class="mega-card-desc">Concept & confidence building</div>
                        </div>
                    </a>
                    <a href="class-3-foundation-program-in-ganaur" class="mega-card">
                        <div class="mega-card-icon"><i class="fas fa-book-open"></i></div>
                        <div class="mega-card-content">
                            <div class="mega-card-title">Class 3</div>
                            <div class="mega-card-desc">Early conceptual clarity</div>
                        </div>
                    </a>
                    <a href="class-4-foundation-program-in-ganaur" class="mega-card">
                        <div class="mega-card-icon"><i class="fas fa-book-open"></i></div>
                        <div class="mega-card-content">
                            <div class="mega-card-title">Class 4</div>
                            <div class="mega-card-desc">Skill & logic discovery</div>
                        </div>
                    </a>
                    <a href="class-5-foundation-program-in-ganaur" class="mega-card">
                        <div class="mega-card-icon"><i class="fas fa-book-open"></i></div>
                        <div class="mega-card-content">
                            <div class="mega-card-title">Class 5</div>
                            <div class="mega-card-desc">Analytical thinking skills</div>
                        </div>
                    </a>
                    <a href="class-6-foundation-program-in-ganaur" class="mega-card">
                        <div class="mega-card-icon"><i class="fas fa-book-open"></i></div>
                        <div class="mega-card-content">
                            <div class="mega-card-title">Class 6</div>
                            <div class="mega-card-desc">Advanced fundamentals</div>
                        </div>
                    </a>
                    <a href="class-7-foundation-program-in-ganaur" class="mega-card">
                        <div class="mega-card-icon"><i class="fas fa-book-open"></i></div>
                        <div class="mega-card-content">
                            <div class="mega-card-title">Class 7</div>
                            <div class="mega-card-desc">Pre-boards preparation</div>
                        </div>
                    </a>
                    <a href="class-8-foundation-program-in-ganaur" class="mega-card">
                        <div class="mega-card-icon"><i class="fas fa-book-open"></i></div>
                        <div class="mega-card-content">
                            <div class="mega-card-title">Class 8</div>
                            <div class="mega-card-desc">Olympiad ready base</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="mega-menu-panel" id="mega-science">
            <div class="container" style="max-width: 800px;">
                <div class="mega-menu-grid cols-2">
                    <a href="class-9-coaching-in-ganaur" class="mega-card">
                        <div class="mega-card-icon"><i class="fas fa-atom"></i></div>
                        <div class="mega-card-content">
                            <div class="mega-card-title">Class 9</div>
                            <div class="mega-card-desc">Core Science Principles</div>
                        </div>
                    </a>
                    <a href="class-10-coaching-in-ganaur" class="mega-card">
                        <div class="mega-card-icon"><i class="fas fa-atom"></i></div>
                        <div class="mega-card-content">
                            <div class="mega-card-title">Class 10</div>
                            <div class="mega-card-desc">Board Exam Focus</div>
                        </div>
                    </a>
                    <a href="class-11-coaching-in-ganaur" class="mega-card">
                        <div class="mega-card-icon"><i class="fas fa-atom"></i></div>
                        <div class="mega-card-content">
                            <div class="mega-card-title">Class 11</div>
                            <div class="mega-card-desc">Specialized Streams</div>
                        </div>
                    </a>
                    <a href="class-12-coaching-in-ganaur" class="mega-card">
                        <div class="mega-card-icon"><i class="fas fa-atom"></i></div>
                        <div class="mega-card-content">
                            <div class="mega-card-title">Class 12</div>
                            <div class="mega-card-desc">Competitive Excellence</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation (Accordion) -->
        <nav class="mobile-nav" id="mobile-nav">
            <div class="mobile-nav-inner">
                <ul>
                    <li><a href="index.php#hero" <?= ($active_page ?? '') === 'home' ? 'class="active"' : '' ?>>Home</a></li>
                    <li class="mobile-accordion-item">
                        <button class="mobile-accordion-trigger">
                            Foundation Program <i class="fas fa-chevron-down dropdown-arrow"></i>
                        </button>
                        <div class="mobile-accordion-content">
                            <a href="class-1-foundation-program-in-ganaur">Class 1</a>
                            <a href="class-2-foundation-program-in-ganaur">Class 2</a>
                            <a href="class-3-foundation-program-in-ganaur">Class 3</a>
                            <a href="class-4-foundation-program-in-ganaur">Class 4</a>
                            <a href="class-5-foundation-program-in-ganaur">Class 5</a>
                            <a href="class-6-foundation-program-in-ganaur">Class 6</a>
                            <a href="class-7-foundation-program-in-ganaur">Class 7</a>
                            <a href="class-8-foundation-program-in-ganaur">Class 8</a>
                        </div>
                    </li>
                    <li class="mobile-accordion-item">
                        <button class="mobile-accordion-trigger">
                            Science Coaching <i class="fas fa-chevron-down dropdown-arrow"></i>
                        </button>
                        <div class="mobile-accordion-content">
                            <a href="class-9-coaching-in-ganaur">Class 9</a>
                            <a href="class-10-coaching-in-ganaur">Class 10</a>
                            <a href="class-11-coaching-in-ganaur">Class 11</a>
                            <a href="class-12-coaching-in-ganaur">Class 12</a>
                        </div>
                    </li>
                    <li><a href="index.php#about" <?= ($active_page ?? '') === 'about' ? 'class="active"' : '' ?>>About</a></li>
                    <li><a href="blogs.php" <?= ($active_page ?? '') === 'blogs' ? 'class="active"' : '' ?>>Blogs</a></li>
                    <li><a href="index.php#contact" <?= ($active_page ?? '') === 'contact' ? 'class="active"' : '' ?>>Contact</a></li>
                </ul>
            </div>
        </nav>
    </header>
