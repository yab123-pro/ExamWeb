<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Quick stats
$total_subjects = $conn->query("SELECT COUNT(*) FROM subjects")->fetch_row()[0];
$total_units = $conn->query("SELECT COUNT(*) FROM units")->fetch_row()[0];
$total_questions = $conn->query("SELECT COUNT(*) FROM questions")->fetch_row()[0];
$total_students = $conn->query("SELECT COUNT(*) FROM users WHERE role='student'")->fetch_row()[0];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - Quiz Master</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .glass-card { 
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s;
        }
        .glass-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.2); }
        .stat-number { font-size: 3rem; font-weight: 800; }
        .btn-glow { box-shadow: 0 0 20px rgba(13, 110, 253, 0.5); }
        .icon-circle { width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; }
    </style>
</head>
<body class="text-white">

<div class="container py-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold mb-3">
            <i class="fas fa-crown text-warning"></i> ADMIN PANEL
        </h1>
        <p class="lead">Welcome, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong> — Manage everything here</p>
        <hr class="bg-white opacity-50">
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="glass-card text-center p-4 text-white">
                <div class="icon-circle bg-primary mx-auto mb-3">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-number text-primary"><?= $total_subjects ?></div>
                <h5>Subjects</h5>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card text-center p-4 text-white">
                <div class="icon-circle bg-success mx-auto mb-3">
                    <i class="fas fa-th-large"></i>
                </div>
                <div class="stat-number text-success"><?= $total_units ?></div>
                <h5>Units</h5>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card text-center p-4 text-white">
                <div class="icon-circle bg-warning mx-auto mb-3">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div class="stat-number text-warning"><?= $total_questions ?></div>
                <h5>Questions</h5>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card text-center p-4 text-white">
                <div class="icon-circle bg-info mx-auto mb-3">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number text-info"><?= $total_students ?></div>
                <h5>Students</h5>
            </div>
        </div>
    </div>

    <!-- Main Action Buttons -->
    <div class="row g-4">
        <!-- CREATE NEW -->
        <div class="col-lg-6">
            <div class="glass-card p-5 text-center">
                <i class="fas fa-plus-circle fa-5x text-success mb-4"></i>
                <h3 class="mb-3">Create New Content</h3>
                <p class="lead mb-4">Add new subjects, units & upload questions</p>
                <a href="create.php" class="btn btn-success btn-lg px-5 btn-glow">
                    <i class="fas fa-magic"></i> Create New
                </a>
            </div>
        </div>

        <!-- MANAGE CURRENT -->
        <div class="col-lg-6">
            <div class="glass-card p-5 text-center">
                <i class="fas fa-edit fa-5x text-primary mb-4"></i>
                <h3 class="mb-3">Manage Existing Content</h3>
                <p class="lead mb-4">Edit correct answers, add more questions, fix anything</p>
                <a href="current.php" class="btn btn-primary btn-lg px-5 btn-glow">
                    <i class="fas fa-tools"></i> Current Content
                </a>
            </div>
        </div>

        <!-- STUDENT SCORES -->
        <div class="col-lg-4">
            <div class="glass-card p-5 text-center">
                <i class="fas fa-chart-bar fa-5x text-info mb-4"></i>
                <h3 class="mb-3">View Student Results</h3>
                <p class="lead mb-4">See who scored what in each unit</p>
                <a href="student_scores.php" class="btn btn-info btn-lg px-5 btn-glow">
                    <i class="fas fa-trophy"></i> Results
                </a>
            </div>
        </div>

        <!-- CHANGE PASSWORD -->
        <div class="col-lg-4">
            <div class="glass-card p-5 text-center">
                <i class="fas fa-key fa-5x text-warning mb-4"></i>
                <h3 class="mb-3">Change Admin Password</h3>
                <p class="lead mb-4">Keep your account secure</p>
                <a href="change_admin_password.php" class="btn btn-warning btn-lg px-5 btn-glow">
                    <i class="fas fa-lock"></i> Change Password
                </a>
            </div>
        </div>

        <!-- LOGOUT -->
        <div class="col-lg-4">
            <div class="glass-card p-5 text-center">
                <i class="fas fa-sign-out-alt fa-5x text-danger mb-4"></i>
                <h3 class="mb-3">Logout</h3>
                <p class="lead mb-4">End your admin session</p>
                <a href="logout.php" class="btn btn-danger btn-lg px-5 btn-glow">
                    <i class="fas fa-power-off"></i> Logout
                </a>
            </div>
        </div>
    </div>

    <div class="text-center mt-5">
        <p class="text-white-50">
            © 2025 Quiz Master System • Built with love for education
        </p>
    </div>
</div>

</body>
</html>