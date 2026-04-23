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

// Filter from GET request
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'All';

// Query based on filter
if ($filter === 'Permanent' || $filter === 'Contract of Service') {
    $stmt = $conn->prepare("
        SELECT employee_id, name, age, place_of_assignment, status, image 
        FROM employees 
        WHERE status = ?
    ");
    $stmt->bind_param("s", $filter);
} else {
    $stmt = $conn->prepare("
        SELECT employee_id, name, age, place_of_assignment, status, image 
        FROM employees
    ");
}

$stmt->execute();
$result = $stmt->get_result();

// Image paths
$image_folder = __DIR__ . "/assets/image/employee/";
$image_url    = "assets/image/employee/";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employees</title>
    <link rel="stylesheet" href="assets/css/employee_list.css">

    <style>
        img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }

        .btn {
            padding: 6px 12px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 5px;
            color: white;
        }

        .permanent {
            background-color: #28a745;
        }

        .contract-of-service {
            background-color: orange;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

<div class="container">
    <h2>List of Employees</h2>

    <!-- Filter -->
    <div class="filter">
        <form method="GET">
            <label>Show: </label>
            <select name="filter" onchange="this.form.submit()">
                <option value="All" <?= $filter == 'All' ? 'selected' : '' ?>>All</option>
                <option value="Permanent" <?= $filter == 'Permanent' ? 'selected' : '' ?>>Permanent</option>
                <option value="Contract of Service" <?= $filter == 'Contract of Service' ? 'selected' : '' ?>>Contract of Service</option>
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
                    <th>Place of Assignment</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
            <?php while($row = $result->fetch_assoc()): 
                $img_file = (!empty($row['image']) && file_exists($image_folder . $row['image']))
                    ? $row['image']
                    : 'default.png';

                $statusClass = strtolower(str_replace(' ', '-', $row['status']));
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['employee_id']); ?></td>

                    <td>
                        <img src="<?= $image_url . htmlspecialchars($img_file); ?>" 
                             alt="<?= htmlspecialchars($row['name']); ?>">
                    </td>

                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= htmlspecialchars($row['age']); ?></td>

                    <td><?= htmlspecialchars($row['place_of_assignment']); ?></td>

                    <td>
                        <span class="badge <?= $statusClass ?>">
                            <?= htmlspecialchars($row['status']); ?>
                        </span>
                    </td>

                    <td>
                        <a href="employee_info.php?employee_id=<?= $row['employee_id']; ?>" class="btn">
                            View
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>