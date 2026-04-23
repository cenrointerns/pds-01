<?php
$conn = new mysqli("localhost", "root", "", "cenro");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM employee_documents ORDER BY uploaded_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Employee Documents</title>
  <style>
    body { font-family: Arial; margin:40px; background:#f5f6f8; }
    .container { max-width:800px; margin:auto; background:white; padding:20px; border-radius:10px; }
    button { padding:10px 15px; background:#3498db; color:white; border:none; cursor:pointer; margin-bottom:15px; }
    table { width:100%; border-collapse:collapse; }
    th, td { padding:10px; border-bottom:1px solid #ddd; text-align:left; }
    .delete { background:#e74c3c; }
  </style>
</head>

<body>

<div class="container">

  <h2>📁 Employee Documents</h2>

  <button onclick="window.location.href='upload_form.php'">
    Upload Document
  </button>

  <table>
    <tr>
      <th>ID</th>
      <th>Employee ID</th>
      <th>File Name</th>
      <th>Type</th>
      <th>Uploaded</th>
      <th>Action</th>
    </tr>

    <?php while($row = $result->fetch_assoc()) { ?>
      <tr>
        <td><?= $row["id"] ?></td>
        <td><?= $row["employee_id"] ?></td>
        <td>
          <a href="<?= $row["file_path"] ?>" target="_blank">
            <?= $row["file_name"] ?>
          </a>
        </td>
        <td><?= $row["document_type"] ?></td>
        <td><?= $row["uploaded_at"] ?></td>
        <td>
          <button class="delete" onclick="alert('You can add delete later')">
            Delete
          </button>
        </td>
      </tr>
    <?php } ?>

  </table>

</div>

</body>
</html>