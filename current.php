<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$step = $_GET['step'] ?? 'grades';
$grade = $_GET['grade'] ?? null;
$subject_id = $_GET['subject_id'] ?? null;
$unit_id = $_GET['unit_id'] ?? null;
$message = '';

// Create uploads folder if not exists
if (!is_dir('uploads/questions')) {
    mkdir('uploads/questions', 0777, true);
}

/* =============== MANUAL + CSV + IMAGE UPLOAD =============== */
if ($step === 'add_questions' && $unit_id && $_SERVER['REQUEST_METHOD'] === 'POST') {

    // MANUAL ENTRY WITH IMAGE
    if (isset($_POST['manual_question'])) {
        $q = trim($_POST['question']);
        $a = trim($_POST['a']); $b = trim($_POST['b']);
        $c = trim($_POST['c']); $d = trim($_POST['d']);
        $correct = strtoupper($_POST['correct']);

        if ($q && $a && $b && $c && $d && in_array($correct, ['A','B','C','D'])) {
            $image_path = '';
            if (isset($_FILES['question_image']) && $_FILES['question_image']['error'] === 0) {
                $ext = pathinfo($_FILES['question_image']['name'], PATHINFO_EXTENSION);
                $filename = 'img_' . time() . '_' . rand(1000,9999) . '.' . strtolower($ext);
                $target = 'uploads/questions/' . $filename;
                if (move_uploaded_file($_FILES['question_image']['tmp_name'], $target)) {
                    $image_path = $target;
                }
            }

            $stmt = $conn->prepare("INSERT INTO questions (unit_id, question_text, option_a, option_b, option_c, option_d, correct_answer, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssssss", $unit_id, $q, $a, $b, $c, $d, $correct, $image_path);
            if ($stmt->execute()) {
                $message = "<div class='alert alert-success text-center'><i class='fas fa-check-circle fa-2x'></i><br>Question added with image!</div>";
            } else {
                $message = "<div class='alert alert-danger'>Database error.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Please fill all fields correctly.</div>";
        }
    }

    // CSV UPLOAD (unchanged — your powerful parser)
    elseif (isset($_FILES['csv']) && $_FILES['csv']['error'] === 0) {
        $file = $_FILES['csv']['tmp_name'];
        $handle = fopen($file, "r");
        $added = 0;
        fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 2) continue;
            $question_text = trim($row[1]);
            $answer = strtoupper(trim($row[2] ?? 'A'));

            $question_text = str_replace(['""', '"'], '"', $question_text);
            if (str_starts_with($question_text, '"') && str_ends_with($question_text, '"')) {
                $question_text = substr($question_text, 1, -1);
            }

            $correct_answer = 'A';
            if (preg_match('/\*\*([A-D])\*\*|\→\s*([A-D])|\(([A-D])\)/i', $question_text, $m)) {
                $correct_answer = strtoupper($m[1] ?? $m[2] ?? $m[3] ?? 'A');
            } elseif (in_array($answer, ['A','B','C','D'])) {
                $correct_answer = $answer;
            }

            preg_match_all('/([A-D])[.)]\s*(.+?)(?=\s*[A-D][.)]|$)/is', $question_text, $matches, PREG_SET_ORDER);
            $a = $b = $c = $d = "Not specified";
            foreach ($matches as $opt) {
                ${strtolower($opt[1])} = trim($opt[2]);
            }

            $stmt = $conn->prepare("INSERT INTO questions (unit_id, question_text, option_a, option_b, option_c, option_d, correct_answer) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssss", $unit_id, $question_text, $a, $b, $c, $d, $correct_answer);
            if ($stmt->execute()) $added++;
        }
        fclose($handle);
        $message = "<div class='alert alert-success text-center'><i class='fas fa-file-csv fa-2x'></i><br>$added questions added from CSV!</div>";
    }
}

// Update correct answer
if (isset($_POST['update_answer'])) {
    $qid = (int)$_POST['question_id'];
    $new_ans = $_POST['correct_answer'];
    if (in_array($new_ans, ['A','B','C','D'])) {
        $stmt = $conn->prepare("UPDATE questions SET correct_answer = ? WHERE id = ?");
        $stmt->bind_param("si", $new_ans, $qid);
        $stmt->execute();
        $message = "<div class='alert alert-success'><i class='fas fa-check'></i> Correct answer updated!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Current Content - Lexa Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-family: 'Poppins', sans-serif; min-height: 100vh; }
        .glass-card { background: rgba(255,255,255,0.97); backdrop-filter: blur(20px); border-radius: 30px; box-shadow: 0 25px 50px rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.3); }
        .card-hover { transition: all 0.4s ease; border-radius: 25px; overflow: hidden; }
        .card-hover:hover { transform: translateY(-15px) scale(1.03); box-shadow: 0 35px 70px rgba(67,97,238,0.5); }
        .grade-card { background: linear-gradient(135deg, #4361ee, #5e7bff); color: white; padding: 50px 20px; text-align: center; }
        .back-home { position: fixed; top: 20px; left: 20px; z-index: 9999; width: 65px; height: 65px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #4361ee; box-shadow: 0 15px 35px rgba(0,0,0,0.3); transition: 0.3s; }
        .back-home:hover { transform: scale(1.2) rotate(360deg); }
        .nav-tabs .nav-link.active { background: #4361ee !important; color: white !important; }
        .preview-img { max-height: 300px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); margin: 15px 0; }
    </style>
</head>
<body>

<a href="admin_dashboard.php" class="back-home">
    <i class="fas fa-home"></i>
</a>

<div class="container py-5 mt-5">
    <div class="text-center mb-5">
        <h1 class="display-3 fw-bold text-white">Current Content</h1>
        <p class="lead text-white opacity-90">The most powerful admin panel</p>
    </div>

    <div class="glass-card p-5">
        <nav class="breadcrumb mb-4 fs-5">
            <a href="current.php" class="text-primary">Grades</a>
            <?php if ($grade): ?> → <a href="current.php?step=subjects&grade=<?= $grade ?>" class="text-primary">Grade <?= $grade ?></a><?php endif; ?>
            <?php if ($subject_id): ?> → <a href="current.php?step=units&subject_id=<?= $subject_id ?>" class="text-primary">Subject</a><?php endif; ?>
            <?php if ($unit_id): ?> → <span class="text-success fw-bold">Unit</span><?php endif; ?>
        </nav>

        <?php if ($message) echo "<div class='text-center mb-4'>$message</div>"; ?>

        <!-- Grades, Subjects, Units — same as before -->
        <?php if ($step === 'grades'): ?>
            <h2 class="text-center mb-5 text-primary">Select Grade</h2>
            <div class="row g-5 justify-content-center">
                <?php for ($g=9; $g<=12; $g++):
                    $stmt = $conn->prepare("SELECT COUNT(*) FROM subjects WHERE grade = ?");
                    $stmt->bind_param("i", $g); $stmt->execute();
                    $count = $stmt->get_result()->fetch_row()[0];
                ?>
                    <div class="col-md-3">
                        <a href="current.php?step=subjects&grade=<?= $g ?>" class="card-hover text-decoration-none">
                            <div class="grade-card">
                                <h1 class="display-2 mb-0">Grade <?= $g ?></h1>
                                <p class="fs-4"><?= $count ?> subjects</p>
                            </div>
                        </a>
                    </div>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

        <?php if ($step === 'subjects' && $grade): 
            $stmt = $conn->prepare("SELECT id, name FROM subjects WHERE grade = ?");
            $stmt->bind_param("i", $grade); $stmt->execute(); $subjects = $stmt->get_result();
        ?>
            <h2 class="text-center mb-5">Grade <?= $grade ?> Subjects</h2>
            <div class="row g-4">
                <?php while ($s = $subjects->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <a href="current.php?step=units&subject_id=<?= $s['id'] ?>" class="card-hover text-decoration-none">
                            <div class="text-white p-5 text-center rounded-4" style="background: linear-gradient(135deg, #4361ee, #764ba2);">
                                <i class="fas fa-book-open fa-4x mb-3"></i>
                                <h4><?= htmlspecialchars($s['name']) ?></h4>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

        <?php if ($step === 'units' && $subject_id): 
            $stmt = $conn->prepare("SELECT id, name FROM units WHERE subject_id = ?");
            $stmt->bind_param("i", $subject_id); $stmt->execute(); $units = $stmt->get_result();
            $stmt = $conn->prepare("SELECT name FROM subjects WHERE id = ?");
            $stmt->bind_param("i", $subject_id); $stmt->execute();
            $subject_name = $stmt->get_result()->fetch_assoc()['name'];
        ?>
            <h2 class="text-center mb-5"><?= htmlspecialchars($subject_name) ?> → Units</h2>
            <div class="row g-4">
                <?php while ($u = $units->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <div class="card card-hover border-0 shadow">
                            <div class="card-body text-center p-5">
                                <i class="fas fa-layer-group fa-4x text-success mb-3"></i>
                                <h5 class="card-title"><?= htmlspecialchars($u['name']) ?></h5>
                                <a href="current.php?step=questions&unit_id=<?= $u['id'] ?>" class="btn btn-outline-primary">View</a>
                                <a href="current.php?step=add_questions&unit_id=<?= $u['id'] ?>" class="btn btn-success mt-2">Add Questions</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

        <!-- Questions List -->
        <?php if ($step === 'questions' && $unit_id): 
            $stmt = $conn->prepare("SELECT q.*, u.name AS unit_name, s.name AS subject_name FROM questions q JOIN units u ON q.unit_id = u.id JOIN subjects s ON u.subject_id = s.id WHERE q.unit_id = ?");
            $stmt->bind_param("i", $unit_id); $stmt->execute(); $questions = $stmt->get_result();
            $first = $questions->fetch_assoc(); $questions->data_seek(0);
        ?>
            <h2 class="text-center mb-4"><?= htmlspecialchars($first['subject_name']) ?> → <?= htmlspecialchars($first['unit_name']) ?></h2>
            <div class="text-end mb-4">
                <a href="current.php?step=add_questions&unit_id=<?= $unit_id ?>" class="btn btn-success btn-lg">
                    Add Questions
                </a>
            </div>
            <?php while ($q = $questions->fetch_assoc()): ?>
                <div class="card mb-3 border-start border-primary border-5">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-9">
                                <strong>Q<?= $q['id'] ?>:</strong> <?= nl2br(htmlspecialchars($q['question_text'])) ?>
                                <?php if (!empty($q['image_path']) && file_exists($q['image_path'])): ?>
                                    <div class="text-center my-3">
                                        <img src="<?= htmlspecialchars($q['image_path']) ?>" class="img-fluid rounded shadow" style="max-height: 300px;">
                                    </div>
                                <?php endif; ?>
                                <div class="mt-3 small text-muted">
                                    A: <?= htmlspecialchars($q['option_a']) ?><br>
                                    B: <?= htmlspecialchars($q['option_b']) ?><br>
                                    C: <?= htmlspecialchars($q['option_c']) ?><br>
                                    D: <?= htmlspecialchars($q['option_d']) ?>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <form method="POST">
                                    <input type="hidden" name="question_id" value="<?= $q['id'] ?>">
                                    <div class="btn-group">
                                        <?php foreach(['A','B','C','D'] as $opt): ?>
                                            <input type="radio" class="btn-check" name="correct_answer" value="<?= $opt ?>" id="o<?= $q['id'].$opt ?>" <?= $q['correct_answer']==$opt?'checked':'' ?>>
                                            <label class="btn <?= $q['correct_answer']==$opt?'btn-success':'btn-outline-secondary' ?>" for="o<?= $q['id'].$opt ?>"><?= $opt ?></label>
                                        <?php endforeach; ?>
                                    </div>
                                    <button type="submit" name="update_answer" class="btn btn-primary btn-sm ms-2">Save</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>

        <!-- ADD QUESTIONS (Manual with Image + CSV) -->
        <?php if ($step === 'add_questions' && $unit_id):
            $stmt = $conn->prepare("SELECT u.name, s.name AS subject FROM units u JOIN subjects s ON u.subject_id = s.id WHERE u.id = ?");
            $stmt->bind_param("i", $unit_id); $stmt->execute(); $info = $stmt->get_result()->fetch_assoc();
        ?>
            <h2 class="text-center mb-5">
                Add Questions → <?= htmlspecialchars($info['subject']) ?> → <?= htmlspecialchars($info['name']) ?>
            </h2>

            <ul class="nav nav-tabs mb-5 justify-content-center">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#manual">Manual Entry</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#csv">Upload CSV</button></li>
            </ul>

            <div class="tab-content">
                <!-- MANUAL WITH IMAGE -->
                <div class="tab-pane fade show active" id="manual">
                    <form method="POST" enctype="multipart/form-data" class="p-4 bg-light rounded-4">
                        <div class="text-center mb-4">
                            <label class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-image fa-2x"></i><br>Upload Question Image
                                <input type="file" name="question_image" accept="image/*" class="d-none" onchange="previewImage(event)">
                            </label>
                            <div id="imagePreview" class="mt-3"></div>
                        </div>

                        <textarea name="question" class="form-control form-control-lg mb-4" rows="5" placeholder="Write your question text here..." required></textarea>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6"><input type="text" name="a" class="form-control" placeholder="A) Option A" required></div>
                            <div class="col-md-6"><input type="text" name="b" class="form-control" placeholder="B) Option B" required></div>
                            <div class="col-md-6"><input type="text" name="c" class="form-control" placeholder="C) Option C" required></div>
                            <div class="col-md-6"><input type="text" name="d" class="form-control" placeholder="D) Option D" required></div>
                        </div>
                        <div class="text-center mb-4">
                            <strong class="fs-4">Correct Answer:</strong><br>
                            <?php foreach(['A','B','C','D'] as $o): ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="correct" value="<?= $o ?>" required>
                                    <label class="form-check-label fs-3 fw-bold"><?= $o ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-center">
                            <button type="submit" name="manual_question" class="btn btn-success btn-lg px-5">
                                Add Question
                            </button>
                        </div>
                    </form>
                </div>

                <!-- CSV UPLOAD -->
                <div class="tab-pane fade" id="csv">
                    <div class="text-center p-5">
                        <i class="fas fa-file-csv fa-6x text-success mb-4"></i>
                        <form method="POST" enctype="multipart/form-data">
                            <input type="file" name="csv" accept=".csv" required class="form-control form-control-lg w-75 mx-auto">
                            <button type="submit" class="btn btn-success btn-lg mt-4 px-5">
                                Upload & Add Questions
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="current.php?step=questions&unit_id=<?= $unit_id ?>" class="btn btn-outline-primary btn-lg">Back to Questions</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function previewImage(event) {
    const preview = document.getElementById('imagePreview');
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="preview-img">`;
        }
        reader.readAsDataURL(file);
    }
}
</script>
</body>
</html>