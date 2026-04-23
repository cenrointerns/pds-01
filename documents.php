<?php
$conn = new mysqli("localhost", "root", "", "cenro");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sections = ["PDS", "IPC", "OPC", "SALN"];
?>

<!DOCTYPE html>
<html>
<head>
  <title>Employee Documents</title>
  <style>
    body { font-family: Arial; margin:40px; background:#f5f6f8; }
    .container { max-width:900px; margin:auto; background:white; padding:20px; border-radius:10px; }

    .header {
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-bottom:20px;
    }

    button {
      background:#3498db;
      color:white;
      border:none;
      padding:10px 15px;
      cursor:pointer;
      border-radius:6px;
    }

    .section {
      margin-bottom:30px;
    }

    h3 {
      background:#2c3e50;
      color:white;
      padding:10px;
      border-radius:6px;
    }

    table {
      width:100%;
      border-collapse:collapse;
      margin-top:10px;
    }

    th, td {
      padding:10px;
      border-bottom:1px solid #ddd;
      text-align:left;
    }

    a {
      color:#2980b9;
      text-decoration:none;
    }

    .empty {
      padding:10px;
      color:gray;
    }
  </style>
</head>

<body>

<div class="container">

  <div class="header">
    <h2>📁 Employee Documents</h2>

    <button onclick="window.location.href='upload_form.php'">
      + Upload Document
    </button>
  </div>

  <?php foreach ($sections as $section) { ?>

    <div class="section">

      <h3><?= $section ?></h3>

      <table>
        <tr>
          <th>Employee ID</th>
          <th>File Name</th>
          <th>Uploaded At</th>
          <th>File</th>
        </tr>

        <?php
        $stmt = $conn->prepare("
          SELECT * FROM employee_documents 
          WHERE document_type = ? 
          ORDER BY uploaded_at DESC
        ");

        $stmt->bind_param("s", $section);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            echo "<tr><td colspan='4' class='empty'>No documents found</td></tr>";
        }

        while ($row = $result->fetch_assoc()) {
        ?>
          <tr>
            <td><?= $row["employee_id"] ?></td>
            <td><?= $row["file_name"] ?></td>
            <td><?= $row["uploaded_at"] ?></td>
            <td>
              <a href="<?= $row["file_path"] ?>" target="_blank">View</a>
            </td>
          </tr>
        <?php } ?>

      </table>
    </div>

  <?php } ?>

</div>

</body>
</html>