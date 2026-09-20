<?php
// Start session (optional)
session_start();

// Include database connection
require_once 'config/db.php';

// Handle AJAX form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Please fill all required fields.']);
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO contacts (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $subject, $message]);
        echo json_encode(['success' => true, 'message' => 'Thank you! Our team will respond within 24 hours.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error. Please try again later.']);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Contact | Jyotisoft Software Training Institute</title>
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

        /* ===== PREMIUM PAGE HEADER ===== */
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

        /* Premium Contact Cards */
        .contact-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px;
            padding: 2rem 1.5rem;
            transition: all 0.4s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
            height: 100%;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .contact-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }
        .contact-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(15, 23, 42, 0.12);
        }
        .contact-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, rgba(0,200,83,0.1), rgba(156,39,176,0.1));
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            transition: transform 0.3s;
        }
        .contact-card:hover .contact-icon {
            transform: scale(1.08);
        }
        .contact-icon i {
            font-size: 2rem;
            color: var(--secondary);
        }

        /* Premium Form (Glassmorphism) */
        .premium-form {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 28px;
            padding: 2rem;
            box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.1);
        }
        .form-control {
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            padding: 12px 16px;
            transition: all 0.2s;
            font-family: inherit;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 200, 83, 0.1);
            outline: none;
        }

        /* Map Container */
        .map-container {
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s;
            height: 100%;
        }
        .map-container:hover {
            transform: scale(1.01);
        }
        .map-container iframe {
            width: 100%;
            height: 100%;
            min-height: 400px;
            border: 0;
        }

        /* Premium Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #00C853, #00AEEF);
            border: none;
            border-radius: 14px;
            box-shadow: 0 15px 30px rgba(0, 200, 83, 0.25);
            padding: 0.8rem 1.8rem;
            font-weight: 700;
            transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 25px 40px rgba(0, 200, 83, 0.35);
            background: linear-gradient(135deg, #00b347, #0095d4);
        }

        /* Statistics Premium */
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

        /* FAQ Glass */
        .faq-item {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            margin-bottom: 1rem;
            padding: 1.2rem 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.2s;
        }
        .faq-question {
            font-weight: 700;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text);
        }
        .faq-question:hover {
            color: var(--secondary);
        }
        .faq-answer {
            display: none;
            padding-top: 1rem;
            color: var(--text-light);
            line-height: 1.6;
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
            .stat-number { font-size: 2rem; }
            .contact-icon { width: 55px; height: 55px; }
            .contact-icon i { font-size: 1.5rem; }
            .map-container iframe { min-height: 300px; }
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
                <li class="nav-item"><a class="nav-link" href="courses.php">Courses</a></li>
                <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
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
        <h1 class="display-4 fw-bold" data-aos="fade-up">Get In <span class="gradient-text">Touch</span></h1>
        <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">We're here to answer your questions and help you choose the right course</p>
    </div>
</section>

<!-- CONTACT INFO CARDS -->
<div class="container mt-5">
    <div class="row g-4">
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="0">
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h5>Visit Us</h5>
                <p class="text-muted">Pokhara, Nepal<br>Srijana chowk, Near sky Bridge</p>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <h5>Call Us</h5>
                <p class="text-muted">+977 9812345678<br>+977 9845678901</p>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h5>Email Us</h5>
                <p class="text-muted">info@jyotisoft.com<br>support@jyotisoft.com</p>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h5>Working Hours</h5>
                <p class="text-muted">Sun-Fri: 9am – 6pm<br>Sat: 10am – 4pm</p>
            </div>
        </div>
    </div>
</div>

<!-- CONTACT FORM + MAP -->
<section class="container my-5">
    <div class="row g-5">
        <div class="col-lg-6" data-aos="fade-right">
            <div class="premium-form">
                <h3 class="mb-4 fw-bold">Send Us a Message</h3>
                <p class="text-muted">Fill out the form below and we'll get back to you within 24 hours.</p>
                <form id="contactForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-semibold">Full Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-semibold">Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label fw-semibold">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="subject" class="form-label fw-semibold">Subject *</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label fw-semibold">Message *</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 magnetic-btn" id="submitContact">Send Message <i class="fas fa-paper-plane ms-2"></i></button>
                    <div id="contactFeedback" class="mt-3 text-center small fw-semibold"></div>
                </form>
                <p class="mt-3 small text-muted"><i class="fas fa-lock me-1"></i> We respect your privacy. Your information will not be shared.</p>
            </div>
        </div>
        <div class="col-lg-6" data-aos="fade-left">
            <div class="map-container">
                <iframe 
                    src="https://maps.google.com/maps?q=28.2114,83.9812&hl=en&z=16&output=embed"
                    allowfullscreen=""
                    loading="lazy"
                    title="Jyotisoft Location Map">
                </iframe>
            </div>
        </div>
    </div>
</section>

<!-- STATISTICS PREMIUM SECTION -->
<section class="stats-premium py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-4"><div class="stat-number counter-num" data-target="5000">0</div><p class="text-white-50 fw-semibold">Happy Students</p></div>
            <div class="col-md-3 col-6 mb-4"><div class="stat-number counter-num" data-target="200">0</div><p class="text-white-50 fw-semibold">Hiring Partners</p></div>
            <div class="col-md-3 col-6 mb-4"><div class="stat-number counter-num" data-target="30">0</div><p class="text-white-50 fw-semibold">Expert Trainers</p></div>
            <div class="col-md-3 col-6 mb-4"><div class="stat-number counter-num" data-target="95">0</div><p class="text-white-50 fw-semibold">Placement Rate (%)</p></div>
        </div>
    </div>
</section>

<!-- FAQ SECTION (Glass Cards) -->
<section class="py-5 bg-soft-light">
    <div class="container">
        <h2 class="section-title text-center" data-aos="fade-up">Frequently Asked Questions</h2>
        <div class="row justify-content-center mt-4">
            <div class="col-lg-8">
                <div class="faq-item" data-aos="fade-up">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>How do I enroll in a course?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">You can enroll by filling out the registration form on our <a href="register.php" style="color: var(--primary);">Register page</a>. Once submitted, our team will contact you for confirmation and payment.</div>
                </div>
                <div class="faq-item" data-aos="fade-up" data-aos-delay="50">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>Do you offer online classes?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">Yes, we provide live online classes with recorded sessions, plus offline classroom training at our Pokhara center.</div>
                </div>
                <div class="faq-item" data-aos="fade-up" data-aos-delay="100">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>What is the fee structure and payment options?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">Course fees vary by program. We offer flexible installment plans, and you can pay via bank transfer, eSewa, or cash at our office.</div>
                </div>
                <div class="faq-item" data-aos="fade-up" data-aos-delay="150">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>Is there a placement guarantee?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">We provide 100% internship assistance and strong placement support with our 200+ hiring partners.</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CALL TO ACTION -->
<section class="container my-5">
    <div class="cta-gradient p-5 text-white text-center rounded-4" data-aos="flip-up">
        <h2 class="fw-bold">Prefer to talk to us directly?</h2>
        <p class="lead mb-4">Call us at +977 9812345678 or visit our campus for a free consultation.</p>
        <a href="register.php" class="btn btn-light btn-lg px-5 magnetic-btn" style="border-radius: 14px; font-weight: 700; color: var(--secondary);">Register Online <i class="fas fa-arrow-right ms-2"></i></a>
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
        const interactiveElements = document.querySelectorAll('a, button, .contact-card, .faq-item, .premium-form');
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

    // Contact form submission via AJAX to same PHP file
    const contactForm = document.getElementById('contactForm');
    const feedbackDiv = document.getElementById('contactFeedback');

    contactForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const submitBtn = document.getElementById('submitContact');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-pulse"></i> Sending...';
        feedbackDiv.innerHTML = '';

        const formData = new FormData(contactForm);
        try {
            const response = await fetch('contact.php', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            const result = await response.json();
            if (result.success) {
                feedbackDiv.innerHTML = `<span class="text-success"><i class="fas fa-check-circle"></i> ${result.message}</span>`;
                contactForm.reset();
            } else {
                feedbackDiv.innerHTML = `<span class="text-danger">${result.message}</span>`;
            }
        } catch (error) {
            feedbackDiv.innerHTML = '<span class="text-danger">Network error. Please try again.</span>';
        }
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        setTimeout(() => { feedbackDiv.innerHTML = ''; }, 5000);
    });

    // FAQ toggle
    window.toggleFAQ = function(element) {
        let answer = element.nextElementSibling;
        let icon = element.querySelector('i');
        if (answer.style.display === 'block') {
            answer.style.display = 'none';
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        } else {
            answer.style.display = 'block';
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        }
    };

    // Counter animation
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
        gsap.fromTo('.page-header h1', { opacity: 0, y: 30 }, { opacity: 1, y: 0, duration: 0.8, delay: 0.2 });
        gsap.fromTo('.page-header .lead', { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.8, delay: 0.4 });
    });
</script>
</body>
</html>