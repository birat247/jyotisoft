<?php
// Optional: Database connection for dynamic courses
// Create a file 'db_connection.php' with your DB credentials
$courses = []; // will hold course data

// Uncomment the following block when your database is ready
/*
include 'db_connection.php';
$result = $conn->query("SELECT course_name, duration, fee, image FROM courses LIMIT 3");
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}
*/

// Fallback static courses (used if DB not yet connected or no rows)
if (empty($courses)) {
    $courses = [
        ['course_name' => 'Web Development', 'duration' => '3 Months', 'fee' => 'NPR 15,000', 'image' => 'assets/images/web-dev.jpg'],
        ['course_name' => 'Python Programming', 'duration' => '2 Months', 'fee' => 'NPR 12,000', 'image' => 'assets/images/python.jpg'],
        ['course_name' => 'Flutter App Dev', 'duration' => '3 Months', 'fee' => 'NPR 18,000', 'image' => 'assets/images/flutter.jpg']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jyotisoft - Software Training Institute</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons (optional but nice) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Custom CSS */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .hero {
            background: linear-gradient(135deg, #0a2b4e, #1e3a5f);
            color: white;
            padding: 80px 0;
        }
        .hero .btn-light {
            background-color: #f8f9fa;
            color: #0a2b4e;
        }
        .section-title {
            position: relative;
            margin-bottom: 40px;
            font-weight: 700;
            color: #0a2b4e;
        }
        .section-title:after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background: #0d6efd;
            margin: 15px auto 0;
            border-radius: 2px;
        }
        .card-course {
            transition: transform 0.3s;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .card-course:hover {
            transform: translateY(-5px);
        }
        .stat-box {
            background: #f0f7ff;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #0d6efd;
        }
        footer {
            background-color: #0a2b4e;
            color: #ccc;
            padding: 40px 0 20px;
        }
        footer a {
            color: #fff;
            text-decoration: none;
        }
        footer a:hover {
            text-decoration: underline;
        }
        .testimonial-carousel .carousel-item {
            padding: 30px;
            background: #f9f9f9;
            border-radius: 15px;
            text-align: center;
        }
    </style>
</head>
<body>

<!-- ==================== NAVBAR ==================== -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Jyotisoft</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                <li class="nav-item"><a class="nav-link" href="courses.php">Courses</a></li>
                <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
                <li class="nav-item"><a class="nav-link" href="testimonials.php">Testimonials</a></li>
                <li class="nav-item"><a class="nav-link" href="placements.php">Placements</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- ==================== HERO SECTION ==================== -->
<section class="hero">
    <div class="container text-center">
        <h1 class="display-4 fw-bold">Learn Programming From Experts</h1>
        <p class="lead mt-3">Join 5000+ successful students | 100% Internship Support | Industry-Ready Skills</p>
        <a href="register.php" class="btn btn-light btn-lg mt-3">Get Started <i class="fas fa-arrow-right"></i></a>
    </div>
</section>

<!-- ==================== ABOUT PREVIEW ==================== -->
<section class="container my-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h2 class="section-title text-start">Welcome to Jyotisoft</h2>
            <p>Jyotisoft is a leading software training institute dedicated to bridging the gap between academic knowledge and industry demands. We offer job‑oriented courses taught by experienced professionals.</p>
            <ul class="list-unstyled">
                <li><i class="fas fa-check-circle text-primary me-2"></i> 10+ Years of Excellence</li>
                <li><i class="fas fa-check-circle text-primary me-2"></i> 5000+ Students Trained</li>
                <li><i class="fas fa-check-circle text-primary me-2"></i> 200+ Hiring Partners</li>
            </ul>
            <a href="about.php" class="btn btn-outline-primary mt-2">Read More</a>
        </div>
        <div class="col-md-6">
            <img src="assets/images/about-img.jpg" alt="About Us" class="img-fluid rounded shadow">
        </div>
    </div>
</section>

<!-- ==================== FEATURED COURSES ==================== -->
<section class="bg-light py-5">
    <div class="container">
        <h2 class="section-title text-center">Our Popular Courses</h2>
        <div class="row mt-4">
            <?php foreach($courses as $course): ?>
            <div class="col-md-4 mb-4">
                <div class="card card-course h-100">
                    <img src="<?= htmlspecialchars($course['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($course['course_name']) ?>" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($course['course_name']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($course['duration']) ?> | <?= htmlspecialchars($course['fee']) ?></p>
                        <a href="register.php" class="btn btn-primary">Enroll Now</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-3">
            <a href="courses.php" class="btn btn-outline-primary">View All Courses <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>

<!-- ==================== WHY CHOOSE US (with icons) ==================== -->
<section class="container my-5">
    <h2 class="section-title text-center">Why Choose Jyotisoft?</h2>
    <div class="row text-center mt-4">
        <div class="col-md-4">
            <i class="fas fa-chalkboard-user fa-3x text-primary mb-3"></i>
            <h5>Expert Trainers</h5>
            <p>Learn from industry professionals with real-world experience.</p>
        </div>
        <div class="col-md-4">
            <i class="fas fa-laptop-code fa-3x text-primary mb-3"></i>
            <h5>Live Projects</h5>
            <p>Work on real projects to build a strong portfolio.</p>
        </div>
        <div class="col-md-4">
            <i class="fas fa-briefcase fa-3x text-primary mb-3"></i>
            <h5>Placement Support</h5>
            <p>100% assistance in interviews and resume preparation.</p>
        </div>
    </div>
</section>

<!-- ==================== STATISTICS / ACHIEVEMENTS ==================== -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-number text-white display-4">5000+</div>
                <p>Students Trained</p>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-number text-white display-4">200+</div>
                <p>Placements</p>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-number text-white display-4">30+</div>
                <p>Expert Trainers</p>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-number text-white display-4">15+</div>
                <p>Courses</p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== TESTIMONIALS CAROUSEL ==================== -->
<section class="container my-5">
    <h2 class="section-title text-center">What Our Students Say</h2>
    <div id="testimonialCarousel" class="carousel slide testimonial-carousel" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="p-4">
                            <i class="fas fa-quote-left fa-2x text-primary mb-3"></i>
                            <p class="fs-5">"Jyotisoft helped me land my first internship as a web developer. The trainers are very supportive and the curriculum is up-to-date."</p>
                            <h6 class="mt-3">- Ramesh Adhikari</h6>
                            <span>Web Development Student</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="p-4">
                            <i class="fas fa-quote-left fa-2x text-primary mb-3"></i>
                            <p class="fs-5">"The Python course was excellent. I got practical knowledge and the placement team helped me get a job at a top IT company."</p>
                            <h6 class="mt-3">- Sita Thapa</h6>
                            <span>Python Batch 2024</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bg-secondary rounded-circle p-2" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon bg-secondary rounded-circle p-2" aria-hidden="true"></span>
        </button>
    </div>
</section>

<!-- ==================== CALL TO ACTION ==================== -->
<section class="bg-light py-5">
    <div class="container text-center">
        <h3>Ready to Start Your Career in IT?</h3>
        <p class="mb-4">Join Jyotisoft today and get hands-on training with 100% placement assistance.</p>
        <a href="register.php" class="btn btn-primary btn-lg">Apply for Admission <i class="fas fa-arrow-right"></i></a>
    </div>
</section>

<!-- ==================== FOOTER ==================== -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5>Jyotisoft</h5>
                <p>Leading software training institute in Nepal, offering industry-relevant IT courses.</p>
            </div>
            <div class="col-md-4 mb-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="courses.php">Courses</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h5>Contact Info</h5>
                <p><i class="fas fa-map-marker-alt me-2"></i> Kathmandu, Nepal</p>
                <p><i class="fas fa-phone me-2"></i> +977 9812345678</p>
                <p><i class="fas fa-envelope me-2"></i> info@jyotisoft.com</p>
            </div>
        </div>
        <hr class="bg-secondary">
        <div class="text-center">
            <small>&copy; 2025 Jyotisoft. All Rights Reserved.</small>
        </div>
    </div>
</footer>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>
</body>
</html>