<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['doctor_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$patient_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Verify patient belongs to logged-in doctor
$stmt = $pdo->prepare("SELECT * FROM patients WHERE id = ? AND doctor_id = ?");
$stmt->execute([$patient_id, $_SESSION['doctor_id']]);
$patient = $stmt->fetch();

if (!$patient) {
    header('Location: list.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $dob = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    
    $stmt = $pdo->prepare("UPDATE patients SET first_name = ?, last_name = ?, date_of_birth = ?, gender = ? WHERE id = ? AND doctor_id = ?");
    try {
        $stmt->execute([$first_name, $last_name, $dob, $gender, $patient_id, $_SESSION['doctor_id']]);
        header('Location: list.php');
        exit();
    } catch(PDOException $e) {
        $error = "Failed to update patient: " . $e->getMessage();
    }
}

require_once('../includes/header.php');
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3>Edit Patient</h3>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" 
                               value="<?php echo htmlspecialchars($patient['first_name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" 
                               value="<?php echo htmlspecialchars($patient['last_name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                               value="<?php echo $patient['date_of_birth']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" value="M" id="gender_m" 
                                   <?php echo $patient['gender'] == 'M' ? 'checked' : ''; ?> required>
                            <label class="form-check-label" for="gender_m">Male</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" value="F" id="gender_f"
                                   <?php echo $patient['gender'] == 'F' ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="gender_f">Female</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" value="Other" id="gender_o"
                                   <?php echo $patient['gender'] == 'Other' ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="gender_o">Other</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Patient</button>
                    <a href="list.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once('../includes/footer.php'); ?>