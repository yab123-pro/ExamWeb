<?php
session_start();
require_once 'config.php';

$error = '';

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin_dashboard.php');
    } else {
        header('Location: student_dashboard.php');
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header('Location: admin_dashboard.php');
            } else {
                header('Location: student_dashboard.php');
            }
            exit;
        }
    }
    $error = "Invalid username or password";
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Quiz Master</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 25px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 2.5rem;
            text-align: center;
        }
        .btn-login {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            padding: 14px;
            font-size: 1.2rem;
            border-radius: 50px;
            font-weight: bold;
        }
        .btn-login:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.5);
        }
        .form-control {
            border-radius: 50px;
            padding: 14px 22px;
            border: 2px solid #e0e0e0;
            font-size: 1.1rem;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }
        .input-group-text {
            border-radius: 50px 0 0 50px;
            background: #f8f9fa;
            font-size: 1.2rem;
        }
        .admin-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #ff4757;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="login-card position-relative">
                <!-- Secret Admin Badge (only visible to admins) -->
                <div class="admin-badge" style="display: none;" id="adminBadge">
                    ADMIN MODE
                </div>

                <div class="card-header">
                    <h1 class="mb-0">
                        Quiz Master
                    </h1>
                    <p class="mb-0 opacity-90 fs-5">Login to continue learning</p>
                </div>
                
                <div class="card-body p-5">
                    <?php if ($error): ?>
                        <div class="alert alert-danger text-center p-3 rounded-pill mb-4">
                            <i class="fas fa-exclamation-triangle"></i> <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" name="username" class="form-control form-control-lg" 
                                       placeholder="Enter your username" required autofocus 
                                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" class="form-control form-control-lg" 
                                       placeholder="Enter your password" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-login w-100 text-white shadow-lg">
                            Login Now
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted mb-2">Don't have an account?</p>
                        <a href="register.php" class="btn btn-outline-success rounded-pill px-5 fw-bold">
                            Register Here
                        </a>
                    </div>

                    <hr class="my-4">

       <div class="text-center mt-5 pt-4">
    <p class="text-black small">
        © 2025 
        Developed with by 
        <strong class="text-info">Yabsira Ashenafi</strong>
    </p>
</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Secret feature: type "admin" in username → show admin badge
document.querySelector('input[name="username"]').addEventListener('input', function() {
    const badge = document.getElementById('adminBadge');
    if (this.value.toLowerCase() === 'admin') {
        badge.style.display = 'block';
    } else {
        badge.style.display = 'none';
    }
});
</script>

</body>
</html>