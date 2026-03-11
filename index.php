<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EHR System - Electronic Health Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/ehr_system">EHR System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php if(isset($_SESSION['doctor_id'])): ?>
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/ehr_system/dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/ehr_system/patients/list.php">Patients</a>
                        </li>
                    </ul>
                    <span class="navbar-text me-3">
                        Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </span>
                    <a href="/ehr_system/auth/logout.php" class="btn btn-light">Logout</a>
                <?php else: ?>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/ehr_system/auth/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/ehr_system/auth/register.php">Register</a>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section-->
    <div class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="display-4 mb-4">Modern Electronic Health Records System</h1>
                    <p class="lead mb-4">Streamline your medical practice with our comprehensive EHR solution. Securely manage patient records, track medical histories, and improve healthcare delivery.</p>
                    <?php
                    if (isset($_SESSION['doctor_id'])) {
                        echo '<a href="/ehr_system/dashboard.php" class="btn btn-primary btn-lg">Go to Dashboard</a>';
                    } else {
                        echo '<a href="/ehr_system/auth/register.php" class="btn btn-primary btn-lg">Get Started</a>';
                    }
                    ?>
                </div>
                <div class="col-md-6">
                    <img src="https://www.enghousevideo.com/wp-content/uploads/2023/09/EHR-Electronic-Health-record-EMR-Medical-automation-system.jpg" alt="Doctor using EHR system" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </div>

    <!--Features Section-->
    <div class="container py-5">
        <h2 class="text-center mb-5">Key Features</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <img src="https://practicebusiness.co.uk/wp-content/uploads/2023/05/iStock-1202233596.jpg" class="card-img-top feature-img" alt="Patient Records">
                    <div class="card-body">
                        <h5 class="card-title">Patient Records Management</h5>
                        <p class="card-text">Efficiently manage and access patient information, medical histories, and treatment plans in one secure location.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <img src="https://thumbs.dreamstime.com/z/krankenakte-anamnese-und-diagnose-der-patientenkarte-arzneimittelcheckliste-mit-checkbox-krankenversicherung-dienstleistungen-169757625.jpg" class="card-img-top feature-img" alt="Medical History">
                    <div class="card-body">
                        <h5 class="card-title">Comprehensive Medical History</h5>
                        <p class="card-text">Track complete patient medical histories, including diagnoses, medications, allergies, and immunizations.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <img src="https://f.hubspotusercontent10.net/hubfs/343611/Best-practices-for-access-control---UPDATED.png" class="card-img-top feature-img" alt="Secure Access">
                    <div class="card-body">
                        <h5 class="card-title">Secure Access</h5>
                        <p class="card-text">Protected health information with secure login and role-based access control for healthcare providers.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .feature-img {
            width: 100%; 
            height: 270px; 
            object-fit: cover; 
        }
    </style>

    <!--About Section-->
    <div id="about" class="bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-4">About Our EHR System</h2>
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <p class="lead text-center">Our Electronic Health Records (EHR) system is designed to help healthcare providers deliver better patient care through efficient digital record management.</p>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Benefits for Doctors</h5>
                            <ul class="list-unstyled">
                                <li>✓ Easy patient record management</li>
                                <li>✓ Quick access to medical histories</li>
                                <li>✓ Secure data storage</li>
                                <li>✓ Streamlined workflow</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>System Features</h5>
                            <ul class="list-unstyled">
                                <li>✓ Patient demographics</li>
                                <li>✓ Medical history tracking</li>
                                <li>✓ Treatment plans</li>
                                <li>✓ Secure doctor accounts</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Footer-->
    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>EHR System</h5>
                    <p>Improving healthcare through digital innovation</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; 2025 EHR System. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>