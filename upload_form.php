<?php include "config.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Employee Document</title>
</head>
<body>

<h2>Upload Employee Document</h2>

<form action="upload.php" method="POST" enctype="multipart/form-data">

    <label>Select Employee:</label>
    <select name="employee_id" required>
        <option value="">-- Select Employee --</option>

        <?php
        $sql = "SELECT employee_id, name FROM employees ORDER BY name ASC";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['employee_id']}'>
                    {$row['employee_id']} - {$row['name']}
                  </option>";
        }
        ?>
    </select>

    <br><br>

    <label>Document Type:</label>
    <select name="document_type" required>
        <option value="">-- Select Document Type --</option>
        <option value="Resume">Resume</option>
        <option value="Contract">Contract</option>
        <option value="ID">ID</option>
        <option value="Certificate">Certificate</option>
        <option value="Other">Other</option>
    </select>

    <br><br>

    <label>Choose File:</label>
    <input type="file" name="document" accept=".pdf,.doc,.docx" required>

    <br><br>

    <button type="submit">Upload</button>
</form>

</body>
</html>