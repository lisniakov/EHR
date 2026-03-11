<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once('../config/database.php');

if (!isset($_SESSION['doctor_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $doctor_id = $_SESSION['doctor_id'];
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $dob = $_POST['date_of_birth'] ?? '';
    $gender = $_POST['gender'] ?? '';
    
    if (empty($first_name) || empty($last_name) || empty($dob) || empty($gender)) {
        $error = "All fields are required";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO patients (doctor_id, first_name, last_name, date_of_birth, gender) 
                                VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$doctor_id, $first_name, $last_name, $dob, $gender]);
            $success = "Patient added successfully!";
            // Redirect after successful addition
            header('Location: list.php');
            exit();
        } catch(PDOException $e) {
            $error = "Failed to create patient: " . $e->getMessage();
        }
    }
}

require_once('../includes/header.php');
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3>Add New Patient</h3>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" 
                               value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" 
                               value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                               value="<?php echo htmlspecialchars($_POST['date_of_birth'] ?? ''); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" value="M" id="gender_m" 
                                   <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'M') ? 'checked' : ''; ?> required>
                            <label class="form-check-label" for="gender_m">Male</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" value="F" id="gender_f"
                                   <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'F') ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="gender_f">Female</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" value="Other" id="gender_o"
                                   <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Other') ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="gender_o">Other</label>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Create Patient</button>
                        <a href="list.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once('../includes/footer.php'); ?>