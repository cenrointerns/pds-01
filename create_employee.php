<?php
// DB CONNECTION
$host = "localhost";
$user = "root";
$pass = "";
$db   = "cenro";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Folder to store images
$image_folder = "assets/image/employee/";
if (!file_exists($image_folder)) {
    mkdir($image_folder, 0777, true);
}

/* ================= CREATE ================= */
if (isset($_POST['add'])) {

    $name = $_POST['name'];
    $age = $_POST['age'];
    $status = $_POST['status'];

    // NEW FIELDS
    $gender = $_POST['gender'];
    $dob = $_POST['date_of_birth'];
    $nosca = $_POST['nosca_item_number'];
    $assignment = $_POST['place_of_assignment'];
    $position = $_POST['position_title'];
    $salary = $_POST['salary_grade'];
    $civil = $_POST['civil_service_eligibility'];
    $education = $_POST['education'];
    $appointment = $_POST['date_of_appointment'];
    $service = $_POST['length_of_service'];

    // Image upload
    $image_name = null;
    if (isset($_FILES['image']) && $_FILES['image']['name'] != '') {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $image_folder . $image_name);
    }

    $stmt = $conn->prepare("INSERT INTO employees 
    (name, age, status, image,
     gender, date_of_birth, nosca_item_number, place_of_assignment,
     position_title, salary_grade, civil_service_eligibility,
     education, date_of_appointment, length_of_service)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "sissssssssssss",
        $name, $age, $status, $image_name,
        $gender, $dob, $nosca, $assignment,
        $position, $salary, $civil,
        $education, $appointment, $service
    );

    $stmt->execute();
}

/* ================= DELETE ================= */
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $img = $conn->query("SELECT image FROM employees WHERE employee_id=$id")->fetch_assoc();
    if ($img['image'] && file_exists($image_folder . $img['image'])) {
        unlink($image_folder . $img['image']);
    }

    $conn->query("DELETE FROM employees WHERE employee_id=$id");
}

/* ================= UPDATE ================= */
if (isset($_POST['update'])) {

    $id = $_POST['id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $status = $_POST['status'];

    // NEW FIELDS
    $gender = $_POST['gender'];
    $dob = $_POST['date_of_birth'];
    $nosca = $_POST['nosca_item_number'];
    $assignment = $_POST['place_of_assignment'];
    $position = $_POST['position_title'];
    $salary = $_POST['salary_grade'];
    $civil = $_POST['civil_service_eligibility'];
    $education = $_POST['education'];
    $appointment = $_POST['date_of_appointment'];
    $service = $_POST['length_of_service'];

    $image_name = $_POST['old_image'];

    if (isset($_FILES['image']) && $_FILES['image']['name'] != '') {
        if ($image_name && file_exists($image_folder . $image_name)) {
            unlink($image_folder . $image_name);
        }

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $image_folder . $image_name);
    }

    $stmt = $conn->prepare("UPDATE employees SET 
        name=?, age=?, status=?, image=?,
        gender=?, date_of_birth=?, nosca_item_number=?, place_of_assignment=?,
        position_title=?, salary_grade=?, civil_service_eligibility=?,
        education=?, date_of_appointment=?, length_of_service=?
        WHERE employee_id=?");

    $stmt->bind_param(
        "sissssssssssssi",
        $name, $age, $status, $image_name,
        $gender, $dob, $nosca, $assignment,
        $position, $salary, $civil,
        $education, $appointment, $service,
        $id
    );

    $stmt->execute();
}

/* ================= FETCH ================= */
$result = $conn->query("SELECT * FROM employees");

/* ================= EDIT ================= */
$edit = false;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
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
button { padding:10px; background:#4facfe; color:white; border:none; cursor:pointer; }
table { width:100%; margin-top:20px; border-collapse:collapse; }
th, td { padding:10px; border-bottom:1px solid #ddd; text-align:center; }
.permanent { color:green; font-weight:bold; }
.cos { color:orange; font-weight:bold; }
a { margin:0 5px; text-decoration:none; }
img { width:50px; height:50px; object-fit:cover; border-radius:50%; }
</style>
</head>
<body>

<div class="container">
<h2>Employee Management</h2>

<form method="POST" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $edit ? $editData['employee_id'] : '' ?>">
<input type="hidden" name="old_image" value="<?= $edit ? $editData['image'] : '' ?>">

<input type="text" name="name" placeholder="Name" required value="<?= $edit ? $editData['name'] : '' ?>">
<input type="number" name="age" placeholder="Age" required value="<?= $edit ? $editData['age'] : '' ?>">

<select name="status" required>
<option value="">Select Status</option>
<option value="Permanent" <?= ($edit && $editData['status']=="Permanent") ? "selected" : "" ?>>Permanent</option>
<option value="Contract of Service" <?= ($edit && $editData['status']=="Contract of Service") ? "selected" : "" ?>>Contract of Service</option>
</select>

<!-- REMOVED OFFICE FIELD -->

<!-- NEW FIELDS -->
<input type="text" name="gender" placeholder="Gender" value="<?= $edit ? $editData['gender'] : '' ?>">
<input type="date" name="date_of_birth" value="<?= $edit ? $editData['date_of_birth'] : '' ?>">
<input type="text" name="nosca_item_number" placeholder="NOSCA Item Number" value="<?= $edit ? $editData['nosca_item_number'] : '' ?>">
<input type="text" name="place_of_assignment" placeholder="Place of Assignment" value="<?= $edit ? $editData['place_of_assignment'] : '' ?>">
<input type="text" name="position_title" placeholder="Position Title" value="<?= $edit ? $editData['position_title'] : '' ?>">
<input type="text" name="salary_grade" placeholder="Salary Grade" value="<?= $edit ? $editData['salary_grade'] : '' ?>">
<input type="text" name="civil_service_eligibility" placeholder="Civil Service Eligibility" value="<?= $edit ? $editData['civil_service_eligibility'] : '' ?>">
<input type="text" name="education" placeholder="Education" value="<?= $edit ? $editData['education'] : '' ?>">
<input type="date" name="date_of_appointment" value="<?= $edit ? $editData['date_of_appointment'] : '' ?>">
<input type="text" name="length_of_service" placeholder="Length of Service" value="<?= $edit ? $editData['length_of_service'] : '' ?>">

<label>Employee Image:</label>
<input type="file" name="image">

<?php if ($edit): ?>
<button type="submit" name="update">Update Employee</button>
<?php else: ?>
<button type="submit" name="add">Add Employee</button>
<?php endif; ?>

</form>

<table>
<tr>
<th>ID</th>
<th>Image</th>
<th>Name</th>
<th>Age</th>
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
<td><?= $row['age'] ?></td>

<td class="<?= strtolower($row['status']) == 'permanent' ? 'permanent' : 'cos' ?>">
<?= $row['status'] ?>
</td>

<td>
<a href="?edit=<?= $row['employee_id'] ?>">Edit</a>
<a href="?delete=<?= $row['employee_id'] ?>" onclick="return confirm('Delete?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>

</table>

</div>

</body>
</html>