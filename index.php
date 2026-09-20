 <?php
// Start session (optional, for future use)
session_start();

// Include database connection
require_once 'config/db.php';
 

// Handle newsletter subscription AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    $email = trim($_POST['email'] ?? '');
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Valid email required']);
        exit;
    }
    try {
        $stmt = $pdo->prepare("INSERT INTO subscribers (email) VALUES (?)");
        $stmt->execute([$email]);
        echo json_encode(['success' => true, 'message' => 'Subscribed successfully!']);
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            echo json_encode(['success' => false, 'message' => 'Email already subscribed']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Subscription failed']);
        }
    }
    exit;
}

// Fetch courses from database
$stmt = $pdo->query("SELECT * FROM courses ORDER BY id");
$allCourses = $stmt->fetchAll();

// Fetch trainers from database
$stmt = $pdo->query("SELECT * FROM trainers ORDER BY id");
$allTrainers = $stmt->fetchAll();

// Fetch testimonials from database
$stmt = $pdo->query("SELECT * FROM testimonials ORDER BY id");
$allTestimonials = $stmt->fetchAll();

// For "load more" we will only display first 6 courses and first 3 trainers initially
$displayCourses = array_slice($allCourses, 0, 6);
$displayTrainers = array_slice($allTrainers, 0, 3);
$hasMoreCourses = count($allCourses) > 6;
$hasMoreTrainers = count($allTrainers) > 3;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Jyotisoft | Software Training Institute</title>
    <!-- Bootstrap 5 + Font Awesome + Premium Font (Plus Jakarta Sans) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        /* ========== PREMIUM COLOR SCIENCE ========== */
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
 

        /* ===== ENHANCED PREMIUM HERO ===== */
        .hero {
            background: radial-gradient(circle at 20% 30%, rgba(0, 200, 83, 0.12), transparent 45%),
                        radial-gradient(circle at 85% 70%, rgba(156, 39, 176, 0.12), transparent 50%),
                        linear-gradient(135deg, #ffffff, #f8fafc, #eef2ff);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
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
            transition: transform 0.2s ease-out;
            will-change: transform;
        }

        .blob-1 {
            width: 350px;
            height: 350px;
            background: var(--primary);
            top: 10%;
            left: -120px;
            opacity: 0.25;
        }

        .blob-2 {
            width: 400px;
            height: 400px;
            background: var(--secondary);
            right: -120px;
            top: 20%;
            opacity: 0.22;
        }

        .blob-3 {
            width: 280px;
            height: 280px;
            background: var(--accent-blue);
            bottom: -50px;
            left: 40%;
            opacity: 0.18;
        }

        .hero-icons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
            position: relative;
            z-index: 5;
        }

        .icon-bubble {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border-radius: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
            animation: floatIcon 5s ease-in-out infinite;
        }

        .icon-bubble:nth-child(1) { animation-delay: 0s; background: linear-gradient(135deg, rgba(0,200,83,0.1), white); color: var(--primary); }
        .icon-bubble:nth-child(2) { animation-delay: 0.6s; background: linear-gradient(135deg, rgba(0,184,255,0.1), white); color: var(--accent-blue); }
        .icon-bubble:nth-child(3) { animation-delay: 1.2s; background: linear-gradient(135deg, rgba(156,39,176,0.1), white); color: var(--secondary); }
        .icon-bubble:nth-child(4) { animation-delay: 1.8s; background: linear-gradient(135deg, rgba(255,109,0,0.1), white); color: var(--accent-orange); }
        .icon-bubble:nth-child(5) { animation-delay: 2.4s; background: linear-gradient(135deg, rgba(0,200,83,0.1), white); color: var(--primary); }

        @keyframes floatIcon {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(3deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }

        .icon-bubble:hover {
            transform: scale(1.1) translateY(-5px);
            box-shadow: 0 25px 40px rgba(0, 0, 0, 0.15);
            border-color: var(--primary);
        }

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

        .hero-title {
            font-weight: 800;
            font-size: 3.8rem;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: var(--text-light);
            max-width: 700px;
            margin: 1.5rem auto;
            font-weight: 500;
        }

        .btn-primary {
            background: linear-gradient(135deg, #00C853, #00AEEF);
            border: none;
            border-radius: 14px;
            box-shadow: 0 15px 30px rgba(0, 200, 83, 0.25);
            padding: 0.9rem 2rem;
            font-weight: 700;
            transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            color: white;
            position: relative;
            overflow: hidden;
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
            border-color: transparent;
        }

        .custom-cursor {
            width: 28px;
            height: 28px;
            background: radial-gradient(circle, rgba(0,200,83,0.5), rgba(156,39,176,0.3));
            border-radius: 50%;
            position: fixed;
            pointer-events: none;
            z-index: 9999;
            mix-blend-mode: screen;
            transition: transform 0.1s, width 0.2s, height 0.2s;
            transform: translate(-50%, -50%);
            backdrop-filter: blur(4px);
        }

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
        .mentor-card {
            background: white;
            border-radius: 28px;
            transition: all 0.3s;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        .mentor-card:hover {
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
        .testimonial-glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s;
        }
        .testimonial-glass:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.95);
        }
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
        .trusted-logos {
            font-size: 1.3rem;
            font-weight: 600;
            letter-spacing: 2px;
            color: var(--text-light);
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2rem;
        }
        .trusted-logos span {
            opacity: 0.7;
            transition: opacity 0.2s;
        }
        .trusted-logos span:hover {
            opacity: 1;
            color: var(--primary);
        }
        .roadmap-step {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.03);
            border-left: 4px solid var(--primary);
            transition: all 0.2s;
        }
        .partner-badge {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 40px;
            padding: 0.7rem 1.5rem;
            font-weight: 600;
            color: var(--dark);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
         
        @media (max-width: 768px) {
            .hero-title { font-size: 2.2rem; }
            .trusted-logos { font-size: 0.9rem; gap: 1rem; }
            .icon-bubble { width: 55px; height: 55px; font-size: 1.6rem; border-radius: 18px; }
            .hero-icons { gap: 1rem; }
            .custom-cursor { display: none; }
        }
        .text-gradient-primary {
            background: linear-gradient(135deg, var(--primary), var(--accent-blue));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .bg-soft-light { background-color: var(--surface-2); }
        section { scroll-margin-top: 90px; }

        /* For load more: hidden courses */
        .extra-course, .extra-trainer {
            display: none;
        }
        .show-extra .extra-course, .show-extra .extra-trainer {
            display: block;
        }
    </style>
</head>
<body>

<div class="scroll-progress" id="scrollProgress"></div>
<div class="custom-cursor" id="customCursor"></div>

 <?php include 'includes/navbar.php'; ?>

<!-- PREMIUM HERO SECTION -->
<section class="hero">
    <div class="hero-grid"></div>
    <div class="blob blob-1" id="blob1"></div>
    <div class="blob blob-2" id="blob2"></div>
    <div class="blob blob-3" id="blob3"></div>
    <div class="container position-relative z-3 text-center">
        <div class="hero-icons" data-aos="fade-down" data-aos-duration="800">
            <div class="icon-bubble"><i class="fas fa-code"></i></div>
            <div class="icon-bubble"><i class="fas fa-robot"></i></div>
            <div class="icon-bubble"><i class="fas fa-shield-alt"></i></div>
            <div class="icon-bubble"><i class="fas fa-chart-line"></i></div>
            <div class="icon-bubble"><i class="fas fa-mobile-alt"></i></div>
        </div>
        <h1 class="hero-title fw-bold" data-aos="fade-up" data-aos-duration="1000">
            Transform Your Future With <br>
            <span class="gradient-text">Industry-Ready Technology Skills</span>
        </h1>
        <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="150" data-aos-duration="800">
            Master Programming, AI, Cyber Security, Data Science and Full-Stack Development through real projects,
            expert mentors and career acceleration programs.
        </p>
        <div class="mt-4" data-aos="fade-up" data-aos-delay="300">
            <button class="btn btn-primary btn-lg me-3 magnetic-btn" onclick="window.location.href='register.php'">Start Learning <i class="fas fa-arrow-right ms-2"></i></button>
            <button class="btn btn-outline-premium btn-lg magnetic-btn" onclick="window.location.href='courses.php'">Explore Programs</button>
        </div>
    </div>
</section>

<!-- ABOUT JYOTISOFT -->
<section id="about" class="container my-5 py-5">
    <div class="row g-5 align-items-center">
        <div class="col-lg-6" data-aos="fade-right">
            <h2 class="section-title text-start">Where <span class="text-gradient-primary">Innovation</span> Meets Education</h2>
            <p class="lead">Jyotisoft is Nepal's premier software training institute, bridging the gap between academic theory and real-world industry demands.</p>
            <p>Since 2015, we've empowered 5000+ tech professionals through project-based learning, mentorship from industry veterans, and guaranteed internship pathways. Our curriculum is co-designed with tech giants to ensure you're job-ready from day one.</p>
            <div class="row mt-4">
                <div class="col-md-6 mb-3"><div class="d-flex"><i class="fas fa-certificate fa-2x text-success me-3"></i><div><h6>Global Certifications</h6><small>Recognized by top MNCs</small></div></div></div>
                <div class="col-md-6 mb-3"><div class="d-flex"><i class="fas fa-user-graduate fa-2x text-success me-3"></i><div><h6>Placement Assurance</h6><small>Internship + job support</small></div></div></div>
                <div class="col-md-6 mb-3"><div class="d-flex"><i class="fas fa-laptop-code fa-2x text-success me-3"></i><div><h6>Live Projects</h6><small>Real startup experience</small></div></div></div>
                <div class="col-md-6 mb-3"><div class="d-flex"><i class="fas fa-chalkboard-user fa-2x text-success me-3"></i><div><h6>Elite Trainers</h6><small>10+ years in FAANG</small></div></div></div>
            </div>
        </div>
        <div class="col-lg-6" data-aos="fade-left">
            <div class="row g-3">
                <div class="col-6"><div class="stat-card-glow p-4 text-center bg-white shadow-sm rounded-4 border"><div class="display-6 fw-bold text-success">10+</div><p>Years Excellence</p></div></div>
                <div class="col-6"><div class="stat-card-glow p-4 text-center bg-white shadow-sm rounded-4 border"><div class="display-6 fw-bold text-success">5000+</div><p>Students Trained</p></div></div>
                <div class="col-6"><div class="stat-card-glow p-4 text-center bg-white shadow-sm rounded-4 border"><div class="display-6 fw-bold text-success">30+</div><p>Expert Trainers</p></div></div>
                <div class="col-6"><div class="stat-card-glow p-4 text-center bg-white shadow-sm rounded-4 border"><div class="display-6 fw-bold text-success">95%</div><p>Placement Rate</p></div></div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURED COURSES SECTION -->
<section id="courses" class="py-5 bg-soft-light">
    <div class="container">
        <h2 class="section-title text-center" data-aos="fade-up">🔥 Flagship Programs</h2>
        <div class="row g-4" id="coursesGrid">
            <?php foreach ($displayCourses as $course): ?>
                <div class="col-md-6 col-lg-4 course-item">
                    <div class="card-premium h-100">
                        <div class="overflow-hidden">
                            <img src="<?php echo htmlspecialchars($course['image_path'] ?: 'https://via.placeholder.com/300x180'); ?>" class="card-img-top course-img" style="height: 200px; object-fit: cover;" alt="<?php echo htmlspecialchars($course['title']); ?>">
                        </div>
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold"><?php echo htmlspecialchars($course['title']); ?></h5>
                            <p class="card-text small text-muted"><?php echo htmlspecialchars($course['description']); ?></p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span><i class="far fa-clock"></i> <?php echo htmlspecialchars($course['duration']); ?></span>
                                <span class="fw-bold text-gradient-primary"><?php echo htmlspecialchars($course['fee']); ?></span>
                            </div>
                            <button class="btn btn-primary w-100 enrollBtn" onclick="window.location.href='register.php'">Enroll Now →</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if ($hasMoreCourses): ?>
                <?php $extraCourses = array_slice($allCourses, 6); ?>
                <?php foreach ($extraCourses as $course): ?>
                    <div class="col-md-6 col-lg-4 extra-course">
                        <div class="card-premium h-100">
                            <div class="overflow-hidden">
                                <img src="<?php echo htmlspecialchars($course['image_path'] ?: 'https://via.placeholder.com/300x180'); ?>" class="card-img-top course-img" style="height: 200px; object-fit: cover;" alt="<?php echo htmlspecialchars($course['title']); ?>">
                            </div>
                            <div class="card-body p-4">
                                <h5 class="card-title fw-bold"><?php echo htmlspecialchars($course['title']); ?></h5>
                                <p class="card-text small text-muted"><?php echo htmlspecialchars($course['description']); ?></p>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span><i class="far fa-clock"></i> <?php echo htmlspecialchars($course['duration']); ?></span>
                                    <span class="fw-bold text-gradient-primary"><?php echo htmlspecialchars($course['fee']); ?></span>
                                </div>
                                <button class="btn btn-primary w-100 enrollBtn" onclick="window.location.href='register.php'">Enroll Now →</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php if ($hasMoreCourses): ?>
            <div class="text-center mt-4">
                <button id="loadMoreCoursesBtn" class="btn btn-outline-premium px-5">View All Courses <i class="fas fa-arrow-right ms-2"></i></button>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- LEARNING ROADMAP -->
<section class="container my-5 py-4">
    <h2 class="section-title text-center" data-aos="fade-up">Your Success <span class="gradient-text">Roadmap</span></h2>
    <div class="row g-4 mt-3">
        <div class="col-md-3" data-aos="fade-up"><div class="roadmap-step text-center"><i class="fas fa-book-open fa-3x text-primary mb-3"></i><h5>1. Foundation</h5><p>Core programming & logic building</p></div></div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="100"><div class="roadmap-step text-center"><i class="fas fa-code-branch fa-3x text-primary mb-3"></i><h5>2. Specialization</h5><p>Choose track: Web, AI, Security</p></div></div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="200"><div class="roadmap-step text-center"><i class="fas fa-users fa-3x text-primary mb-3"></i><h5>3. Live Projects</h5><p>Real-world client work</p></div></div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="300"><div class="roadmap-step text-center"><i class="fas fa-briefcase fa-3x text-primary mb-3"></i><h5>4. Placement</h5><p>Interview prep & job referral</p></div></div>
    </div>
</section>

<!-- MENTORS SECTION -->
<section id="mentors" class="py-5 bg-soft-light">
    <div class="container">
        <h2 class="section-title text-center" data-aos="fade-up">🌟 Meet Your <span class="gradient-text">Elite Mentors</span></h2>
        <div class="row g-4" id="trainerCardsRow">
            <?php foreach ($displayTrainers as $trainer): ?>
                <div class="col-md-4 trainer-item">
                    <div class="mentor-card text-center p-4 h-100">
                        <img src="<?php echo htmlspecialchars($trainer['image_path'] ?: 'https://via.placeholder.com/130'); ?>" class="trainer-avatar mb-3" alt="<?php echo htmlspecialchars($trainer['name']); ?>">
                        <h5 class="fw-bold mt-2"><?php echo htmlspecialchars($trainer['name']); ?></h5>
                        <p class="text-primary fw-semibold"><?php echo htmlspecialchars($trainer['role']); ?></p>
                        <small class="text-muted"><?php echo htmlspecialchars($trainer['description']); ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if ($hasMoreTrainers): ?>
                <?php $extraTrainers = array_slice($allTrainers, 3); ?>
                <?php foreach ($extraTrainers as $trainer): ?>
                    <div class="col-md-4 extra-trainer">
                        <div class="mentor-card text-center p-4 h-100">
                            <img src="<?php echo htmlspecialchars($trainer['image_path'] ?: 'https://via.placeholder.com/130'); ?>" class="trainer-avatar mb-3" alt="<?php echo htmlspecialchars($trainer['name']); ?>">
                            <h5 class="fw-bold mt-2"><?php echo htmlspecialchars($trainer['name']); ?></h5>
                            <p class="text-primary fw-semibold"><?php echo htmlspecialchars($trainer['role']); ?></p>
                            <small class="text-muted"><?php echo htmlspecialchars($trainer['description']); ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php if ($hasMoreTrainers): ?>
            <div class="text-center mt-4">
                <button id="loadMoreTrainersBtn" class="btn btn-outline-premium px-5">Discover All Mentors <i class="fas fa-chevron-down ms-2"></i></button>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- STUDENT SUCCESS STORIES -->
<section class="container my-5 py-4">
    <h2 class="section-title text-center" data-aos="fade-up">❤️ Student Success Stories</h2>
    <div class="row justify-content-center g-4 mt-2" id="testimonialDynamic">
        <?php foreach ($allTestimonials as $testimonial): ?>
            <div class="col-md-4">
                <div class="testimonial-glass p-4 h-100">
                    <i class="fas fa-quote-left fa-2x text-primary opacity-50 mb-3"></i>
                    <p class="fst-italic">“<?php echo htmlspecialchars($testimonial['text']); ?>”</p>
                    <div class="d-flex"><?php echo str_repeat('<i class="fas fa-star text-warning"></i>', $testimonial['rating']); ?></div>
                    <h6 class="mt-3 mb-0">- <?php echo htmlspecialchars($testimonial['name']); ?></h6>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="text-center mt-4">
        <button id="refreshTestimonialsBtn" class="btn btn-sm btn-outline-premium rounded-pill"><i class="fas fa-sync-alt me-1"></i> Refresh Stories</button>
    </div>
</section>

<!-- INTERNSHIP & PLACEMENT PARTNERS -->
<section class="py-5 bg-soft-light">
    <div class="container text-center">
        <h2 class="section-title text-center" data-aos="fade-up">🤝 Internship & Placement Partners</h2>
        <div class="d-flex flex-wrap justify-content-center gap-3 mt-4" data-aos="zoom-in">
            <span class="partner-badge">Deerwalk Inc.</span>
            <span class="partner-badge">Leapfrog Technology</span>
            <span class="partner-badge">F1Soft Intl.</span>
            <span class="partner-badge">Cotiviti Nepal</span>
            <span class="partner-badge">CloudFactory</span>
            <span class="partner-badge">LogPoint</span>
        </div>
        <p class="mt-4 text-muted">150+ hiring partners actively recruit our graduates.</p>
    </div>
</section>

<!-- NEWSLETTER SECTION -->
<section class="py-5">
    <div class="container text-center">
        <h3 data-aos="flip-up" class="fw-bold">Ready to accelerate your career?</h3>
        <p class="mb-4">Get exclusive course updates & scholarship alerts.</p>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="input-group mb-3 shadow-sm rounded-pill overflow-hidden border">
                    <input type="email" id="newsletterEmail" class="form-control border-0 py-3" placeholder="Your email address">
                    <button class="btn btn-primary px-4" id="subscribeAjaxBtn">Subscribe <i class="fas fa-paper-plane"></i></button>
                </div>
                <div id="newsletterMsg" class="small fw-semibold"></div>
            </div>
        </div>
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

    // Custom cursor + parallax blobs
    const cursor = document.getElementById('customCursor');
    if (cursor) {
        document.addEventListener('mousemove', (e) => {
            gsap.to(cursor, { duration: 0.15, x: e.clientX, y: e.clientY, ease: "power2.out" });
        });
        const interactiveElements = document.querySelectorAll('a, button, .card-premium, .mentor-card, .testimonial-glass, .icon-bubble');
        interactiveElements.forEach(el => {
            el.addEventListener('mouseenter', () => gsap.to(cursor, { scale: 1.6, opacity: 0.7, duration: 0.2 }));
            el.addEventListener('mouseleave', () => gsap.to(cursor, { scale: 1, opacity: 1, duration: 0.2 }));
        });
    }

    const blob1 = document.getElementById('blob1');
    const blob2 = document.getElementById('blob2');
    const blob3 = document.getElementById('blob3');
    document.addEventListener('mousemove', (e) => {
        const mouseX = e.clientX / window.innerWidth;
        const mouseY = e.clientY / window.innerHeight;
        if (blob1) gsap.to(blob1, { duration: 1, x: (mouseX - 0.5) * 40, y: (mouseY - 0.5) * 30, ease: "power2.out" });
        if (blob2) gsap.to(blob2, { duration: 1.2, x: (mouseX - 0.5) * -35, y: (mouseY - 0.5) * 25, ease: "power2.out" });
        if (blob3) gsap.to(blob3, { duration: 0.8, x: (mouseX - 0.5) * 25, y: (mouseY - 0.5) * -20, ease: "power2.out" });
    });

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

    // Load more functionality
    const loadCoursesBtn = document.getElementById('loadMoreCoursesBtn');
    if (loadCoursesBtn) {
        loadCoursesBtn.addEventListener('click', () => {
            document.querySelectorAll('.extra-course').forEach(el => el.style.display = 'block');
            loadCoursesBtn.style.display = 'none';
        });
    }
    const loadTrainersBtn = document.getElementById('loadMoreTrainersBtn');
    if (loadTrainersBtn) {
        loadTrainersBtn.addEventListener('click', () => {
            document.querySelectorAll('.extra-trainer').forEach(el => el.style.display = 'block');
            loadTrainersBtn.style.display = 'none';
        });
    }

    // Refresh testimonials (reload page or fetch new? For simplicity, reload page)
    document.getElementById('refreshTestimonialsBtn')?.addEventListener('click', () => {
        window.location.reload();
    });

    // Newsletter subscription (AJAX to same file)
    document.getElementById('subscribeAjaxBtn')?.addEventListener('click', async () => {
        const email = document.getElementById('newsletterEmail').value;
        const msgDiv = document.getElementById('newsletterMsg');
        if (!email || !email.includes('@')) {
            msgDiv.innerHTML = '<span class="text-danger">Valid email required</span>';
            return;
        }
        msgDiv.innerHTML = '<span class="text-secondary"><i class="fas fa-spinner fa-pulse"></i> Subscribing...</span>';
        try {
            const formData = new FormData();
            formData.append('email', email);
            const response = await fetch('index.php', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            const result = await response.json();
            if (result.success) {
                msgDiv.innerHTML = `<span class="text-success">${result.message}</span>`;
                document.getElementById('newsletterEmail').value = '';
            } else {
                msgDiv.innerHTML = `<span class="text-danger">${result.message}</span>`;
            }
        } catch (error) {
            msgDiv.innerHTML = '<span class="text-danger">Network error</span>';
        }
        setTimeout(() => msgDiv.innerHTML = '', 3000);
    });
</script>
</body>
</html>