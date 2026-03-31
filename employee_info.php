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
    $image_path = 'assets/image/employee/default.png';
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

/* TABLE STYLE */
.details-section {
    padding: 20px;
}
.details-section h3 {
    margin-bottom: 15px;
    color: #333;
}
.details-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 15px;
    background: #fff;
}
.details-table th {
    background: #4facfe;
    color: white;
    padding: 10px;
    text-align: left;
}
.details-table td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    color: #555;
}
.details-table tr:nth-child(even) {
    background: #f9f9f9;
}
.details-table tr:hover {
    background: #f1f1f1;
}

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

    <!-- Employee Header -->
    <div class="employee-header">
        
        <!-- Image -->
        <div class="employee-image">
            <img src="<?= htmlspecialchars($image_path); ?>" 
                 alt="<?= htmlspecialchars($employee['name']); ?>">
        </div>

        <!-- Info -->
        <div class="employee-info">
            <h2><?= htmlspecialchars($employee['name']); ?></h2>

            <div class="info-left">
                <p><span>Age:</span> <?= htmlspecialchars($employee['age']); ?></p>
                <p><span>Gender:</span> <?= htmlspecialchars($employee['gender'] ?? 'N/A'); ?></p>
                <p><span>Date of Birth:</span> <?= htmlspecialchars($employee['date_of_birth'] ?? 'N/A'); ?></p>
                <p><span>NOSCA ITEM NUMBER:</span> <?= htmlspecialchars($employee['nosca_item_number'] ?? 'N/A'); ?></p>
            </div>

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

    <!-- Employment Details Table -->
    <div class="details-section">
        <h3>Employment Details</h3>

        <table class="details-table">
            <thead>
                <tr>
                    <th>Civil Service Eligibility</th>
                    <th>Position Title</th>
                    <th>Education</th>
                    <th>Salary Grade</th>
                    <th>Date of Appointment</th>
                    <th>Length of Service</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= htmlspecialchars($employee['civil_service_eligibility'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($employee['position_title'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($employee['education'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($employee['salary_grade'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($employee['date_of_appointment'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($employee['length_of_service'] ?? 'N/A'); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>