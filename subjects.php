<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: login.php');
    exit;
}

$grade = $_GET['grade'] ?? null;
if (!$grade || !in_array($grade, [9,10,11,12])) {
    header('Location: student_dashboard.php');
    exit;
}

$student_id = $_SESSION['user_id'];

// Fetch all subjects for this grade
$stmt = $conn->prepare("SELECT id, name FROM subjects WHERE grade = ? ORDER BY name");
$stmt->bind_param("i", $grade);
$stmt->execute();
$subjects_result = $stmt->get_result();

// Build score data safely
$scores = [];
$score_query = $conn->query("
    SELECT s.id as subject_id,
           MAX(sc.correct) as best_correct,
           MAX(sc.correct + sc.missed) as total_taken,
           ROUND(MAX(sc.correct * 100.0 / (sc.correct + sc.missed)), 1) as best_percent
    FROM subjects s
    LEFT JOIN units u ON u.subject_id = s.id
    LEFT JOIN student_scores sc ON sc.unit_id = u.id AND sc.student_id = $student_id
    WHERE s.grade = $grade
    GROUP BY s.id
");

while ($row = $score_query->fetch_assoc()) {
    $scores[$row['subject_id']] = [
        'best_correct' => $row['best_correct'] ?? 0,
        'total_taken' => $row['total_taken'] ?? 0,
        'best_percent' => $row['best_percent'] ?? 0
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Grade <?= $grade ?> Subjects - Lexa Exam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #4361ee; --success: #2ecc71; --warning: #f39c12; --danger: #e74c3c; }
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-family: 'Poppins', sans-serif; min-height: 100vh; }
        .glass-card { background: rgba(255,255,255,0.95); backdrop-filter: blur(15px); border-radius: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.15); transition: 0.4s; }
        .glass-card:hover { transform: translateY(-12px); }
        .subject-card { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.1); transition: 0.4s; height: 100%; position: relative; }
        .subject-card:hover { transform: translateY(-15px) scale(1.03); box-shadow: 0 25px 50px rgba(67,97,238,0.3); }
        .subject-header { height: 120px; background: linear-gradient(135deg, var(--primary), #5e7bff); position: relative; overflow: hidden; }
        .subject-header::before { content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,0.1) 10px, rgba(255,255,255,0.1) 20px); animation: shine 15s linear infinite; }
        @keyframes shine { 0% { transform: translateX(-100%) translateY(-100%); } 100% { transform: translateX(100%) translateY(100%); } }
        .subject-icon { width: 80px; height: 80px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: var(--primary); box-shadow: 0 10px 20px rgba(0,0,0,0.2); position: absolute; bottom: -40px; left: 50%; transform: translateX(-50%); }
        .subject-body { padding: 50px 20px 25px; text-align: center; }
        .subject-name { font-size: 1.4rem; font-weight: 700; margin-bottom: 15px; color: #2c3e50; }
        .progress { height: 14px; border-radius: 7px; background: #e9ecef; margin: 15px 0; }
        .progress-bar { border-radius: 7px; background: linear-gradient(90deg, #2ecc71, #3498db); }
        .score-badge { position: absolute; top: 15px; right: 15px; font-weight: bold; padding: 8px 14px; border-radius: 50px; font-size: 0.9rem; box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
        .unit-count { background: #f8f9fa; border-radius: 50px; padding: 8px 16px; font-size: 0.9rem; color: #666; margin-top: 10px; display: inline-block; }
        .back-btn { position: fixed; top: 20px; left: 20px; z-index: 1000; width: 50px; height: 50px; border-radius: 50%; background: rgba(255,255,255,0.9); display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(0,0,0,0.2); transition: 0.3s; }
        .back-btn:hover { background: white; transform: scale(1.1); }
        @media (max-width: 768px) { .subject-icon { width: 60px; height: 60px; font-size: 2rem; bottom: -30px; } }
    </style>
</head>
<body>

<a href="student_dashboard.php" class="back-btn">
    <i class="fas fa-arrow-left text-primary fa-lg"></i>
</a>

<div class="container py-5 mt-4">
    <div class="text-center mb-5">
        <h1 class="text-white display-4 fw-bold mb-3">
            Grade <?= $grade ?>
        </h1>
        <p class="text-white opacity-90 lead">Choose your subject to start learning</p>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 justify-content-center">
        <?php while ($subject = $subjects_result->fetch_assoc()): 
            $subject_id = $subject['id'];
            $score_data = $scores[$subject_id] ?? ['best_correct' => 0, 'total_taken' => 0, 'best_percent' => 0];
            $percent = $score_data['best_percent'];
            $badge_color = $percent >= 80 ? 'bg-success' : ($percent >= 60 ? 'bg-warning' : ($percent > 0 ? 'bg-danger' : 'bg-secondary'));
            $badge_text = $percent > 0 ? "$percent%" : "Not Started";

            // Count units safely
            $unit_stmt = $conn->prepare("SELECT COUNT(*) FROM units WHERE subject_id = ?");
            $unit_stmt->bind_param("i", $subject_id);
            $unit_stmt->execute();
            $unit_count = $unit_stmt->get_result()->fetch_row()[0];

            $icons = ['fa-flask', 'fa-calculator', 'fa-book', 'fa-globe', 'fa-atom', 'fa-brain', 'fa-dna', 'fa-language'];
            $icon = $icons[array_rand($icons)];
        ?>
        <div class="col">
            <a href="units.php?subject_id=<?= $subject_id ?>" class="text-decoration-none">
                <div class="subject-card glass-card">
                    <div class="subject-header">
                        <div class="subject-icon">
                            <i class="fas <?= $icon ?>"></i>
                        </div>
                        <div class="score-badge <?= $badge_color ?> text-white">
                            <?= $badge_text ?>
                        </div>
                    </div>
                    <div class="subject-body">
                        <h5 class="subject-name"><?= htmlspecialchars($subject['name']) ?></h5>
                        
                        <?php if ($percent > 0): ?>
                        <div class="progress">
                            <div class="progress-bar" style="width: <?= $percent ?>%"></div>
                        </div>
                        <small class="text-success fw-bold">
                            <?= $score_data['best_correct'] ?> / <?= $score_data['total_taken'] ?> correct
                        </small>
                        <?php else: ?>
                        <p class="text-muted mb-2">Ready to begin!</p>
                        <?php endif; ?>
                        
                        <div class="unit-count">
                            <i class="fas fa-th-large"></i> <?= $unit_count ?> Units Available
                        </div>
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