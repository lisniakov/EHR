<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['doctor_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: ../auth/login.php');
    exit();
}

$patient_id = isset($_POST['patient_id']) ? (int)$_POST['patient_id'] : 0;

// Verify patient belongs to logged-in doctor before deletion
$stmt = $pdo->prepare("DELETE FROM patients WHERE id = ? AND doctor_id = ?");
$stmt->execute([$patient_id, $_SESSION['doctor_id']]);

header('Location: list.php');
exit();