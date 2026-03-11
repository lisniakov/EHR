<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['doctor_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Get patient ID from URL
$patient_id = isset($_GET['patient_id']) ? (int)$_GET['patient_id'] : 0;

// Verify patient belongs to logged-in doctor
$stmt = $pdo->prepare("SELECT * FROM patients WHERE id = ? AND doctor_id = ?");
$stmt->execute([$patient_id, $_SESSION['doctor_id']]);
$patient = $stmt->fetch();

// If patient not found or doesn't belong to this doctor, redirect
if (!$patient) {
    header('Location: list.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO medical_records (
                patient_id, visit_date, chief_complaint, medical_history,
                current_medications, allergies, height, weight,
                blood_pressure, temperature, immunization_status,
                lab_results, diagnosis, treatment_plan, notes
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $patient_id,
            $_POST['visit_date'],
            $_POST['chief_complaint'],
            $_POST['medical_history'],
            $_POST['current_medications'],
            $_POST['allergies'],
            $_POST['height'],
            $_POST['weight'],
            $_POST['blood_pressure'],
            $_POST['temperature'],
            $_POST['immunization_status'],
            $_POST['lab_results'],
            $_POST['diagnosis'],
            $_POST['treatment_plan'],
            $_POST['notes']
        ]);
        
        $success = "Medical record added successfully!";
        header('Location: view.php?id=' . $patient_id);
        exit();
    } catch(PDOException $e) {
        $error = "Failed to add medical record: " . $e->getMessage();
    }
}

require_once('../includes/header.php');
?>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h3>Add Medical Record - <?php echo htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']); ?></h3>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form method="POST" class="needs-validation" novalidate>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="visit_date" class="form-label">Visit Date*</label>
                            <input type="date" class="form-control" id="visit_date" name="visit_date" 
                                   value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="chief_complaint" class="form-label">Chief Complaint*</label>
                            <input type="text" class="form-control" id="chief_complaint" name="chief_complaint" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="medical_history" class="form-label">Medical History</label>
                        <textarea class="form-control" id="medical_history" name="medical_history" rows="3"></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="current_medications" class="form-label">Current Medications</label>
                            <textarea class="form-control" id="current_medications" name="current_medications" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="allergies" class="form-label">Allergies</label>
                            <textarea class="form-control" id="allergies" name="allergies" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="height" class="form-label">Height (cm)</label>
                            <input type="number" step="0.01" class="form-control" id="height" name="height">
                        </div>
                        <div class="col-md-3">
                            <label for="weight" class="form-label">Weight (kg)</label>
                            <input type="number" step="0.01" class="form-control" id="weight" name="weight">
                        </div>
                        <div class="col-md-3">
                            <label for="blood_pressure" class="form-label">Blood Pressure</label>
                            <input type="text" class="form-control" id="blood_pressure" name="blood_pressure" 
                                   placeholder="120/80">
                        </div>
                        <div class="col-md-3">
                            <label for="temperature" class="form-label">Temperature (°C)</label>
                            <input type="number" step="0.1" class="form-control" id="temperature" name="temperature">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="immunization_status" class="form-label">Immunization Status</label>
                        <textarea class="form-control" id="immunization_status" name="immunization_status" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="lab_results" class="form-label">Lab Results</label>
                        <textarea class="form-control" id="lab_results" name="lab_results" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="diagnosis" class="form-label">Diagnosis*</label>
                        <textarea class="form-control" id="diagnosis" name="diagnosis" rows="2" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="treatment_plan" class="form-label">Treatment Plan*</label>
                        <textarea class="form-control" id="treatment_plan" name="treatment_plan" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Additional Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Save Medical Record</button>
                        <a href="view.php?id=<?php echo $patient_id; ?>" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
})()
</script>

<?php require_once('../includes/footer.php'); ?>