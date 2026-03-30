<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "cenro";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get filter from GET request, default to All
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'All';

// Prepare query based on filter
if ($filter === 'Permanent' || $filter === 'Contract of Service') {
    $stmt = $conn->prepare("SELECT employee_id, name, age, office, status, image FROM employees WHERE status = ?");
    $stmt->bind_param("s", $filter);
} else { // All
    $stmt = $conn->prepare("SELECT employee_id, name, age, office, status, image FROM employees");
}

$stmt->execute();
$result = $stmt->get_result();

// Folder where images are stored
$image_folder = __DIR__ . "/assets/image/employee/"; // server path for file_exists
$image_url    = "assets/image/employee/";           // URL path for <img>
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employees</title>
    <link rel="stylesheet" href="assets/css/employee_list.css">
    <style>
        img { width: 50px; height: 50px; object-fit: cover; border-radius: 50%; }
        .btn { padding: 6px 12px; background-color: #007bff; color: white; border-radius: 5px; text-decoration: none; }
        .badge.permanent { background-color: #28a745; color: white; padding: 4px 8px; border-radius: 5px; }
        .badge.cos { background-color: orange; color: white; padding: 4px 8px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<div class="container">
    <h2>List of Employees</h2>

    <!-- Filter Dropdown -->
    <div class="filter">
        <form method="GET" action="">
            <label for="filter">Show: </label>
            <select name="filter" id="filter" onchange="this.form.submit()">
                <option value="All" <?= $filter=='All'?'selected':'' ?>>All</option>
                <option value="Permanent" <?= $filter=='Permanent'?'selected':'' ?>>Permanent</option>
                <option value="Contract of Service" <?= $filter=='Contract of Service'?'selected':'' ?>>Contract of Service</option>
            </select>
        </form>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Office</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
            <?php while($row = $result->fetch_assoc()): 
                // Determine which image to show
                $img_file = ($row['image'] && file_exists($image_folder . $row['image'])) 
                            ? $row['image'] 
                            : 'default.png';
            ?>
            <tr>
                <td><?= htmlspecialchars($row['employee_id']); ?></td>
                <td>
                    <img src="<?= $image_url . htmlspecialchars($img_file); ?>" alt="<?= htmlspecialchars($row['name']); ?>">
                </td>
                <td><?= htmlspecialchars($row['name']); ?></td>
                <td><?= htmlspecialchars($row['age']); ?></td>
                <td><?= htmlspecialchars($row['office']); ?></td>
                <td>
                    <span class="badge <?= $row['status']=='Permanent'?'permanent':'cos' ?>">
                        <?= htmlspecialchars($row['status']); ?>
                    </span>
                </td>
                <td>
                    <a href="employee_info.php?employee_id=<?= $row['employee_id']; ?>" class="btn">View</a>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>