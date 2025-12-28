<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: login.php');
    exit;
}

$student_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Fetch student stats
$stmt = $conn->prepare("
    SELECT COUNT(*) as total_quizzes,
           SUM(correct) as total_correct,
           SUM(missed) as total_missed
    FROM student_scores WHERE student_id = ?
");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stats = $stmt->get_result()->fetch_assoc();
$total_quizzes = $stats['total_quizzes'] ?? 0;
$total_correct = $stats['total_correct'] ?? 0;
$total_missed = $stats['total_missed'] ?? 0;
$overall_accuracy = $total_quizzes > 0 ? round(($total_correct / ($total_correct + $total_missed)) * 100, 1) : 0;

// Fetch recent activity
$recent = $conn->query("
    SELECT un.name as unit, s.name as subject, sc.correct, sc.missed, sc.taken_at
    FROM student_scores sc
    JOIN units un ON sc.unit_id = un.id
    JOIN subjects s ON un.subject_id = s.id
    WHERE sc.student_id = $student_id
    ORDER BY sc.taken_at DESC
    LIMIT 5
");

// Fetch available grades with subjects
$grades = $conn->query("SELECT DISTINCT grade FROM subjects ORDER BY grade");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($username) ?> - Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --success: #2ecc71;
            --warning: #f39c12;
            --danger: #e74c3c;
            --light: #f8f9fa;
            --dark: #2c3e50;
        }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            color: #333;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.3);
            transition: all 0.3s ease;
        }
        .glass-card:hover { transform: translateY(-10px); }
        .profile-avatar {
            width: 120px; height: 120px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            font-size: 3rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
        }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-icon {
            width: 60px; height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 1.8rem;
            color: white;
        }
        .grade-btn {
            width: 160px; height: 160px;
            border-radius: 50%;
            font-size: 2.2rem;
            font-weight: 700;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            box-shadow: 0 12px 25px rgba(0,0,0,0.2);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        .grade-btn::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.2), transparent);
            opacity: 0;
            transition: 0.4s;
        }
        .grade-btn:hover::before { opacity: 1; }
        .grade-btn:hover {
            transform: scale(1.12) translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        .grade-btn span { font-size: 1rem; margin-top: 5px; }
        .activity-item {
            background: #f8f9fa;
            border-left: 4px solid var(--primary);
            padding: 12px 15px;
            border-radius: 0 10px 10px 0;
            margin-bottom: 8px;
            transition: 0.3s;
        }
        .activity-item:hover {
            background: #e3f2fd;
            transform: translateX(5px);
        }
        .progress {
            height: 12px;
            border-radius: 6px;
            background: #e0e0e0;
        }
        .progress-bar {
            border-radius: 6px;
            transition: width 1.5s ease;
        }
        .badge-level {
            font-size: 1.1rem;
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 600;
        }
        .logout-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #e74c3c;
            color: white;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(231, 76, 60, 0.4);
            transition: 0.3s;
            z-index: 1000;
        }
        .logout-btn:hover {
            background: #c0392b;
            transform: scale(1.1);
        }
        @media (max-width: 768px) {
            .grade-btn { width: 120px; height: 120px; font-size: 1.8rem; }
            .profile-avatar { width: 90px; height: 90px; font-size: 2.2rem; }
        }
    </style>
</head>
<body>

<!-- Floating Logout -->
<a href="logout.php" class="logout-btn">
    <i class="fas fa-power-off"></i>
</a>

<div class="container py-4 py-md-5">

    <!-- Welcome Section -->
    <div class="text-center mb-5">
        <div class="profile-avatar">
            <?= strtoupper(substr($username, 0, 2)) ?>
        </div>
        <h1 class="text-white fw-bold mb-2">Welcome back, <span class="text-warning"><?= htmlspecialchars($username) ?>!</span></h1>
        <p class="text-white opacity-90">Ready to master your exams?</p>
    </div>

    <!-- Stats Row -->
    <div class="row g-3 g-md-4 mb-5">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="fas fa-trophy"></i>
                </div>
                <h3 class="mb-0 text-primary"><?= $total_quizzes ?></h3>
                <small class="text-muted">Quizzes Taken</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 class="mb-0 text-success"><?= $total_correct ?></h3>
                <small class="text-muted">Correct</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-warning">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="mb-0 text-warning"><?= $overall_accuracy ?>%</h3>
                <small class="text-muted">Accuracy</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #9b59b6;">
                    <i class="fas fa-star"></i>
                </div>
                <h3 class="mb-0" style="color: #9b59b6;">
                    <?= $overall_accuracy >= 90 ? 'A+' : ($overall_accuracy >= 80 ? 'A' : ($overall_accuracy >= 70 ? 'B' : 'C')) ?>
                </h3>
                <small class="text-muted">Grade Level</small>
            </div>
        </div>
    </div>

    <!-- Overall Progress -->
    <div class="glass-card p-4 mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"><i class="fas fa-brain text-primary"></i> Overall Progress</h5>
            <span class="badge bg-primary fs-6"><?= $overall_accuracy ?>%</span>
        </div>
        <div class="progress">
            <div class="progress-bar bg-gradient" style="width: <?= $overall_accuracy ?>%; background: linear-gradient(90deg, #2ecc71, #3498db);"></div>
        </div>
    </div>

    <!-- Grade Selection -->
    <div class="text-center mb-5">
        <h3 class="text-white mb-4"><i class="fas fa-graduation-cap"></i> Choose Your Grade</h3>
        <div class="d-flex justify-content-center flex-wrap gap-3">
            <?php while ($grade = $grades->fetch_assoc()): ?>
                <?php
                $g = $grade['grade'];
                $color = $g == 9 ? '#e74c3c' : ($g == 10 ? '#f39c12' : ($g == 11 ? '#3498db' : '#2ecc71'));
                ?>
                <a href="subjects.php?grade=<?= $g ?>" class="text-decoration-none">
                    <div class="grade-btn" style="background: <?= $color ?>;">
                        <?= $g ?>
                        <span>Grade</span>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Recent Activity -->
    <?php if ($recent->num_rows > 0): ?>
    <div class="glass-card p-4">
        <h5 class="mb-3"><i class="fas fa-history text-primary"></i> Recent Activity</h5>
        <div class="activity-list">
            <?php while ($act = $recent->fetch_assoc()): ?>
                <?php
                $score = $act['correct'] + $act['missed'];
                $percent = $score > 0 ? round(($act['correct'] / $score) * 100) : 0;
                $badge = $percent >= 80 ? 'bg-success' : ($percent >= 60 ? 'bg-warning' : 'bg-danger');
                ?>
                <div class="activity-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?= htmlspecialchars($act['subject']) ?> → <?= htmlspecialchars($act['unit']) ?></strong>
                            <br>
                            <small class="text-muted"><?= date('M d, Y - h:i A', strtotime($act['taken_at'])) ?></small>
                        </div>
                        <div class="text-end">
                            <span class="badge <?= $badge ?>"><?= $percent ?>%</span>
                            <br>
                            <small><?= $act['correct'] ?>/<?= $score ?></small>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <div class="text-center mt-3">
            <a href="student_scores.php" class="btn btn-outline-primary btn-sm">View All Results →</a>
        </div>
    </div>
    <?php else: ?>
    <div class="text-center py-5">
        <div class="glass-card p-5 d-inline-block">
            <i class="fas fa-book-open fa-3x text-primary mb-3"></i>
            <h4>No activity yet!</h4>
            <p class="text-muted">Start by selecting your grade above</p>
        </div>
    </div>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>