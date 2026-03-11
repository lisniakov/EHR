<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['doctor_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Get search parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Prepare the base query
$query = "SELECT * FROM patients WHERE doctor_id = ?";
$params = [$_SESSION['doctor_id']];

// Add search condition if search term is provided
if (!empty($search)) {
    $query .= " AND (
        LOWER(first_name) LIKE LOWER(?) OR 
        LOWER(last_name) LIKE LOWER(?) OR 
        CONCAT(LOWER(first_name), ' ', LOWER(last_name)) LIKE LOWER(?) OR
        date_of_birth LIKE ?
    )";
    $searchTerm = "%{$search}%";
    $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
}

$query .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$patients = $stmt->fetchAll();

require_once('../includes/header.php');
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-15">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>My Patients</h2>
                <a href="/ehr_system/patients/create.php" class="btn btn-primary">Add New Patient</a>
            </div>
            
            <!-- Search Form -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3 align-items-center">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" id="search" name="search" 
                                       placeholder="Search by name or date of birth..."
                                       value="<?php echo htmlspecialchars($search); ?>">
                                <button type="submit" class="btn btn-primary">Search</button>
                                <?php if (!empty($search)): ?>
                                    <a href="list.php" class="btn btn-secondary">Clear</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Date of Birth</th>
                                    <th>Gender</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($patients as $patient): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($patient['date_of_birth'])); ?></td>
                                        <td><?php echo htmlspecialchars($patient['gender']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($patient['created_at'])); ?></td>
                                        <td>
                                            <a href="edit.php?id=<?php echo $patient['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                            <a href="view.php?id=<?php echo $patient['id']; ?>" class="btn btn-sm btn-info">View</a>
                                            <form action="delete.php" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this patient?');">
                                                <input type="hidden" name="patient_id" value="<?php echo $patient['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($patients)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <?php echo !empty($search) ? 'No patients found matching your search criteria' : 'No patients found'; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('../includes/footer.php'); ?>