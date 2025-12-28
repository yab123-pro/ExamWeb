<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$step = $_GET['step'] ?? 1;
$grade = $_POST['grade'] ?? $_GET['grade'] ?? null;
$use_existing = $_POST['use_existing'] ?? null;
$subject_id = $_POST['subject_id'] ?? $_GET['subject_id'] ?? null; // Keep after creation

// Validate grade
if ($grade && !in_array($grade, [9,10,11,12])) $grade = null;

// Handle new subject creation
$subject_created = false;
if (isset($_POST['create_subject']) && !$subject_id) {
    $name = trim($_POST['new_subject']);
    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO subjects (name, grade) VALUES (?, ?)");
        $stmt->bind_param("si", $name, $grade);
        $stmt->execute();
        $subject_id = $conn->insert_id;
        $subject_created = true;
    }
}

// Handle new unit creation
$unit_created = false;
if (isset($_POST['create_unit']) && $subject_id) {
    $name = trim($_POST['unit_name']);
    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO units (name, subject_id) VALUES (?, ?)");
        $stmt->bind_param("si", $name, $subject_id);
        $stmt->execute();
        $unit_created = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Content - Quiz Master</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; font-family: 'Segoe UI', sans-serif; }
        .glass-card { background: rgba(255,255,255,0.95); backdrop-filter: blur(15px); border-radius: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); }
        .grade-circle { width: 120px; height: 120px; border-radius: 50%; background: white; color: #4361ee; font-size: 2.5rem; font-weight: bold; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 30px rgba(0,0,0,0.2); cursor: pointer; transition: all 0.3s; }
        .grade-circle:hover, .grade-circle.selected { transform: scale(1.1); border: 6px solid #4361ee; background: #e7f0ff; }
        .option-box { padding: 40px 20px; border-radius: 20px; background: #f8f9fa; cursor: pointer; transition: all 0.3s; border: 3px solid transparent; }
        .option-box:hover, .option-box.selected { border-color: #4361ee; background: #e7f0ff; transform: translateY(-8px); }
        .btn-glow { box-shadow: 0 0 25px rgba(13,110,253,0.6); transition: all 0.3s; }
        .btn-glow:hover { transform: translateY(-4px); box-shadow: 0 0 40px rgba(13,110,253,0.8); }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-5 text-white fw-bold">Create New Content</h1>
        <p class="text-white opacity-90">Step <?= $step ?> of 3</p>
    </div>

    <!-- STEP 1: Choose Grade -->
    <?php if ($step == 1): ?>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="glass-card p-5">
                <h3 class="text-center mb-5 text-primary">Select Grade</h3>
                <form method="POST" action="create.php?step=2" id="gradeForm">
                    <div class="d-flex justify-content-center gap-5 flex-wrap">
                        <?php for($g=9; $g<=12; $g++): ?>
                        <div class="text-center">
                            <input type="radio" name="grade" value="<?= $g ?>" id="grade<?= $g ?>" class="d-none" required <?= ($_POST['grade'] ?? '')==$g?'checked':'' ?>>
                            <label for="grade<?= $g ?>" class="d-block cursor-pointer">
                                <div class="grade-circle <?= ($_POST['grade'] ?? '')==$g?'selected':'' ?>">
                                    <?= $g ?>
                                </div>
                                <div class="mt-3 text-white fw-bold fs-4">Grade <?= $g ?></div>
                            </label>
                        </div>
                        <?php endfor; ?>
                    </div>
                    <div class="text-center mt-5">
                        <button type="submit" class="btn btn-primary btn-lg px-5 btn-glow">Next</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- STEP 2: Choose Subject Type -->
    <?php if ($step == 2 && $grade): ?>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="glass-card p-5">
                <h3 class="text-center mb-5 text-primary">Subject for Grade <?= $grade ?></h3>
                <form method="POST" action="create.php?step=3" id="subjectForm">
                    <input type="hidden" name="grade" value="<?= $grade ?>">

                    <div class="row g-5 mb-5">
                        <div class="col-md-6">
                            <label class="d-block text-center">
                                <input type="radio" name="use_existing" value="no" id="newSubject" class="d-none" required <?= $use_existing==='no'?'checked':'' ?>>
                                <div class="option-box text-center <?= $use_existing==='no'?'selected':'' ?>">
                                    <i class="fas fa-plus-circle fa-5x text-success mb-4"></i>
                                    <h4 class="text-success">Create New Subject</h4>
                                    <p class="text-muted">Start fresh with a brand new subject</p>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="d-block text-center">
                                <input type="radio" name="use_existing" value="yes" id="existingSubject" class="d-none" <?= $use_existing==='yes'?'checked':'' ?>>
                                <div class="option-box text-center <?= $use_existing==='yes'?'selected':'' ?>">
                                    <i class="fas fa-copy fa-5x text-primary mb-4"></i>
                                    <h4 class="text-primary">Use Existing Subject</h4>
                                    <p class="text-muted">Add units to an existing subject</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div id="existingSubjectsBox" style="display: <?= $use_existing==='yes'?'block':'none' ?>;">
                        <div class="text-center mb-3">
                            <h5 class="text-primary">Select Subject to Add Units</h5>
                        </div>
                        <select name="subject_id" class="form-select form-select-lg" id="subjectDropdown">
                            <option value="">-- Choose Existing Subject --</option>
                            <?php
                            $subs = $conn->query("SELECT id, name FROM subjects WHERE grade = $grade ORDER BY name");
                            while($s = $subs->fetch_assoc()): ?>
                                <option value="<?= $s['id'] ?>" <?= $subject_id==$s['id']?'selected':'' ?>>
                                    <?= htmlspecialchars($s['name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="text-center mt-5">
                        <a href="create.php" class="btn btn-secondary btn-lg me-3">Back</a>
                        <button type="submit" class="btn btn-success btn-lg px-5 btn-glow">Next</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- STEP 3: Subject & Unit Creation -->
    <?php if ($step == 3 && $grade): ?>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="glass-card p-5">
                <?php
                // Determine current subject name
                $current_subject_name = '';
                if ($subject_id) {
                    $name_result = $conn->query("SELECT name FROM subjects WHERE id = $subject_id");
                    if ($name_result->num_rows > 0) {
                        $current_subject_name = $name_result->fetch_row()[0];
                    }
                }

                if ($use_existing === 'yes' && $subject_id) {
                    echo "<h3 class='text-center text-success mb-4'>Adding Units to: <strong>$current_subject_name</strong></h3>";
                } else {
                    
                    if ($subject_created) {
                        echo "<div class='alert alert-success text-center mb-4'>Subject '<strong>$current_subject_name</strong>' created successfully!</div>";
                    }
                }

                // Show new subject form only if no subject yet and not using existing
                if (!$subject_id && $use_existing !== 'yes'): ?>
                <form method="POST" class="text-center mb-5">
                    <input type="hidden" name="grade" value="<?= $grade ?>">
                    <input type="hidden" name="step" value="3">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <input type="text" name="new_subject" placeholder="Enter new subject name" required class="form-control form-control-lg text-center">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" name="create_subject" class="btn btn-primary btn-lg w-100">Create Subject</button>
                        </div>
                    </div>
                </form>
                <?php endif; ?>

                <?php if ($subject_id): ?>
                <hr class="my-5">
                <h4 class="text-center mb-4 text-success">Create New Unit for <strong><?= htmlspecialchars($current_subject_name) ?></strong></h4>
                <?php if ($unit_created): ?>
                <div class="alert alert-success text-center mb-4">
                    Unit created successfully!<br>
                    <a href="current.php" class="btn btn-primary mt-3">Go Add Questions Now</a>
                </div>
                <?php endif; ?>
                <form method="POST" class="row g-3 justify-content-center">
                    <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
                    <input type="hidden" name="grade" value="<?= $grade ?>">
                    <input type="hidden" name="step" value="3">
                    <div class="col-md-7">
                        <input type="text" name="unit_name" placeholder="Enter unit name (e.g., Chapter 1: Introduction)" required class="form-control form-control-lg" value="<?= $_POST['unit_name'] ?? '' ?>">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" name="create_unit" class="btn btn-success btn-lg w-100 btn-glow">
                            Create Unit
                        </button>
                    </div>
                </form>
                <div class="text-center mt-5">
                    <a href="admin_dashboard.php" class="btn btn-outline-light btn-lg px-5">Back to Dashboard</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
// Grade selection
document.querySelectorAll('input[name="grade"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.grade-circle').forEach(c => c.classList.remove('selected'));
        this.closest('label').querySelector('.grade-circle').classList.add('selected');
    });
});

// Subject type selection
document.getElementById('newSubject')?.addEventListener('change', function() {
    document.querySelectorAll('.option-box').forEach(box => box.classList.remove('selected'));
    this.closest('label').querySelector('.option-box').classList.add('selected');
    document.getElementById('existingSubjectsBox').style.display = 'none';
});

document.getElementById('existingSubject')?.addEventListener('change', function() {
    document.querySelectorAll('.option-box').forEach(box => box.classList.remove('selected'));
    this.closest('label').querySelector('.option-box').classList.add('selected');
    document.getElementById('existingSubjectsBox').style.display = 'block';
});

// Trigger on page load
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('existingSubject')?.checked) {
        document.getElementById('existingSubjectsBox').style.display = 'block';
    }
});
</script>

</body>
</html>