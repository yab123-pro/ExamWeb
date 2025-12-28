<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header('Location: login.php');
    exit;
}

$unit_id = $_GET['unit_id'] ?? null;
if (!$unit_id) {
    header('Location: student_dashboard.php');
    exit;
}

// Get unit info
$stmt = $conn->prepare("SELECT u.name AS unit, s.name AS subject, s.grade FROM units u JOIN subjects s ON u.subject_id = s.id WHERE u.id = ?");
$stmt->bind_param("i", $unit_id);
$stmt->execute();
$unit_info = $stmt->get_result()->fetch_assoc();

// Fetch questions
$stmt = $conn->prepare("SELECT id, question_text, option_a, option_b, option_c, option_d, correct_answer, image_path FROM questions WHERE unit_id = ? ORDER BY id");
$stmt->bind_param("i", $unit_id);
$stmt->execute();
$result = $stmt->get_result();
$questions = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$total_questions = count($questions);

$show_results = false;
$correct_count = 0;
$answered_count = 0;
$percentage = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $answers = $_POST['q'] ?? [];

    foreach ($questions as $q) {
        $user_answer = $answers[$q['id']] ?? null;
        if ($user_answer !== null) {
            $answered_count++;
            if (strtoupper($user_answer) === $q['correct_answer']) {
                $correct_count++;
            }
        }
    }

    $percentage = $answered_count > 0 ? round(($correct_count / $answered_count) * 100, 1) : 0;

    // Save score
    $missed = $answered_count - $correct_count;
    $stmt = $conn->prepare("INSERT INTO student_scores (student_id, unit_id, correct, missed) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiii", $_SESSION['user_id'], $unit_id, $correct_count, $missed);
    $stmt->execute();
    $stmt->close();

    $show_results = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quiz - <?= htmlspecialchars($unit_info['unit'] ?? 'Unit') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; font-family: 'Poppins', sans-serif; }
        .quiz-card { background: rgba(255,255,255,0.97); backdrop-filter: blur(15px); border-radius: 30px; box-shadow: 0 25px 50px rgba(0,0,0,0.2); padding: 40px; }
        .question-card { background: #f8f9fa; border-radius: 20px; border-left: 6px solid #4361ee; padding: 30px; margin-bottom: 30px; transition: 0.3s; }
        .question-card:hover { transform: translateX(10px); box-shadow: 0 15px 30px rgba(67,97,238,0.3); }
        .question-image { max-height: 350px; max-width: 100%; border-radius: 15px; box-shadow: 0 15px 30px rgba(0,0,0,0.2); margin: 20px 0; }
        .option-label {
            font-size: 1.2rem; cursor: pointer; transition: all 0.4s ease;
            padding: 18px 25px; border-radius: 18px; margin-bottom: 12px;
            border: 3px solid transparent; background: white; box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        .option-label:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.15); }
        .option-correct { background: #d4edda !important; border-color: #28a745 !important; color: #155724; }
        .option-wrong { background: #f8d7da !important; border-color: #dc3545 !important; color: #721c24; }
        .submit-btn { padding: 18px 60px; font-size: 1.4rem; border-radius: 50px; box-shadow: 0 15px 35px rgba(40,167,69,0.5); }
        .back-btn { position: fixed; top: 20px; left: 20px; z-index: 1000; width: 60px; height: 60px; border-radius: 50%; background: white; box-shadow: 0 10px 30px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: #4361ee; transition: 0.3s; }
        .back-btn:hover { transform: scale(1.15); }
        .progress-info { background: #e3f2fd; border-radius: 15px; padding: 15px; margin-bottom: 30px; }
    </style>
</head>
<body>

<a href="student_dashboard.php" class="back-btn">
    <i class="fas fa-home"></i>
</a>

<div class="container py-5">
    <div class="quiz-card">
        <div class="text-center mb-5">
            <h1 class="display-5 text-primary fw-bold">
                <?= htmlspecialchars($unit_info['subject'] ?? '') ?> → <?= htmlspecialchars($unit_info['unit'] ?? '') ?>
            </h1>
            <p class="lead text-muted">Grade <?= $unit_info['grade'] ?? '' ?> • <?= $total_questions ?> Questions</p>
        </div>

        <?php if ($show_results): ?>
            <div class="alert text-center p-5 rounded-4 shadow-lg <?= $percentage >= 70 ? 'alert-success' : 'alert-warning' ?>">
                <h2><i class="fas fa-trophy fa-3x text-warning mb-4"></i></h2>
                <h3>Quiz Submitted!</h3>
                <h4>You answered <strong><?= $answered_count ?></strong> out of <?= $total_questions ?> questions</h4>
                <h4>Score: <strong class="text-success"><?= $correct_count ?></strong> correct</h4>
                <h2 class="display-4 text-primary fw-bold"><?= $percentage ?>%</h2>
                <div class="mt-4">
                    <a href="student_dashboard.php" class="btn btn-primary btn-lg px-5">Back to Dashboard</a>
                </div>
            </div>
        <?php else: ?>
            <div class="progress-info text-center">
                <p class="mb-2 text-muted">Select an answer → get instant feedback</p>
                <p class="fw-bold text-primary">You can change your answer anytime • Submit when ready</p>
            </div>

            <form method="POST">
                <?php foreach ($questions as $index => $q): ?>
                    <div class="question-card" id="question-<?= $q['id'] ?>">
                        <h5 class="mb-4 text-primary"><strong>Question <?= $index + 1 ?></strong></h5>

                        <?php if (!empty($q['image_path']) && file_exists($q['image_path'])): ?>
                            <div class="text-center mb-4">
                                <img src="<?= htmlspecialchars($q['image_path']) ?>" class="question-image img-fluid" alt="Question Image">
                            </div>
                        <?php endif; ?>

                        <p class="lead mb-4"><?= nl2br(htmlspecialchars($q['question_text'])) ?></p>

                        <div class="row g-3">
                            <?php 
                            $options = ['A' => $q['option_a'], 'B' => $q['option_b'], 'C' => $q['option_c'], 'D' => $q['option_d']];
                            foreach ($options as $letter => $text): 
                                $text = trim($text) ?: "[No option]";
                            ?>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="q[<?= $q['id'] ?>]" value="<?= $letter ?>" 
                                               id="q<?= $q['id'] ?>_<?= $letter ?>" onchange="checkAnswer(<?= $q['id'] ?>, '<?= $q['correct_answer'] ?>')">
                                        <label class="form-check-label option-label d-block" for="q<?= $q['id'] ?>_<?= $letter ?>">
                                            <strong><?= $letter ?>.</strong> <?= htmlspecialchars($text) ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-success submit-btn">
                        Submit Quiz
                    </button>
                    <a href="student_dashboard.php" class="btn btn-outline-secondary btn-lg ms-3">Cancel</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<script>
function checkAnswer(questionId, correct) {
    const card = document.getElementById('question-' + questionId);
    const labels = card.querySelectorAll('.option-label');
    labels.forEach(label => label.classList.remove('option-correct', 'option-wrong'));

    const radios = card.querySelectorAll(`input[name="q[${questionId}]"]`);
    let selectedValue = null;
    radios.forEach(radio => {
        if (radio.checked) selectedValue = radio.value;
    });

    radios.forEach(radio => {
        const label = radio.closest('.form-check').querySelector('.option-label');
        if (radio.value === correct) {
            label.classList.add('option-correct');
        } else if (radio.value === selectedValue) {
            label.classList.add('option-wrong');
        }
    });
}
</script>

</body>
</html>