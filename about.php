 <?php
// Start session (optional, for future use)
session_start();

// Include database connection
require_once 'config/db.php';

// Fetch all trainers from database
$stmt = $pdo->query("SELECT * FROM trainers ORDER BY id");
$allTrainers = $stmt->fetchAll();

// Total number of trainers for the counter
$totalTrainers = count($allTrainers);

// For "load more" functionality: first 3 trainers, then the rest hidden
$displayTrainers = array_slice($allTrainers, 0, 3);
$hasMoreTrainers = $totalTrainers > 3;
$extraTrainers = array_slice($allTrainers, 3);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>About Us | Jyotisoft Software Training Institute</title>
    <!-- Bootstrap 5 + Font Awesome + Premium Font (Plus Jakarta Sans) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        /* ========== PREMIUM COLOR SCIENCE (Matching index.html) ========== */
        :root {
            --primary: #00C853;
            --primary-dark: #009624;
            --secondary: #9C27B0;
            --secondary-light: #D05CE3;
            --accent-blue: #00B8FF;
            --accent-orange: #FF6D00;
            --dark: #0B1120;
            --dark-2: #111827;
            --surface: #FFFFFF;
            --surface-2: #F8FAFC;
            --text: #0F172A;
            --text-light: #64748B;
            --glass: rgba(255, 255, 255, 0.08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
            background: var(--surface);
            scroll-behavior: smooth;
            color: var(--text);
            cursor: default;
        }

        /* scroll progress */
        .scroll-progress {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 4px;
            background: var(--primary);
            z-index: 9999;
            transition: width 0.1s ease;
        }

        /* ===== PREMIUM NAVBAR (GLASSMORPHISM) ===== */
        .navbar {
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 40px rgba(15, 23, 42, 0.06);
            padding: 0.75rem 0;
            transition: all 0.3s ease;
        }

        .navbar .nav-link {
            color: var(--text) !important;
            font-weight: 600;
            font-size: 1rem;
            margin: 0 0.2rem;
            transition: color 0.2s;
        }

        .navbar .nav-link:hover,
        .navbar .nav-link.active {
            color: var(--secondary) !important;
        }

        .brand-text {
            color: var(--text);
            font-weight: 800;
            font-size: 1.8rem;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .logo-container {
            width: 55px;
            height: 55px;
            background: white;
            border-radius: 14px;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease;
        }

        .logo-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* ===== PREMIUM PAGE HEADER (Gradient + Glass) ===== */
        .page-header {
            background: radial-gradient(circle at 20% 30%, rgba(0, 200, 83, 0.12), transparent 45%),
                        radial-gradient(circle at 85% 70%, rgba(156, 39, 176, 0.12), transparent 50%),
                        linear-gradient(135deg, #ffffff, #f8fafc, #eef2ff);
            min-height: 50vh;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            text-align: center;
            padding: 80px 0;
        }

        .hero-grid {
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(rgba(0, 200, 83, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 200, 83, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
            z-index: 0;
            opacity: 0.5;
        }

        .blob {
            position: absolute;
            filter: blur(100px);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }
        .blob-1 { width: 300px; height: 300px; background: var(--primary); top: 10%; left: -100px; opacity: 0.2; }
        .blob-2 { width: 350px; height: 350px; background: var(--secondary); right: -100px; top: 20%; opacity: 0.18; }
        .blob-3 { width: 250px; height: 250px; background: var(--accent-blue); bottom: 0; left: 50%; opacity: 0.15; }

        .gradient-text {
            background: linear-gradient(120deg, #00C853, #00B8FF, #9C27B0, #00C853);
            background-size: 300% auto;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer 6s linear infinite;
        }
        @keyframes shimmer {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Section Titles */
        .section-title {
            font-weight: 800;
            font-size: 2.5rem;
            position: relative;
            margin-bottom: 2rem;
            color: var(--text);
        }
        .section-title:after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            margin: 15px auto 0 auto;
            border-radius: 10px;
        }
        .text-start.section-title:after {
            margin-left: 0;
        }

        /* Premium Cards (Mission, Vision, Achievements) */
        .premium-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 28px;
            transition: all 0.4s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            box-shadow: 0 20px 35px -12px rgba(0,0,0,0.08);
            height: 100%;
            overflow: hidden;
            position: relative;
        }
        .premium-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            z-index: 2;
        }
        .premium-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(15, 23, 42, 0.12);
            border-color: rgba(0,200,83,0.2);
        }

        /* Trainer Card (premium style) */
        .trainer-card {
            background: white;
            border-radius: 28px;
            transition: all 0.3s;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        .trainer-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 40px rgba(0, 0, 0, 0.1);
            border-color: var(--primary);
        }
        .trainer-avatar {
            width: 130px;
            height: 130px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
        }

        /* Premium Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #00C853, #00AEEF);
            border: none;
            border-radius: 14px;
            box-shadow: 0 15px 30px rgba(0, 200, 83, 0.25);
            padding: 0.9rem 2rem;
            font-weight: 700;
            transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-4px);
            box-shadow: 0 25px 50px rgba(0, 200, 83, 0.4);
            background: linear-gradient(135deg, #00b347, #0095d4);
        }
        .btn-outline-premium {
            border-radius: 14px;
            border: 2px solid var(--secondary);
            background: transparent;
            color: var(--secondary);
            font-weight: 700;
            padding: 0.8rem 1.8rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(4px);
        }
        .btn-outline-premium:hover {
            background: var(--secondary);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(156, 39, 176, 0.25);
        }

        /* Statistics Premium Section (Dark with gradient overlay) */
        .stats-premium {
            background: linear-gradient(135deg, #0B1120, #111827);
            position: relative;
            overflow: hidden;
        }
        .stats-premium::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top left, rgba(0, 200, 83, 0.15), transparent);
            pointer-events: none;
        }
        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--accent-blue));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Cert badges */
        .cert-badge {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 40px;
            padding: 0.7rem 1.5rem;
            font-weight: 600;
            color: var(--dark);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
            display: inline-block;
            margin: 0.3rem;
        }
        .cert-badge:hover {
            transform: translateY(-3px);
            background: var(--secondary);
            color: white;
        }

        /* CTA Gradient */
        .cta-gradient {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 32px;
        }

        /* Custom cursor */
        .custom-cursor {
            width: 28px;
            height: 28px;
            background: radial-gradient(circle, rgba(0,200,83,0.5), rgba(156,39,176,0.3));
            border-radius: 50%;
            position: fixed;
            pointer-events: none;
            z-index: 9999;
            mix-blend-mode: screen;
            transform: translate(-50%, -50%);
            backdrop-filter: blur(4px);
        }

        @media (max-width: 768px) {
            .section-title { font-size: 2rem; }
            .custom-cursor { display: none; }
            .hero-grid { background-size: 30px 30px; }
        }

        /* For "load more" trainers */
        .extra-trainer {
            display: none;
        }
        .show-extra .extra-trainer {
            display: block;
        }
    </style>
</head>
<body>

<div class="scroll-progress" id="scrollProgress"></div>
<div class="custom-cursor" id="customCursor"></div>

 <!--   Navbar -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <div class="logo-container me-3">
                <img src="./images/logo.png" alt="Jyotisoft Logo" onerror="this.src='https://via.placeholder.com/55?text=J'">
            </div>
            <span class="brand-text">Jyotisoft</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                <li class="nav-item"><a class="nav-link" href="courses.php">Courses</a></li>
                <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- PREMIUM PAGE HEADER (gradient + blobs + animated grid) -->
<section class="page-header">
    <div class="hero-grid"></div>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
    <div class="container position-relative z-3">
        <h1 class="display-4 fw-bold" data-aos="fade-up">About <span class="gradient-text">Jyotisoft</span></h1>
        <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">Empowering careers through quality IT education since 2015</p>
    </div>
</section>

<!-- Institute History -->
<section class="container my-5 py-4">
    <div class="row align-items-center g-5">
        <div class="col-lg-6" data-aos="fade-right">
            <h2 class="section-title text-start">Our Journey</h2>
            <p class="lead">Jyotisoft was founded in 2015 with a simple yet powerful mission: to bridge the gap between academic knowledge and industry demands.</p>
            <p>What started as a small classroom with 10 students has now grown into a premier software training institute with over 5000+ successful graduates. We have consistently updated our curriculum to keep pace with the latest technologies – from Web Development to AI & Machine Learning. Our alumni work at top companies like Google, Microsoft, and leading startups across the globe.</p>
            <p>Today, Jyotisoft is recognized as one of Nepal's most trusted IT training centers, offering both online and offline courses with a strong focus on practical, project-based learning.</p>
        </div>
        <div class="col-lg-6" data-aos="fade-left">
            <img src="https://picsum.photos/id/20/600/400" alt="Institute History" class="img-fluid rounded-4 shadow-lg" style="object-fit: cover; width: 100%; border-radius: 28px;">
        </div>
    </div>
</section>

<!-- Mission & Vision (Premium Cards) -->
<section class="bg-soft-light py-5" style="background-color: var(--surface-2);">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6" data-aos="zoom-in">
                <div class="premium-card p-4 text-center h-100">
                    <i class="fas fa-bullseye fa-3x" style="color: var(--primary); margin-bottom: 1rem;"></i>
                    <h3>Our Mission</h3>
                    <p class="text-muted">To provide industry-relevant, affordable, and accessible IT education that empowers students to excel in their careers.</p>
                </div>
            </div>
            <div class="col-md-6" data-aos="zoom-in" data-aos-delay="100">
                <div class="premium-card p-4 text-center h-100">
                    <i class="fas fa-eye fa-3x" style="color: var(--secondary); margin-bottom: 1rem;"></i>
                    <h3>Our Vision</h3>
                    <p class="text-muted">To become a global leader in IT training, recognized for producing highly skilled professionals who drive technological advancement worldwide.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Key Achievements -->
<section class="container my-5">
    <h2 class="section-title text-center" data-aos="fade-down">Our Milestones</h2>
    <div class="row g-4 mt-2">
        <div class="col-md-4" data-aos="flip-left">
            <div class="premium-card p-4 text-center h-100">
                <i class="fas fa-trophy fa-2x" style="color: var(--accent-orange); margin-bottom: 1rem;"></i>
                <h5>Best IT Training Institute</h5>
                <p class="text-muted">Winner of "Education Excellence Award" 2023 & 2024</p>
            </div>
        </div>
        <div class="col-md-4" data-aos="flip-left" data-aos-delay="100">
            <div class="premium-card p-4 text-center h-100">
                <i class="fas fa-users fa-2x" style="color: var(--primary); margin-bottom: 1rem;"></i>
                <h5>5000+ Students Trained</h5>
                <p class="text-muted">Over 5,000 students placed in top IT companies</p>
            </div>
        </div>
        <div class="col-md-4" data-aos="flip-left" data-aos-delay="200">
            <div class="premium-card p-4 text-center h-100">
                <i class="fas fa-chalkboard-user fa-2x" style="color: var(--secondary); margin-bottom: 1rem;"></i>
                <h5>30+ Expert Trainers</h5>
                <p class="text-muted">Industry professionals with 10+ years of experience</p>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section (premium dark) -->
<section class="stats-premium py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-4"><div class="stat-number counter-num" data-target="10">0</div><p class="text-white-50 fw-semibold">Years of Excellence</p></div>
            <div class="col-md-3 col-6 mb-4"><div class="stat-number counter-num" data-target="5800">0</div><p class="text-white-50 fw-semibold">Students Trained</p></div>
            <div class="col-md-3 col-6 mb-4"><div class="stat-number counter-num" data-target="3120">0</div><p class="text-white-50 fw-semibold">Placements</p></div>
            <div class="col-md-3 col-6 mb-4"><div class="stat-number counter-num" data-target="<?php echo $totalTrainers; ?>">0</div><p class="text-white-50 fw-semibold">Expert Trainers</p></div>
        </div>
    </div>
</section>

<!-- Trainer Profiles (dynamic from database) -->
<section class="container my-5">
    <h2 class="section-title text-center" data-aos="fade-up">Meet Our Core Trainers</h2>
    <div class="row g-4" id="trainerCardsRow">
        <?php foreach ($displayTrainers as $trainer): ?>
            <div class="col-md-4 trainer-item">
                <div class="trainer-card text-center p-4 h-100">
                    <img src="<?php echo htmlspecialchars($trainer['image_path'] ?: 'https://via.placeholder.com/130'); ?>" class="trainer-avatar mb-3" alt="<?php echo htmlspecialchars($trainer['name']); ?>" onerror="this.src='https://randomuser.me/api/portraits/men/1.jpg'">
                    <h5 class="fw-bold mt-2"><?php echo htmlspecialchars($trainer['name']); ?></h5>
                    <p class="text-primary fw-semibold"><?php echo htmlspecialchars($trainer['role']); ?></p>
                    <small class="text-muted"><?php echo htmlspecialchars($trainer['description']); ?></small>
                    <div class="mt-3">
                        <i class="fab fa-linkedin text-primary me-2"></i>
                        <i class="fab fa-github text-dark"></i>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if ($hasMoreTrainers): ?>
            <?php foreach ($extraTrainers as $trainer): ?>
                <div class="col-md-4 extra-trainer">
                    <div class="trainer-card text-center p-4 h-100">
                        <img src="<?php echo htmlspecialchars($trainer['image_path'] ?: 'https://via.placeholder.com/130'); ?>" class="trainer-avatar mb-3" alt="<?php echo htmlspecialchars($trainer['name']); ?>" onerror="this.src='https://randomuser.me/api/portraits/men/1.jpg'">
                        <h5 class="fw-bold mt-2"><?php echo htmlspecialchars($trainer['name']); ?></h5>
                        <p class="text-primary fw-semibold"><?php echo htmlspecialchars($trainer['role']); ?></p>
                        <small class="text-muted"><?php echo htmlspecialchars($trainer['description']); ?></small>
                        <div class="mt-3">
                            <i class="fab fa-linkedin text-primary me-2"></i>
                            <i class="fab fa-github text-dark"></i>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?php if ($hasMoreTrainers): ?>
        <div class="text-center mt-4">
            <button id="loadMoreTrainersBtn" class="btn btn-outline-premium px-5 magnetic-btn">View More Trainers <i class="fas fa-chevron-down ms-2"></i></button>
        </div>
    <?php endif; ?>
    <div class="text-center mt-4">
        <button id="refreshTrainersBtn" class="btn btn-outline-premium rounded-pill px-4 magnetic-btn"><i class="fas fa-sync-alt me-2"></i> Refresh Trainers</button>
    </div>
</section>

<!-- Certifications & Recognitions (glass badges) -->
<section class="py-5" style="background-color: var(--surface-2);">
    <div class="container">
        <h2 class="section-title text-center" data-aos="fade-up">Certifications & Recognitions</h2>
        <div class="text-center mt-4" data-aos="zoom-in">
            <span class="cert-badge"><i class="fas fa-check-circle me-2" style="color: var(--primary);"></i>ISO 9001:2024 Certified</span>
            <span class="cert-badge"><i class="fas fa-check-circle me-2" style="color: var(--primary);"></i>Microsoft Learning Partner</span>
            <span class="cert-badge"><i class="fas fa-check-circle me-2" style="color: var(--primary);"></i>Google Developers Authorized</span>
            <span class="cert-badge"><i class="fas fa-check-circle me-2" style="color: var(--primary);"></i>Websoft Technology Partner</span>
            <span class="cert-badge"><i class="fas fa-check-circle me-2" style="color: var(--primary);"></i>Government of Nepal Approved</span>
        </div>
    </div>
</section>

<!-- Call to Action (gradient card) -->
<section class="container my-5">
    <div class="cta-gradient p-5 text-white text-center rounded-4" data-aos="flip-up">
        <h2 class="fw-bold">Become Part of Our Success Story</h2>
        <p class="lead mb-4">Join thousands of students who have transformed their careers with Jyotisoft.</p>
        <a href="register.php" class="btn btn-light btn-lg px-5 magnetic-btn" style="border-radius: 14px; font-weight: 700;">Apply for Admission <i class="fas fa-arrow-right ms-2"></i></a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script>
    AOS.init({ duration: 800, once: false, mirror: true, offset: 80 });
    gsap.registerPlugin(ScrollTrigger);

    // Scroll progress
    window.addEventListener('scroll', () => {
        const winScroll = document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - window.innerHeight;
        const scrolled = (winScroll / height) * 100;
        document.getElementById('scrollProgress').style.width = scrolled + '%';
    });

    // Custom cursor
    const cursor = document.getElementById('customCursor');
    if (cursor) {
        document.addEventListener('mousemove', (e) => {
            gsap.to(cursor, { duration: 0.15, x: e.clientX, y: e.clientY, ease: "power2.out" });
        });
        const interactiveElements = document.querySelectorAll('a, button, .premium-card, .trainer-card, .cert-badge');
        interactiveElements.forEach(el => {
            el.addEventListener('mouseenter', () => gsap.to(cursor, { scale: 1.6, opacity: 0.7, duration: 0.2 }));
            el.addEventListener('mouseleave', () => gsap.to(cursor, { scale: 1, opacity: 1, duration: 0.2 }));
        });
    }

    // Magnetic buttons
    const magneticBtns = document.querySelectorAll('.magnetic-btn');
    magneticBtns.forEach(btn => {
        btn.addEventListener('mousemove', (e) => {
            const rect = btn.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            gsap.to(btn, { duration: 0.25, x: x * 0.2, y: y * 0.2, ease: "power2.out" });
        });
        btn.addEventListener('mouseleave', () => gsap.to(btn, { duration: 0.25, x: 0, y: 0, ease: "power2.out" }));
    });

    // Load more trainers functionality
    const loadMoreBtn = document.getElementById('loadMoreTrainersBtn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', () => {
            document.querySelectorAll('.extra-trainer').forEach(el => el.style.display = 'block');
            loadMoreBtn.style.display = 'none';
        });
    }

    // Refresh trainers (reload page)
    document.getElementById('refreshTrainersBtn')?.addEventListener('click', () => {
        window.location.reload();
    });

    // Counter animation with GSAP
    function initCounters() {
        const counters = document.querySelectorAll('.counter-num');
        counters.forEach(counter => {
            let target = parseInt(counter.getAttribute('data-target'));
            if (isNaN(target)) target = 0;
            ScrollTrigger.create({
                trigger: counter,
                start: "top 80%",
                once: true,
                onEnter: () => {
                    gsap.fromTo(counter, { innerText: 0 }, {
                        innerText: target,
                        duration: 2,
                        snap: { innerText: 1 },
                        onUpdate: () => { counter.innerText = Math.floor(parseInt(counter.innerText)); }
                    });
                }
            });
        });
    }

    window.addEventListener('DOMContentLoaded', () => {
        initCounters();
        // Animate page header
        gsap.fromTo('.page-header h1', { opacity: 0, y: 30 }, { opacity: 1, y: 0, duration: 0.8, delay: 0.2 });
        gsap.fromTo('.page-header .lead', { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.8, delay: 0.4 });
    });
</script>
</body>
</html>