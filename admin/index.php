 <?php
session_start();
require_once '../config/db.php';

// Check if any admin exists
$stmt = $pdo->query("SELECT COUNT(*) FROM admin_users");
$adminCount = $stmt->fetchColumn();

// If no admin and form submitted for registration
if ($adminCount == 0 && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    $errors = [];
    if (empty($username)) $errors[] = "Username required";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters";
    if ($password !== $confirm) $errors[] = "Passwords do not match";

    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO admin_users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hashed]);
        // Redirect to login page with success message
        header("Location: index.php?registered=1");
        exit;
    }
}

// If already logged in, go to dashboard
if (isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | Jyotisoft</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            background: linear-gradient(135deg, #00C853, #9C27B0);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
            padding: 1rem;
        }
        .card {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            border-radius: 32px;
            padding: 2rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.3);
        }
        .btn-primary {
            background: linear-gradient(135deg, #00C853, #00AEEF);
            border: none;
            border-radius: 14px;
            padding: 0.8rem;
            font-weight: 700;
            width: 100%;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .logo { width: 70px; height: 70px; background: white; border-radius: 20px; margin: 0 auto 1rem; padding: 8px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        .logo img { width:100%; height:100%; object-fit:contain; }
        h3 { background: linear-gradient(135deg, #00C853, #9C27B0); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800; }
    </style>
</head>
<body>
<div class="card">
    <div class="logo"><img src="../images/logo.png" alt="Logo"></div>
    <?php if ($adminCount == 0): ?>
        <!-- Registration Form (no admin exists) -->
        <h3 class="text-center mb-3">Create Admin Account</h3>
        <p class="text-muted text-center mb-4">No admin found. Please register to set up the admin panel.</p>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger"><?php echo implode('<br>', $errors); ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <input type="text" name="username" class="form-control form-control-lg" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control form-control-lg" placeholder="Password (min 6 chars)" required>
            </div>
            <div class="mb-3">
                <input type="password" name="confirm_password" class="form-control form-control-lg" placeholder="Confirm Password" required>
            </div>
            <button type="submit" name="register" class="btn btn-primary">Create Account</button>
        </form>
    <?php else: ?>
        <!-- Login Form -->
        <h3 class="text-center mb-3">Admin Portal</h3>
        <p class="text-muted text-center mb-4">Sign in to manage Jyotisoft</p>
        <?php if (isset($_GET['error'])) echo '<div class="alert alert-danger">Invalid credentials</div>'; ?>
        <?php if (isset($_GET['registered'])) echo '<div class="alert alert-success">Account created! Please login.</div>'; ?>
        <form method="POST" action="authenticate.php">
            <div class="mb-3">
                <input type="text" name="username" class="form-control form-control-lg" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <!-- Optional: If you want to allow adding more admins later, you can add a link -->
    <?php endif; ?>
</div>
</body>
</html>