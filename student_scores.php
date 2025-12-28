<?php
session_start();
require_once 'config.php';
if ($_SESSION['role'] !== 'admin') header('Location: login.php');

$results = $conn->query("
    SELECT u.username, s.name AS subject, un.name AS unit, 
           sc.correct, sc.missed, sc.correct + sc.missed AS total,
           ROUND(sc.correct * 100.0 / (sc.correct + sc.missed), 1) AS percentage,
           sc.taken_at
    FROM student_scores sc
    JOIN users u ON sc.student_id = u.id
    JOIN units un ON sc.unit_id = un.id
    JOIN subjects s ON un.subject_id = s.id
    ORDER BY sc.taken_at DESC
");
?>

<!DOCTYPE html>
<html><head><title>Student Results</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="bg-light">
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-success text-white text-center"><h3>Student Quiz Results</h3></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Student</th><th>Subject</th><th>Unit</th><th>Correct</th><th>Wrong</th><th>Total</th><th>Score %</th><th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($r = $results->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($r['username']) ?></strong></td>
                            <td><?= htmlspecialchars($r['subject']) ?></td>
                            <td><?= htmlspecialchars($r['unit']) ?></td>
                            <td class="text-success fw-bold"><?= $r['correct'] ?></td>
                            <td class="text-danger"><?= $r['missed'] ?></td>
                            <td><?= $r['total'] ?></td>
                            <td><span class="badge bg-<?= $r['percentage']>=70?'success':($r['percentage']>=50?'warning':'danger') ?> fs-6"><?= $r['percentage'] ?>%</span></td>
                            <td><?= date('d/m/Y H:i', strtotime($r['taken_at'])) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</div>
</body></html>