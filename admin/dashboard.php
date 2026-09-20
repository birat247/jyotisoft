 <?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header('Location: index.php');
    exit;
}
require_once '../config/db.php';

// Helper functions
function showToast($msg) {
    echo "<script>showToast('$msg');</script>";
}

// Handle POST actions (Add/Edit/Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Course actions
    if (isset($_POST['add_course'])) {
        $title = $_POST['title'];
        $duration = $_POST['duration'];
        $fee = $_POST['fee'];
        $category = $_POST['category'];
        $desc = $_POST['description'];
        $image_path = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/courses/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = time() . '_' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename);
            $image_path = 'uploads/courses/' . $filename;
        }
        $stmt = $pdo->prepare("INSERT INTO courses (title, duration, fee, category, description, image_path) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$title, $duration, $fee, $category, $desc, $image_path]);
        header('Location: dashboard.php?section=courses&msg=added');
        exit;
    }
    if (isset($_POST['edit_course'])) {
        $id = $_POST['course_id'];
        $title = $_POST['title'];
        $duration = $_POST['duration'];
        $fee = $_POST['fee'];
        $category = $_POST['category'];
        $desc = $_POST['description'];
        $image_path = $_POST['existing_image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/courses/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = time() . '_' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename);
            $image_path = 'uploads/courses/' . $filename;
        }
        $stmt = $pdo->prepare("UPDATE courses SET title=?, duration=?, fee=?, category=?, description=?, image_path=? WHERE id=?");
        $stmt->execute([$title, $duration, $fee, $category, $desc, $image_path, $id]);
        header('Location: dashboard.php?section=courses&msg=updated');
        exit;
    }
    if (isset($_POST['delete_course'])) {
        $id = $_POST['delete_course'];
        $stmt = $pdo->prepare("DELETE FROM courses WHERE id=?");
        $stmt->execute([$id]);
        header('Location: dashboard.php?section=courses&msg=deleted');
        exit;
    }

    // Trainer actions
    if (isset($_POST['add_trainer'])) {
        $name = $_POST['name'];
        $role = $_POST['role'];
        $desc = $_POST['description'];
        $image_path = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/trainers/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = time() . '_' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename);
            $image_path = 'uploads/trainers/' . $filename;
        }
        $stmt = $pdo->prepare("INSERT INTO trainers (name, role, description, image_path) VALUES (?,?,?,?)");
        $stmt->execute([$name, $role, $desc, $image_path]);
        header('Location: dashboard.php?section=trainers&msg=added');
        exit;
    }
    if (isset($_POST['edit_trainer'])) {
        $id = $_POST['trainer_id'];
        $name = $_POST['name'];
        $role = $_POST['role'];
        $desc = $_POST['description'];
        $image_path = $_POST['existing_image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/trainers/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = time() . '_' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename);
            $image_path = 'uploads/trainers/' . $filename;
        }
        $stmt = $pdo->prepare("UPDATE trainers SET name=?, role=?, description=?, image_path=? WHERE id=?");
        $stmt->execute([$name, $role, $desc, $image_path, $id]);
        header('Location: dashboard.php?section=trainers&msg=updated');
        exit;
    }
    if (isset($_POST['delete_trainer'])) {
        $id = $_POST['delete_trainer'];
        $stmt = $pdo->prepare("DELETE FROM trainers WHERE id=?");
        $stmt->execute([$id]);
        header('Location: dashboard.php?section=trainers&msg=deleted');
        exit;
    }

    // Testimonial actions
    if (isset($_POST['add_testimonial'])) {
        $name = $_POST['name'];
        $text = $_POST['text'];
        $rating = $_POST['rating'];
        $stmt = $pdo->prepare("INSERT INTO testimonials (name, text, rating) VALUES (?,?,?)");
        $stmt->execute([$name, $text, $rating]);
        header('Location: dashboard.php?section=testimonials&msg=added');
        exit;
    }
    if (isset($_POST['edit_testimonial'])) {
        $id = $_POST['testimonial_id'];
        $name = $_POST['name'];
        $text = $_POST['text'];
        $rating = $_POST['rating'];
        $stmt = $pdo->prepare("UPDATE testimonials SET name=?, text=?, rating=? WHERE id=?");
        $stmt->execute([$name, $text, $rating, $id]);
        header('Location: dashboard.php?section=testimonials&msg=updated');
        exit;
    }
    if (isset($_POST['delete_testimonial'])) {
        $id = $_POST['delete_testimonial'];
        $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id=?");
        $stmt->execute([$id]);
        header('Location: dashboard.php?section=testimonials&msg=deleted');
        exit;
    }

    // Delete from other tables
    if (isset($_POST['delete_contact'])) {
        $id = $_POST['delete_contact'];
        $stmt = $pdo->prepare("DELETE FROM contacts WHERE id=?");
        $stmt->execute([$id]);
        header('Location: dashboard.php?section=contacts&msg=deleted');
        exit;
    }
    if (isset($_POST['delete_subscriber'])) {
        $id = $_POST['delete_subscriber'];
        $stmt = $pdo->prepare("DELETE FROM subscribers WHERE id=?");
        $stmt->execute([$id]);
        header('Location: dashboard.php?section=subscribers&msg=deleted');
        exit;
    }
    if (isset($_POST['delete_registration'])) {
        $id = $_POST['delete_registration'];
        $stmt = $pdo->prepare("DELETE FROM registrations WHERE id=?");
        $stmt->execute([$id]);
        header('Location: dashboard.php?section=registrations&msg=deleted');
        exit;
    }
}

$section = $_GET['section'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Jyotisoft</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        :root {
            --primary: #00C853;
            --secondary: #9C27B0;
            --accent-orange: #FF6D00;
            --surface-2: #F8FAFC;
            --dark: #0B1120;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--surface-2); overflow-x: hidden; }
        .admin-wrapper { display: flex; min-height: 100vh; }
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            transition: transform 0.3s ease;
            z-index: 1000;
        }
        .brand { padding: 1.5rem 1.2rem; border-bottom: 1px solid rgba(255,255,255,0.2); display: flex; align-items: center; gap: 12px; }
        .logo-container { width: 48px; height: 48px; background: rgba(255,255,255,0.96); border-radius: 14px; padding: 6px; display: flex; align-items: center; justify-content: center; }
        .logo-container img { width: 100%; height: 100%; object-fit: contain; }
        .brand-text { font-size: 1.45rem; font-weight: 800; text-shadow: 0 2px 6px rgba(0,0,0,0.2); }
        .sidebar-nav { padding: 1.5rem 0; list-style: none; }
        .nav-item-admin { margin: 0.3rem 0; }
        .nav-link-admin { display: flex; align-items: center; gap: 14px; padding: 0.75rem 1.5rem; color: rgba(255,255,255,0.88); text-decoration: none; transition: all 0.2s; border-left: 3px solid transparent; font-weight: 600; }
        .nav-link-admin i { width: 24px; text-align: center; font-size: 1.2rem; }
        .nav-link-admin:hover, .nav-link-admin.active { background: rgba(255,255,255,0.15); color: white; border-left-color: var(--accent-orange); }
        .main-content { flex: 1; margin-left: 280px; padding: 1.8rem 2rem; transition: margin-left 0.3s; }
        .top-bar { background: rgba(255,255,255,0.85); backdrop-filter: blur(20px); border-radius: 24px; padding: 0.8rem 1.5rem; margin-bottom: 2rem; box-shadow: 0 10px 40px rgba(15,23,42,0.06); border: 1px solid rgba(255,255,255,0.3); display: flex; justify-content: space-between; align-items: center; }
        .stat-card { background: rgba(255,255,255,0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.3); border-radius: 24px; padding: 1.2rem; transition: all 0.3s; box-shadow: 0 15px 35px rgba(0,0,0,0.05); }
        .stat-card:hover { transform: translateY(-5px); border-color: var(--primary); }
        .stat-card h3 { font-weight: 800; background: linear-gradient(135deg, var(--primary), var(--secondary)); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; font-size: 2rem; }
        .glass-card-admin { background: rgba(255,255,255,0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.3); border-radius: 28px; padding: 1.5rem; margin-bottom: 1.8rem; box-shadow: 0 15px 35px rgba(0,0,0,0.05); }
        .btn-primary { background: linear-gradient(135deg, #00C853, #00AEEF); border: none; border-radius: 14px; padding: 0.6rem 1.5rem; font-weight: 700; color: white; transition: all 0.3s; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,200,83,0.3); }
        .btn-outline-premium { border-radius: 14px; border: 2px solid var(--secondary); background: transparent; color: var(--secondary); font-weight: 600; transition: all 0.3s; }
        .btn-outline-premium:hover { background: var(--secondary); color: white; transform: translateY(-2px); }
        .table-custom th { background: var(--surface-2); font-weight: 700; color: var(--secondary); border-bottom: 2px solid var(--primary); }
        .action-icons i { font-size: 1.2rem; margin: 0 5px; cursor: pointer; transition: 0.2s; color: var(--secondary); }
        .action-icons i:hover { transform: scale(1.1); color: var(--accent-orange); }
        .modal-content { border-radius: 28px; border: none; background: rgba(255,255,255,0.98); backdrop-filter: blur(20px); }
        .modal-header { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; border-radius: 28px 28px 0 0; border: none; }
        .modal-header .btn-close { filter: brightness(0) invert(1); }
        .image-preview { max-width: 100px; max-height: 80px; margin-top: 8px; border-radius: 12px; border: 1px solid #ddd; object-fit: cover; }
        .toast-notify { position: fixed; bottom: 25px; right: 25px; background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; padding: 12px 24px; border-radius: 60px; font-weight: 600; z-index: 1100; box-shadow: 0 8px 18px rgba(0,0,0,0.2); }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); width: 260px; }
            .sidebar.mobile-open { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 1rem; }
            .menu-toggle { display: block; font-size: 1.5rem; cursor: pointer; color: var(--secondary); }
        }
        .menu-toggle { display: none; }
        canvas { max-height: 220px; width: 100% !important; }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <div class="sidebar" id="adminSidebar">
        <div class="brand">
            <div class="logo-container"><img src="../images/logo.png" alt="logo"></div>
            <span class="brand-text">Jyotisoft</span>
        </div>
        <ul class="sidebar-nav">
            <li class="nav-item-admin"><a class="nav-link-admin <?php echo $section=='dashboard'?'active':''; ?>" href="?section=dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li class="nav-item-admin"><a class="nav-link-admin <?php echo $section=='courses'?'active':''; ?>" href="?section=courses"><i class="fas fa-laptop-code"></i> Courses</a></li>
            <li class="nav-item-admin"><a class="nav-link-admin <?php echo $section=='trainers'?'active':''; ?>" href="?section=trainers"><i class="fas fa-chalkboard-user"></i> Trainers</a></li>
            <li class="nav-item-admin"><a class="nav-link-admin <?php echo $section=='testimonials'?'active':''; ?>" href="?section=testimonials"><i class="fas fa-star"></i> Testimonials</a></li>
            <li class="nav-item-admin"><a class="nav-link-admin <?php echo $section=='contacts'?'active':''; ?>" href="?section=contacts"><i class="fas fa-envelope"></i> Contact Msgs</a></li>
            <li class="nav-item-admin"><a class="nav-link-admin <?php echo $section=='subscribers'?'active':''; ?>" href="?section=subscribers"><i class="fas fa-users"></i> Subscribers</a></li>
            <li class="nav-item-admin"><a class="nav-link-admin <?php echo $section=='registrations'?'active':''; ?>" href="?section=registrations"><i class="fas fa-user-graduate"></i> Registrations</a></li>
            <li class="nav-item-admin"><a class="nav-link-admin" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <div class="menu-toggle" id="mobileMenuToggle"><i class="fas fa-bars"></i></div>
            <h5 class="m-0 fw-semibold" style="background: linear-gradient(135deg, var(--primary), var(--secondary)); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">Content Management</h5>
            <span class="badge" style="background: var(--primary);">Admin</span>
        </div>

        <?php if($section == 'dashboard'): 
            $courses = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn();
            $trainers = $pdo->query("SELECT COUNT(*) FROM trainers")->fetchColumn();
            $testimonials = $pdo->query("SELECT COUNT(*) FROM testimonials")->fetchColumn();
            $contacts = $pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
            $registrations = $pdo->query("SELECT COUNT(*) FROM registrations")->fetchColumn();
            $subscribers = $pdo->query("SELECT COUNT(*) FROM subscribers")->fetchColumn();
            $totalInquiries = $contacts + $registrations;
            // Chart data: courses by category
            $stmt = $pdo->query("SELECT category, COUNT(*) as cnt FROM courses GROUP BY category");
            $catData = [];
            while($row = $stmt->fetch()) $catData[$row['category']] = $row['cnt'];
            $categories = ['web','programming','mobile','data','security'];
            $catCounts = array_map(function($cat) use ($catData) { return $catData[$cat] ?? 0; }, $categories);
            // Trainer names for bar chart
            $trainerNames = $pdo->query("SELECT name FROM trainers ORDER BY id")->fetchAll(PDO::FETCH_COLUMN);
            ?>
            <div class="row g-4" id="statsCards">
                <div class="col-md-3 col-6"><div class="stat-card"><h3><?=$courses?></h3><p>Courses</p></div></div>
                <div class="col-md-3 col-6"><div class="stat-card"><h3><?=$trainers?></h3><p>Trainers</p></div></div>
                <div class="col-md-3 col-6"><div class="stat-card"><h3><?=$testimonials?></h3><p>Testimonials</p></div></div>
                <div class="col-md-3 col-6"><div class="stat-card"><h3><?=$totalInquiries?></h3><p>Inquiries</p></div></div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6"><div class="glass-card-admin"><canvas id="coursesChart"></canvas></div></div>
                <div class="col-md-6"><div class="glass-card-admin"><canvas id="trainersChart"></canvas></div></div>
            </div>
            <div class="glass-card-admin"><h6><i class="fas fa-bell me-2" style="color: var(--secondary);"></i> Recent Activities</h6><ul id="recentActivityList" class="list-unstyled">
                <?php
                $recentContacts = $pdo->query("SELECT name, submitted_at FROM contacts ORDER BY submitted_at DESC LIMIT 3")->fetchAll();
                $recentRegs = $pdo->query("SELECT name, submitted_at FROM registrations ORDER BY submitted_at DESC LIMIT 3")->fetchAll();
                $activities = array_merge($recentContacts, $recentRegs);
                usort($activities, function($a,$b){ return strtotime($b['submitted_at']) - strtotime($a['submitted_at']); });
                $activities = array_slice($activities, 0, 5);
                foreach($activities as $act):
                    echo "<li><i class='fas fa-envelope me-2' style='color:#9C27B0'></i> New inquiry from {$act['name']}</li>";
                endforeach;
                if(empty($activities)) echo "<li>No recent activity</li>";
                ?>
            </ul></div>
            <script>
                new Chart(document.getElementById('coursesChart'), { type: 'doughnut', data: { labels: ['Web','Programming','Mobile','Data','Security'], datasets: [{ data: <?=json_encode($catCounts)?>, backgroundColor: ['#00C853','#9C27B0','#FF6D00','#00B8FF','#D05CE3'] }] } });
                new Chart(document.getElementById('trainersChart'), { type: 'bar', data: { labels: <?=json_encode($trainerNames)?>, datasets: [{ label: 'Trainers', data: <?=json_encode(array_fill(0, count($trainerNames), 1))?>, backgroundColor: '#9C27B0' }] } });
            </script>
        <?php elseif($section == 'courses'):
            $courses = $pdo->query("SELECT * FROM courses ORDER BY id")->fetchAll();
            ?>
            <div class="d-flex justify-content-between align-items-center mb-3"><h4><i class="fas fa-laptop-code me-2" style="color: var(--secondary);"></i>Courses</h4><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#courseModal" onclick="resetCourseForm()"><i class="fas fa-plus"></i> Add Course</button></div>
            <div class="glass-card-admin"><div class="table-responsive"><table class="table table-custom table-hover"><thead><tr><th>ID</th><th>Title</th><th>Duration</th><th>Fee</th><th>Category</th><th>Image</th><th>Actions</th></tr></thead><tbody>
            <?php foreach($courses as $c): ?>
                <tr><td><?=$c['id']?></td><td><?=htmlspecialchars($c['title'])?></td><td><?=$c['duration']?></td><td><?=$c['fee']?></td><td><?=$c['category']?></td><td><img src="../<?=$c['image_path']?>" width="50" height="40" style="object-fit:cover; border-radius:8px"></td>
                <td class="action-icons"><i class="fas fa-edit text-primary" onclick="editCourse(<?=$c['id']?>,'<?=addslashes($c['title'])?>','<?=addslashes($c['duration'])?>','<?=addslashes($c['fee'])?>','<?=addslashes($c['category'])?>','<?=addslashes($c['description'])?>','<?=$c['image_path']?>')"></i>
                <form method="POST" style="display:inline" onsubmit="return confirm('Delete course?')"><input type="hidden" name="delete_course" value="<?=$c['id']?>"><button type="submit" class="btn btn-link p-0 m-0" style="color:var(--secondary);"><i class="fas fa-trash text-danger"></i></button></form></td></tr>
            <?php endforeach; ?>
            </tbody></table></div></div>
            <!-- Course Modal -->
            <div class="modal fade" id="courseModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Course</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><form method="POST" enctype="multipart/form-data" id="courseForm"><input type="hidden" name="course_id" id="course_id"><input type="hidden" name="existing_image" id="existing_image"><div class="mb-2"><label>Title</label><input type="text" name="title" id="title" class="form-control" required></div><div class="mb-2"><label>Duration</label><input type="text" name="duration" id="duration" class="form-control" required></div><div class="mb-2"><label>Fee</label><input type="text" name="fee" id="fee" class="form-control" required></div><div class="mb-2"><label>Category</label><select name="category" id="category" class="form-select"><option value="web">Web Development</option><option value="programming">Programming</option><option value="mobile">Mobile Apps</option><option value="data">Data Science</option><option value="security">Cybersecurity</option></select></div><div class="mb-2"><label>Description</label><textarea name="description" id="description" class="form-control" rows="2"></textarea></div><div class="mb-2"><label>Image (JPG/PNG, max 5MB)</label><input type="file" name="image" id="image" class="form-control" accept="image/jpeg,image/png,image/jpg"><div id="imagePreview" class="image-preview"></div></div><div class="modal-footer"><button type="submit" name="add_course" id="addCourseSubmit" class="btn btn-primary">Add</button><button type="submit" name="edit_course" id="editCourseSubmit" class="btn btn-primary" style="display:none">Update</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button></div></form></div></div></div></div>
            <script>
                function resetCourseForm(){ document.getElementById('course_id').value=''; document.getElementById('title').value=''; document.getElementById('duration').value=''; document.getElementById('fee').value=''; document.getElementById('category').value='web'; document.getElementById('description').value=''; document.getElementById('existing_image').value=''; document.getElementById('imagePreview').innerHTML=''; document.getElementById('addCourseSubmit').style.display='inline-block'; document.getElementById('editCourseSubmit').style.display='none'; document.getElementById('image').value=''; }
                function editCourse(id,title,duration,fee,category,desc,img){ document.getElementById('course_id').value=id; document.getElementById('title').value=title; document.getElementById('duration').value=duration; document.getElementById('fee').value=fee; document.getElementById('category').value=category; document.getElementById('description').value=desc; document.getElementById('existing_image').value=img; if(img) document.getElementById('imagePreview').innerHTML='<img src="../'+img+'" class="image-preview">'; document.getElementById('addCourseSubmit').style.display='none'; document.getElementById('editCourseSubmit').style.display='inline-block'; new bootstrap.Modal(document.getElementById('courseModal')).show(); }
                document.getElementById('image')?.addEventListener('change', function(e){ if(e.target.files[0]){ var reader=new FileReader(); reader.onload=function(ev){ document.getElementById('imagePreview').innerHTML='<img src="'+ev.target.result+'" class="image-preview">'; }; reader.readAsDataURL(e.target.files[0]); } });
            </script>
        <?php elseif($section == 'trainers'):
            $trainers = $pdo->query("SELECT * FROM trainers ORDER BY id")->fetchAll();
            ?>
            <div class="d-flex justify-content-between align-items-center mb-3"><h4>Trainers</h4><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#trainerModal" onclick="resetTrainerForm()"><i class="fas fa-plus"></i> Add Trainer</button></div>
            <div class="glass-card-admin"><div class="table-responsive"><table class="table table-custom"><thead><tr><th>ID</th><th>Name</th><th>Role</th><th>Image</th><th>Actions</th></tr></thead><tbody>
            <?php foreach($trainers as $t): ?>
                <tr><td><?=$t['id']?></td><td><?=htmlspecialchars($t['name'])?></td><td><?=htmlspecialchars($t['role'])?></td><td><img src="../<?=$t['image_path']?>" width="40" height="40" style="border-radius:50%"></td>
                <td class="action-icons"><i class="fas fa-edit" onclick="editTrainer(<?=$t['id']?>,'<?=addslashes($t['name'])?>','<?=addslashes($t['role'])?>','<?=addslashes($t['description'])?>','<?=$t['image_path']?>')"></i>
                <form method="POST" style="display:inline" onsubmit="return confirm('Delete trainer?')"><input type="hidden" name="delete_trainer" value="<?=$t['id']?>"><button type="submit" class="btn btn-link p-0 m-0"><i class="fas fa-trash text-danger"></i></button></form></td></tr>
            <?php endforeach; ?>
            </tbody></table></div></div>
            <div class="modal fade" id="trainerModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Trainer</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><form method="POST" enctype="multipart/form-data"><input type="hidden" name="trainer_id" id="trainer_id"><input type="hidden" name="existing_image" id="trainer_existing_image"><div class="mb-2"><label>Name</label><input type="text" name="name" id="trainer_name" class="form-control" required></div><div class="mb-2"><label>Role</label><input type="text" name="role" id="trainer_role" class="form-control" required></div><div class="mb-2"><label>Description</label><textarea name="description" id="trainer_desc" class="form-control"></textarea></div><div class="mb-2"><label>Image</label><input type="file" name="image" id="trainer_image" class="form-control" accept="image/*"><div id="trainerImagePreview" class="image-preview"></div></div><div class="modal-footer"><button type="submit" name="add_trainer" id="addTrainerSubmit" class="btn btn-primary">Add</button><button type="submit" name="edit_trainer" id="editTrainerSubmit" class="btn btn-primary" style="display:none">Update</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button></div></form></div></div></div></div>
            <script>
                function resetTrainerForm(){ document.getElementById('trainer_id').value=''; document.getElementById('trainer_name').value=''; document.getElementById('trainer_role').value=''; document.getElementById('trainer_desc').value=''; document.getElementById('trainer_existing_image').value=''; document.getElementById('trainerImagePreview').innerHTML=''; document.getElementById('addTrainerSubmit').style.display='inline-block'; document.getElementById('editTrainerSubmit').style.display='none'; document.getElementById('trainer_image').value=''; }
                function editTrainer(id,name,role,desc,img){ document.getElementById('trainer_id').value=id; document.getElementById('trainer_name').value=name; document.getElementById('trainer_role').value=role; document.getElementById('trainer_desc').value=desc; document.getElementById('trainer_existing_image').value=img; if(img) document.getElementById('trainerImagePreview').innerHTML='<img src="../'+img+'" class="image-preview">'; document.getElementById('addTrainerSubmit').style.display='none'; document.getElementById('editTrainerSubmit').style.display='inline-block'; new bootstrap.Modal(document.getElementById('trainerModal')).show(); }
                document.getElementById('trainer_image')?.addEventListener('change', function(e){ if(e.target.files[0]){ var reader=new FileReader(); reader.onload=function(ev){ document.getElementById('trainerImagePreview').innerHTML='<img src="'+ev.target.result+'" class="image-preview">'; }; reader.readAsDataURL(e.target.files[0]); } });
            </script>
        <?php elseif($section == 'testimonials'):
            $testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY id")->fetchAll();
            ?>
            <div class="d-flex justify-content-between align-items-center mb-3"><h4>Testimonials</h4><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#testimonialModal" onclick="resetTestimonialForm()"><i class="fas fa-plus"></i> Add Testimonial</button></div>
            <div class="glass-card-admin"><div class="table-responsive"><table class="table table-custom"><thead><tr><th>ID</th><th>Name</th><th>Text</th><th>Rating</th><th>Actions</th></tr></thead><tbody>
            <?php foreach($testimonials as $t): ?>
                <tr><td><?=$t['id']?></td><td><?=htmlspecialchars($t['name'])?></td><td><?=substr($t['text'],0,50)?>...</td><td><?=str_repeat('★',$t['rating'])?></td>
                <td class="action-icons"><i class="fas fa-edit" onclick="editTestimonial(<?=$t['id']?>,'<?=addslashes($t['name'])?>','<?=addslashes($t['text'])?>',<?=$t['rating']?>)"></i>
                <form method="POST" style="display:inline" onsubmit="return confirm('Delete testimonial?')"><input type="hidden" name="delete_testimonial" value="<?=$t['id']?>"><button type="submit" class="btn btn-link p-0 m-0"><i class="fas fa-trash text-danger"></i></button></form></td></tr>
            <?php endforeach; ?>
            </tbody></table></div></div>
            <div class="modal fade" id="testimonialModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Testimonial</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><form method="POST"><input type="hidden" name="testimonial_id" id="testimonial_id"><div class="mb-2"><label>Name</label><input type="text" name="name" id="testi_name" class="form-control" required></div><div class="mb-2"><label>Text</label><textarea name="text" id="testi_text" class="form-control" rows="2" required></textarea></div><div class="mb-2"><label>Rating (1-5)</label><input type="number" name="rating" id="testi_rating" class="form-control" min="1" max="5" required></div><div class="modal-footer"><button type="submit" name="add_testimonial" id="addTestimonialSubmit" class="btn btn-primary">Add</button><button type="submit" name="edit_testimonial" id="editTestimonialSubmit" class="btn btn-primary" style="display:none">Update</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button></div></form></div></div></div></div>
            <script>
                function resetTestimonialForm(){ document.getElementById('testimonial_id').value=''; document.getElementById('testi_name').value=''; document.getElementById('testi_text').value=''; document.getElementById('testi_rating').value=5; document.getElementById('addTestimonialSubmit').style.display='inline-block'; document.getElementById('editTestimonialSubmit').style.display='none'; }
                function editTestimonial(id,name,text,rating){ document.getElementById('testimonial_id').value=id; document.getElementById('testi_name').value=name; document.getElementById('testi_text').value=text; document.getElementById('testi_rating').value=rating; document.getElementById('addTestimonialSubmit').style.display='none'; document.getElementById('editTestimonialSubmit').style.display='inline-block'; new bootstrap.Modal(document.getElementById('testimonialModal')).show(); }
            </script>
        <?php elseif($section == 'contacts'):
            $contacts = $pdo->query("SELECT * FROM contacts ORDER BY submitted_at DESC")->fetchAll();
            ?>
            <h4>Contact Messages</h4>
            <div class="glass-card-admin"><div class="table-responsive"><table class="table table-custom"><thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Date</th><th>Action</th></tr></thead><tbody>
            <?php foreach($contacts as $c): ?>
                <tr><td><?=$c['id']?></td><td><?=htmlspecialchars($c['name'])?></td><td><?=$c['email']?></td><td><?=htmlspecialchars($c['subject'])?></td><td><?=substr($c['message'],0,50)?>...</td><td><?=$c['submitted_at']?></td>
                <td><form method="POST" onsubmit="return confirm('Delete?')"><input type="hidden" name="delete_contact" value="<?=$c['id']?>"><button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form></td></tr>
            <?php endforeach; ?>
            </tbody></table></div></div>
        <?php elseif($section == 'subscribers'):
            $subs = $pdo->query("SELECT * FROM subscribers ORDER BY subscribed_at DESC")->fetchAll();
            ?>
            <h4>Newsletter Subscribers</h4>
            <div class="glass-card-admin"><div class="table-responsive"><table class="table"><thead><tr><th>Email</th><th>Date</th><th>Action</th></tr></thead><tbody>
            <?php foreach($subs as $s): ?>
                <tr><td><?=$s['email']?></td><td><?=$s['subscribed_at']?></td><td><form method="POST" onsubmit="return confirm('Unsubscribe?')"><input type="hidden" name="delete_subscriber" value="<?=$s['id']?>"><button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form></td></tr>
            <?php endforeach; ?>
            </tbody></table></div></div>
        <?php elseif($section == 'registrations'):
            $regs = $pdo->query("SELECT * FROM registrations ORDER BY submitted_at DESC")->fetchAll();
            ?>
            <h4>Student Registrations</h4>
            <div class="glass-card-admin"><div class="table-responsive"><table class="table"><thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Course</th><th>Message</th><th>Date</th><th>Action</th></tr></thead><tbody>
            <?php foreach($regs as $r): ?>
                <tr><td><?=$r['id']?></td><td><?=htmlspecialchars($r['name'])?></td><td><?=$r['email']?></td><td><?=$r['phone']?></td><td><?=$r['course']?></td><td><?=substr($r['message'],0,40)?></td><td><?=$r['submitted_at']?></td>
                <td><form method="POST" onsubmit="return confirm('Delete registration?')"><input type="hidden" name="delete_registration" value="<?=$r['id']?>"><button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form></td></tr>
            <?php endforeach; ?>
            </tbody></table></div></div>
        <?php endif; ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('mobileMenuToggle')?.addEventListener('click', () => document.getElementById('adminSidebar').classList.toggle('mobile-open'));
    <?php if(isset($_GET['msg'])) echo "setTimeout(()=>{let t=document.createElement('div');t.className='toast-notify';t.innerText='Operation successful';document.body.appendChild(t);setTimeout(()=>t.remove(),2000);},100);"; ?>
</script>
</body>
</html>