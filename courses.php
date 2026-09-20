 <?php
// Start session (optional)
session_start();

// Include database connection
require_once 'config/db.php';

// Fetch all courses from database
$stmt = $pdo->query("SELECT * FROM courses ORDER BY id");
$courses = $stmt->fetchAll();

// Total number of courses for the counter
$totalCourses = count($courses);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Courses | Jyotisoft Software Training Institute</title>
    <!-- Bootstrap 5 + Font Awesome + Premium Font (Plus Jakarta Sans) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        /* ========== PREMIUM COLOR SCIENCE (Matching index.php) ========== */
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

        /* ===== PREMIUM PAGE HEADER (Gradient + Blobs) ===== */
        .page-header {
            background: radial-gradient(circle at 20% 30%, rgba(0, 200, 83, 0.12), transparent 45%),
                        radial-gradient(circle at 85% 70%, rgba(156, 39, 176, 0.12), transparent 50%),
                        linear-gradient(135deg, #ffffff, #f8fafc, #eef2ff);
            min-height: 45vh;
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

        /* Premium Filter Buttons */
        .filter-btn {
            margin: 5px;
            border-radius: 40px;
            padding: 8px 24px;
            transition: all 0.3s ease;
            font-weight: 600;
            border: 1px solid #e2e8f0;
            background: white;
            color: var(--text);
            font-size: 0.9rem;
        }
        .filter-btn.active {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-color: transparent;
            box-shadow: 0 8px 20px rgba(0, 200, 83, 0.25);
            transform: scale(1.02);
        }
        .filter-btn:hover:not(.active) {
            transform: translateY(-2px);
            border-color: var(--primary);
            color: var(--primary);
        }

        /* Premium Course Cards */
        .card-premium {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
            position: relative;
            height: 100%;
        }
        .card-premium::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #00C853, #00B8FF, #9C27B0);
            z-index: 2;
        }
        .card-premium:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(15, 23, 42, 0.12);
        }
        .course-img {
            transition: transform 0.5s ease;
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        .card-premium:hover .course-img {
            transform: scale(1.05);
        }

        /* Premium Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #00C853, #00AEEF);
            border: none;
            border-radius: 14px;
            box-shadow: 0 15px 30px rgba(0, 200, 83, 0.25);
            padding: 0.7rem 1.5rem;
            font-weight: 700;
            transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 25px 40px rgba(0, 200, 83, 0.35);
            background: linear-gradient(135deg, #00b347, #0095d4);
        }
        .btn-outline-premium {
            border-radius: 14px;
            border: 2px solid var(--secondary);
            background: transparent;
            color: var(--secondary);
            font-weight: 700;
            padding: 0.6rem 1.5rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(4px);
        }
        .btn-outline-premium:hover {
            background: var(--secondary);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(156, 39, 176, 0.25);
        }

        /* Statistics Premium Section */
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

        /* Why Choose Us Cards */
        .why-card {
            background: white;
            border-radius: 24px;
            padding: 2rem;
            transition: all 0.3s;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.05);
            height: 100%;
        }
        .why-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 40px rgba(0, 0, 0, 0.1);
            border-color: var(--primary);
        }

        /* CTA Section */
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
            .stat-number { font-size: 2rem; }
        }

        .bg-soft-light { background-color: var(--surface-2); }
        .toast-notify {
            position: fixed; bottom: 25px; right: 25px; z-index: 1100;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white; padding: 12px 24px;
            border-radius: 60px; font-weight: 600; box-shadow: 0 8px 18px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

<div class="scroll-progress" id="scrollProgress"></div>
<div class="custom-cursor" id="customCursor"></div>

<!-- PREMIUM GLASS NAVBAR (links updated to .php) -->
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
                <li class="nav-item"><a class="nav-link active" href="courses.php">Courses</a></li>
                <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- PREMIUM PAGE HEADER -->
<section class="page-header">
    <div class="hero-grid"></div>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
    <div class="container position-relative z-3">
        <h1 class="display-4 fw-bold" data-aos="fade-up">Explore Our <span class="gradient-text">Premium Courses</span></h1>
        <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">Industry‑aligned curriculum designed to launch your tech career</p>
    </div>
</section>

<!-- Filter Buttons -->
<div class="container mt-5">
    <div class="text-center mb-4" data-aos="fade-down">
        <div class="btn-group flex-wrap justify-content-center" role="group">
            <button class="btn filter-btn active" data-filter="all">All Courses</button>
            <button class="btn filter-btn" data-filter="web">Web Development</button>
            <button class="btn filter-btn" data-filter="programming">Programming</button>
            <button class="btn filter-btn" data-filter="mobile">Mobile Apps</button>
            <button class="btn filter-btn" data-filter="data">Data Science</button>
            <button class="btn filter-btn" data-filter="security">Cybersecurity</button>
        </div>
    </div>
</div>

<!-- Courses Grid + View More -->
<section class="container my-4">
    <div class="row g-4" id="coursesContainer"></div>
    <div class="text-center mt-4" id="coursesLoadMoreBtnContainer" style="display: none;">
        <button id="loadMoreCoursesBtn" class="btn btn-outline-premium px-5 magnetic-btn">View All Courses <i class="fas fa-arrow-right ms-2"></i></button>
    </div>
    <div class="text-center mt-3">
        <button id="refreshCoursesBtn" class="btn btn-outline-premium rounded-pill px-4 magnetic-btn"><i class="fas fa-sync-alt me-2"></i> Refresh Courses</button>
    </div>
</section>

<!-- Statistics Section (Premium Dark) -->
<section class="stats-premium py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-4"><div class="stat-number counter-num" id="coursesCountDisplay"><?php echo $totalCourses; ?></div><p class="text-white-50 fw-semibold">Expert-Led Courses</p></div>
            <div class="col-md-3 col-6 mb-4"><div class="stat-number counter-num" data-target="5000">0</div><p class="text-white-50 fw-semibold">Students Enrolled</p></div>
            <div class="col-md-3 col-6 mb-4"><div class="stat-number counter-num" data-target="95">0</div><p class="text-white-50 fw-semibold">Placement Rate (%)</p></div>
            <div class="col-md-3 col-6 mb-4"><div class="stat-number counter-num" data-target="30">0</div><p class="text-white-50 fw-semibold">Industry Experts</p></div>
        </div>
    </div>
</section>

<!-- Why Choose Us (Premium Cards) -->
<section class="container my-5">
    <h2 class="section-title text-center" data-aos="fade-up">Why Learn With Jyotisoft?</h2>
    <div class="row g-4 mt-2">
        <div class="col-md-4" data-aos="zoom-in">
            <div class="why-card text-center">
                <i class="fas fa-chalkboard-user fa-3x" style="color: var(--primary); margin-bottom: 1rem;"></i>
                <h5>Expert Trainers</h5>
                <p class="text-muted">Learn from industry professionals with real-world experience.</p>
            </div>
        </div>
        <div class="col-md-4" data-aos="zoom-in" data-aos-delay="100">
            <div class="why-card text-center">
                <i class="fas fa-laptop-code fa-3x" style="color: var(--secondary); margin-bottom: 1rem;"></i>
                <h5>Live Projects</h5>
                <p class="text-muted">Work on real projects to build a strong portfolio.</p>
            </div>
        </div>
        <div class="col-md-4" data-aos="zoom-in" data-aos-delay="200">
            <div class="why-card text-center">
                <i class="fas fa-briefcase fa-3x" style="color: var(--accent-orange); margin-bottom: 1rem;"></i>
                <h5>Placement Support</h5>
                <p class="text-muted">100% assistance in interviews and resume preparation.</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="container my-5">
    <div class="cta-gradient p-5 text-white text-center rounded-4" data-aos="flip-up">
        <h3 class="fw-bold">Ready to enroll in your dream course?</h3>
        <p class="lead mb-4">Get started today and take the first step toward a successful IT career.</p>
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
        const interactiveElements = document.querySelectorAll('a, button, .card-premium, .why-card');
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

    // ---------- COURSES DATA EMBEDDED FROM PHP ----------
    // The PHP variable $courses is converted to JSON and assigned to allCourses
    const allCourses = <?php echo json_encode($courses, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    let displayedLimit = 6;
    let currentFilter = 'all';

    // Update the courses count display (already set via PHP, but ensure data-target for counter animation)
    document.getElementById('coursesCountDisplay').setAttribute('data-target', allCourses.length);

    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }

    function renderCoursesByFilterAndLimit() {
        let filtered = allCourses;
        if (currentFilter !== 'all') {
            filtered = allCourses.filter(c => c.category === currentFilter);
        }
        const toShow = filtered.slice(0, displayedLimit);
        const container = document.getElementById('coursesContainer');
        if (toShow.length === 0) {
            container.innerHTML = '<div class="col-12 text-center py-5"><p class="lead text-muted">No courses found in this category. Check back soon!</p></div>';
        } else {
            container.innerHTML = toShow.map(c => `
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="50">
                    <div class="card-premium h-100">
                        <div class="overflow-hidden">
                            <img src="${c.image_path || 'https://via.placeholder.com/300x200'}" class="course-img" alt="${escapeHtml(c.title)}" onerror="this.src='https://via.placeholder.com/300x200?text=Course'">
                        </div>
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold">${escapeHtml(c.title)}</h5>
                            <p class="card-text small text-muted">${escapeHtml(c.description)}</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span><i class="far fa-clock"></i> ${escapeHtml(c.duration)}</span>
                                <span class="fw-bold text-gradient-primary" style="background: linear-gradient(135deg, var(--primary), var(--accent-blue)); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">${escapeHtml(c.fee)}</span>
                            </div>
                            <a href="register.php" class="btn btn-primary w-100 enroll-btn">Enroll Now →</a>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Re-attach magnetic effect for new buttons
        document.querySelectorAll('.magnetic-btn').forEach(btn => {
            btn.addEventListener('mousemove', (e) => {
                const rect = btn.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                gsap.to(btn, { duration: 0.25, x: x * 0.2, y: y * 0.2, ease: "power2.out" });
            });
            btn.addEventListener('mouseleave', () => gsap.to(btn, { duration: 0.25, x: 0, y: 0, ease: "power2.out" }));
        });
        // Enroll buttons alert (optional, you can change to redirect)
        document.querySelectorAll('.enroll-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                alert("✨ Welcome! Our admission team will contact you shortly with scholarship details.");
            });
        });
        AOS.refresh();

        const btnContainer = document.getElementById('coursesLoadMoreBtnContainer');
        if (filtered.length > displayedLimit) {
            btnContainer.style.display = 'block';
        } else {
            btnContainer.style.display = 'none';
        }
    }

    function applyFilterAndRender() {
        displayedLimit = 6;
        renderCoursesByFilterAndLimit();
    }

    // Load More button
    document.getElementById('loadMoreCoursesBtn')?.addEventListener('click', () => {
        displayedLimit = allCourses.length;
        renderCoursesByFilterAndLimit();
        document.getElementById('coursesLoadMoreBtnContainer').style.display = 'none';
    });

    // Refresh button (simply reload the page to get latest from database)
    document.getElementById('refreshCoursesBtn')?.addEventListener('click', () => {
        window.location.reload();
    });

    // Filter buttons
    const filterBtns = document.querySelectorAll('.filter-btn');
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.getAttribute('data-filter');
            applyFilterAndRender();
        });
    });

    // Counter animation (for stats numbers)
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

    function showToast(msg) {
        const toast = document.createElement('div');
        toast.className = 'toast-notify';
        toast.innerText = msg;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2000);
    }

    window.addEventListener('DOMContentLoaded', () => {
        renderCoursesByFilterAndLimit();
        initCounters();
        gsap.fromTo('.page-header h1', { opacity: 0, y: 30 }, { opacity: 1, y: 0, duration: 0.8, delay: 0.2 });
        gsap.fromTo('.page-header .lead', { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.8, delay: 0.4 });
    });
</script>
</body>
</html>