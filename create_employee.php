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

// ================= CREATE =================
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $status = $_POST['status'];
    $office = $_POST['office'];

    // Handle image upload
    $image_name = null;
    if(isset($_FILES['image']) && $_FILES['image']['name'] != '') {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $image_folder . $image_name);
    }

    $stmt = $conn->prepare("INSERT INTO employees (name, age, status, office, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisss", $name, $age, $status, $office, $image_name);
    $stmt->execute();
}

// ================= DELETE =================
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Delete image file
    $img = $conn->query("SELECT image FROM employees WHERE employee_id=$id")->fetch_assoc();
    if($img['image'] && file_exists($image_folder.$img['image'])) unlink($image_folder.$img['image']);

    $conn->query("DELETE FROM employees WHERE employee_id=$id");
}

// ================= UPDATE =================
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $status = $_POST['status'];
    $office = $_POST['office'];

    $image_name = $_POST['old_image']; // Keep old image by default
    if(isset($_FILES['image']) && $_FILES['image']['name'] != '') {
        // Delete old image
        if($image_name && file_exists($image_folder.$image_name)) unlink($image_folder.$image_name);

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $image_folder . $image_name);
    }

    $stmt = $conn->prepare("UPDATE employees SET name=?, age=?, status=?, office=?, image=? WHERE employee_id=?");
    $stmt->bind_param("sisssi", $name, $age, $status, $office, $image_name, $id);
    $stmt->execute();
}

// FETCH DATA
$result = $conn->query("SELECT * FROM employees");

// EDIT MODE
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

    <!-- FORM -->
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $edit ? $editData['employee_id'] : '' ?>">
        <input type="hidden" name="old_image" value="<?= $edit ? $editData['image'] : '' ?>">

        <input type="text" name="name" placeholder="Name" required
            value="<?= $edit ? $editData['name'] : '' ?>">

        <input type="number" name="age" placeholder="Age" required
            value="<?= $edit ? $editData['age'] : '' ?>">

        <select name="status" required>
            <option value="">Select Status</option>
            <option value="Permanent" <?= ($edit && $editData['status']=="Permanent") ? "selected" : "" ?>>Permanent</option>
            <option value="Contract of Service" <?= ($edit && $editData['status']=="Contract of Service") ? "selected" : "" ?>>Contract of Service</option>
        </select>

        <input type="text" name="office" placeholder="Office" required
            value="<?= $edit ? $editData['office'] : '' ?>">

        <label>Employee Image:</label>
        <input type="file" name="image" accept="image/*">

        <?php if ($edit): ?>
            <button type="submit" name="update">Update Employee</button>
        <?php else: ?>
            <button type="submit" name="add">Add Employee</button>
        <?php endif; ?>
    </form>

    <!-- TABLE -->
    <table>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Age</th>
            <th>Status</th>
            <th>Office</th>
            <th>Actions</th>
        </tr>

        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['employee_id'] ?></td>
            <td>
                <?php if($row['image'] && file_exists($image_folder.$row['image'])): ?>
                    <img src="<?= $image_folder.$row['image'] ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                <?php else: ?>
                    <img src="<?= $image_folder ?>default.png" alt="Default Image">
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= $row['age'] ?></td>
            <td class="<?= strtolower($row['status']) == 'permanent' ? 'permanent' : 'cos' ?>">
                <?= $row['status'] ?>
            </td>
            <td><?= htmlspecialchars($row['office']) ?></td>
            <td>
                <a href="?edit=<?= $row['employee_id'] ?>">Edit</a>
                <a href="?delete=<?= $row['employee_id'] ?>" onclick="return confirm('Delete this employee?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

</div>

</body>
</html>