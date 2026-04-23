<?php include "config.php"; ?>

<?php
if (!isset($_GET['id'])) {
    die("Invalid request");
}

$id = $_GET['id'];

// fetch data
$sql = "SELECT * FROM employee_documents WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if (!$row) {
    die("Document not found");
}

// update
if (isset($_POST['update'])) {

    $document_type = $_POST['document_type'];
    $file_name = $_POST['file_name'];

    $update = "
        UPDATE employee_documents 
        SET document_type='$document_type',
            file_name='$file_name'
        WHERE id=$id
    ";

    if ($conn->query($update)) {
        echo "<script>
            alert('Document updated successfully');
            window.location='documents.php';
        </script>";
    } else {
        echo "Error updating record.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Document</title>
</head>
<body>

<h2>Edit Document</h2>

<form method="POST">

    <label>Document Type:</label><br>
    <input type="text" name="document_type" 
           value="<?= $row['document_type'] ?>" required>
    <br><br>

    <label>File Name:</label><br>
    <input type="text" name="file_name" 
           value="<?= $row['file_name'] ?>" required>
    <br><br>

    <button type="submit" name="update">Update</button>

</form>

</body>
</html>