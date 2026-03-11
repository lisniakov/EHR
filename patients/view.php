<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['doctor_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Get patient ID from URL
$patient_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Verify patient belongs to logged-in doctor
$stmt = $pdo->prepare("SELECT * FROM patients WHERE id = ? AND doctor_id = ?");
$stmt->execute([$patient_id, $_SESSION['doctor_id']]);
$patient = $stmt->fetch();

// If patient not found or doesn't belong to this doctor, redirect
if (!$patient) {
    header('Location: list.php');
    exit();
}

// Get medical records ordered by visit date
$stmt = $pdo->prepare("SELECT * FROM medical_records WHERE patient_id = ? ORDER BY visit_date DESC");
$stmt->execute([$patient_id]);
$medical_records = $stmt->fetchAll();

// Get the consolidated medical history from records
$medical_history = array_filter(array_column($medical_records, 'medical_history'));
$all_notes = array_filter(array_column($medical_records, 'notes'));

require_once('../includes/header.php');
?>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3>Patient Details</h3>
                    <div>
                        <a href="edit.php?id=<?php echo $patient['id']; ?>" class="btn btn-primary">Edit Patient</a>
                        <a href="list.php" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Personal Information Section -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h4>Personal Information</h4>
                        <table class="table table-borderless">
                            <tr>
                                <th class="w-25">Full Name:</th>
                                <td><?php echo htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']); ?></td>
                            </tr>
                            <tr>
                                <th>Date of Birth:</th>
                                <td><?php echo date('F d, Y', strtotime($patient['date_of_birth'])); ?></td>
                            </tr>
                            <tr>
                                <th>Gender:</th>
                                <td><?php echo htmlspecialchars($patient['gender']); ?></td>
                            </tr>
                            <tr>
                                <th>Patient ID:</th>
                                <td><?php echo htmlspecialchars($patient['id']); ?></td>
                            </tr>
                            <tr>
                                <th>Created:</th>
                                <td><?php echo date('F d, Y', strtotime($patient['created_at'])); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h4>Recent Activity</h4>
                        <?php if (!empty($medical_records)): ?>
                            <p>Last visit: <?php echo date('F d, Y', strtotime($medical_records[0]['visit_date'])); ?></p>
                            <p>Total visits: <?php echo count($medical_records); ?></p>
                        <?php else: ?>
                            <p class="text-muted">No visits recorded yet</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Medical Records Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">Medical Records</h4>
                                    <a href="add_medical_record.php?patient_id=<?php echo $patient['id']; ?>" 
                                       class="btn btn-primary">Add Medical Record</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php if (empty($medical_records)): ?>
                                    <div class="alert alert-info">
                                        No medical records found for this patient.
                                    </div>
                                <?php else: ?>
                                    <div class="accordion" id="medicalRecords">
                                        <?php foreach ($medical_records as $index => $record): ?>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="heading<?php echo $index; ?>">
                                                    <button class="accordion-button <?php echo $index === 0 ? '' : 'collapsed'; ?>" 
                                                            type="button" data-bs-toggle="collapse" 
                                                            data-bs-target="#collapse<?php echo $index; ?>">
                                                        Visit Date: <?php echo date('F d, Y', strtotime($record['visit_date'])); ?> - 
                                                        <?php echo htmlspecialchars($record['chief_complaint']); ?>
                                                    </button>
                                                </h2>
                                                <div id="collapse<?php echo $index; ?>" 
                                                     class="accordion-collapse collapse <?php echo $index === 0 ? 'show' : ''; ?>" 
                                                     data-bs-parent="#medicalRecords">
                                                    <div class="accordion-body">
                                                        <div class="mt-3">
                                                            <a href="edit_medical_record.php?id=<?php echo $record['id']; ?>" 
                                                               class="btn btn-sm btn-primary">Edit Record</a>
                                                            <form action="delete_medical_record.php" method="POST" 
                                                                  class="d-inline" 
                                                                  onsubmit="return confirm('Are you sure you want to delete this medical record?');">
                                                                <input type="hidden" name="record_id" 
                                                                       value="<?php echo $record['id']; ?>">
                                                                <button type="submit" class="btn btn-sm btn-danger">Delete Record</button>
                                                            </form>
                                                        </div>
                                                        
                                                        <!-- Record Details -->
                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                <h5>Vital Signs</h5>
                                                                <ul class="list-unstyled">
                                                                    <?php if ($record['height']): ?>
                                                                        <li>Height: <?php echo htmlspecialchars($record['height']); ?> cm</li>
                                                                    <?php endif; ?>
                                                                    <?php if ($record['weight']): ?>
                                                                        <li>Weight: <?php echo htmlspecialchars($record['weight']); ?> kg</li>
                                                                    <?php endif; ?>
                                                                    <?php if ($record['blood_pressure']): ?>
                                                                        <li>Blood Pressure: <?php echo htmlspecialchars($record['blood_pressure']); ?></li>
                                                                    <?php endif; ?>
                                                                    <?php if ($record['temperature']): ?>
                                                                        <li>Temperature: <?php echo htmlspecialchars($record['temperature']); ?> °C</li>
                                                                    <?php endif; ?>
                                                                </ul>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h5>Medications & Allergies</h5>
                                                                <?php if ($record['current_medications']): ?>
                                                                    <p><strong>Current Medications:</strong><br>
                                                                    <?php echo nl2br(htmlspecialchars($record['current_medications'])); ?></p>
                                                                <?php endif; ?>
                                                                <?php if ($record['allergies']): ?>
                                                                    <p><strong>Allergies:</strong><br>
                                                                    <?php echo nl2br(htmlspecialchars($record['allergies'])); ?></p>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        
                                                        <?php if ($record['diagnosis']): ?>
                                                            <h5>Diagnosis</h5>
                                                            <p><?php echo nl2br(htmlspecialchars($record['diagnosis'])); ?></p>
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($record['treatment_plan']): ?>
                                                            <h5>Treatment Plan</h5>
                                                            <p><?php echo nl2br(htmlspecialchars($record['treatment_plan'])); ?></p>
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($record['lab_results']): ?>
                                                            <h5>Lab Results</h5>
                                                            <p><?php echo nl2br(htmlspecialchars($record['lab_results'])); ?></p>
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($record['medical_history']): ?>
                                                            <h5>Medical History</h5>
                                                            <p><?php echo nl2br(htmlspecialchars($record['medical_history'])); ?></p>
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($record['notes']): ?>
                                                            <h5>Additional Notes</h5>
                                                            <p><?php echo nl2br(htmlspecialchars($record['notes'])); ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medical History Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Consolidated Medical History</h4>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($medical_history)): ?>
                                    <?php foreach ($medical_history as $history): ?>
                                        <div class="mb-3">
                                            <p><?php echo nl2br(htmlspecialchars($history)); ?></p>
                                            <hr>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="alert alert-info">No medical history records available.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">All Notes</h4>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($all_notes)): ?>
                                    <?php foreach ($all_notes as $note): ?>
                                        <div class="mb-3">
                                            <p><?php echo nl2br(htmlspecialchars($note)); ?></p>
                                            <hr>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="alert alert-info">No notes available.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('../includes/footer.php'); ?>