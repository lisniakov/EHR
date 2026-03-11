<?php
// delete_medical_record.php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['doctor_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: ../auth/login.php');
    exit();
}

$record_id = isset($_POST['record_id']) ? (int)$_POST['record_id'] : 0;

// Verify medical record belongs to a patient of the logged-in doctor
$stmt = $pdo->prepare("
    DELETE mr FROM medical_records mr
    JOIN patients p ON mr.patient_id = p.id
    WHERE mr.id = ? AND p.doctor_id = ?
");
$stmt->execute([$record_id, $_SESSION['doctor_id']]);

// Redirect back to the patient's view page
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();