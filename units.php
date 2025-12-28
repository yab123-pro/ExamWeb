<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: login.php');
    exit;
}

$subject_id = $_GET['subject_id'] ?? null;
if (!$subject_id || !is_numeric($subject_id)) {
    header('Location: student_dashboard.php');
    exit;
}

$student_id = $_SESSION['user_id'];

// Get subject name
$stmt = $conn->prepare("SELECT name, grade FROM subjects WHERE id = ?");
$stmt->bind_param("i", $subject_id);
$stmt->execute();
$subject = $stmt->get_result()->fetch_assoc();
if (!$subject) {
    header('Location: student_dashboard.php');
    exit;
}

// Get all units + student's best score per unit
$units_query = $conn->query("
    SELECT u.id, u.name,
           sc.correct as best_correct,
           sc.missed as best_missed,
           sc.taken_at
    FROM units u
    LEFT JOIN student_scores sc ON sc.unit_id = u.id AND sc.student_id = $student_id
    AND sc.id = (
        SELECT MAX(id) FROM student_scores sc2 
        WHERE sc2.unit_id = u.id AND sc2.student_id = $student_id
    )
    WHERE u.subject_id = $subject_id
    ORDER BY u.id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($subject['name']) ?> - Grade <?= $subject['grade'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #4361ee; --success: #2ecc71; --warning: #f39c12; --danger: #e74c3c; }
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-family: 'Poppins', sans-serif; min-height: 100vh; }
        .glass-card { background: rgba(255,255,255,0.95); backdrop-filter: blur(15px); border-radius: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.15); transition: 0.4s; }
        .glass-card:hover { transform: translateY(-10px); }
        .unit-card { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.1); transition: all 0.4s; height: 100%; position: relative; }
        .unit-card:hover { transform: translateY(-15px) scale(1.04); box-shadow: 0 30px 60px rgba(67,97,238,0.35); }
        .unit-header { height: 100px; background: linear-gradient(135deg, var(--primary), #5e7bff); position: relative; }
        .unit-number { position: absolute; top: 15px; left: 20px; background: rgba(0,0,0,0.3); color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; }
        .unit-icon { position: absolute; bottom: -35px; right: 20px; width: 80px; height: 80px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: var(--primary); box-shadow: 0 10px 20px rgba(0,0,0,0.2); }
        .unit-body { padding: 50px 25px 25px; text-align: center; }
        .unit-name { font-size: 1.35rem; font-weight: 700; margin-bottom: 15px; color: #2c3e50; }
        .progress { height: 16px; border-radius: 8px; background: #e9ecef; margin: 15px 0; }
        .progress-bar { border-radius: 8px; background: linear-gradient(90deg, #2ecc71, #3498db); }
        .score-badge { position: absolute; top: 15px; right: 15px; font-weight: bold; padding: 8px 16px; border-radius: 50px; font-size: 0.95rem; box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
        .status-text { font-size: 0.9rem; padding: 6px 14px; border-radius: 50px; background: #f8f9fa; color: #666; margin-top: 10px; display: inline-block; }
        .back-btn { position: fixed; top: 20px; left: 20px; z-index: 1000; width: 50px; height: 50px; border-radius: 50%; background: rgba(255,255,255,0.9); display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(0,0,0,0.2); transition: 0.3s; }
        .back-btn:hover { background: white; transform: scale(1.1); }
        .lock-overlay { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); border-radius: 20px; display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem; z-index: 10; }
        @media (max-width: 768px) { .unit-icon { width: 60px; height: 60px; font-size: 2rem; bottom: -30px; } }
    </style>
</head>
<body>

<a href="subjects.php?grade=<?= $subject['grade'] ?>" class="back-btn">
    <i class="fas fa-arrow-left text-primary fa-lg"></i>
</a>

<div class="container py-5 mt-4">
    <div class="text-center mb-5">
        <h1 class="text-white display-4 fw-bold mb-3">
            <?= htmlspecialchars($subject['name']) ?>
        </h1>
        <p class="text-white opacity-90 lead">Grade <?= $subject['grade'] ?> • Choose a unit to start quiz</p>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 justify-content-center">
        <?php $unit_num = 1; while ($unit = $units_query->fetch_assoc()): 
            $total = $unit['best_correct'] + $unit['best_missed'];
            $percent = $total > 0 ? round(($unit['best_correct'] / $total) * 100) : 0;
            $badge_color = $percent >= 80 ? 'bg-success' : ($percent >= 60 ? 'bg-warning' : ($percent > 0 ? 'bg-danger' : 'bg-secondary'));
            $badge_text = $percent > 0 ? "$percent%" : "Not Started";
            $status = $percent >= 80 ? "Mastered" : ($percent >= 60 ? "Good" : ($percent > 0 ? "Try Again" : "Locked"));
            $icon = $unit_num % 2 == 0 ? 'fa-lightbulb' : 'fa-puzzle-piece';
        ?>
        <div class="col">
            <a href="quiz.php?unit_id=<?= $unit['id'] ?>" class="text-decoration-none position-relative">
                <div class="unit-card glass-card">
                    <?php if ($percent == 0): ?>
                        <div class="lock-overlay">
                            <i class="fas fa-lock"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="unit-header">
                        <div class="unit-number"><?= $unit_num++ ?></div>
                        <div class="unit-icon">
                            <i class="fas <?= $icon ?>"></i>
                        </div>
                        <div class="score-badge <?= $badge_color ?> text-white">
                            <?= $badge_text ?>
                        </div>
                    </div>
                    
                    <div class="unit-body">
                        <h5 class="unit-name"><?= htmlspecialchars($unit['name']) ?></h5>
                        
                        <?php if ($percent > 0): ?>
                        <div class="progress">
                            <div class="progress-bar" style="width: <?= $percent ?>%"></div>
                        </div>
                        <small class="text-success fw-bold">
                            <?= $unit['best_correct'] ?> / <?= $total ?> correct
                        </small>
                        <div class="status-text">
                            <i class="fas fa-trophy"></i> <?= $status ?>
                        </div>
                        <?php else: ?>
                        <p class="text-muted mb-2">Click to begin</p>
                        <div class="status-text">
                            <i class="fas fa-lock"></i> Ready to unlock
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
        </div>
        <?php endwhile; ?>
    </div>

    <div class="text-center mt-5">
        <p class="text-white opacity-80">
            Developed with <i class="fas fa-heart text-danger"></i> by 
            <strong class="text-warning">Yabsira Ashenafi</strong>
        </p>
    </div>
</div>

</body>
</html>