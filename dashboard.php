<?php
session_start();
require_once('config/database.php');

if (!isset($_SESSION['doctor_id'])) {
    header('Location: auth/login.php');
    exit();
}

// Get count of patients
$stmt = $pdo->prepare("SELECT COUNT(*) as patient_count FROM patients WHERE doctor_id = ?");
$stmt->execute([$_SESSION['doctor_id']]);
$result = $stmt->fetch();
$patient_count = $result['patient_count'];

require_once('includes/header.php');
?>

<style>
    .stats-card {
        transition: transform 0.2s ease-in-out;
        border-left: 4px solid #0d6efd;
    }
    .stats-card:hover {
        transform: translateY(-5px);
    }
    .welcome-section {
        background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(71, 171, 237, 0.4)),
                    url('https://thumbs.dreamstime.com/z/healthcare-technology-doctor-using-digital-tablet-icon-medical-network-hospital-background-162019727.jpg?w=992');
        background-size: cover;
        background-position: center;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
    }
    .dashboard-icon {
        width: 50px;
        height: 50px;
        object-fit: cover;
        margin-bottom: 1rem;
    }
    .quick-action-card {
        transition: all 0.2s ease;
        border-radius: 10px;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .quick-action-card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
</style>

<div class="container py-5">
    <div class="welcome-section">
        <h2 class="mb-4">Welcome, Dr. <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
        <p class="text-muted">Manage your patients and medical records efficiently</p>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stats-card h-100">
                <div class="card-body">
                    <img src="https://cdn-icons-png.flaticon.com/512/3481/3481061.png" alt="Patients" class="dashboard-icon">
                    <h5 class="card-title text-muted">Total Patients</h5>
                    <p class="display-4 mb-0"><?php echo $patient_count; ?></p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card stats-card h-100">
                <div class="card-body">
                    <img src="https://cdn-icons-png.flaticon.com/512/747/747310.png" alt="Calendar" class="dashboard-icon">
                    <h5 class="card-title text-muted">Today's Date</h5>
                    <p class="h4 mb-0"><?php echo date('F d, Y'); ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
    <div class="card stats-card h-100">
        <div class="card-body">
            <img src="https://cdn-icons-png.flaticon.com/512/2088/2088617.png" alt="System" class="dashboard-icon">
            <h5 class="card-title text-muted">Time</h5>
            <p class="h4 mb-0" id="current-time"><?php echo date('h:i A'); ?></p>
        </div>
    </div>
</div>

<script>
    function updateTime() {
        const now = new Date();
        const options = { hour: '2-digit', minute: '2-digit', hour12: true };
        document.getElementById('current-time').textContent = now.toLocaleTimeString('en-US', options);
    }
    setInterval(updateTime, 1000);
</script>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card quick-action-card">
                <div class="card-body text-center">
                    <img src="https://cdn.icon-icons.com/icons2/1458/PNG/512/addnewfile_99671.png" alt="Add Patient" class="dashboard-icon">
                    <h5 class="card-title">Add New Patient</h5>
                    <p class="card-text text-muted">Register a new patient to your practice</p>
                    <a href="/ehr_system/patients/create.php" class="btn btn-primary">Add Patient</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card quick-action-card">
                <div class="card-body text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/1/1755.png" alt="View Patients" class="dashboard-icon">
                    <h5 class="card-title">View All Patients</h5>
                    <p class="card-text text-muted">Access your patient records</p>
                    <a href="/ehr_system/patients/list.php" class="btn btn-secondary">View Patients</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('includes/footer.php'); ?>