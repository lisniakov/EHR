<?php
// edit_medical_record.php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['doctor_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$record_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Verify medical record belongs to a patient of the logged-in doctor
$stmt = $pdo->prepare("
    SELECT mr.*, p.first_name, p.last_name 
    FROM medical_records mr
    JOIN patients p ON mr.patient_id = p.id
    WHERE mr.id = ? AND p.doctor_id = ?
");
$stmt->execute([$record_id, $_SESSION['doctor_id']]);
$record = $stmt->fetch();

if (!$record) {
    header('Location: list.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $stmt = $pdo->prepare("
            UPDATE medical_records SET
                visit_date = ?,
                chief_complaint = ?,
                medical_history = ?,
                current_medications = ?,
                allergies = ?,
                height = ?,
                weight = ?,
                blood_pressure = ?,
                temperature = ?,
                immunization_status = ?,
                lab_results = ?,
                diagnosis = ?,
                treatment_plan = ?,
                notes = ?
            WHERE id = ?
        ");
        
        $stmt->execute([
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
            $_POST['notes'],
            $record_id
        ]);
        
        $success = "Medical record updated successfully!";
        header('Location: view.php?id=' . $record['patient_id']);
        exit();
    } catch(PDOException $e) {
        $error = "Failed to update medical record: " . $e->getMessage();
    }
}

require_once('../includes/header.php');
?>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h3>Edit Medical Record - <?php echo htmlspecialchars($record['first_name'] . ' ' . $record['last_name']); ?></h3>
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
                                   value="<?php echo htmlspecialchars($record['visit_date']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="chief_complaint" class="form-label">Chief Complaint*</label>
                            <input type="text" class="form-control" id="chief_complaint" name="chief_complaint" 
                                   value="<?php echo htmlspecialchars($record['chief_complaint']); ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="medical_history" class="form-label">Medical History</label>
                        <textarea class="form-control" id="medical_history" name="medical_history" rows="3"><?php echo htmlspecialchars($record['medical_history']); ?></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="current_medications" class="form-label">Current Medications</label>
                            <textarea class="form-control" id="current_medications" name="current_medications" rows="2"><?php echo htmlspecialchars($record['current_medications']); ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="allergies" class="form-label">Allergies</label>
                            <textarea class="form-control" id="allergies" name="allergies" rows="2"><?php echo htmlspecialchars($record['allergies']); ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="height" class="form-label">Height (cm)</label>
                            <input type="number" step="0.01" class="form-control" id="height" name="height" 
                                   value="<?php echo htmlspecialchars($record['height']); ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="weight" class="form-label">Weight (kg)</label>
                            <input type="number" step="0.01" class="form-control" id="weight" name="weight" 
                                   value="<?php echo htmlspecialchars($record['weight']); ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="blood_pressure" class="form-label">Blood Pressure</label>
                            <input type="text" class="form-control" id="blood_pressure" name="blood_pressure" 
                                   value="<?php echo htmlspecialchars($record['blood_pressure']); ?>" placeholder="120/80">
                        </div>
                        <div class="col-md-3">
                            <label for="temperature" class="form-label">Temperature (°C)</label>
                            <input type="number" step="0.1" class="form-control" id="temperature" name="temperature" 
                                   value="<?php echo htmlspecialchars($record['temperature']); ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="immunization_status" class="form-label">Immunization Status</label>
                        <textarea class="form-control" id="immunization_status" name="immunization_status" rows="2"><?php echo htmlspecialchars($record['immunization_status']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="lab_results" class="form-label">Lab Results</label>
                        <textarea class="form-control" id="lab_results" name="lab_results" rows="3"><?php echo htmlspecialchars($record['lab_results']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="diagnosis" class="form-label">Diagnosis*</label>
                        <textarea class="form-control" id="diagnosis" name="diagnosis" rows="2" required><?php echo htmlspecialchars($record['diagnosis']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="treatment_plan" class="form-label">Treatment Plan*</label>
                        <textarea class="form-control" id="treatment_plan" name="treatment_plan" rows="3" required><?php echo htmlspecialchars($record['treatment_plan']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Additional Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"><?php echo htmlspecialchars($record['notes']); ?></textarea>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update Medical Record</button>
                        <a href="view.php?id=<?php echo $record['patient_id']; ?>" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once('../includes/footer.php'); ?>