<?php
// Database connection
$host = "localhost";
$user = "root"; // your DB username
$pass = "";     // your DB password
$db   = "cenro";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get employee_id from GET
$employee_id = isset($_GET['employee_id']) ? intval($_GET['employee_id']) : 0;

// Fetch employee info
$stmt = $conn->prepare("SELECT * FROM employees WHERE employee_id = ?");
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

// Redirect if employee not found
if (!$employee) {
    die("Employee not found.");
}

// Handle image path and fallback
$image_path = 'assets/image/employee/' . $employee['image'];
if (!file_exists($image_path) || empty($employee['image'])) {
    $image_path = 'assets/image/employee/default.png'; // placeholder image
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($employee['name']); ?> - Employee Info</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.4/css/all.min.css">
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f4f6f9;
    margin: 0;
    padding: 20px;
}
.container {
    max-width: 950px;
    margin: auto;
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    overflow: hidden;
}
.employee-header {
    display: flex;
    flex-wrap: wrap;
    padding: 20px;
    align-items: flex-start;
}
.employee-image {
    flex: 0 0 200px;
    text-align: center;
    margin-right: 30px;
}
.employee-image img {
    width: 180px;
    height: 180px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid #4facfe;
}
.employee-info {
    flex: 1;
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}
.info-left, .info-right {
    flex: 1;
    min-width: 200px;
}
.employee-info h2 {
    width: 100%;
    margin-top: 0;
    margin-bottom: 20px;
    color: #333;
}
.employee-info p {
    margin: 8px 0;
    font-size: 16px;
    color: #555;
}
.employee-info p span {
    font-weight: bold;
}
.employee-buttons {
    display: flex;
    flex-wrap: wrap;
    padding: 20px;
    gap: 10px;
    justify-content: center;
    border-top: 1px solid #eee;
    background: #f9f9f9;
}
.employee-buttons a {
    flex: 1 1 120px;
    text-align: center;
    padding: 12px 10px;
    border-radius: 8px;
    text-decoration: none;
    color: white;
    font-weight: bold;
    transition: 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}
.employee-buttons a i {
    font-size: 16px;
}
.pds { background-color: #007bff; }
.saln { background-color: #28a745; }
.ipc  { background-color: #ffc107; color: #333; }
.opc  { background-color: #17a2b8; }
.service { background-color: #6f42c1; }
.designation { background-color: #fd7e14; }
.employee-buttons a:hover { opacity: 0.85; }

@media (max-width: 800px) {
    .employee-header {
        flex-direction: column;
        align-items: center;
    }
    .employee-image {
        margin-right: 0;
        margin-bottom: 20px;
    }
    .employee-info {
        flex-direction: column;
    }
}
</style>
</head>
<body>

<div class="container">
    <div class="employee-header">
        <!-- Employee Image -->
        <div class="employee-image">
            <img src="<?= htmlspecialchars($image_path); ?>" 
                 alt="<?= htmlspecialchars($employee['name']); ?>">
        </div>

        <!-- Employee Info -->
        <div class="employee-info">
            <h2><?= htmlspecialchars($employee['name']); ?></h2>
            
            <!-- Left Column -->
            <div class="info-left">
                <p><span>Age:</span> <?= htmlspecialchars($employee['age']); ?></p>
                <p><span>Gender:</span> <?= htmlspecialchars($employee['gender'] ?? 'N/A'); ?></p>
                <p><span>Date of Birth:</span> <?= htmlspecialchars($employee['date_of_birth'] ?? 'N/A'); ?></p>
                <p><span>NOSCA ITEM NUMBER:</span> <?= htmlspecialchars($employee['nosca_item_number'] ?? 'N/A'); ?></p>
            </div>
            
            <!-- Right Column -->
            <div class="info-right">
                <p><span>Place of Assignment:</span> <?= htmlspecialchars($employee['place_of_assignment'] ?? 'N/A'); ?></p>
                <p><span>Position Title:</span> <?= htmlspecialchars($employee['position_title'] ?? 'N/A'); ?></p>
                <p><span>Salary Grade:</span> <?= htmlspecialchars($employee['salary_grade'] ?? 'N/A'); ?></p>
                <p><span>Office:</span> <?= htmlspecialchars($employee['office']); ?></p>
                <p><span>Status:</span> <?= htmlspecialchars($employee['status']); ?></p>
                <p><span>Joined:</span> <?= date('F d, Y', strtotime($employee['created_at'])); ?></p>
            </div>
        </div>
    </div>

    <!-- Buttons -->
    <div class="employee-buttons">
        <a href="#" class="pds"><i class="fas fa-file-alt"></i> PDS</a>
        <a href="#" class="saln"><i class="fas fa-file-signature"></i> SALN</a>
        <a href="#" class="ipc"><i class="fas fa-file"></i> IPC</a>
        <a href="#" class="opc"><i class="fas fa-file-contract"></i> OPC</a>
        <a href="#" class="service"><i class="fas fa-briefcase"></i> Service Record</a>
        <a href="#" class="designation"><i class="fas fa-id-badge"></i> Designation</a>
    </div>
</div>

</body>
</html>