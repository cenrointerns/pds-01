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
            window.location='view_documents.php';
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

    <style>
      body {
    font-family: 'Segoe UI', Tahoma, sans-serif;

    /* 🔥 Background image */
    background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/e/e8/Logo_of_the_Department_of_Environment_and_Natural_Resources.svg/1280px-Logo_of_the_Department_of_Environment_and_Natural_Resources.svg.png'); /* change path here */
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;

    margin: 0;
    padding: 20px;
}
        .container {
            width: 450px;
            margin: 80px auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #2d6cdf;
        }

        label {
            font-weight: bold;
            font-size: 14px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: 0.3s;
        }

        input:focus {
            border-color: #2d6cdf;
            outline: none;
            box-shadow: 0 0 5px rgba(45,108,223,0.3);
        }

        button {
            width: 100%;
            padding: 12px;
            background: #2d6cdf;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #1b4fb3;
        }

        .card-header {
            text-align: center;
            margin-bottom: 15px;
        }

        .badge {
            display: inline-block;
            background: #eee;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .back {
            text-align: center;
            margin-top: 15px;
        }

        .back a {
            text-decoration: none;
            color: #2d6cdf;
            font-size: 13px;
        }

        .back a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="card-header">
        <h2>✏ Edit Document</h2>
        <span class="badge">ID: <?= $row['id'] ?></span>
    </div>

    <form method="POST">

        <label>Document Type</label>
        <input type="text" name="document_type"
               value="<?= $row['document_type'] ?>" required>

        <label>File Name</label>
        <input type="text" name="file_name"
               value="<?= $row['file_name'] ?>" required>

        <button type="submit" name="update">Update Document</button>

    </form>

    <div class="back">
        <a href="view_documents.php">← Back to Documents</a>
    </div>

</div>

</body>
</html>