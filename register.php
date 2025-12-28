<?php
session_start();
require_once 'config.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if (strlen($username) < 3) {
        $error = "Username must be at least 3 characters";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match";
    } else {
        // Check if username exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = "Username already taken";
        } else {
            // Create new student
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $role = 'student';
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashed, $role);
            if ($stmt->execute()) {
                $message = "Registration successful! You can now login.";
            } else {
                $error = "Something went wrong. Try again.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Quiz Master</title>
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
        .register-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 25px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .btn-register {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            padding: 12px;
            font-size: 1.1rem;
            border-radius: 50px;
        }
        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
        .form-control {
            border-radius: 50px;
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .input-group-text {
            border-radius: 50px 0 0 50px;
            background: #f8f9fa;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="register-card">
                <div class="card-header">
                    <h2 class="mb-0">
                        <i class="fas fa-user-plus fa-2x"></i><br>
                        Create Account
                    </h2>
                    <p class="mb-0 opacity-90">Join thousands of students learning today!</p>
                </div>
                
                <div class="card-body p-5">
                    <?php if ($message): ?>
                        <div class="alert alert-success text-center p-3 rounded-pill">
                            <i class="fas fa-check-circle"></i> <?= $message ?>
                            <br><a href="login.php" class="btn btn-success btn-sm mt-2">Go to Login</a>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger text-center p-3 rounded-pill">
                            <i class="fas fa-exclamation-triangle"></i> <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" name="username" class="form-control form-control-lg" 
                                       placeholder="Choose a username" required minlength="3" value="<?= $_POST['username'] ?? '' ?>">
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" class="form-control form-control-lg" 
                                       placeholder="Create a strong password" required minlength="6">
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="confirm_password" class="form-control form-control-lg" 
                                       placeholder="Confirm your password" required minlength="6">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-register w-100 text-white fw-bold">
                            <i class="fas fa-user-plus"></i> Create My Account
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted mb-2">Already have an account?</p>
                        <a href="login.php" class="btn btn-outline-primary rounded-pill px-5">
                            <i class="fas fa-sign-in-alt"></i> Login Here
                        </a>
                    </div>

                    <div class="text-center mt-4 text-muted small">
                        <i class="fas fa-shield-alt text-success"></i> Your data is safe & secure
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>