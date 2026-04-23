<?php
// DB CONNECTION
$host = "localhost";
$user = "root";
$pass = "";
$db   = "cenro";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$image_folder = "assets/image/employee/";
if (!file_exists($image_folder)) {
    mkdir($image_folder, 0777, true);
}

/* ================= FILTER ================= */
$statusFilter = isset($_GET['status']) ? $_GET['status'] : "";

/* ================= CREATE ================= */
if (isset($_POST['add'])) {

    $name = $_POST['name'];
    $status = $_POST['status'];

    $gender = $_POST['gender'];
    $dob = $_POST['date_of_birth'];
    $nosca = $_POST['nosca_item_number'];
    $assignment = $_POST['place_of_assignment'];
    $position = $_POST['position_title'];
    $salary = $_POST['salary_grade'];
    $civil = $_POST['civil_service_eligibility'];
    $education = $_POST['education'];
    $appointment = $_POST['date_of_appointment'];

    $image_name = null;
    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $image_folder . $image_name);
    }

    $stmt = $conn->prepare("INSERT INTO employees 
    (name, status, image,
     gender, date_of_birth, nosca_item_number, place_of_assignment,
     position_title, salary_grade, civil_service_eligibility,
     education, date_of_appointment)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "ssssssssssss",
        $name, $status, $image_name,
        $gender, $dob, $nosca, $assignment,
        $position, $salary, $civil,
        $education, $appointment
    );

    $stmt->execute();
}

/* ================= DELETE ================= */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    $img = $conn->query("SELECT image FROM employees WHERE employee_id=$id")->fetch_assoc();
    if (!empty($img['image']) && file_exists($image_folder . $img['image'])) {
        unlink($image_folder . $img['image']);
    }

    $conn->query("DELETE FROM employees WHERE employee_id=$id");
}

/* ================= UPDATE ================= */
if (isset($_POST['update'])) {

    $id = $_POST['id'];
    $name = $_POST['name'];
    $status = $_POST['status'];

    $gender = $_POST['gender'];
    $dob = $_POST['date_of_birth'];
    $nosca = $_POST['nosca_item_number'];
    $assignment = $_POST['place_of_assignment'];
    $position = $_POST['position_title'];
    $salary = $_POST['salary_grade'];
    $civil = $_POST['civil_service_eligibility'];
    $education = $_POST['education'];
    $appointment = $_POST['date_of_appointment'];

    $image_name = $_POST['old_image'];

    if (!empty($_FILES['image']['name'])) {
        if ($image_name && file_exists($image_folder . $image_name)) {
            unlink($image_folder . $image_name);
        }

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $image_folder . $image_name);
    }

    $stmt = $conn->prepare("UPDATE employees SET 
        name=?, status=?, image=?,
        gender=?, date_of_birth=?, nosca_item_number=?, place_of_assignment=?,
        position_title=?, salary_grade=?, civil_service_eligibility=?,
        education=?, date_of_appointment=?
        WHERE employee_id=?");

    $stmt->bind_param(
        "ssssssssssssi",
        $name, $status, $image_name,
        $gender, $dob, $nosca, $assignment,
        $position, $salary, $civil,
        $education, $appointment,
        $id
    );

    $stmt->execute();
}

/* ================= PAGINATION ================= */
$limit = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

$where = "";
if ($statusFilter == "Permanent" || $statusFilter == "Contract of Service") {
    $where = "WHERE status = '" . $conn->real_escape_string($statusFilter) . "'";
}

$totalResult = $conn->query("SELECT COUNT(*) as total FROM employees $where");
$totalRow = $totalResult->fetch_assoc();
$totalPages = ceil($totalRow['total'] / $limit);

$result = $conn->query("
    SELECT * FROM employees 
    $where
    ORDER BY employee_id DESC 
    LIMIT $limit OFFSET $offset
");

/* ================= EDIT ================= */
$edit = false;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $editData = $conn->query("SELECT * FROM employees WHERE employee_id=$id")->fetch_assoc();
    $edit = true;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Employee CRUD</title>

<style>
body { font-family: Arial; background:#f4f6f9; padding:20px; }
.container { max-width:900px; margin:auto; background:white; padding:20px; border-radius:10px; }

input, select { padding:8px; margin:5px; width:100%; }

button {
    padding:10px;
    background:#4facfe;
    color:white;
    border:none;
    cursor:pointer;
    border-radius:6px;
}

.reset-btn {
    width:50px;
}

table { width:100%; margin-top:20px; border-collapse:collapse; }
th, td { padding:10px; border-bottom:1px solid #ddd; text-align:center; }

.permanent { color:green; font-weight:bold; }
.cos { color:orange; font-weight:bold; }

img { width:50px; height:50px; object-fit:cover; border-radius:50%; }

.pagination { margin-top:20px; text-align:center; }
.pagination a {
    margin:0 5px;
    padding:5px 10px;
    border:1px solid #ccc;
    text-decoration:none;
    border-radius:5px;
}
.pagination a.active { background:#4facfe; color:white; }
</style>

</head>
<body>

<div class="container">

<h2>Employee Management</h2>

<!-- FORM -->
<form method="POST" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $edit ? $editData['employee_id'] : '' ?>">
<input type="hidden" name="old_image" value="<?= $edit ? $editData['image'] : '' ?>">

<input type="text" name="name" placeholder="Name" required value="<?= $edit ? $editData['name'] : '' ?>">

<select name="status" required>
<option value="">Select Status</option>
<option value="Permanent" <?= ($edit && $editData['status']=="Permanent")?"selected":"" ?>>Permanent</option>
<option value="Contract of Service" <?= ($edit && $editData['status']=="Contract of Service")?"selected":"" ?>>Contract of Service</option>
</select>

<input type="text" name="gender" placeholder="Gender" value="<?= $edit ? $editData['gender'] : '' ?>">
<input type="date" name="date_of_birth" value="<?= $edit ? $editData['date_of_birth'] : '' ?>">
<input type="text" name="nosca_item_number" placeholder="NOSCA Item Number" value="<?= $edit ? $editData['nosca_item_number'] : '' ?>">
<input type="text" name="place_of_assignment" placeholder="Place of Assignment" value="<?= $edit ? $editData['place_of_assignment'] : '' ?>">
<input type="text" name="position_title" placeholder="Position Title" value="<?= $edit ? $editData['position_title'] : '' ?>">
<input type="text" name="salary_grade" placeholder="Salary Grade" value="<?= $edit ? $editData['salary_grade'] : '' ?>">
<input type="text" name="civil_service_eligibility" placeholder="Eligibility" value="<?= $edit ? $editData['civil_service_eligibility'] : '' ?>">
<input type="text" name="education" placeholder="Education" value="<?= $edit ? $editData['education'] : '' ?>">
<input type="date" name="date_of_appointment" value="<?= $edit ? $editData['date_of_appointment'] : '' ?>">

<input type="file" name="image">

<?php if ($edit): ?>
<button name="update">Update</button>
<?php else: ?>
<button name="add">Add</button>
<?php endif; ?>

<!-- RESET ONLY INPUT FIELDS -->
<button type="button" class="reset-btn" onclick="resetForm()">🔄</button>

</form>

<!-- FILTER -->
<form method="GET" style="margin-top:15px;">
<select name="status" onchange="this.form.submit()">
<option value="">All</option>
<option value="Permanent" <?= ($statusFilter=="Permanent")?"selected":"" ?>>Permanent</option>
<option value="Contract of Service" <?= ($statusFilter=="Contract of Service")?"selected":"" ?>>Contract of Service</option>
</select>
<input type="hidden" name="page" value="1">
</form>

<!-- TABLE -->
<table>
<tr>
<th>ID</th>
<th>Image</th>
<th>Name</th>
<th>Status</th>
<th>Actions</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $row['employee_id'] ?></td>

<td>
<?php if($row['image'] && file_exists($image_folder.$row['image'])): ?>
<img src="<?= $image_folder.$row['image'] ?>">
<?php else: ?>
<img src="<?= $image_folder ?>default.png">
<?php endif; ?>
</td>

<td><?= htmlspecialchars($row['name']) ?></td>

<td class="<?= strtolower($row['status'])=='permanent'?'permanent':'cos' ?>">
<?= $row['status'] ?>
</td>

<td>
<a href="?edit=<?= $row['employee_id'] ?>&page=<?= $page ?>&status=<?= $statusFilter ?>">Edit</a>
<a href="?delete=<?= $row['employee_id'] ?>&page=<?= $page ?>&status=<?= $statusFilter ?>" onclick="return confirm('Delete?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>

</table>

<!-- PAGINATION -->
<div class="pagination">

<?php if ($page > 1): ?>
<a href="?page=<?= $page-1 ?>&status=<?= $statusFilter ?>">Prev</a>
<?php endif; ?>

<?php for ($i=1; $i<=$totalPages; $i++): ?>
<a href="?page=<?= $i ?>&status=<?= $statusFilter ?>" class="<?= ($i==$page)?'active':'' ?>">
<?= $i ?>
</a>
<?php endfor; ?>

<?php if ($page < $totalPages): ?>
<a href="?page=<?= $page+1 ?>&status=<?= $statusFilter ?>">Next</a>
<?php endif; ?>

</div>

</div>

<!-- RESET SCRIPT -->
<script>
function resetForm() {
    const form = document.querySelector("form");
    form.reset();

    const file = form.querySelector('input[type="file"]');
    if (file) file.value = "";
}
</script>

</body>
</html>